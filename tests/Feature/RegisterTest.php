<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => fake()->email(),
            'password' => 'password',
            'password_confirmation' => 'password'
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
