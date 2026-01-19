<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventBookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_correct_total_price_for_booking()
    {
        $event = Event::factory()->create([
            'price_per_musician' => 150,
        ]);

        $booking = Booking::create([
            'event_id' => $event->id,
            'company_name' => 'Test Corp',
            'contact_person' => 'John Doe',
            'email' => 'john@test.com',
            'phone' => '+49 123 456789',
            'num_musicians' => 3,
            'total_price' => $event->price_per_musician * 3,
            'status' => 'pending',
        ]);

        $this->assertEquals(450, $booking->total_price);
    }

    /** @test */
    public function it_creates_booking_with_correct_default_status()
    {
        $event = Event::factory()->create();

        $booking = Booking::create([
            'event_id' => $event->id,
            'company_name' => 'Test Corp',
            'contact_person' => 'John Doe',
            'email' => 'john@test.com',
            'phone' => '+49 123 456789',
            'num_musicians' => 2,
            'total_price' => 200,
            'status' => 'pending',
        ]);

        $this->assertEquals('pending', $booking->status);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function it_associates_booking_with_event_correctly()
    {
        $event = Event::factory()->create([
            'title' => 'Corporate Jazz Night',
        ]);

        $booking = Booking::create([
            'event_id' => $event->id,
            'company_name' => 'Test Corp',
            'contact_person' => 'John Doe',
            'email' => 'john@test.com',
            'phone' => '+49 123 456789',
            'num_musicians' => 2,
            'total_price' => 200,
            'status' => 'pending',
        ]);

        $this->assertEquals($event->id, $booking->event_id);
        $this->assertEquals('Corporate Jazz Night', $booking->event->title);
    }

    /** @test */
    public function it_updates_booking_status_correctly()
    {
        $event = Event::factory()->create();

        $booking = Booking::create([
            'event_id' => $event->id,
            'company_name' => 'Test Corp',
            'contact_person' => 'John Doe',
            'email' => 'john@test.com',
            'phone' => '+49 123 456789',
            'num_musicians' => 2,
            'total_price' => 200,
            'status' => 'pending',
        ]);

        $booking->update(['status' => 'confirmed']);

        $this->assertEquals('confirmed', $booking->fresh()->status);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
        ]);
    }

    /** @test */
    public function it_can_retrieve_all_bookings_for_an_event()
    {
        $event = Event::factory()->create();

        // Create multiple bookings for the same event
        Booking::factory()->count(3)->create([
            'event_id' => $event->id,
        ]);

        $this->assertCount(3, $event->bookings);
    }

    /** @test */
    public function it_stores_special_requests_correctly()
    {
        $event = Event::factory()->create();

        $booking = Booking::create([
            'event_id' => $event->id,
            'company_name' => 'Test Corp',
            'contact_person' => 'John Doe',
            'email' => 'john@test.com',
            'phone' => '+49 123 456789',
            'num_musicians' => 2,
            'total_price' => 200,
            'status' => 'pending',
            'special_requests' => 'Please bring a piano and microphones',
        ]);

        $this->assertEquals('Please bring a piano and microphones', $booking->special_requests);
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'special_requests' => 'Please bring a piano and microphones',
        ]);
    }
}
