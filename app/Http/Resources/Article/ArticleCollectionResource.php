<?php

namespace App\Http\Resources\Article;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollectionResource extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->with = [
            'success' => true
        ];

        return $this->collection->map(function ($article) {
            $category = $article->getRelation('category')->name;

            return [
                'id' => $article->id,
                'name' => $article->name,
                'slug' => $article->slug,
                'category' => $category,
                'image' => $article->image,
                'is_active' => $article->is_active,
                'number_in_category' => $article->number_in_category
            ];
        })->toArray();
    }
}
