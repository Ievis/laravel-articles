<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::create([
            'email' => fake()->email(),
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true,
                'token_type' => 'bearer',
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in'
                ]
            ]);
    }
}
