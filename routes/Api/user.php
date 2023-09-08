<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/users', 'UserController@index');
    Route::get('/users/{user}', 'UserController@show');
    Route::post('/users', 'UserController@store')->can('create', User::class);
    Route::post('/users/{user}', 'UserController@show')->can('update', 'user');
    Route::delete('/users/{user}', 'UserController@delete')->can('delete', 'user');
});
