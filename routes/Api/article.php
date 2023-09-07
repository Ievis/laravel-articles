<?php

use Illuminate\Support\Facades\Route;
use App\Models\Article;

Route::get('/articles', 'ArticleController@index');
Route::get('/articles/{article}', 'ArticleController@show');
Route::post('/articles', 'ArticleController@store')->can('create', Article::class);
Route::post('/articles/{article}', 'ArticleController@show')->can('update', 'article');
Route::delete('/articles/{article}', 'ArticleController@delete')->can('delete', 'article');
