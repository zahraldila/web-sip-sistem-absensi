<?php

use Illuminate\Support\Facades\Route;

Route::prefix('pegawai')->middleware(['web','auth'])->group(function () {
    Route::get('/dashboard', function () { return view('pegawai.dashboard'); });

    Route::post('attendance/checkin', [App\Http\Controllers\AttendanceControllers::class, 'checkIn']);
    Route::post('attendance/checkout', [App\Http\Controllers\AttendanceControllers::class, 'checkOut']);

    Route::resource('pengajuan', App\Http\Controllers\SubmissionControllers::class);
});
