<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Jika belum login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Jika login tapi bukan user
        if (Auth::user()->role !== 'user') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        // Jika role-nya user
        return $next($request);
    }
}
