<?php

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewEnquiryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Enquiry $enquiry
    ) {}

    public function envelope(): Envelope
    {
        $location = $this->enquiry->wedding_location ? " ({$this->enquiry->wedding_location})" : '';
        $subject = "💍 New Wedding Lead: {$this->enquiry->name}{$location} — Paneventz";

        return new Envelope(
            subject: $subject,
            replyTo: !empty($this->enquiry->email) ? [$this->enquiry->email] : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-enquiry',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
