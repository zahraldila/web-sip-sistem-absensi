<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['web','auth','role:Admin'])->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('admin.dashboard');

    // Admin resource routes (placeholders)
    Route::apiResource('employees', App\Http\Controllers\EmployeeControllers::class);
    Route::apiResource('submissions', App\Http\Controllers\SubmissionControllers::class);
    Route::post('approvals/{submission}', [App\Http\Controllers\ApprovalControllers::class, 'store']);
});
