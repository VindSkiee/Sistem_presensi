<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();

    //         $user = Auth::user();

    //         // Redirect berdasarkan role
    //         switch ($user->role) {
    //             case 'super_admin':
    //                 return redirect()->intended('/super-admin/dashboard');
    //             case 'admin':
    //                 return redirect()->intended('/admin/dashboard');
    //             default:
    //                 return redirect()->intended('/user/dashboard');
    //         }


    //         return redirect()->intended('/user/dashboard');
    //     }

    //     return back()->withErrors([
    //         'email' => 'Email atau password salah',
    //     ]);
    // }

    // LOGIN JAM 12 - 2 PAGI
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Kalau role user, cek waktu login
            if ($user->role === 'user') {
                $now = \Carbon\Carbon::now();
                $start = $now->copy()->startOfDay(); // 00:00
                $end = $now->copy()->startOfDay()->addHours(2); // 02:00

                if (!($now->between($start, $end))) {
                    Auth::logout(); // logout lagi biar ga kebobolan
                    return back()->withErrors([
                        'email' => 'Login untuk user hanya diperbolehkan antara jam 00:00 - 02:00 WIB',
                    ]);
                }
            }

            // Redirect berdasarkan role
            switch ($user->role) {
                case 'super_admin':
                    return redirect()->intended('/super-admin/dashboard');
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                default:
                    return redirect()->intended('/user/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
