<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $numMusicians = $this->faker->numberBetween(1, 5);
        $pricePerMusician = $this->faker->numberBetween(80, 300);

        return [
            'event_id' => Event::factory(),
            'company_name' => $this->faker->company(),
            'contact_person' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'num_musicians' => $numMusicians,
            'total_price' => $numMusicians * $pricePerMusician,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'paid', 'cancelled']),
            'special_requests' => $this->faker->optional()->sentence(),
        ];
    }
}
