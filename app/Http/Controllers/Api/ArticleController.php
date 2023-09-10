<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\Article\ArticleCollectionResource;
use App\Http\Resources\Article\ArticleResource;
use App\Http\Resources\Category\CategoryResource;
use App\Models\Article;
use App\Models\Category;
use App\Services\ArticleService;
use App\Services\CategoryService;
use App\Services\SortingService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request): ArticleCollectionResource
    {
        $articles = Article::filter($request->all())->simplePaginateFilter(10);

        return new ArticleCollectionResource($articles);
    }

    public function store(CreateArticleRequest $request): ArticleResource
    {
        $data = $request->validated();
        $data['image'] = ArticleService::uploadImage($data['image']);
        $article_service = new ArticleService($data);
        $data = $article_service->setDefaultFields($data);

        $sorting_service = new SortingService(new Article($data), 'number_in_category');
        $articles = $sorting_service
            ->setModels(function ($model) {
                return Article::query()
                    ->where('is_active', true)
                    ->where('category_id', $model->category_id)
                    ->get();
            })
            ->setData($data)
            ->sort();

        $article = $article_service->upsertSorted($articles);
        return new ArticleResource($article);
    }

    public function show(Article $article): ArticleResource
    {
        return new ArticleResource($article);
    }

    public function update(Article $article, UpdateArticleRequest $request): ArticleResource
    {
        $data = $request->validated();
        $data['image'] = ArticleService::replaceImage($article, $data['image']);
        $article_service = new ArticleService($data);

        $sorting_service = new SortingService($article, 'number_in_category');
        $articles = $sorting_service
            ->setModels(function ($model) {
                return Article::query()
                    ->where('is_active', true)
                    ->where('category_id', $model->category_id)
                    ->where('id', '!=', $model->id)
                    ->get();
            })
            ->setData($data)
            ->sort();

        $article = $article_service->upsertSorted($articles);
        return new ArticleResource($article);
    }

    public function delete(Article $article): ArticleResource
    {
        ArticleService::deleteArticle($article);
        $article_service = new ArticleService();

        $sorting_service = new SortingService($article, 'number_in_category');
        $articles = $sorting_service
            ->setModels(function ($model) {
                return Article::query()
                    ->where('is_active', true)
                    ->where('category_id', $model->category_id)
                    ->get();
            })
            ->getModels();

        $article = $article_service->upsertSorted($articles, $article);
        return new ArticleResource($article);
    }
}
