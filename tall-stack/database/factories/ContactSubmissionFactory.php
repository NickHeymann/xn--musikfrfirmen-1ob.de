<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactSubmission>
 */
class ContactSubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'company' => $this->faker->optional()->company(),
            'inquiry_type' => $this->faker->randomElement(['general', 'booking', 'partnership', 'other']),
            'message' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['new', 'contacted', 'converted', 'archived']),
        ];
    }
}
