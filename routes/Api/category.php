<?php

use Illuminate\Support\Facades\Route;
use App\Models\Category;

Route::get('/categories', 'CategoryController@index');
Route::get('/categories/{category}', 'CategoryController@show');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('/categories', 'CategoryController@store')->can('create', Category::class);
    Route::post('/categories/{category}', 'CategoryController@show')->can('update', 'category');
    Route::delete('/categories/{category}', 'CategoryController@delete')->can('delete', 'category');
});
