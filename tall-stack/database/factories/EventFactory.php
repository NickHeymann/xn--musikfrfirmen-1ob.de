<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'location' => $this->faker->city() . ', ' . $this->faker->streetAddress(),
            'start_time' => $this->faker->dateTimeBetween('now', '+3 months'),
            'end_time' => $this->faker->dateTimeBetween('+3 months', '+6 months'),
            'capacity' => $this->faker->numberBetween(50, 500),
            'price_per_musician' => $this->faker->numberBetween(80, 300),
            'musicians_needed' => $this->faker->numberBetween(1, 10),
            'music_style' => $this->faker->randomElement(['Jazz', 'Classical', 'Rock', 'Pop', 'Blues', 'Folk']),
            'status' => $this->faker->randomElement(['draft', 'published', 'booked', 'completed', 'cancelled']),
            'requirements' => [
                'equipment' => $this->faker->randomElement(['Microphones', 'Piano', 'Drum set', 'PA system']),
                'dress_code' => $this->faker->randomElement(['Formal', 'Business Casual', 'Casual']),
            ],
        ];
    }
}
