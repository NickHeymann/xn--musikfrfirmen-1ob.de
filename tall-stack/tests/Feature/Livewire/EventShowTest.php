<?php

namespace Tests\Feature\Livewire;

use App\Livewire\EventShow;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EventShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_submit_booking_for_event()
    {
        // Create a published event
        $event = Event::factory()->create([
            'status' => 'published',
            'musicians_needed' => 5,
            'price_per_musician' => 100,
        ]);

        // Test Livewire component
        Livewire::test(EventShow::class, ['event' => $event])
            ->set('company_name', 'Test Corp')
            ->set('contact_person', 'John Doe')
            ->set('email', 'john@test.com')
            ->set('phone', '+49 123 456789')
            ->set('num_musicians', 2)
            ->call('submitBooking')
            ->assertHasNoErrors();

        // Verify booking was created
        $this->assertDatabaseCount('bookings', 1);

        $booking = Booking::first();
        $this->assertEquals('Test Corp', $booking->company_name);
        $this->assertEquals('John Doe', $booking->contact_person);
        $this->assertEquals('john@test.com', $booking->email);
        $this->assertEquals(200, $booking->total_price); // 2 musicians * €100
    }

    /** @test */
    public function it_validates_required_fields_on_booking_submission()
    {
        $event = Event::factory()->create([
            'status' => 'published',
            'musicians_needed' => 5,
        ]);

        Livewire::test(EventShow::class, ['event' => $event])
            ->call('submitBooking')
            ->assertHasErrors([
                'company_name',
                'contact_person',
                'email',
                'phone',
            ]);

        // No booking should be created
        $this->assertDatabaseCount('bookings', 0);
    }

    /** @test */
    public function it_validates_email_format_on_booking()
    {
        $event = Event::factory()->create([
            'status' => 'published',
            'musicians_needed' => 5,
        ]);

        Livewire::test(EventShow::class, ['event' => $event])
            ->set('company_name', 'Test Corp')
            ->set('contact_person', 'John Doe')
            ->set('email', 'invalid-email')
            ->set('phone', '+49 123 456789')
            ->set('num_musicians', 2)
            ->call('submitBooking')
            ->assertHasErrors(['email']);

        $this->assertDatabaseCount('bookings', 0);
    }

    /** @test */
    public function it_validates_num_musicians_does_not_exceed_event_capacity()
    {
        $event = Event::factory()->create([
            'status' => 'published',
            'musicians_needed' => 3,
        ]);

        Livewire::test(EventShow::class, ['event' => $event])
            ->set('company_name', 'Test Corp')
            ->set('contact_person', 'John Doe')
            ->set('email', 'john@test.com')
            ->set('phone', '+49 123 456789')
            ->set('num_musicians', 5) // Exceeds musicians_needed (3)
            ->call('submitBooking')
            ->assertHasErrors(['num_musicians']);

        $this->assertDatabaseCount('bookings', 0);
    }

    /** @test */
    public function it_resets_form_fields_after_successful_booking()
    {
        $event = Event::factory()->create([
            'status' => 'published',
            'musicians_needed' => 5,
            'price_per_musician' => 100,
        ]);

        Livewire::test(EventShow::class, ['event' => $event])
            ->set('company_name', 'Test Corp')
            ->set('contact_person', 'John Doe')
            ->set('email', 'john@test.com')
            ->set('phone', '+49 123 456789')
            ->set('num_musicians', 2)
            ->call('submitBooking')
            ->assertSet('company_name', '')
            ->assertSet('contact_person', '')
            ->assertSet('email', '')
            ->assertSet('phone', '')
            ->assertSet('num_musicians', 1)
            ->assertSet('special_requests', '');
    }
}
