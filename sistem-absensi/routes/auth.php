<?php

use Illuminate\Support\Facades\Route;

Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::post('login', [App\Http\Controllers\AuthenticationControllers::class, 'login']);
Route::post('register', [App\Http\Controllers\AuthenticationControllers::class, 'register']);
Route::post('password/forgot', [App\Http\Controllers\AuthenticationControllers::class, 'forgot']);
Route::post('password/reset', [App\Http\Controllers\AuthenticationControllers::class, 'reset']);
