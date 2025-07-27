<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Person;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $adminId = Auth::id(); // ID admin saat ini

        // Ambil schedule hari ini dengan persons milik admin yang sedang login
        $schedule = Schedule::whereDate('date', $today)
            ->whereHas('persons.user', function ($query) use ($adminId) {
                $query->where('admin_id', $adminId);
            })
            ->with(['persons' => function ($query) use ($adminId) {
                $query->whereHas('user', function ($subQuery) use ($adminId) {
                    $subQuery->where('admin_id', $adminId);
                })->with('user');
            }, 'attendances'])
            ->first();

        // Ambil schedule yang belum divalidasi dan milik user dari admin yang sedang login
        $unvalidatedSchedules = Schedule::whereDate('date', '<', $today)
            ->whereHas('attendances', function ($query) {
                $query->where('is_validated', false);
            })
            ->whereHas('persons.user', function ($query) use ($adminId) {
                $query->where('admin_id', $adminId);
            })
            ->with(['persons' => function ($query) use ($adminId) {
                $query->whereHas('user', function ($subQuery) use ($adminId) {
                    $subQuery->where('admin_id', $adminId);
                })->with('user');
            }])
            ->get();

        return view('admin.dashboard', compact('schedule', 'unvalidatedSchedules'));
    }


    public function validateAttendance(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'attendances' => 'required|array',
            'attendances.*.id' => 'required|exists:attendances,id',
            'attendances.*.status' => 'required|in:present,alpa',
        ]);

        $admin = Auth::user();
        $adminUserIds = $admin->users()->pluck('id'); // user bawahan dari admin ini
        $adminPersonIds = Person::whereIn('user_id', $adminUserIds)->pluck('id')->toArray(); // ambil semua person milik user bawahan

        Log::info('Mulai proses validasi absensi', ['schedule_id' => $request->schedule_id, 'admin_id' => $admin->id]);

        foreach ($request->attendances as $attendanceData) {
            $attendance = Attendance::with('person')->find($attendanceData['id']);

            // Cek apakah attendance milik person yang berada di bawah admin ini
            if ($attendance && in_array($attendance->person_id, $adminPersonIds) && !$attendance->is_validated) {
                $attendance->update([
                    'status' => $attendanceData['status'],
                    'is_validated' => true,
                ]);

                Log::info('Attendance diperbarui', [
                    'id' => $attendance->id,
                    'status' => $attendanceData['status'],
                    'person_id' => $attendance->person_id
                ]);
            } else {
                Log::warning('Attendance tidak valid untuk admin ini', [
                    'attendance_id' => $attendanceData['id'] ?? null,
                    'person_id' => $attendance->person_id ?? null
                ]);
            }
        }

        $schedule = Schedule::find($request->schedule_id);
        if ($schedule) {
            $schedule->update([
                'is_validated' => true,
            ]);
            Log::info('Schedule divalidasi dan dikunci', ['schedule_id' => $schedule->id]);
        }

        return back()->with('success', 'Absensi berhasil divalidasi dan dikunci.');
    }
}
