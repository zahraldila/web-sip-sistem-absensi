<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\logHelpers;

class AuthenticationControllers extends Controller
{
    /**
     * Proses login admin / user.
     * - Validasi ke tabel akun (username / email pegawai)
     * - Verifikasi password
     * - Login via Auth::login
     * - Regenerate session & simpan info session (akun_id, pegawai_id, role, nama_pegawai)
     * - Redirect ke dashboard admin
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        $loginInput = $request->input('email');
        $password = $request->input('password');

        // Mencari akun berdasarkan username atau email pegawai
        $akun = Akun::where('username', $loginInput)
            ->orWhereHas('pegawai', function ($query) use ($loginInput) {
                $query->where('email', $loginInput);
            })
            ->first();

        $validPassword = false;
        if ($akun) {
            $storedPassword = $akun->password;

            // Jika password sudah bcrypt, gunakan Hash::check.
            // Jika password masih plain-text (legacy import), cocokkan langsung.
            if (is_string($storedPassword) && preg_match('/^\$2[axyb]\$.{56}$/', $storedPassword)) {
                $validPassword = Hash::check($password, $storedPassword);
            } else {
                $validPassword = hash_equals((string) $storedPassword, $password);

                if ($validPassword) {
                    $akun->password = Hash::make($password);
                    $akun->save();
                }
            }
        }

        if (! $akun || ! $validPassword) {
            return back()
                ->withErrors(['email' => 'Username/Email atau password yang Anda masukkan salah.'])
                ->onlyInput('email');
        }

        // Login user
        Auth::login($akun, $request->boolean('remember'));

        // Regenerate session untuk keamanan
        $request->session()->regenerate();
        $request->session()->put('lastActivityTime', time());

        // Menyimpan informasi user/pegawai ke dalam session Laravel
        $pegawai = $akun->pegawai;
        session([
            'akun_id' => $akun->akun_id,
            'pegawai_id' => $akun->pegawai_id,
            'role' => $akun->role,
            'nama_pegawai' => $pegawai ? $pegawai->nama_pegawai : null,
            'email_pegawai' => $pegawai ? $pegawai->email : null,
        ]);

        // ---------------------------------------------------------
        // INJEKSI LOG ACTIVITY: Mencatat bahwa user berhasil login
        // ---------------------------------------------------------
        $roleName = ucfirst($akun->role); // Membuat huruf pertama kapital (misal: 'Admin' atau 'Pegawai')
        logHelpers::record($akun->akun_id, "{$roleName} berhasil login ke dalam sistem");
        // ---------------------------------------------------------

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Proses logout.
     * - Logout dari guard 'web'
     * - Invalidate session & regenerate CSRF token
     * - Redirect ke halaman login
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // ---------------------------------------------------------
        // INJEKSI LOG ACTIVITY: Mencatat bahwa user melakukan logout
        // Pastikan kita ambil ID sebelum user benar-benar di-logout
        // ---------------------------------------------------------
        if (Auth::check()) {
            $akunId = Auth::user()->akun_id; 
            // Atau bisa juga menggunakan ID dari session: $request->session()->get('akun_id');
            
            $roleName = ucfirst(Auth::user()->role);
            logHelpers::record($akunId, "{$roleName} melakukan logout dari sistem");
        }
        // ---------------------------------------------------------

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // register/forgot/reset masih placeholder, belum masuk scope sprint ini
    public function register(Request $request)
    {
        //
    }

    public function forgot(Request $request)
    {
        //
    }

    public function reset(Request $request)
    {
        //
    }
}