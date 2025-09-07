<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function index()
    {
        // Ambil semua admin dengan users (role=user), persons, dan schedules mereka
        $admins = User::where('role', 'admin')
            ->with([
                'users' => function ($query) {
                    $query->where('role', 'user') // pastikan hanya ambil user biasa
                          ->with([
                              'person.schedules' => function ($scheduleQuery) {
                                  $scheduleQuery->with('persons.user'); 
                              }
                          ]);
                },
            ])
            ->get();

        return view('superadmin.dashboard', compact('admins'));
    }
}
