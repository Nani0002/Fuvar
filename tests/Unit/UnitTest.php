<?php

namespace Tests\Unit;

use App\Models\Job;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_admin(): void
    {
        $adminUser = User::factory()->create([
            "admin" => true
        ]);

        $this->assertTrue($adminUser->isAdmin());
    }

    public function test_user_is_not_admin(): void
    {
        $notAdminUser = User::factory()->create();
        $this->assertFalse($notAdminUser->isAdmin());
    }

    public function test_user_vehicle_connection(): void
    {
        $user = User::factory()->create();
        Vehicle::factory()->for($user)->create();

        $this->assertNotNull($user->vehicle);
    }

    public function test_user_job_connection(): void{
        $user = User::factory()->create();
        $job = Job::factory()->create();

        $this->assertNull($job->user);
        $job->user_id = $user->id;

        $job->update();

        $this->assertEquals($user->id, $job->user_id);

        $job = Job::with('user')->find($job->id);
        $this->assertEquals($user->name, $job->user->name);

        $this->assertEquals(1, count($user->jobs()->get()));

        Job::factory(2)->for($user)->create();
        $this->assertEquals(3, count($user->jobs()->get()));
    }
}
