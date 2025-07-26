<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function history(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);
        $weeks = $request->input('weeks', 1);

        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->addWeeks($weeks)->endOfWeek();

        $schedules = Schedule::with(['attendances.person'])
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.attendances.history', compact('schedules', 'year', 'month', 'weeks'));
    }
}