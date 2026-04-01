<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Inertia\Inertia;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $inquiries = Inquiry::orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Inquiries/Index', [
            'inquiries' => $inquiries,
        ]);
    }

    public function show(Inquiry $inquiry)
    {
        $inquiry->attachment_url = $inquiry->attachment
            ? '/media/' . str_replace('\\', '/', trim($inquiry->attachment))
            : null;

        return Inertia::render('Admin/Inquiries/Show', [
            'inquiry' => $inquiry,
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="inquiries-' . date('Y-m-d-His') . '.csv"',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID', 'Name', 'Company', 'Email', 'WhatsApp', 'Country',
                'Product Interest', 'Quantity', 'Message', 'IP', 'Created At',
            ]);

            Inquiry::orderByDesc('created_at')->chunk(100, function ($inquiries) use ($handle) {
                foreach ($inquiries as $row) {
                    fputcsv($handle, [
                        $row->id,
                        $row->name,
                        $row->company_name,
                        $row->email,
                        $row->whatsapp,
                        $row->country,
                        $row->product_interest,
                        $row->quantity,
                        $row->message,
                        $row->ip_address,
                        $row->created_at->toIso8601String(),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
