<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingRequestSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>|null  $companyResearch
     */
    public function __construct(
        public array $bookingData,
        public ?array $companyResearch = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [
                new Address($this->bookingData['email'], $this->bookingData['name']),
            ],
            subject: 'Neue Erstgesprächs-Anfrage von musikfürfirmen.de',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-request-submitted',
            with: [
                'bookingData' => $this->bookingData,
                'companyResearch' => $this->companyResearch,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
