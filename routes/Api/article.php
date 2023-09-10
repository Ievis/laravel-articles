<?php

use Illuminate\Support\Facades\Route;
use App\Models\Article;

Route::get('/articles', 'ArticleController@index');
Route::get('/articles/{article}', 'ArticleController@show');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/articles', 'ArticleController@store')->can('create', Article::class);
    Route::post('/articles/{article}', 'ArticleController@update')->can('update', 'article');
    Route::delete('/articles/{article}', 'ArticleController@delete')->can('delete', 'article');
});
