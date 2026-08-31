<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect('/login');
        }

        // Pada Tahap 3, pembatasan hak akses (enforcement) belum diterapkan ke rute /admin.
        // Semua role (Super Admin, HR / HRD, Direktur, Pegawai) yang terautentikasi dapat mengakses halaman.
        return $next($request);
    }
}
