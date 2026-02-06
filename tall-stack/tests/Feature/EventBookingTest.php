<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EventBookingTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_calculates_correct_total_price_for_booking(): void
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

    #[Test]
    public function it_creates_booking_with_correct_default_status(): void
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

    #[Test]
    public function it_associates_booking_with_event_correctly(): void
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

    #[Test]
    public function it_updates_booking_status_correctly(): void
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

    #[Test]
    public function it_can_retrieve_all_bookings_for_an_event(): void
    {
        $event = Event::factory()->create();

        // Create multiple bookings for the same event
        Booking::factory()->count(3)->create([
            'event_id' => $event->id,
        ]);

        $this->assertCount(3, $event->bookings);
    }

    #[Test]
    public function it_stores_special_requests_correctly(): void
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
