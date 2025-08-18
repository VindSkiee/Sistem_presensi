<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Person;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $adminId = Auth::id();

        // Hapus foto schedule yang sudah lewat
        $oldSchedules = Schedule::whereDate('date', '<', $today)->get();
        foreach ($oldSchedules as $oldSchedule) {
            if ($oldSchedule->photo && Storage::exists($oldSchedule->photo)) {
                Storage::delete($oldSchedule->photo);
            }
            $oldSchedule->photo = null;
            $oldSchedule->save();
        }

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
        $adminUserIds = $admin->users()->pluck('id');
        $adminPersonIds = Person::whereIn('user_id', $adminUserIds)->pluck('id')->toArray();

        Log::info('Mulai proses validasi absensi', [
            'schedule_id' => $request->schedule_id,
            'admin_id' => $admin->id
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);

        // Jika schedule sudah divalidasi, kembalikan error
        if ($schedule->is_validated) {
            return back()->with('error', 'Jadwal ini sudah terkunci dan tidak dapat diubah.');
        }

        $validatedAny = false;

        foreach ($request->attendances as $attendanceData) {
            // Hanya proses jika is_validated true (checkbox dicentang)
            if (!isset($attendanceData['is_validated']) || $attendanceData['is_validated'] != '1') {
                continue;
            }

            $attendance = Attendance::with('person')->find($attendanceData['id']);

            // ✅ Perbaikan: Tambahkan kurung tutup di akhir in_array
            if ($attendance && in_array($attendance->person_id, $adminPersonIds)) {
                $attendance->update([
                    'status' => $attendanceData['status'],
                    'is_validated' => true,
                ]);
                $validatedAny = true;

                Log::info('Attendance diperbarui', [
                    'id' => $attendance->id,
                    'status' => $attendanceData['status'],
                    'person_id' => $attendance->person_id
                ]);
            }
        }

        // Jika ada minimal satu yang divalidasi, lock seluruh schedule
        if ($validatedAny) {
            $schedule->update([
                'is_validated' => true,
                'validated_at' => now(),
            ]);

            Log::info('Schedule terkunci setelah validasi parsial', ['schedule_id' => $schedule->id]);
        }

        return back()->with('success', 'Absensi berhasil divalidasi dan jadwal terkunci.');
    }
}
