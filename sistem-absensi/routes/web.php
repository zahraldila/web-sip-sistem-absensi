<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

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
