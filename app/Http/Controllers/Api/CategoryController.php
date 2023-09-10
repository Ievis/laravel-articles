<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\Category\CategoryCollectionResource;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Models\User;
use App\Services\CategoryService;
use App\Services\SortingService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(Request $request): CategoryCollectionResource
    {
        $categories = Category::filter($request->all())->simplePaginateFilter(10);

        return new CategoryCollectionResource($categories);
    }

    public function store(CreateCategoryRequest $request): CategoryResource
    {
        $data = $request->validated();
        $category_service = new CategoryService($data);
        $data = $category_service->setDefaultFields($data);

        $sorting_service = new SortingService(new Category($data), 'number');
        $categories = $sorting_service
            ->setModels(function ($model) {
                return Category::query()
                    ->where('is_active', true)
                    ->get();
            })
            ->setData($data)
            ->sort();

        $category = $category_service->upsertSorted($categories);
        return new CategoryResource($category);
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    public function update(Category $category, UpdateCategoryRequest $request): CategoryResource
    {
        $data = $request->validated();
        $category_service = new CategoryService($data);

        $sorting_service = new SortingService($category, 'number');
        $categories = $sorting_service
            ->setModels(function ($model) {
                return Category::query()
                    ->where('is_active', true)
                    ->where('id', '!=', $model->id)
                    ->get();
            })
            ->setData($data)
            ->sort();

        $category = $category_service->upsertSorted($categories);
        return new CategoryResource($category);
    }

    public function delete(Category $category): CategoryResource
    {
        CategoryService::deleteCategory($category);
        $category_service = new CategoryService();

        $sorting_service = new SortingService($category, 'number');
        $categories = $sorting_service
            ->setModels(function ($model) {
                return Category::query()
                    ->where('is_active', true)
                    ->get();
            })
            ->getModels();

        $category = $category_service->upsertSorted($categories, $category);
        return new CategoryResource($category);
    }
}
