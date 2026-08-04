<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->middleware(['api'])->group(function () {
    // Auth
    Route::post('login', [App\Http\Controllers\AuthenticationControllers::class, 'login']);
    Route::post('logout', [App\Http\Controllers\AuthenticationControllers::class, 'logout'])->middleware('auth:sanctum');

    // Attendance
    Route::post('attendance/checkin', [App\Http\Controllers\AttendanceControllers::class, 'checkIn'])->middleware('auth:sanctum');
    Route::post('attendance/checkout', [App\Http\Controllers\AttendanceControllers::class, 'checkOut'])->middleware('auth:sanctum');

    // Submissions
    Route::apiResource('submissions', App\Http\Controllers\SubmissionControllers::class)->middleware('auth:sanctum');

    // Dashboard
    Route::get('dashboard/admin', [App\Http\Controllers\DashboardControllers::class, 'admin'])->middleware('auth:sanctum');
});
