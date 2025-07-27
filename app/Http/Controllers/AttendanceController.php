<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function history(Request $request)
    {
        $adminId = Auth::id();
        $year = intval($request->input('year', now()->year));
        $month = intval($request->input('month', now()->month));
        $range = $request->input('range', 'weeks'); // default ke minggu
        $weeks = intval($request->input('weeks', 1));

        $startDate = Carbon::create($year, $month, 1);
        $endDate = $range === 'month'
            ? $startDate->copy()->endOfMonth()
            : $startDate->copy()->addWeeks($weeks)->endOfWeek();

        $schedules = Schedule::with([
            'attendances.person'
        ])
            ->whereHas('attendances', function ($query) use ($adminId) {
                $query->where('admin_id', $adminId); // filter hanya yang dimiliki admin
            })
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();



        return view('admin.attendances.history', compact('schedules', 'year', 'month', 'weeks', 'range'));
    }
}
