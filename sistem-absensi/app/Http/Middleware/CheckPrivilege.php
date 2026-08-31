<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPrivilege
{
    /**
     * Tahap 4B: Proteksi halaman berdasarkan privilege role.
     *
     * Alur:
     *   Auth::user() → Akun → roleAkses (Role) → hasPrivilege($privilege)
     *
     * Super Admin selalu lolos (ditangani di Role::hasPrivilege()).
     * Role lain harus memiliki privilege yang sesuai di tabel role_privilege.
     *
     * Jika tidak memiliki privilege → HTTP 403 (bukan redirect).
     * Middleware ini TIDAK mengubah authentication, session, atau login flow.
     *
     * @param  string  $privilege  Nama privilege yang diperlukan (contoh: 'lihat_dashboard')
     */
    public function handle(Request $request, Closure $next, string $privilege): mixed
    {
        $user = Auth::user();

        // Jika belum login, kembalikan ke halaman login (ditangani auth middleware)
        if (! $user) {
            return redirect('/login');
        }

        /** @var \App\Models\Role|null $role */
        $role = $user->roleAkses;

        // Jika user tidak punya role, atau role tidak memiliki privilege ini → 403
        if (! $role || ! $role->hasPrivilege($privilege)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
