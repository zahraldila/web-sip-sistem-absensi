<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApprovalControllers;

Route::prefix('admin')->middleware(['web','auth','role:Admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardControllers::class, 'admin'])->name('admin.dashboard');
    Route::get('/dashboard', [App\Http\Controllers\DashboardControllers::class, 'admin'])->name('admin.dashboard.index');

    Route::get('/laporan-kehadiran', [App\Http\Controllers\AttendanceReportController::class, 'index'])->name('admin.laporan-kehadiran');
    Route::get('/laporan-kehadiran/export/excel', [App\Http\Controllers\AttendanceReportController::class, 'exportExcel'])->name('admin.laporan-kehadiran.export.excel');
    Route::get('/laporan-kehadiran/export/pdf', [App\Http\Controllers\AttendanceReportController::class, 'exportPdf'])->name('admin.laporan-kehadiran.export.pdf');
    Route::get('/manajemen-akun', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'index'])->name('admin.manajemen-akun');
    Route::get('/employee-management', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'index'])->name('admin.employee-management.index');
    Route::get('/employee-management/create', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'create'])->name('admin.employee-management.create');
    Route::post('/employee-management', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'store'])->name('admin.employee-management.store');
    Route::post('/employee-management/divisions', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'storeDivision'])->name('admin.employee-management.storeDivision');
    Route::post('/employee-management/roles', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'storeRole'])->name('admin.employee-management.storeRole');
    Route::post('/employee-management/export', [App\Http\Controllers\Admin\EmployeeExportController::class, 'export'])->name('admin.employee-management.export');
    Route::get('/employee-management/{pegawai}/edit', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'edit'])->name('admin.employee-management.edit');
    Route::put('/employee-management/{pegawai}', [App\Http\Controllers\Admin\EmployeeManagementController::class, 'update'])->name('admin.employee-management.update');

    // persetujuan pengajuan
    Route::get('/persetujuan', [ApprovalControllers::class, 'index'])
        ->name('admin.persetujuan');
    Route::get(
        '/persetujuan/export/excel',
        [App\Http\Controllers\ApprovalControllers::class, 'exportExcel']
    )->name('admin.persetujuan.export.excel');
    Route::get('/persetujuan/{approval}', [ApprovalControllers::class, 'show'])
        ->name('admin.persetujuan.detail');
    Route::post('/persetujuan/{pengajuan}/approve', [ApprovalControllers::class, 'approve'])
        ->name('admin.persetujuan.approve');
    Route::post('/persetujuan/{pengajuan}/reject', [ApprovalControllers::class, 'reject'])
        ->name('admin.persetujuan.reject');
    Route::post('/persetujuan/{pengajuan}/process', [ApprovalControllers::class, 'process'])
        ->name('admin.persetujuan.process');
    Route::get('/log-aktivitas', [App\Http\Controllers\AdminPlaceholderController::class, 'logAktivitas'])->name('admin.log-aktivitas');
    Route::get('/tampilan-branding', [App\Http\Controllers\AdminPlaceholderController::class, 'tampilanBranding'])->name('admin.tampilan-branding');
    Route::post('/tampilan-branding/simpan', [App\Http\Controllers\AdminPlaceholderController::class, 'simpanBranding'])->name('admin.tampilan-branding.simpan');
    Route::post('/tampilan-branding/reset', [App\Http\Controllers\AdminPlaceholderController::class, 'resetBranding'])->name('admin.tampilan-branding.reset');
    Route::post('/tampilan-branding/logo', [App\Http\Controllers\AdminPlaceholderController::class, 'simpanLogo'])->name('admin.tampilan-branding.logo');
    Route::get('/pengaturan', [App\Http\Controllers\AdminPlaceholderController::class, 'pengaturan'])->name('admin.pengaturan');
    Route::get('/bantuan', [App\Http\Controllers\AdminPlaceholderController::class, 'bantuan'])->name('admin.bantuan');
    Route::get('/chart-statistik', [App\Http\Controllers\DashboardControllers::class, 'chartStatistik'])->name('admin.chart-statistik');
    Route::post('/jam-kerja', [App\Http\Controllers\DashboardControllers::class, 'simpanJamKerja'])->name('admin.jam-kerja.simpan');

    // Admin resource routes (placeholders)
    Route::apiResource('employees', App\Http\Controllers\EmployeeControllers::class);
    Route::apiResource('submissions', App\Http\Controllers\SubmissionControllers::class);
    Route::post('approvals/{submission}', [App\Http\Controllers\ApprovalControllers::class, 'store']);
});
