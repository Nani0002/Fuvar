<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class FeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_valid_credentials(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->post('/login', [
            '_token' => csrf_token(),
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_unauthorized_user_cannot_access_protected_route(): void
    {
        $response = $this->getJson('/api/jobs');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_jobs(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/jobs');
        $response->assertStatus(200);
    }

    public function test_user_specific_route(): void
    {
        $admin = User::factory()->create(['admin' => true]);
        $user = User::factory()->create(['admin' => false]);

        $this->actingAs($user, 'sanctum')->get('/api/jobs/1')->assertStatus(403);

        $this->actingAs($user, 'sanctum')->get("/api/jobs/{$user->id}")->assertStatus(200);

        $this->actingAs($admin, 'sanctum')->get('/api/jobs/1')->assertStatus(200);

        $this->actingAs($admin, 'sanctum')->get('/api/jobs/2')->assertStatus(200);
    }
}
