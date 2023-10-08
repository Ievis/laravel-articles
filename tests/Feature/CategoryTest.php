<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_categories()
    {
        $response = $this->get('/api/categories');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'is_active',
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'path',
                    'to'
                ]
            ]);
    }

    public function test_get_existing_category_by_id()
    {
        $category = Category::factory()->create();

        $response = $this->get('/api/categories/' . $category->id);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'number',
                    'is_active'
                ]
            ]);
    }

    public function test_get_non_existing_category_by_id()
    {
        $response = $this->get('/api/categories/' . 0);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Ресурс не найден'
            ]);
    }

    public function test_authorized_create_category()
    {
        $category = Category::factory()->make();
        $response = $this->postJson('/api/categories', [
            'name' => $category->name,
            'number' => $category->number
        ], [
            'Authorization' => 'Bearer ' . $this->auth('main_admin')
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => []
            ]);
    }

    public function test_unauthorized_create_category()
    {
        $category = Category::factory()->make();
        $response = $this->postJson('/api/categories', [
            'name' => $category->name,
            'number' => $category->number
        ], [
            'Authorization' => 'Bearer ' . $this->auth('admin')
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Действие не авторизовано'
            ]);
    }

    public function test_unauthenticated_create_category()
    {
        $category = Category::factory()->make();
        $response = $this->postJson('/api/categories', [
            'name' => $category->name,
            'number' => $category->number
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Ошибка аутентификации'
            ]);
    }

    public function test_authorized_update_category()
    {
        $category = Category::factory()->create();
        $new_category = Category::factory()->make();
        $response = $this->postJson(
            '/api/categories/' . $category->id,
            collect($new_category)->except('id')->toArray(),
            [
                'Authorization' => 'Bearer ' . $this->auth('main_admin')
            ]
        );

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true
            ])
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'number',
                    'is_active'
                ]
            ]);
    }

    public function test_unauthorized_update_category()
    {
        $category = Category::factory()->create();
        $new_category = Category::factory()->make();
        $response = $this->postJson(
            '/api/categories/' . $category->id,
            collect($new_category)->except('id')->toArray(),
            [
                'Authorization' => 'Bearer ' . $this->auth('admin')
            ]
        );

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Действие не авторизовано'
            ]);
    }

    public function test_unauthenticated_update_category()
    {
        $category = Category::factory()->create();
        $new_category = Category::factory()->make();
        $response = $this->postJson(
            '/api/categories/' . $category->id,
            collect($new_category)->except('id')->toArray()
        );

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Ошибка аутентификации'
            ]);
    }

    public function test_update_non_existing_category()
    {
        $category = Category::factory()->make();
        $response = $this->postJson('/api/categories/' . 0, [
            collect($category)->except('id')->toArray()
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Ресурс не найден'
            ]);
    }

    public function test_authorized_delete_category()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson('/api/categories/' . $category->id, [], [
            'Authorization' => 'Bearer ' . $this->auth('main_admin')
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'success' => true
            ])
            ->assertJsonStructure([
               'success',
               'data' => [
                   'id',
                   'name',
                   'number',
                   'is_active'
               ]
            ]);
    }

    public function test_unauthorized_delete_category()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson('/api/categories/' . $category->id, [], [
            'Authorization' => 'Bearer ' . $this->auth('admin')
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Действие не авторизовано'
            ]);
    }

    public function test_unauthenticated_delete_category()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson('/api/categories/' . $category->id);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Ошибка аутентификации'
            ]);
    }

    public function test_delete_non_existing_category()
    {
        $response = $this->deleteJson('/api/categories/' . 0);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Ресурс не найден'
            ]);
    }

    private function auth(string $role)
    {
        $user = User::create([
            'email' => fake()->email(),
            'role' => $role,
            'password' => Hash::make('password')
        ]);
        $response = json_decode($this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ])->getContent());

        return $response->data->access_token;
    }
}

