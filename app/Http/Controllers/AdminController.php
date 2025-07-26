<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $schedule = Schedule::with(['persons', 'attendances'])
            ->whereDate('date', $today)
            ->first();

        $unvalidatedSchedules = Schedule::where('is_validated', false)
            ->where('date', '<', $today)
            ->get();

        return view('admin.dashboard', compact('schedule', 'unvalidatedSchedules'));
    }

    public function validateAttendance(Request $request)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.id' => 'required|exists:attendances,id',
            'attendances.*.is_validated' => 'required|boolean',
            'attendances.*.status' => 'required|in:hadir,alpa,tidak_valid',
        ]);

        foreach ($request->attendances as $attendanceData) {
            $attendance = Attendance::find($attendanceData['id']);
            $attendance->update([
                'is_validated' => $attendanceData['is_validated'],
                'status' => $attendanceData['status'],
            ]);
        }

        $schedule = Schedule::find($request->schedule_id);
        $schedule->update(['is_validated' => true]);

        return back()->with('success', 'Attendance validated successfully');
    }
}