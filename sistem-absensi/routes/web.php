<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.index');
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
