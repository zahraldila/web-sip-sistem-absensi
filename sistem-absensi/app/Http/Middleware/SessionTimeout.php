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
                Session::flush();
                return redirect('/login')->withErrors(['message' => 'Session expired. Please login again.']);
            }
            Session::put('lastActivityTime', $now);
        }
        return $next($request);
    }
}
