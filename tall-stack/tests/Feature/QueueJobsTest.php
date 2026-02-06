<?php

namespace Tests\Feature;

use App\Jobs\SendBookingNotification;
use App\Jobs\SendContactFormNotification;
use App\Jobs\SendEventRequestNotification;
use App\Livewire\BookingCalendarModal;
use App\Livewire\ContactForm;
use App\Livewire\EventRequestModal;
use App\Mail\BookingRequestSubmitted;
use App\Mail\ContactFormSubmitted;
use App\Mail\EventRequestNotification;
use App\Models\ContactSubmission;
use App\Services\CompanyResearchService;
use App\Services\GoogleSheetsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class QueueJobsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function event_request_modal_dispatches_job_on_submit(): void
    {
        Queue::fake();

        Livewire::test(EventRequestModal::class)
            ->set('date', now()->addWeek()->format('Y-m-d'))
            ->set('city', 'Hamburg')
            ->set('guests', 'lt100')
            ->call('nextStep')
            ->set('package', 'band')
            ->call('nextStep')
            ->set('firstname', 'Max')
            ->set('lastname', 'Mustermann')
            ->set('company', 'Test GmbH')
            ->set('email', 'max@test.de')
            ->set('privacy', true)
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('submitStatus', 'success');

        Queue::assertPushed(SendEventRequestNotification::class, function ($job) {
            return $job->eventData['email'] === 'max@test.de'
                && $job->eventData['city'] === 'Hamburg'
                && $job->eventData['package'] === 'band';
        });
    }

    #[Test]
    public function contact_form_dispatches_job_on_submit(): void
    {
        Queue::fake();

        Livewire::test(ContactForm::class)
            ->set('name', 'John Doe')
            ->set('email', 'john@test.com')
            ->set('company', 'Doe Industries')
            ->set('inquiry_type', 'booking')
            ->set('message', 'I need a band for our corporate event')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseCount('contact_submissions', 1);

        Queue::assertPushed(SendContactFormNotification::class, function ($job) {
            return $job->submission->email === 'john@test.com';
        });
    }

    #[Test]
    public function booking_calendar_dispatches_job_on_submit(): void
    {
        Queue::fake();

        $date = now()->addWeekday()->format('Y-m-d');

        Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('selectDate', $date)
            ->call('selectTime', '10:00')
            ->set('name', 'John Doe')
            ->set('company', 'Doe Corp')
            ->set('email', 'john@example.com')
            ->set('phone', '+49 123 456789')
            ->call('submitBooking')
            ->assertHasNoErrors();

        Queue::assertPushed(SendBookingNotification::class, function ($job) {
            return $job->bookingData['email'] === 'john@example.com'
                && $job->bookingData['company'] === 'Doe Corp';
        });
    }

    #[Test]
    public function event_request_job_sends_email_with_research(): void
    {
        Mail::fake();

        $sheetsService = $this->mock(GoogleSheetsService::class);
        $sheetsService->shouldReceive('createEventRequest')
            ->once()
            ->andReturn(true);

        $researchService = $this->mock(CompanyResearchService::class);
        $researchService->shouldReceive('research')
            ->once()
            ->with('Test GmbH')
            ->andReturn(['industry' => 'IT', 'sources' => []]);

        $job = new SendEventRequestNotification([
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
            'message' => 'Test message',
        ]);

        $job->handle($sheetsService, $researchService);

        Mail::assertSent(EventRequestNotification::class, function ($mail) {
            return $mail->eventData['email'] === 'max@test.de'
                && $mail->companyResearch !== null
                && $mail->companyResearch['industry'] === 'IT';
        });
    }

    #[Test]
    public function contact_form_job_sends_email_with_research(): void
    {
        Mail::fake();

        $researchService = $this->mock(CompanyResearchService::class);
        $researchService->shouldReceive('research')
            ->once()
            ->with('Test Corp')
            ->andReturn(['industry' => 'Finance', 'sources' => []]);

        $submission = ContactSubmission::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'company' => 'Test Corp',
            'inquiry_type' => 'general',
            'message' => 'Test message',
            'status' => 'new',
        ]);

        $job = new SendContactFormNotification($submission);
        $job->handle($researchService);

        Mail::assertSent(ContactFormSubmitted::class, function ($mail) {
            return $mail->submission->email === 'john@test.com'
                && $mail->companyResearch !== null
                && $mail->companyResearch['industry'] === 'Finance';
        });

        // Verify research was persisted to model
        $submission->refresh();
        $this->assertNotNull($submission->company_research);
        $this->assertEquals('Finance', $submission->company_research['industry']);
    }

    #[Test]
    public function booking_notification_job_sends_email_with_research(): void
    {
        Mail::fake();

        $sheetsService = $this->mock(GoogleSheetsService::class);
        $sheetsService->shouldReceive('createBookingRequest')
            ->once()
            ->andReturn(true);

        $researchService = $this->mock(CompanyResearchService::class);
        $researchService->shouldReceive('research')
            ->once()
            ->with('Acme Corp')
            ->andReturn(['industry' => 'Manufacturing', 'sources' => []]);

        $job = new SendBookingNotification([
            'name' => 'John Doe',
            'company' => 'Acme Corp',
            'email' => 'john@example.com',
            'phone' => '+49 123 456789',
            'selectedDate' => '2026-03-15',
            'selectedTime' => '10:00',
            'message' => 'Test booking',
        ]);

        $job->handle($sheetsService, $researchService);

        Mail::assertSent(BookingRequestSubmitted::class, function ($mail) {
            return $mail->bookingData['email'] === 'john@example.com'
                && $mail->companyResearch !== null
                && $mail->companyResearch['industry'] === 'Manufacturing';
        });
    }

    #[Test]
    public function jobs_send_email_without_research_when_research_fails(): void
    {
        Mail::fake();

        $sheetsService = $this->mock(GoogleSheetsService::class);
        $sheetsService->shouldReceive('createEventRequest')
            ->once()
            ->andReturn(true);

        $researchService = $this->mock(CompanyResearchService::class);
        $researchService->shouldReceive('research')
            ->once()
            ->andThrow(new \Exception('API error'));

        $job = new SendEventRequestNotification([
            'name' => 'Max',
            'firstname' => 'Max',
            'lastname' => '',
            'email' => 'max@test.de',
            'phone' => '',
            'company' => 'Test',
            'date' => '2026-03-15',
            'time' => '',
            'city' => 'Berlin',
            'budget' => '',
            'guests' => 'lt100',
            'package' => 'dj',
            'message' => '',
        ]);

        $job->handle($sheetsService, $researchService);

        // Email should still be sent, just without research
        Mail::assertSent(EventRequestNotification::class, function ($mail) {
            return $mail->companyResearch === null;
        });
    }

    #[Test]
    public function jobs_handle_email_failure_gracefully(): void
    {
        Mail::shouldReceive('to->send')
            ->andThrow(new \Exception('SMTP connection failed'));

        $sheetsService = $this->mock(GoogleSheetsService::class);
        $sheetsService->shouldReceive('createEventRequest')
            ->once()
            ->andReturn(true);

        $researchService = $this->mock(CompanyResearchService::class);
        $researchService->shouldReceive('research')
            ->once()
            ->andReturn(null);

        $job = new SendEventRequestNotification([
            'name' => 'Max',
            'firstname' => 'Max',
            'lastname' => '',
            'email' => 'max@test.de',
            'phone' => '',
            'company' => 'Test',
            'date' => '2026-03-15',
            'time' => '',
            'city' => 'Berlin',
            'budget' => '',
            'guests' => 'lt100',
            'package' => 'dj',
            'message' => '',
        ]);

        // Should not throw - email failure is caught, Google Sheets still runs
        $job->handle($sheetsService, $researchService);

        $this->assertTrue(true);
    }

    #[Test]
    public function jobs_handle_google_sheets_failure_gracefully(): void
    {
        Mail::fake();

        $sheetsService = $this->mock(GoogleSheetsService::class);
        $sheetsService->shouldReceive('createBookingRequest')
            ->once()
            ->andThrow(new \Exception('Google API unavailable'));

        $researchService = $this->mock(CompanyResearchService::class);
        $researchService->shouldReceive('research')
            ->once()
            ->andReturn(null);

        $job = new SendBookingNotification([
            'name' => 'John Doe',
            'company' => 'Test Corp',
            'email' => 'john@example.com',
            'phone' => '+49 123 456789',
            'selectedDate' => '2026-03-15',
            'selectedTime' => '10:00',
            'message' => '',
        ]);

        // Should not throw - Sheets failure is caught, email still sent
        $job->handle($sheetsService, $researchService);

        Mail::assertSent(BookingRequestSubmitted::class);
    }
}
