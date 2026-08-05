<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('login', [App\Http\Controllers\AuthenticationControllers::class, 'login'])->name('login.attempt');
    Route::post('register', [App\Http\Controllers\AuthenticationControllers::class, 'register']);
    Route::post('password/forgot', [App\Http\Controllers\AuthenticationControllers::class, 'forgot']);
    Route::post('password/reset', [App\Http\Controllers\AuthenticationControllers::class, 'reset']);
});

Route::post('logout', [App\Http\Controllers\AuthenticationControllers::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
