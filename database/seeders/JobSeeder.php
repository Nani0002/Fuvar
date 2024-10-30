<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carriers = User::all()->where("admin", false);
        foreach ($carriers as $carrier) {
            Job::factory(3)->for($carrier)->create();
        }
    }
}
