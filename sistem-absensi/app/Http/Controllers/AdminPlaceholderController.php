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

    public function tampilanBranding(Request $request)
    {
        try {
            $savedColor = \App\Models\Setting::get('primary_color', '#123D91');
            $savedLogo  = company_logo_url();
            $activeTab  = $request->query('tab', 'branding');

            $daftarLokasi = \Illuminate\Support\Facades\DB::table('lokasi_kantor')->get();
            foreach ($daftarLokasi as $lokasi) {
                $lokasi->wifis = \Illuminate\Support\Facades\DB::table('wifi_kantor')
                    ->where('lokasi_id', $lokasi->lokasi_id)
                    ->get();
            }

            return view('admin.settings.branding', compact('savedColor', 'savedLogo', 'daftarLokasi', 'activeTab'));
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Gagal memuat halaman Settings. Terjadi masalah koneksi ke database: ' . $e->getMessage());
        }
    }

    public function simpanLokasi(Request $request)
    {
        $request->validate([
            'lokasi_id'     => 'nullable|integer',
            'nama_kantor'   => 'required|string|max:100',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
            'radius_meter'  => 'required|numeric|min:1',
            'wifi_ssids'    => 'nullable|string',
        ]);

        $lokasiId = $request->input('lokasi_id');
        $namaKantor = trim($request->input('nama_kantor'));
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radiusMeter = $request->input('radius_meter');

        if ($lokasiId) {
            // Update existing
            \Illuminate\Support\Facades\DB::table('lokasi_kantor')
                ->where('lokasi_id', $lokasiId)
                ->update([
                    'nama_kantor'  => $namaKantor,
                    'latitude'     => $latitude,
                    'longitude'    => $longitude,
                    'radius_meter' => $radiusMeter,
                ]);
            $msg = "Kantor cabang '{$namaKantor}' berhasil diperbarui.";
            $actionLog = "Memperbarui lokasi kantor cabang '{$namaKantor}'";
        } else {
            // Insert new
            $lokasiId = \Illuminate\Support\Facades\DB::table('lokasi_kantor')
                ->insertGetId([
                    'nama_kantor'  => $namaKantor,
                    'latitude'     => $latitude,
                    'longitude'    => $longitude,
                    'radius_meter' => $radiusMeter,
                ], 'lokasi_id');
            $msg = "Kantor cabang '{$namaKantor}' berhasil ditambahkan.";
            $actionLog = "Menambahkan lokasi kantor cabang baru '{$namaKantor}'";
        }

        // Process Wi-Fi SSIDs if provided
        if ($request->filled('wifi_ssids')) {
            $rawSsids = explode(',', $request->input('wifi_ssids'));
            foreach ($rawSsids as $rawSsid) {
                $trimmed = trim($rawSsid);
                if (!empty($trimmed)) {
                    $existing = \Illuminate\Support\Facades\DB::table('wifi_kantor')
                        ->where('ssid', $trimmed)
                        ->first();
                    if ($existing) {
                        \Illuminate\Support\Facades\DB::table('wifi_kantor')
                            ->where('wifi_id', $existing->wifi_id)
                            ->update(['lokasi_id' => $lokasiId, 'aktif' => true]);
                    } else {
                        $randomHex = substr(md5($trimmed), 0, 12);
                        $formattedBssid = implode(':', str_split($randomHex, 2));
                        
                        \Illuminate\Support\Facades\DB::table('wifi_kantor')->insert([
                            'lokasi_id' => $lokasiId,
                            'ssid'      => $trimmed,
                            'bssid'     => $formattedBssid,
                            'aktif'     => true,
                        ]);
                    }
                }
            }
        }

        // Catat audit log
        $user = Auth::user();
        if ($user && $user->akun_id) {
            logHelpers::record($user->akun_id, $actionLog);
        }

        return redirect()->route('admin.tampilan-branding', ['tab' => 'lokasi'])
            ->with('success', $msg);
    }

    public function hapusLokasi($id)
    {
        $lokasi = \Illuminate\Support\Facades\DB::table('lokasi_kantor')->where('lokasi_id', $id)->first();
        if ($lokasi) {
            // Unlink or delete associated wifi
            \Illuminate\Support\Facades\DB::table('wifi_kantor')->where('lokasi_id', $id)->update(['lokasi_id' => null]);
            \Illuminate\Support\Facades\DB::table('lokasi_kantor')->where('lokasi_id', $id)->delete();

            $user = Auth::user();
            if ($user && $user->akun_id) {
                logHelpers::record($user->akun_id, "Menghapus lokasi kantor cabang '{$lokasi->nama_kantor}'");
            }

            return redirect()->route('admin.tampilan-branding', ['tab' => 'lokasi'])
                ->with('success', "Kantor cabang '{$lokasi->nama_kantor}' berhasil dihapus.");
        }

        return redirect()->route('admin.tampilan-branding', ['tab' => 'lokasi'])
            ->with('error', 'Kantor cabang tidak ditemukan.');
    }

    public function simpanBranding(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);
    
        \App\Models\Setting::set('primary_color', $request->primary_color);
    
        if ($request->hasFile('logo')) {
            $oldLogo = \App\Models\Setting::get('company_logo');
            $newPath = $this->uploadCompanyLogo($request->file('logo'));
            \App\Models\Setting::set('company_logo', $newPath);

            if ($oldLogo && $oldLogo !== $newPath) {
                $this->deleteCompanyLogo($oldLogo);
            }
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
        $oldLogo = \App\Models\Setting::get('company_logo');
        if ($oldLogo) {
            $this->deleteCompanyLogo($oldLogo);
        }

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
            'logo' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
        ]);
    
        if ($request->hasFile('logo')) {
            $oldLogo = \App\Models\Setting::get('company_logo');
            $newPath = $this->uploadCompanyLogo($request->file('logo'));
            \App\Models\Setting::set('company_logo', $newPath);

            if ($oldLogo && $oldLogo !== $newPath) {
                $this->deleteCompanyLogo($oldLogo);
            }
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

    protected function uploadCompanyLogo($file): string
    {
        $bucket = config('supabase.assets_bucket', 'company-assets');
        $ext = $file->getClientOriginalExtension() ?: $file->extension() ?: 'png';
        $fileName = 'logo_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . strtolower($ext);
        $remotePath = 'logos/' . $fileName;

        $baseUrl = supabase_url() ?: config('supabase.url');
        if (! $baseUrl) {
            throw new \RuntimeException('Supabase URL belum dikonfigurasi. Pastikan SUPABASE_URL di .env terisi.');
        }

        $baseUrl = rtrim($baseUrl, '/');
        if (! preg_match('/^https?:\/\//i', $baseUrl)) {
            $baseUrl = 'https://' . ltrim($baseUrl, '/');
        }

        $uploadUrl = $baseUrl . '/storage/v1/object/' . rawurlencode($bucket) . '/' . $remotePath;
        $apiKey = supabase_key() ?: config('supabase.key');
        if (! $apiKey) {
            throw new \RuntimeException('Supabase key belum dikonfigurasi. Pastikan SUPABASE_KEY di .env terisi.');
        }

        $resp = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'apikey' => $apiKey,
            'Content-Type' => $file->getClientMimeType(),
            'x-upsert' => 'true',
        ])->withBody(file_get_contents($file->getRealPath()), $file->getClientMimeType())->post($uploadUrl);

        if (! $resp->successful()) {
            $resp = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'apikey' => $apiKey,
                'Content-Type' => $file->getClientMimeType(),
            ])->withBody(file_get_contents($file->getRealPath()), $file->getClientMimeType())->put($uploadUrl);
        }

        if (! $resp->successful()) {
            throw new \RuntimeException('Gagal mengunggah logo ke Supabase Storage: ' . $resp->body());
        }

        return rtrim($bucket, '/') . '/' . $remotePath;
    }

    protected function deleteCompanyLogo(?string $path): void
    {
        if (! $path || trim($path) === '') {
            return;
        }

        $path = trim($path);
        if (preg_match('/^https?:\/\//i', $path) || str_starts_with($path, 'images/') || str_starts_with($path, 'assets/') || str_ends_with($path, 'logo-sip.png')) {
            return;
        }

        $bucket = config('supabase.assets_bucket', 'company-assets');
        $baseUrl = supabase_url() ?: config('supabase.url');
        $apiKey = supabase_key() ?: config('supabase.key');
        if (! $baseUrl || ! $apiKey) {
            return;
        }

        $baseUrl = rtrim($baseUrl, '/');
        if (! preg_match('/^https?:\/\//i', $baseUrl)) {
            $baseUrl = 'https://' . ltrim($baseUrl, '/');
        }

        $objectPath = ltrim($path, '/');
        if (str_starts_with($objectPath, 'public/' . $bucket . '/')) {
            $objectPath = substr($objectPath, strlen('public/' . $bucket . '/'));
        }
        if (str_starts_with($objectPath, $bucket . '/')) {
            $objectPath = substr($objectPath, strlen($bucket . '/'));
        }

        $deleteUrl = $baseUrl . '/storage/v1/object/' . rawurlencode($bucket) . '/' . implode('/', array_map('rawurlencode', explode('/', $objectPath)));

        \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'apikey' => $apiKey,
        ])->delete($deleteUrl);
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