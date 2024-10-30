<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake();

        $l1 = strtoupper($faker->randomLetter());
        $l2 = strtoupper($faker->randomLetter());
        $l3 = strtoupper($faker->randomLetter());
        return [
            "brand" => fake()->randomElement(["Mercedes", "Ford", "Renault", "Opel"]),
            "type" => fake()->randomElement(["Type1", "Type2", "Type3", "Type4", "Type5", "Type6"]),
            "registration" => "{$l1}{$l2}{$l3}-{$faker->randomDigit()}{$faker->randomDigit()}{$faker->randomDigit()}",
        ];
    }
}
