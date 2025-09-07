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
        $user = Auth::user();
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
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'person_id' => 'required|exists:persons,id',
            'status' => 'required|in:present,alpa',
            'description' => $request->status === 'alpa' ? 'required|string' : 'nullable|string',
        ]);

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

        return back()->with('success', 'Attendance submitted successfully');
    }

    public function uploadPhoto(Request $request, $scheduleId)
    {
        $schedule = Schedule::findOrFail($scheduleId);

        // Jika sudah ada foto, hentikan
        if ($schedule->photo) {
            return back()->with('error', 'Foto sudah diupload untuk jadwal ini.');
        }

        // Validasi
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Simpan foto ke storage
        $path = $request->file('photo')->store('schedules', 'public');

        // Update kolom photo di schedules
        $schedule->photo = $path;
        $schedule->save();

        return back()->with('success', 'Foto berhasil diupload.');
    }
}
