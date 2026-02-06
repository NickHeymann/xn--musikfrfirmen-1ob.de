<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>|null  $companyResearch
     */
    public function __construct(
        public ContactSubmission $submission,
        public ?array $companyResearch = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address($this->submission->email, $this->submission->name),
            ],
            subject: 'Neue Kontaktanfrage von musikfürfirmen.de',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-form-submitted',
            with: [
                'submission' => $this->submission,
                'companyResearch' => $this->companyResearch,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
