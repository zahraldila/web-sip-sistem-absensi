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

        $userRole = strtolower(trim((string) ($user->role ?? '')));

        // If user is Super Admin or Admin, they always satisfy Admin role requirements
        if (in_array($userRole, ['admin', 'super admin', 'super_admin'], true) || (isset($user->role_id) && $user->role_id === 1)) {
            return $next($request);
        }

        // Build list of allowed roles
        $allowed = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $item) {
                $trimmed = strtolower(trim($item));
                $allowed[] = $trimmed;
                if ($trimmed === 'admin') {
                    $allowed[] = 'super admin';
                    $allowed[] = 'super_admin';
                }
            }
        }

        if (! in_array($userRole, $allowed, true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
