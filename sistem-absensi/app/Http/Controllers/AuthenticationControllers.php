<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationControllers extends Controller
{
    /**
     * Proses login admin.
     * - Validasi form (via LoginRequest)
     * - Attempt autentikasi dengan rate limiting implicit
     * - Regenerate session (mencegah session fixation)
     * - Set lastActivityTime untuk session timeout
     * - Redirect ke dashboard admin
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password yang Anda masukkan salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->put('lastActivityTime', time());

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
