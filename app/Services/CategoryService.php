<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryService
{
    private array $default_fields = [
        'is_active' => false
    ];
    private null|array $data;

    public function __construct(null|array $data = null)
    {
        $this->data = $data;
    }

    public static function deleteCategory(Category $category): Category
    {
        $category->delete();

        return $category;
    }

    public function setDefaultFields(array $data)
    {
        foreach ($this->default_fields as $default_field => $default_value) {
            $data[$default_field] = $data[$default_field] ?? $default_value;
        }

        return $data;
    }

    public function upsertSorted(Collection $categories, null|Category $category = null)
    {
        $active_categories_count = $categories->where('is_active', true)->count();
        Category::query()
            ->where('is_active', false)
            ->where('number', '>', $active_categories_count + 1)
            ->get()
            ->map(function ($category) use ($active_categories_count, $categories) {
                if ($category) {
                    $category->number = $active_categories_count + 1;
                    $categories->push(collect($category)->except(['created_at', 'updated_at']));
                }
            });

        Category::upsert($categories->toArray(), ['id'], ['name', 'number', 'is_active']);
        return $category ?? Category::query()->where('name', $this->data['name'])->first();
    }
}
