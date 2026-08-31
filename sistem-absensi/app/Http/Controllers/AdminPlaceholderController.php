<?php

namespace App\Http\Controllers;

use App\Helpers\logHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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

            // ── Tahap 4B: Proteksi per-tab Settings ──────────────────────────
            // Route Settings hanya satu (?tab=branding/lokasi/roles).
            // Cek privilege berdasarkan tab yang sedang diakses.
            $tabPrivilegeMap = [
                'branding'   => 'kelola_tampilan_branding',
                'lokasi'     => 'kelola_lokasi_cabang',
                'roles'      => 'kelola_role_hak_akses',
                'role-akses' => 'kelola_role_hak_akses',
            ];

            /** @var \App\Models\Role|null $currentRole */
            $currentRole = Auth::user()?->roleAkses;

            $requiredPrivilege = $tabPrivilegeMap[$activeTab] ?? null;

            if ($requiredPrivilege && (! $currentRole || ! $currentRole->hasPrivilege($requiredPrivilege))) {
                // Tab yang diminta tidak boleh diakses — coba redirect ke tab yang bisa
                foreach ($tabPrivilegeMap as $tab => $priv) {
                    if ($currentRole && $currentRole->hasPrivilege($priv)) {
                        return redirect()->route('admin.tampilan-branding', ['tab' => $tab]);
                    }
                }
                // Tidak ada tab Settings yang bisa diakses sama sekali
                abort(403, 'Anda tidak memiliki akses ke halaman Settings ini.');
            }
            // ── /Tahap 4B ─────────────────────────────────────────────────────

            $daftarLokasi = DB::table('lokasi_kantor')->get();
            foreach ($daftarLokasi as $lokasi) {
                $lokasi->wifis = DB::table('wifi_kantor')
                    ->where('lokasi_id', $lokasi->lokasi_id)
                    ->get();
            }

            // Data Master Role & Privileges untuk Tab Role & Hak Akses
            $daftarRole = \App\Models\Role::with('privileges')->withCount('akun')->orderBy('role_id')->get();
            $daftarPrivilege = \App\Models\Privilege::orderBy('privilege_id')->get()->groupBy('kategori');
            $selectedRoleId = (int) $request->query('role_id', ($daftarRole->first()?->role_id ?? 1));

            return view('admin.settings.branding', compact(
                'savedColor',
                'savedLogo',
                'daftarLokasi',
                'activeTab',
                'daftarRole',
                'daftarPrivilege',
                'selectedRoleId'
            ));
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Gagal memuat halaman Settings: ' . $e->getMessage());
        }
    }

    public function simpanRolePrivilege(Request $request)
    {
        $request->validate([
            'role_id'         => 'required|integer|exists:role,role_id',
            'privilege_ids'   => 'nullable|array',
            'privilege_ids.*' => 'integer|exists:privilege,privilege_id',
        ], [
            'role_id.required' => 'Role wajib dipilih.',
            'role_id.exists'   => 'Role tidak valid.',
        ]);

        try {
            $role = \App\Models\Role::findOrFail($request->role_id);
            $privilegeIds = $request->input('privilege_ids', []);

            // Proteksi Super Admin: Hak akses inti Kelola Role & Hak Akses wajib selalu aktif
            $isSuperAdmin = strcasecmp($role->nama_role, 'Super Admin') === 0 || $role->role_id === 1;
            if ($isSuperAdmin) {
                $corePrivilegeId = \App\Models\Privilege::where('nama_privilege', 'kelola_role_hak_akses')->value('privilege_id');
                if ($corePrivilegeId && ! in_array($corePrivilegeId, $privilegeIds)) {
                    $privilegeIds[] = $corePrivilegeId;
                }
            }

            // Sinkronisasi hak akses ke tabel pivot role_privilege
            $role->privileges()->sync($privilegeIds);

            // Catat log aktivitas
            $user = Auth::user();
            if ($user && isset($user->akun_id)) {
                logHelpers::record(
                    $user->akun_id,
                    "Memperbarui hak akses (privilege) untuk role: {$role->nama_role}"
                );
            }

            return redirect()
                ->route('admin.tampilan-branding', [
                    'tab' => 'roles',
                    'role_id' => $role->role_id,
                ])
                ->with('success', "Hak akses untuk role \"{$role->nama_role}\" berhasil diperbarui.");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.tampilan-branding', [
                    'tab' => 'roles',
                    'role_id' => $request->role_id,
                ])
                ->with('error', 'Gagal menyimpan hak akses: ' . $e->getMessage());
        }
    }

    public function tambahRole(Request $request)
    {
        $request->validate([
            'nama_role' => 'required|string|max:100|unique:role,nama_role',
            'deskripsi' => 'nullable|string|max:255',
        ], [
            'nama_role.required' => 'Nama role wajib diisi.',
            'nama_role.max'      => 'Nama role maksimal 100 karakter.',
            'nama_role.unique'   => 'Nama role sudah terdaftar, silakan gunakan nama lain.',
            'deskripsi.max'      => 'Deskripsi role maksimal 255 karakter.',
        ]);

        try {
            $role = \App\Models\Role::create([
                'nama_role' => trim($request->nama_role),
                'deskripsi' => $request->filled('deskripsi') ? trim($request->deskripsi) : null,
            ]);

            // Catat log aktivitas
            $user = Auth::user();
            if ($user && isset($user->akun_id)) {
                logHelpers::record(
                    $user->akun_id,
                    "Menambahkan role master baru: {$role->nama_role}"
                );
            }

            return redirect()
                ->route('admin.tampilan-branding', [
                    'tab' => 'roles',
                    'role_id' => $role->role_id,
                ])
                ->with('success', "Role \"{$role->nama_role}\" berhasil ditambahkan. Silakan atur hak akses privilege untuk role ini.");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.tampilan-branding', [
                    'tab' => 'roles',
                ])
                ->with('error', 'Gagal menambahkan role: ' . $e->getMessage());
        }
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
        if ($user && isset($user->akun_id)) {
            $aktivitas = $request->hasFile('logo')
                ? 'Mengubah tampilan branding dan logo sistem'
                : 'Mengubah warna branding sistem';
    
            logHelpers::record(
                $user->akun_id,
                $aktivitas
            );
        }
    
        return redirect()
            ->route('admin.tampilan-branding', ['tab' => 'branding'])
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
        if ($user && isset($user->akun_id)) {
            logHelpers::record(
                $user->akun_id,
                'Mereset tampilan branding sistem ke pengaturan awal'
            );
        }
    
        return redirect()
            ->route('admin.tampilan-branding', ['tab' => 'branding'])
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
        if ($user && isset($user->akun_id)) {
            logHelpers::record(
                $user->akun_id,
                'Mengubah logo sistem'
            );
        }
    
        return redirect()
            ->route('admin.tampilan-branding', ['tab' => 'branding'])
            ->with('success', 'Logo berhasil diperbarui.');
    }

    public function simpanLokasi(Request $request)
    {
        $request->validate([
            'lokasi_id'     => 'nullable|integer',
            'nama_kantor'   => 'required|string|max:100',
            'latitude'      => 'required|numeric',
            'longitude'     => 'required|numeric',
            'radius_meter'  => 'required|integer|min:1',
            'wifi_ssids'    => 'nullable|string',
        ]);

        $lokasiId = $request->input('lokasi_id');
        $namaKantor = $request->input('nama_kantor');
        $latitude = (float) $request->input('latitude');
        $longitude = (float) $request->input('longitude');
        $radiusMeter = (int) $request->input('radius_meter');
        $wifiSsids = $request->input('wifi_ssids', '');

        try {
            if ($lokasiId) {
                // Update existing location
                DB::table('lokasi_kantor')
                    ->where('lokasi_id', $lokasiId)
                    ->update([
                        'nama_kantor'  => $namaKantor,
                        'latitude'     => $latitude,
                        'longitude'    => $longitude,
                        'radius_meter' => $radiusMeter,
                    ]);

                $pesan = "Kantor cabang '{$namaKantor}' berhasil diperbarui.";
            } else {
                // Insert new location
                $lokasiId = DB::table('lokasi_kantor')
                    ->insertGetId([
                        'nama_kantor'  => $namaKantor,
                        'latitude'     => $latitude,
                        'longitude'    => $longitude,
                        'radius_meter' => $radiusMeter,
                    ], 'lokasi_id');

                $pesan = "Kantor cabang '{$namaKantor}' berhasil ditambahkan.";
            }

            // Sync Wi-Fi SSIDs if provided
            if ($wifiSsids !== null) {
                $ssids = array_filter(array_map('trim', explode(',', $wifiSsids)));

                // Unlink old wifis for this location
                DB::table('wifi_kantor')->where('lokasi_id', $lokasiId)->delete();

                foreach ($ssids as $ssid) {
                    $trimmed = trim($ssid);
                    if (!empty($trimmed)) {
                        $randomHex = substr(md5($trimmed), 0, 12);
                        $formattedBssid = implode(':', str_split($randomHex, 2));

                        DB::table('wifi_kantor')->insert([
                            'lokasi_id' => $lokasiId,
                            'ssid'      => $trimmed,
                            'bssid'     => $formattedBssid,
                            'aktif'     => true,
                        ]);
                    }
                }
            }

            // Audit log with integer akun_id
            $user = Auth::user();
            if ($user && isset($user->akun_id)) {
                logHelpers::record(
                    $user->akun_id,
                    "Admin menyimpan konfigurasi kantor cabang: {$namaKantor} (ID: {$lokasiId})"
                );
            }

            return redirect()->route('admin.tampilan-branding', ['tab' => 'lokasi'])->with('success', $pesan);
        } catch (\Exception $e) {
            return redirect()->route('admin.tampilan-branding', ['tab' => 'lokasi'])->with('error', 'Gagal menyimpan data kantor cabang: ' . $e->getMessage());
        }
    }

    public function hapusLokasi($id)
    {
        try {
            $lokasi = DB::table('lokasi_kantor')->where('lokasi_id', $id)->first();
            if (!$lokasi) {
                return redirect()->route('admin.tampilan-branding', ['tab' => 'lokasi'])->with('error', 'Data kantor cabang tidak ditemukan.');
            }

            $nama = $lokasi->nama_kantor;

            // Unlink Wi-Fis
            DB::table('wifi_kantor')->where('lokasi_id', $id)->delete();

            // Delete location
            DB::table('lokasi_kantor')->where('lokasi_id', $id)->delete();

            // Audit log with integer akun_id
            $user = Auth::user();
            if ($user && isset($user->akun_id)) {
                logHelpers::record(
                    $user->akun_id,
                    "Admin menghapus kantor cabang: {$nama} (ID: {$id})"
                );
            }

            return redirect()->route('admin.tampilan-branding', ['tab' => 'lokasi'])->with('success', "Kantor cabang '{$nama}' berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('admin.tampilan-branding', ['tab' => 'lokasi'])->with('error', 'Gagal menghapus kantor cabang: ' . $e->getMessage());
        }
    }

    protected function uploadCompanyLogo($file): string
    {
        $bucket = config('supabase.assets_bucket', 'company-assets');
        $ext = $file->getClientOriginalExtension() ?: $file->extension() ?: 'png';
        $fileName = 'logo_' . time() . '_' . Str::random(6) . '.' . strtolower($ext);
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

        $resp = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'apikey' => $apiKey,
            'Content-Type' => $file->getClientMimeType(),
            'x-upsert' => 'true',
        ])->withBody(file_get_contents($file->getRealPath()), $file->getClientMimeType())->post($uploadUrl);

        if (! $resp->successful()) {
            $resp = Http::withHeaders([
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

        Http::withHeaders([
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