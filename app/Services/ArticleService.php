<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ArticleService
{
    private array $default_fields = [
        'is_active' => false
    ];
    private null|array $data;

    public function __construct(null|array $data = null)
    {
        $this->data = $data;
    }

    public static function deleteArticle(Article $article): Article
    {
        Storage::delete($article->image);
        $article->delete();

        return $article;
    }

    public function setDefaultFields(array $data)
    {
        foreach ($this->default_fields as $default_field => $default_value) {
            $data[$default_field] = $data[$default_field] ?? $default_value;
        }

        return $data;
    }

    public function upsertSorted(Collection $articles, null|Article $article = null)
    {
        $active_articles_count = $articles
            ->where('is_active', true)
            ->where('category_id', $article->category_id ?? $this->data['category_id'])
            ->count();

        Article::query()
            ->where('is_active', false)
            ->where('category_id', $article->category_id ?? $this->data['category_id'])
            ->where('number_in_category', '>', $active_articles_count + 1)
            ->get()
            ->map(function ($article) use ($active_articles_count, $articles) {
                if ($article) {
                    $article->number_in_category = $active_articles_count + 1;
                    $articles->push(collect($article)->except(['created_at', 'updated_at']));
                }
            });

        Article::upsert($articles->toArray(), ['id'], ['name', 'slug', 'category_id', 'image', 'number_in_category', 'is_active']);
        return $article ?? Article::query()->where('name', $article->name ?? $this->data['name'])->first();
    }

    public static function uploadImage(UploadedFile $image): string
    {
        return $image->store('/images');
    }

    public static function replaceImage(Article $article, UploadedFile $image): string
    {
        Storage::delete($article->image);

        return self::uploadImage($image);
    }
}
