<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            // Jika URL diawali dengan /admin, redirect ke halaman login biasa
            if ($request->is('admin/*')) {
                return route('login'); // saat ini kamu hanya punya satu login page
            }

            // Default redirect untuk user biasa juga ke route login
            return route('login');
        }

        return null;
    }
}
