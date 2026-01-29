<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventRequestNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array{
     *     name: string,
     *     email: string,
     *     phone: string,
     *     company: string,
     *     date: string,
     *     time: string,
     *     city: string,
     *     budget: string,
     *     guests: string,
     *     package: string,
     *     message: string,
     * } $eventData
     */
    public function __construct(
        public array $eventData
    ) {}

    public function envelope(): Envelope
    {
        $city = $this->eventData['city'];
        $date = $this->eventData['date'];

        return new Envelope(
            replyTo: [
                new Address($this->eventData['email'], $this->eventData['name']),
            ],
            subject: "Neue Anfrage: {$city} am {$date}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.event-request-notification',
            with: [
                'data' => $this->eventData,
                'packageLabels' => [
                    'dj' => 'Nur DJ',
                    'band' => 'Full Band',
                    'band_dj' => 'Full Band + DJ',
                ],
                'guestLabels' => [
                    'lt100' => 'Unter 100',
                    '100-300' => '100 - 300',
                    '300-500' => '300 - 500',
                    'gt500' => '>500',
                ],
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
