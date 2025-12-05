<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Person;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        // Get schedules with count of persons
        $schedules = Schedule::withCount('persons')
            ->where('admin_id', $admin->id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        // Get all persons under this admin (if needed for other functionality)
        $persons = Person::whereIn('user_id', $admin->users->pluck('id'))->get();

        return view('admin.schedules.index', compact('schedules', 'persons'));
    }

    public function create()
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();
        $persons = Person::whereIn('user_id', $admin->users->pluck('id'))->get();
        return view('admin.schedules.create', compact('persons'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'persons' => 'array',
        ], [
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'persons.array' => 'Data orang tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            /** @var \App\Models\User $admin */
            $admin = Auth::user();

            $schedule = Schedule::create([
                'date' => $request->date,
                'day_name' => Carbon::parse($request->date)->locale('id')->isoFormat('dddd'),
                'admin_id' => $admin->id,
            ]);

            if ($request->has('persons')) {
                $schedule->persons()->attach($request->persons);

                foreach ($request->persons as $personId) {
                    $person = Person::with('user')->find($personId);

                    Attendance::create([
                        'schedule_id' => $schedule->id,
                        'person_id' => $person->id,
                        'user_id' => $person->user_id,
                        'admin_id' => $admin->id, // ⬅️ tambahkan ini agar sesuai dengan admin yang login
                        'status' => 'alpa',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menambah jadwal', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat menambah jadwal.');
        }
    }


    public function edit(Schedule $schedule)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();

        // Authorization
        abort_if($schedule->admin_id !== $admin->id, 403, 'Unauthorized action.');

        // Ambil orang-orang yang memang terdaftar di jadwal ini
        $persons = Person::whereIn('user_id', $admin->users->pluck('id'))
            ->whereHas('attendances', function ($query) use ($schedule) {
                $query->where('schedule_id', $schedule->id);
            })
            ->with(['attendances' => function ($query) use ($schedule) {
                $query->where('schedule_id', $schedule->id);
            }])
            ->orderBy('name')
            ->get();

        return view('admin.schedules.edit', compact('schedule', 'persons'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();
        abort_if($schedule->admin_id !== $admin->id, 403, 'Unauthorized action.');

        $validated = $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.person_id' => 'required|exists:persons,id',
            'attendances.*.status' => 'required|in:present,alpa',
            // Remove the 'sometimes' rule as we always want this field
            'attendances.*.is_validated' => 'required|boolean'
        ], [
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'attendances.required' => 'Data kehadiran wajib diisi.',
            'attendances.array' => 'Data kehadiran tidak valid.',
            'attendances.*.person_id.required' => 'ID orang wajib diisi.',
            'attendances.*.person_id.exists' => 'Orang tidak ditemukan.',
            'attendances.*.status.required' => 'Status kehadiran wajib diisi.',
            'attendances.*.status.in' => 'Status harus hadir atau alpa.',
            'attendances.*.is_validated.required' => 'Status validasi wajib diisi.',
            'attendances.*.is_validated.boolean' => 'Status validasi tidak valid.',
        ]);

        DB::transaction(function () use ($schedule, $validated) {
            // Update schedule date
            $schedule->update([
                'date' => Carbon::parse($validated['date']),
                'day_name' => Carbon::parse($validated['date'])->isoFormat('dddd')
            ]);

            // Update attendances
            foreach ($validated['attendances'] as $attendanceData) {
                $attendance = $schedule->attendances()
                    ->firstOrNew(['person_id' => $attendanceData['person_id']]);

                $attendance->fill([
                    'status' => $attendanceData['status'],
                    'is_validated' => $attendanceData['is_validated'] // Always use the submitted value
                ])->save();
            }

            // Update overall schedule validation status
            $allValidated = $schedule->attendances()->where('is_validated', false)->doesntExist();
            $schedule->update(['is_validated' => $allValidated]);
        });

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->admin_id !== Auth::id()) {
            abort(403);
        }

        $schedule->attendances()->delete();
        $schedule->persons()->detach();
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully');
    }

    public function showGenerateWeeklyForm()
    {
        /** @var \App\Models\User $admin */
        $admin = Auth::user();
        $persons = Person::whereIn('user_id', $admin->users->pluck('id'))->get();
        if ($persons->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada orang yang tersedia untuk dijadwalkan.');
        }
        return view('admin.schedules.generate-weekly', compact('persons'));
    }

    public function generateWeekly(Request $request)
    {
        Log::info('Memulai generateWeekly()');

        $personIds = $request->input('persons');

        if (!$personIds || count($personIds) === 0) {
            Log::warning('Tidak ada orang yang dipilih.');
            return redirect()->back()->with('error', 'Pilih minimal satu orang.');
        }

        Log::info('Orang yang dipilih: ', $personIds);

        /** @var \App\Models\User $admin */
        $admin = Auth::user();
        $adminId = $admin && $admin->hasRole('admin') ? $admin->id : null;

        // Ambil jadwal terakhir untuk admin ini saja
        $lastSchedule = Schedule::where('admin_id', $adminId)
            ->orderBy('date', 'desc')
            ->first();

        $today = Carbon::today();

        if ($lastSchedule) {
            $lastScheduleDate = Carbon::parse($lastSchedule->date);
            $startDate = $lastScheduleDate->copy()->addDay();
        } else {
            $startDate = $today;
        }

        Log::info('Tanggal mulai generate jadwal: ' . $startDate->toDateString());

        // Buat jadwal 7 hari ke depan
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayName = $date->locale('id')->translatedFormat('l');

            $schedule = Schedule::create([
                'date' => $date,
                'day_name' => $dayName,
                'admin_id' => $adminId,
            ]);

            Log::info("Membuat schedule untuk tanggal {$date->toDateString()} dengan ID: {$schedule->id}");

            foreach ($personIds as $personId) {
                $person = Person::with('user')->find($personId);

                Attendance::create([
                    'schedule_id' => $schedule->id,
                    'person_id' => $personId,
                    'status' => 'alpa',
                    'user_id' => $person->user_id,
                    'admin_id' => $adminId,
                ]);
            }

            $schedule->persons()->attach($personIds);
        }

        Log::info('Selesai generateWeekly().');

        return redirect()->back()->with('success', 'Jadwal mingguan berhasil dibuat.');
    }

    public function showUnvalidatedSchedules()
    {
        $schedules = Schedule::with(['attendances.person'])
            ->where('date', '<', Carbon::today()) // ambil yang sebelum hari ini
            ->whereHas('attendances', function ($query) {
                $query->where('is_validated', false);
            })
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.schedules.validate_previous', compact('schedules'));
    }

    public function updateUnvalidated(Request $request, $scheduleId)
    {
        foreach ($request->input('attendances', []) as $attendanceId => $data) {
            $attendance = \App\Models\Attendance::find($attendanceId);

            if ($attendance && !$attendance->is_validated) {
                $attendance->is_validated = isset($data['is_validated']);
                $attendance->save();
            }
        }

        return redirect()->back()->with('success', 'Validasi berhasil disimpan.');
    }
}
