<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $email],
            ['subscribed_at' => now()]
        );

        $message = $subscriber->wasRecentlyCreated
            ? 'Thanks for subscribing!'
            : 'You are already subscribed.';

        return back()->with('newsletter_success', $message);
    }
}
