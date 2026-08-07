<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['web','auth','role:Admin'])->group(function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('admin.dashboard');
    Route::get('/dashboard', function () {
        return view('admin.index');
    })->name('admin.dashboard.index');

    Route::get('/laporan-kehadiran', [App\Http\Controllers\AdminPlaceholderController::class, 'laporanKehadiran'])->name('admin.laporan-kehadiran');
    Route::get('/manajemen-akun', [App\Http\Controllers\AdminPlaceholderController::class, 'manajemenAkun'])->name('admin.manajemen-akun');
    Route::get('/persetujuan', [App\Http\Controllers\AdminPlaceholderController::class, 'persetujuan'])->name('admin.persetujuan');
    Route::get('/log-aktivitas', [App\Http\Controllers\AdminPlaceholderController::class, 'logAktivitas'])->name('admin.log-aktivitas');
    Route::get('/tampilan-branding', [App\Http\Controllers\AdminPlaceholderController::class, 'tampilanBranding'])->name('admin.tampilan-branding');
    Route::get('/pengaturan', [App\Http\Controllers\AdminPlaceholderController::class, 'pengaturan'])->name('admin.pengaturan');
    Route::get('/bantuan', [App\Http\Controllers\AdminPlaceholderController::class, 'bantuan'])->name('admin.bantuan');

    // Admin resource routes (placeholders)
    Route::apiResource('employees', App\Http\Controllers\EmployeeControllers::class);
    Route::apiResource('submissions', App\Http\Controllers\SubmissionControllers::class);
    Route::post('approvals/{submission}', [App\Http\Controllers\ApprovalControllers::class, 'store']);
});
