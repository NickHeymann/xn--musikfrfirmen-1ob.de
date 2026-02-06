<?php

namespace Tests\Feature;

use App\Mail\BookingRequestSubmitted;
use App\Mail\ContactFormSubmitted;
use App\Mail\EventRequestNotification;
use App\Models\ContactSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmailRenderTest extends TestCase
{
    use RefreshDatabase;

    private array $sampleEventData = [
        'name' => 'Max Mustermann',
        'firstname' => 'Max',
        'lastname' => 'Mustermann',
        'email' => 'max@test.de',
        'phone' => '+49 123 456789',
        'company' => 'Test GmbH',
        'date' => '2026-03-15',
        'time' => '14:00',
        'city' => 'Hamburg',
        'budget' => '5000',
        'guests' => 'lt100',
        'package' => 'band',
        'message' => 'Wir suchen eine Band für unser Sommerfest.',
    ];

    private array $sampleResearch = [
        'industry' => 'IT-Dienstleistungen',
        'employee_count' => '250',
        'website' => 'https://testgmbh.de',
        'location' => 'Hamburg',
        'description' => 'Test GmbH ist ein IT-Unternehmen.',
        'recent_news' => [
            ['title' => 'Test GmbH expandiert', 'url' => 'https://news.example.com/test'],
        ],
        'past_events' => [
            ['title' => 'Sommerfest 2025', 'url' => 'https://events.example.com/test'],
        ],
        'sources' => ['https://testgmbh.de', 'https://news.example.com'],
    ];

    private array $sampleBookingData = [
        'name' => 'John Doe',
        'company' => 'Acme Corp',
        'email' => 'john@example.com',
        'phone' => '+49 123 456789',
        'selectedDate' => '2026-03-15',
        'selectedTime' => '10:00',
        'message' => 'Freue mich auf das Gespräch.',
    ];

    #[Test]
    public function event_request_email_renders_with_research(): void
    {
        $mailable = new EventRequestNotification($this->sampleEventData, $this->sampleResearch);
        $html = $mailable->render();

        $this->assertStringContainsString('NEUE EVENT-ANFRAGE', $html);
        $this->assertStringContainsString('Max Mustermann', $html);
        $this->assertStringContainsString('Test GmbH', $html);
        $this->assertStringContainsString('Hamburg', $html);
        $this->assertStringContainsString('IT-Dienstleistungen', $html);
        $this->assertStringContainsString('testgmbh.de', $html);
        $this->assertStringContainsString('FIRMEN-PROFIL', $html);
        $this->assertStringContainsString('mailto:max@test.de', $html);
        $this->assertStringContainsString('tel:+49 123 456789', $html);
    }

    #[Test]
    public function event_request_email_renders_without_research(): void
    {
        $mailable = new EventRequestNotification($this->sampleEventData);
        $html = $mailable->render();

        $this->assertStringContainsString('NEUE EVENT-ANFRAGE', $html);
        $this->assertStringContainsString('Max Mustermann', $html);
        $this->assertStringNotContainsString('FIRMEN-PROFIL', $html);
    }

    #[Test]
    public function event_request_email_has_correct_subject(): void
    {
        $mailable = new EventRequestNotification($this->sampleEventData);

        $mailable->assertHasSubject('Neue Anfrage: Hamburg am 2026-03-15');
    }

    #[Test]
    public function booking_email_renders_with_research(): void
    {
        $mailable = new BookingRequestSubmitted($this->sampleBookingData, $this->sampleResearch);
        $html = $mailable->render();

        $this->assertStringContainsString('NEUES', $html);
        $this->assertStringContainsString('John Doe', $html);
        $this->assertStringContainsString('Acme Corp', $html);
        $this->assertStringContainsString('10:00', $html);
        $this->assertStringContainsString('IT-Dienstleistungen', $html);
        $this->assertStringContainsString('mailto:john@example.com', $html);
    }

    #[Test]
    public function booking_email_renders_without_research(): void
    {
        $mailable = new BookingRequestSubmitted($this->sampleBookingData);
        $html = $mailable->render();

        $this->assertStringContainsString('John Doe', $html);
        $this->assertStringNotContainsString('FIRMEN-PROFIL', $html);
    }

    #[Test]
    public function contact_email_renders_with_research(): void
    {
        $submission = ContactSubmission::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '+49 987 654321',
            'company' => 'Smith AG',
            'inquiry_type' => 'booking',
            'message' => 'Wir planen eine Firmenfeier.',
            'status' => 'new',
        ]);

        $mailable = new ContactFormSubmitted($submission, $this->sampleResearch);
        $html = $mailable->render();

        $this->assertStringContainsString('NEUE KONTAKTANFRAGE', $html);
        $this->assertStringContainsString('Jane Smith', $html);
        $this->assertStringContainsString('Smith AG', $html);
        $this->assertStringContainsString('IT-Dienstleistungen', $html);
        $this->assertStringContainsString('mailto:jane@example.com', $html);
        $this->assertStringContainsString('Admin', $html);
    }

    #[Test]
    public function contact_email_renders_without_research(): void
    {
        $submission = ContactSubmission::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'company' => 'Smith AG',
            'inquiry_type' => 'general',
            'message' => 'Allgemeine Anfrage.',
            'status' => 'new',
        ]);

        $mailable = new ContactFormSubmitted($submission);
        $html = $mailable->render();

        $this->assertStringContainsString('NEUE KONTAKTANFRAGE', $html);
        $this->assertStringNotContainsString('FIRMEN-PROFIL', $html);
    }

    #[Test]
    public function event_request_email_without_optional_fields(): void
    {
        $minimalData = [
            'name' => 'Min User',
            'email' => 'min@test.de',
            'phone' => '',
            'company' => 'Min GmbH',
            'date' => '2026-06-01',
            'time' => '',
            'city' => 'Berlin',
            'budget' => '',
            'guests' => 'lt100',
            'package' => 'dj',
            'message' => '',
        ];

        $mailable = new EventRequestNotification($minimalData);
        $html = $mailable->render();

        $this->assertStringContainsString('Berlin', $html);
        $this->assertStringContainsString('Min User', $html);
    }
}
