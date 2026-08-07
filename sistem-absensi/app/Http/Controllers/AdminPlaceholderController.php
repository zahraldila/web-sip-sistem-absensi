<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminPlaceholderController extends Controller
{
    public function dashboard()
    {
        return redirect()->route('admin.dashboard');
    }

    public function laporanKehadiran()
    {
        return view('admin.placeholder', ['title' => 'Laporan Kehadiran']);
    }

    public function manajemenAkun()
    {
        return view('admin.placeholder', ['title' => 'Manajemen Akun']);
    }

    public function persetujuan()
    {
        return view('admin.placeholder', ['title' => 'Persetujuan']);
    }

    public function logAktivitas()
    {
        return view('admin.placeholder', ['title' => 'Log Aktivitas']);
    }

    public function tampilanBranding()
    {
        return view('admin.placeholder', ['title' => 'Tampilan & Branding']);
    }

    public function pengaturan()
    {
        return view('admin.placeholder', ['title' => 'Pengaturan']);
    }

    public function bantuan()
    {
        return view('admin.placeholder', ['title' => 'Bantuan']);
    }
}
