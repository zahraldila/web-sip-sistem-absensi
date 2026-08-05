<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionTimeout
{
    protected $timeout = 1800; // 30 minutes

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $last = Session::get('lastActivityTime');
            $now = time();

            if ($last && ($now - $last) > $this->timeout) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/login')->withErrors(['message' => 'Sesi Anda telah berakhir. Silakan login kembali.']);
            }

            Session::put('lastActivityTime', $now);
        }

        return $next($request);
    }
}
