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

    private string $admin_access_token;
    private string $main_admin_access_token;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin_access_token = $this->auth('admin');
        $this->main_admin_access_token = $this->auth('main_admin');
    }

    public function test_get_all_categories()
    {
        $categories = Category::factory(20)->create();
        $response = $this->getJson('/api/categories');

        $this->assertDatabaseCount('categories', $categories->count());
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

    public function test_get_category_that_exists_by_id()
    {
        $category = Category::factory()->create();

        $response = $this->getJson('/api/categories/' . $category->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => collect($category)->except(['created_at', 'updated_at'])->toArray()
            ]);
    }

    public function test_get_category_that_not_exists_by_id()
    {
        $response = $this->getJson('/api/categories/' . 0);

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
            'number' => $category->number,
            'is_active' => $category->is_active
        ], [
            'Authorization' => 'Bearer ' . $this->main_admin_access_token
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => collect($category)->except(['created_at', 'updated_at', 'is_active'])->toArray()
            ]);
    }

    public function test_authorized_invalid_request_create_category()
    {

    }

    public function test_unauthorized_create_category()
    {
        $category = Category::factory()->make();
        $response = $this->postJson('/api/categories', [
            'name' => $category->name,
            'number' => $category->number
        ], [
            'Authorization' => 'Bearer ' . $this->admin_access_token
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

    public function test_authorized_update_category_by_id()
    {
        $category = Category::factory()->create();
        $new_category = Category::factory()->make();

        $response = $this->postJson(
            '/api/categories/' . $category->id,
            collect($new_category)->except('id')->toArray(),
            [
                'Authorization' => 'Bearer ' . $this->main_admin_access_token
            ]
        );

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => collect($new_category)->except(['created_at', 'updated_at'])->toArray()
            ]);
    }

    public function test_authorized_invalid_request_update_category_by_id()
    {

    }

    public function test_unauthorized_update_category_by_id()
    {
        $category = Category::factory()->create();
        $new_category = Category::factory()->make();

        $response = $this->postJson(
            '/api/categories/' . $category->id,
            collect($new_category)->except('id')->toArray(),
            [
                'Authorization' => 'Bearer ' . $this->admin_access_token
            ]
        );

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Действие не авторизовано'
            ]);
    }

    public function test_unauthenticated_update_category_by_id()
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

    public function test_update_non_existing_category_by_id()
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

    public function test_authorized_delete_category_by_id()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson('/api/categories/' . $category->id, [], [
            'Authorization' => 'Bearer ' . $this->main_admin_access_token
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => collect($category)->except(['created_at', 'updated_at'])->toArray()
            ]);
    }

    public function test_unauthorized_delete_category_by_id()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson('/api/categories/' . $category->id, [], [
            'Authorization' => 'Bearer ' . $this->admin_access_token
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Действие не авторизовано'
            ]);
    }

    public function test_unauthenticated_delete_category_by_id()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson('/api/categories/' . $category->id);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Ошибка аутентификации'
            ]);
    }

    public function test_delete_non_existing_category_by_id()
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
        $user = User::factory()->create([
            'role' => $role
        ]);
        $response = json_decode($this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password'
        ])->getContent());

        return $response->data->access_token;
    }
}

