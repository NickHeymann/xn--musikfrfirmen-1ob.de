<?php

namespace Tests\Feature;

use App\Livewire\BookingCalendarModal;
use App\Models\CalendarBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BookingCalendarModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_renders_successfully(): void
    {
        Livewire::test(BookingCalendarModal::class)
            ->assertStatus(200);
    }

    public function test_modal_opens_when_event_is_dispatched(): void
    {
        Livewire::test(BookingCalendarModal::class)
            ->assertSet('isOpen', false)
            ->dispatch('openBookingModal')
            ->assertSet('isOpen', true);
    }

    public function test_can_navigate_to_next_month(): void
    {
        $component = Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal');

        $currentMonth = $component->get('currentMonth');

        $component->call('nextMonth');

        $newMonth = $component->get('currentMonth');

        $this->assertNotEquals($currentMonth, $newMonth);
    }

    public function test_can_navigate_to_previous_month(): void
    {
        $component = Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('nextMonth');

        $currentMonth = $component->get('currentMonth');

        $component->call('previousMonth');

        $newMonth = $component->get('currentMonth');

        $this->assertNotEquals($currentMonth, $newMonth);
    }

    public function test_can_select_a_date(): void
    {
        $date = now()->addWeekday()->format('Y-m-d');

        Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('selectDate', $date)
            ->assertSet('selectedDate', $date)
            ->assertSet('step', 1);
    }

    public function test_can_select_a_time_slot(): void
    {
        $date = now()->addWeekday()->format('Y-m-d');
        $time = '10:00';

        Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('selectDate', $date)
            ->call('selectTime', $time)
            ->assertSet('selectedTime', $time)
            ->assertSet('step', 3); // Should progress to contact form
    }

    public function test_displays_time_slots_in_24h_format(): void
    {
        $component = Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal');

        $slots = $component->get('availableSlots');

        // All slots should be in 24h format (HH:MM)
        expect($slots)->toContain('09:00');
        expect($slots)->toContain('17:00');
        expect($slots)->not->toContain('9:00 am');
        expect($slots)->not->toContain('5:00 pm');
    }

    public function test_validates_required_fields_when_submitting_booking(): void
    {
        $date = now()->addWeekday()->format('Y-m-d');
        $time = '10:00';

        Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('selectDate', $date)
            ->call('selectTime', $time)
            ->set('name', '')
            ->set('email', '')
            ->set('phone', '')
            ->call('submitBooking')
            ->assertHasErrors(['name', 'email', 'phone']);
    }

    public function test_validates_email_format(): void
    {
        $date = now()->addWeekday()->format('Y-m-d');
        $time = '10:00';

        Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('selectDate', $date)
            ->call('selectTime', $time)
            ->set('name', 'John Doe')
            ->set('email', 'invalid-email')
            ->set('phone', '+49 123 456789')
            ->call('submitBooking')
            ->assertHasErrors(['email']);
    }

    public function test_can_submit_booking_with_valid_data(): void
    {
        $date = now()->addWeekday()->format('Y-m-d');
        $time = '10:00';

        $this->assertEquals(0, CalendarBooking::count());

        Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('selectDate', $date)
            ->call('selectTime', $time)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '+49 123 456789')
            ->set('message', 'Looking forward to discussing our event')
            ->call('submitBooking')
            ->assertHasNoErrors()
            ->assertSet('showSuccess', true);

        $this->assertEquals(1, CalendarBooking::count());

        $booking = CalendarBooking::first();
        $this->assertEquals('John Doe', $booking->name);
        $this->assertEquals('john@example.com', $booking->email);
        $this->assertEquals('+49 123 456789', $booking->phone);
        $this->assertEquals($date, $booking->selected_date->format('Y-m-d')); // Format as date string
        $this->assertEquals($time, $booking->selected_time->format('H:i')); // Format as time string
        $this->assertEquals('pending', $booking->status);
    }

    public function test_can_go_back_from_contact_form_to_time_selection(): void
    {
        $date = now()->addWeekday()->format('Y-m-d');
        $time = '10:00';

        Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('selectDate', $date)
            ->call('selectTime', $time)
            ->assertSet('step', 3)
            ->call('goBack')
            ->assertSet('step', 2); // Goes back to step 2, not step 1
        // Note: selectedTime is NOT cleared when going back from step 3 to 2
        // This is intentional UX - user can see their selected time again
    }

    public function test_modal_closes_and_resets_state(): void
    {
        $date = now()->addWeekday()->format('Y-m-d');

        Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('selectDate', $date)
            ->set('name', 'John Doe')
            ->call('close')
            ->assertSet('isOpen', false)
            ->assertSet('selectedDate', null)
            ->assertSet('name', '');
    }

    public function test_message_field_is_optional(): void
    {
        $date = now()->addWeekday()->format('Y-m-d');
        $time = '10:00';

        Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('selectDate', $date)
            ->call('selectTime', $time)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '+49 123 456789')
            ->set('message', '') // Empty message
            ->call('submitBooking')
            ->assertHasNoErrors();

        $this->assertEquals(1, CalendarBooking::count());
    }

    public function test_available_slots_are_displayed_correctly(): void
    {
        $date = now()->addWeekday()->format('Y-m-d');

        $component = Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('selectDate', $date);

        // Check that all expected time slots are available
        $expectedSlots = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00',
        ];

        $availableSlots = $component->get('availableSlots');
        $this->assertEquals($expectedSlots, $availableSlots);
    }

    public function test_calendar_displays_weekday_headers(): void
    {
        Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->assertSee('Mo')
            ->assertSee('Di')
            ->assertSee('Mi')
            ->assertSee('Do')
            ->assertSee('Fr')
            ->assertSee('Sa')
            ->assertSee('So');
    }

    public function test_success_message_is_shown_after_booking_submission(): void
    {
        $date = now()->addWeekday()->format('Y-m-d');
        $time = '10:00';

        Livewire::test(BookingCalendarModal::class)
            ->dispatch('openBookingModal')
            ->call('selectDate', $date)
            ->call('selectTime', $time)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '+49 123 456789')
            ->call('submitBooking')
            ->assertSet('showSuccess', true)
            ->assertSee('Vielen Dank!')
            ->assertSee('Wir haben Ihre Anfrage erhalten');
    }
}
