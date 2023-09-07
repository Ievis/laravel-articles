<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    public Collection $articles;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->articles = $this->articles ?? collect();

        $words_count = random_int(3, 9);
        $name = implode(' ', fake()->unique()->words($words_count));
        $slug = Str::slug($name);
        $category = Category::inRandomOrder()->first();
        $is_active = (bool)random_int(0, 1);
        $articles_count = $this->articles->where('category_id', $category->id)->count();

        $article = [
            'name' => $name,
            'slug' => $slug,
            'category_id' => $category->id,
            'image' => '/images/image-default.png',
            'is_active' => $is_active,
            'number_in_category' => $articles_count + 1
        ];
        $this->articles->push($article);

        return $article;
    }
}
