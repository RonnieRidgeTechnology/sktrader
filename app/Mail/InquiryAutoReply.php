<?php

namespace App\Mail;

use App\Models\Inquiry;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryAutoReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Inquiry $inquiry
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank you for contacting Atlantic Garden Furniture',
            from: new Address(
                config('mail.from.address'),
                Setting::get('site_name') ?: config('zuaaz.name', 'Atlantic Garden Furniture')
            ),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.inquiry-auto-reply',
        );
    }
}
