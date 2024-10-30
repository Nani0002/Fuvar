<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "start_address" => fake()->address(),
            "end_address" => fake()->address(),
            "addressee_name" => fake()->name(),
            "addressee_phone" => fake()->phoneNumber(),
            "status" => fake()->numberBetween(0, 3)
        ];
    }
}
