<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\DashboardTv\TvDashboardController::class, 'index'])->name('tv.dashboard');
Route::get('/api/tv-dashboard/stats', [App\Http\Controllers\DashboardTv\TvDashboardController::class, 'getStats'])->name('tv.dashboard.stats');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
});

// Load additional route groups if present (admin, pegawai, auth)
if (file_exists($path = base_path('routes/admin.php'))) {
    require $path;
}

if (file_exists($path = base_path('routes/pegawai.php'))) {
    require $path;
}

if (file_exists($path = base_path('routes/auth.php'))) {
    require $path;
}

Route::get('/admin/log-aktivitas', [\App\Http\Controllers\AuditLogControllers::class, 'webIndex'])->name('admin.log-aktivitas');