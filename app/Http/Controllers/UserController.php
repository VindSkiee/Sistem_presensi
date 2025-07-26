<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $schedule = Schedule::with(['persons', 'attendances'])
            ->whereDate('date', $today)
            ->first();

        return view('user.dashboard', compact('schedule'));
    }

    public function submitAttendance(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'person_id' => 'required|exists:persons,id',
            'status' => 'required|in:hadir,alpa',
            'description' => 'required_if:status,alpa',
        ]);

        Attendance::updateOrCreate(
            [
                'schedule_id' => $request->schedule_id,
                'person_id' => $request->person_id,
            ],
            [
                'user_id' => Auth::user()->id,
                'status' => $request->status,
                'description' => $request->description,
            ]
        );

        return back()->with('success', 'Attendance submitted successfully');
    }
}
