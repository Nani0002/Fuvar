<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            "name" => "admin",
            "password" => "pass",
            "email" => "email@gmail.com",
            "admin" => true
        ]);

        User::factory()->create([
            "name" => "Józsi",
            "password" => "pass",
            "email" => "email2@gmail.com",
        ]);

        User::factory(3)->create();

        $this->call([
            JobSeeder::class,
            VehicleSeeder::class
        ]);
    }
}
