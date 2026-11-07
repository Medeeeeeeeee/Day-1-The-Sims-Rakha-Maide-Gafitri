<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_user_and_redirects()
    {
        $response = $this->post('/register', [
            'nim' => 'NIM12345678',
            'email' => 'jane@example.com',
            'gender' => 'Pria',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'nim' => 'NIM12345678'
        ]);
    }

    public function test_login_with_existing_user()
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_logout_works()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_view_profile()
    {
        $user = User::factory()->create(['nim' => 'NIM0001']);
        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $response->assertSee('NIM0001');
        $response->assertSee($user->email);
        // notif count zero by default
        $response->assertSee('0');
    }
}
