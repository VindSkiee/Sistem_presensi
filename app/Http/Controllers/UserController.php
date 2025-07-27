<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $today = Carbon::today();
        $schedule = null;

        if ($user->role === 'admin') {
            // Admin melihat semua data
            $schedule = Schedule::where('admin_id', $user->id)
                ->whereDate('date', $today)
                ->with(['persons', 'attendances']) // Load semua attendance
                ->first();
        } else {
            // User biasa melihat jadwal mereka
            $person = $user->person;

            if ($person) {
                $schedule = Schedule::whereDate('date', $today)
                    ->whereHas('persons', function ($query) use ($person) {
                        $query->where('person_id', $person->id);
                    })
                    ->with(['persons', 'attendances']) // Load semua attendance
                    ->first();
            }
        }

        return view('user.dashboard', compact('schedule'));
    }


    public function submitAttendance(Request $request)
    {
        Log::info('Submit start', $request->all());

        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'person_id' => 'required|exists:persons,id',
            'status' => 'required|in:present,alpa',
            'description' => $request->status === 'alpa' ? 'required|string' : 'nullable|string',
        ]);
        Log::info('Validation passed');

        $userId = Auth::id();

        $attendance = Attendance::updateOrCreate(
            [
                'schedule_id' => $request->schedule_id,
                'person_id' => $request->person_id,
            ],
            [
                'user_id' => $userId,
                'status' => $request->status,
                'description' => $request->status === 'alpa' ? $request->description : null,
            ]
        );
        Log::info('Attendance updated/created', ['attendance' => $attendance]);

        return back()->with('success', 'Attendance submitted successfully');
    }
}
