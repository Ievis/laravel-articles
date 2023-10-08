<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_login_page_returns_a_successful_response(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_main_page_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_register_page_returns_a_successful_response()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }
}
