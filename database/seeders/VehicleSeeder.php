<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carriers = User::all()->where("admin", false);
        foreach ($carriers as $carrier) {
            Vehicle::factory()->for($carrier)->create();
        }
    }
}
