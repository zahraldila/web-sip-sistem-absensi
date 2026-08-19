<?php

namespace App\Http\Controllers;

use App\Helpers\logHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return view('admin.settings.branding');
    }

    public function simpanBranding(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    
        \App\Models\Setting::set('primary_color', $request->primary_color);
    
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            \App\Models\Setting::set('company_logo', 'storage/' . $path);
        }
    
        // Catat aktivitas admin
        $user = Auth::user();
    
        if ($user && $user->akun_id) {
            $aktivitas = $request->hasFile('logo')
                ? 'Mengubah tampilan branding dan logo sistem'
                : 'Mengubah warna branding sistem';
    
            logHelpers::record(
                $user->akun_id,
                $aktivitas
            );
        }
    
        return redirect()
            ->back()
            ->with('success', 'Pengaturan tampilan & branding berhasil disimpan.');
    }
    

    public function resetBranding()
    {
        \App\Models\Setting::where('key', 'primary_color')->delete();
        \App\Models\Setting::where('key', 'company_logo')->delete();
    
        // Catat aktivitas admin
        $user = Auth::user();
    
        if ($user && $user->akun_id) {
            logHelpers::record(
                $user->akun_id,
                'Mereset tampilan branding sistem ke pengaturan awal'
            );
        }
    
        return redirect()
            ->back()
            ->with('success', 'Tampilan & branding berhasil direset ke pengaturan awal.');
    }

    public function simpanLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);
    
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            \App\Models\Setting::set('company_logo', 'storage/' . $path);
        }
    
        // Catat aktivitas admin
        $user = Auth::user();
    
        if ($user && $user->akun_id) {
            logHelpers::record(
                $user->akun_id,
                'Mengubah logo sistem'
            );
        }
    
        return redirect()
            ->back()
            ->with('success', 'Logo berhasil diperbarui.');
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
