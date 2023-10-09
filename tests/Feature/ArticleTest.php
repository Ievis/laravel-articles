<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    private string $admin_access_token;
    private string $main_admin_access_token;

    public function setUp(): void
    {
        parent::setUp();

        Category::factory(20)->create();
        $this->admin_access_token = $this->auth('admin');
        $this->main_admin_access_token = $this->auth('main_admin');
    }

    public function test_get_all_articles()
    {
        Article::factory(20)->create();

        $response = $this->getJson('/api/articles');

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
                        'slug',
                        'category',
                        'image',
                        'is_active',
                        'number_in_category'
                    ]
                ]
            ]);
    }

    public function test_get_article_that_exists_by_id()
    {
        $article = Article::factory()->create();

        $response = $this->getJson('/api/articles/' . $article->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => array_merge(
                    collect($article)->except(['created_at', 'updated_at', 'is_active', 'category_id'])->toArray(), [
                    'is_active' => (int)$article->is_active,
                    'category' => $article->category()->first()->name
                ])
            ]);
    }

    public function test_get_article_that_not_exists_by_id()
    {
        $response = $this->getJson('/api/articles/' . 0);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Ресурс не найден'
            ]);
    }

    public function test_authorized_create_article()
    {
        $category = Category::inRandomOrder()->first();
        $image = UploadedFile::fake()->image('avatar.png');
        $article = Article::factory()->make([
            'image' => 'images/' . $image->hashName()
        ]);

        $response = $this->postJson('/api/articles', [
            'name' => $article->name,
            'slug' => $article->slug,
            'category_id' => $category->id,
            'image' => $image,
            'is_active' => $article->is_active,
            'number_in_category' => $article->number_in_category
        ], ['Authorization' => 'Bearer ' . $this->main_admin_access_token]);

        Storage::disk('local')->delete('images/' . $image->hashName());
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => array_merge(
                    collect($article)->except(['created_at', 'updated_at', 'is_active', 'category_id', 'image'])->toArray(), [
                    'image' => $article->image,
                    'is_active' => (int)$article->is_active,
                    'category' => $category->name
                ])
            ]);
    }

    public function test_authorized_invalid_request_create_article()
    {
        $response = $this->postJson('/api/articles', [
            'name' => '',
            'slug' => '',
            'category_id' => 0,
            'image' => '',
            'number_in_category' => 999
        ], ['Authorization' => 'Bearer ' . $this->main_admin_access_token]);

        $response->assertStatus(400)
            ->assertJsonFragment([
                'success' => false,
                'message' => 'Ошибки валидации'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'name',
                    'slug',
                    'category_id',
                    'image',
                    'number_in_category'
                ]
            ]);
    }

    public function test_unauthorized_create_article()
    {
        $category = Category::inRandomOrder()->first();
        $image = UploadedFile::fake()->image('avatar.png');
        $article = Article::factory()->make([
            'image' => 'images/' . $image->hashName()
        ]);

        $response = $this->postJson('/api/articles', [
            'name' => $article->name,
            'slug' => $article->slug,
            'category_id' => $category->id,
            'image' => $image,
            'is_active' => $article->is_active,
            'number_in_category' => $article->number_in_category
        ], ['Authorization' => 'Bearer ' . $this->admin_access_token]);

        Storage::disk('local')->delete('images/' . $image->hashName());
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Действие не авторизовано'
            ]);
    }

    public function test_unauthenticated_create_article()
    {
        $category = Category::inRandomOrder()->first();
        $image = UploadedFile::fake()->image('avatar.png');
        $article = Article::factory()->make([
            'image' => 'images/' . $image->hashName()
        ]);

        $response = $this->postJson('/api/articles', [
            'name' => $article->name,
            'slug' => $article->slug,
            'category_id' => $category->id,
            'image' => $image,
            'is_active' => $article->is_active,
            'number_in_category' => $article->number_in_category
        ]);

        Storage::disk('local')->delete('images/' . $image->hashName());
        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Ошибка аутентификации'
            ]);
    }

    public function test_authorized_update_article_by_id()
    {
        $category = Category::inRandomOrder()->first();
        $image = UploadedFile::fake()->image('avatar.png');
        $article = Article::factory()->create();
        $new_article = Article::factory()->make();

        $response = $this->postJson('/api/articles/' . $article->id, [
            'name' => $new_article->name,
            'slug' => $new_article->slug,
            'category_id' => $category->id,
            'image' => $image,
            'is_active' => $new_article->is_active,
        ], ['Authorization' => 'Bearer ' . $this->main_admin_access_token]);
        Storage::disk('local')->delete('images/' . $image->hashName());

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => array_merge(
                    collect($new_article)->except(['created_at', 'updated_at', 'is_active', 'category_id', 'image'])->toArray(), [
                    'image' => 'images/' . $image->hashName(),
                    'is_active' => (int)$new_article->is_active,
                    'category' => $category->name
                ])
            ]);
    }

    public function test_authorized_invalid_request_update_article_by_id()
    {
        $image = UploadedFile::fake()->image('avatar.png');
        $article = Article::factory()->create();

        $response = $this->postJson('/api/articles/' . $article->id, [
            'name' => '',
            'slug' => '',
            'category_id' => 0,
            'image' => '',
            'number_in_category' => 999
        ], ['Authorization' => 'Bearer ' . $this->main_admin_access_token]);
        Storage::disk('local')->delete('images/' . $image->hashName());

        $response->assertStatus(400)
            ->assertJsonFragment([
                'success' => false,
                'message' => 'Ошибки валидации'
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'name',
                    'slug',
                    'category_id',
                    'image',
                    'number_in_category'
                ]
            ]);
    }

    public function test_unauthorized_update_article_by_id()
    {

    }

    public function test_unauthenticated_update_article_by_id()
    {

    }

    public function test_authorized_delete_article_by_id()
    {

    }

    public function test_authorized_delete_not_existing_article_by_id()
    {

    }

    public function test_unauthorized_delete_article()
    {

    }

    public function test_unauthenticated_delete_article()
    {

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
