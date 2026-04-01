<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInquiryRequest;
use App\Mail\InquiryAutoReply;
use App\Mail\InquiryReceivedNotification;
use App\Models\Inquiry;
use App\Models\PageContent;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class InquiryController extends Controller
{
    public function store(StoreInquiryRequest $request)
    {
        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('inquiries', 'media');
        }

        $inquiry = Inquiry::create([
            'name'            => $request->validated('name'),
            'company_name'    => $request->validated('company_name'),
            'email'           => $request->validated('email'),
            'whatsapp'        => $request->validated('whatsapp'),
            'country'         => $request->validated('country'),
            'product_interest'=> $request->validated('product_interest'),
            'quantity'        => $request->validated('quantity'),
            'message'         => $request->validated('message'),
            'attachment'      => $path,
            'ip_address'      => $request->ip(),
        ]);

        try {
            Mail::to(Setting::get('contact_email') ?: config('zuaaz.contact.email', 'info@atlanticgardenfurniture.com'))
                ->queue(new InquiryReceivedNotification($inquiry));

            Mail::to($inquiry->email)
                ->queue(new InquiryAutoReply($inquiry));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('inquiry.thank-you');
    }

    public function thankYou()
    {
        return Inertia::render('InquiryThankYou', [
            'title' => 'Thank You',
            'pageContent' => PageContent::getForPage('inquiry-thank-you'),
        ]);
    }
}
