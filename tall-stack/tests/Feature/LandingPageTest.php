<?php

use App\Livewire\BookingCalendarModal;
use App\Models\CalendarBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/**
 * End-to-End Tests für musikfürfirmen.de Landing Page
 *
 * Testet den kompletten User Flow vom Laden der Seite bis zur Buchung
 */
beforeEach(function () {
    // Fake mail sending to test email functionality
    Mail::fake();

    // Disable queue for testing (send emails immediately)
    config(['queue.default' => 'sync']);
});

test('landing page loads successfully', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('musikfürfirmen.de');
    $response->assertSee('Kostenloses Erstgespräch');
});

test('header is visible with correct navigation links', function () {
    $response = $this->get('/');

    // Check navigation items
    $response->assertSee('Kostenloses Erstgespräch');
    $response->assertSee('Angebote');
    $response->assertSee('Über uns');
    $response->assertSee('Kontakt');
});

test('header changes color based on scroll position', function () {
    $response = $this->get('/');

    // Header should have Alpine.js color detection
    $response->assertSee('data-section-bg');
    $response->assertSee('isDark');
});

test('hero section displays correctly', function () {
    $response = $this->get('/');

    // Check for hero content
    $response->assertSee('Livebands');
    $response->assertSee('DJs');
    $response->assertSee('Technik');
});

test('booking modal component is present on page', function () {
    $response = $this->get('/');

    // Check for Livewire component
    $response->assertSeeLivewire(BookingCalendarModal::class);
});

test('can open booking modal via button click', function () {
    Livewire::test(BookingCalendarModal::class)
        ->assertSet('isOpen', false)
        ->dispatch('openBookingModal')
        ->assertSet('isOpen', true)
        ->assertSee('Kostenloses Erstgespräch')
        ->assertSee('30 Minuten');
});

test('complete booking flow from landing page', function () {
    $date = now()->addWeekday()->format('Y-m-d');
    $time = '10:00';

    expect(CalendarBooking::count())->toBe(0);

    // User opens modal
    $component = Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal')
        ->assertSet('isOpen', true);

    // User selects a date
    $component->call('selectDate', $date)
        ->assertSet('selectedDate', $date);
    // Date is displayed in the component

    // Time slots displayed in 24h format
    $component->assertSee('09:00')
        ->assertSee('17:00');

    // User selects a time slot
    $component->call('selectTime', $time)
        ->assertSet('selectedTime', $time)
        ->assertSet('step', 3); // Should progress to contact form

    // User fills out contact form
    $component->set('name', 'Max Mustermann')
        ->set('company', 'Mustermann GmbH')
        ->set('email', 'max@example.com')
        ->set('phone', '+49 123 456789')
        ->set('message', 'Wir planen ein Firmenevent im Juni');

    // User submits the form
    $component->call('submitBooking')
        ->assertHasNoErrors()
        ->assertSet('showSuccess', true)
        ->assertSee('Vielen Dank!')
        ->assertSee('Wir haben Ihre Anfrage erhalten');

    // Verify booking was created in database
    expect(CalendarBooking::count())->toBe(1);

    $booking = CalendarBooking::first();
    expect($booking->name)->toBe('Max Mustermann');
    expect($booking->email)->toBe('max@example.com');
    expect($booking->phone)->toBe('+49 123 456789');
    expect($booking->selected_date->format('Y-m-d'))->toBe($date);
    expect($booking->selected_time->format('H:i'))->toBe($time);

    // Note: Email sending is tested separately
    // The ENV variable EVENT_REQUEST_RECIPIENTS contains:
    // kontakt@musikfürfirmen.de,moin@nickheymann.de,moin@jonasglamann.de
});

test('user cannot submit booking without required fields', function () {
    $date = now()->addWeekday()->format('Y-m-d');
    $time = '10:00';

    Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal')
        ->call('selectDate', $date)
        ->call('selectTime', $time)
        ->set('name', '')
        ->set('company', '')
        ->set('email', '')
        ->set('phone', '')
        ->call('submitBooking')
        ->assertHasErrors(['name', 'company', 'email', 'phone']);

    // No booking should be created
    expect(CalendarBooking::count())->toBe(0);

    // No emails should be sent
    Mail::assertNothingSent();
});

test('modal preserves form data when closing for localStorage persistence', function () {
    $component = Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal')
        ->set('name', 'John Doe');

    // Close the modal
    $component->call('close');

    // Data is preserved on close (for localStorage feature)
    // Alpine.js handles the actual localStorage sync and confirmation dialogs
    expect($component->get('name'))->toBe('John Doe');
    expect($component->get('isOpen'))->toBe(false);
});

test('calendar displays a month with available dates on open', function () {
    $component = Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal');

    // Check that currentMonthName property is set and not empty
    $currentMonthName = $component->get('currentMonthName');
    expect($currentMonthName)->not->toBeEmpty();
    expect($currentMonthName)->toBeString();
});

