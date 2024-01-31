<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $title = "Lorem ipsum dolor sit amet";
    protected $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";


    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function test_user_can_login_and_get_token()
    {
        // Create a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => bcrypt('secret123$'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@gmail.com',
            'password' => 'secret123$',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['accessToken']);
        $token = $response->json('accessToken');
        Sanctum::actingAs($user);

        $this->getJson('/api/v1/auth/logout');
        $this->assertFalse(auth()->check());

        /*$user = User::factory()->create();
        Sanctum::actingAs($user);

        // Simulate a GET request to the logout route
        $response = $this->getJson('/api/v1/auth/logout');

        // Assert that the response has a successful status code (e.g., 200)
        $response->assertStatus(200);*/
    }

}
