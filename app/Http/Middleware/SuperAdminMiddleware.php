<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'super_admin') {
            return $next($request);
        }

        if (Auth::check()) {
            return redirect()->route('user.dashboard')->with('error', 'Unauthorized access');
        }

        return redirect()->route('login')->with('error', 'Please login first');
    }
}