test('calendar shows weekday headers in German', function () {
    Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal')
        ->assertSee('Mo')
        ->assertSee('Di')
        ->assertSee('Mi')
        ->assertSee('Do')
        ->assertSee('Fr')
        ->assertSee('Sa')
        ->assertSee('So');
});

test('only weekdays are selectable in calendar', function () {
    $component = Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal');

    $calendarDays = $component->get('calendarDays');

    // Filter for weekend days
    $weekendDays = collect($calendarDays)->filter(fn ($day) => $day['isWeekend']);

    // All weekend days should not be available
    foreach ($weekendDays as $day) {
        expect($day['isAvailable'])->toBeFalse();
    }
});

test('time slots are displayed in 30-minute intervals', function () {
    $component = Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal');

    $slots = $component->get('availableSlots');

    // Should have all expected slots from 09:00 to 17:00
    expect($slots)->toContain('09:00');
    expect($slots)->toContain('09:30');
    expect($slots)->toContain('17:00');
    expect(count($slots))->toBe(17); // 17 slots total
});

test('selected date is displayed in correct format', function () {
    $date = now()->addWeekday();

    $component = Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal')
        ->call('selectDate', $date->format('Y-m-d'));

    // Should see day number prominently
    $component->assertSee($date->format('d'));
});

test('user can navigate between months', function () {
    $component = Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal');

    $currentMonth = $component->get('currentMonth');

    // Navigate to next month
    $component->call('nextMonth');
    $nextMonth = $component->get('currentMonth');
    expect($nextMonth)->not->toBe($currentMonth);

    // Navigate back
    $component->call('previousMonth');
    $backMonth = $component->get('currentMonth');
    expect($backMonth)->toBe($currentMonth);
});

test('success message is shown after booking', function () {
    $date = now()->addWeekday()->format('Y-m-d');

    Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal')
        ->call('selectDate', $date)
        ->call('selectTime', '10:00')
        ->set('name', 'Test User')
        ->set('company', 'Test GmbH')
        ->set('email', 'test@example.com')
        ->set('phone', '+49 123 456789')
        ->call('submitBooking')
        ->assertSet('showSuccess', true)
        ->assertSee('Vielen Dank!')
        ->assertSee('Wir haben Ihre Anfrage erhalten')
        ->assertSee('Schließen'); // Close button should be visible
});

test('modal can be closed after successful booking', function () {
    $date = now()->addWeekday()->format('Y-m-d');

    $component = Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal')
        ->call('selectDate', $date)
        ->call('selectTime', '10:00')
        ->set('name', 'Test User')
        ->set('company', 'Test GmbH')
        ->set('email', 'test@example.com')
        ->set('phone', '+49 123 456789')
        ->call('submitBooking')
        ->assertSet('showSuccess', true);

    // Close modal - showSuccess is reset via async event (reset-success-after-close)
    $component->call('close')
        ->assertSet('isOpen', false)
        ->assertDispatched('reset-success-after-close');

    // Simulate the browser dispatching the reset event after animation
    $component->dispatch('reset-success-state')
        ->assertSet('showSuccess', false);
});

test('email validation works correctly', function () {
    $date = now()->addWeekday()->format('Y-m-d');

    Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal')
        ->call('selectDate', $date)
        ->call('selectTime', '10:00')
        ->set('name', 'Test User')
        ->set('company', 'Test GmbH')
        ->set('email', 'invalid-email') // Invalid email
        ->set('phone', '+49 123 456789')
        ->call('submitBooking')
        ->assertHasErrors(['email']);

    expect(CalendarBooking::count())->toBe(0);
    Mail::assertNothingSent();
});

test('message field is optional', function () {
    $date = now()->addWeekday()->format('Y-m-d');

    Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal')
        ->call('selectDate', $date)
        ->call('selectTime', '10:00')
        ->set('name', 'Test User')
        ->set('company', 'Test GmbH')
        ->set('email', 'test@example.com')
        ->set('phone', '+49 123 456789')
        ->set('message', '') // Empty message
        ->call('submitBooking')
        ->assertHasNoErrors();

    expect(CalendarBooking::count())->toBe(1);
});

test('booking stores correct status as pending', function () {
    $date = now()->addWeekday()->format('Y-m-d');

    Livewire::test(BookingCalendarModal::class)
        ->dispatch('openBookingModal')
        ->call('selectDate', $date)
        ->call('selectTime', '10:00')
        ->set('name', 'Test User')
        ->set('company', 'Test GmbH')
        ->set('email', 'test@example.com')
        ->set('phone', '+49 123 456789')
        ->call('submitBooking');

    $booking = CalendarBooking::first();
    expect($booking->status)->toBe('pending');
});
