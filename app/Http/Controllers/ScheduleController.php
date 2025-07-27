<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Person;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $admin = auth()->user();
        $schedules = Schedule::with('persons')
            ->where('admin_id', $admin->id)
            ->orderBy('date', 'desc')
            ->paginate(10);

        $persons = Person::whereIn('user_id', $admin->users->pluck('id'))->get();

        return view('admin.schedules.index', compact('schedules', 'persons'));
    }

    public function create()
    {
        $admin = auth()->user();
        $persons = Person::whereIn('user_id', $admin->users->pluck('id'))->get();
        return view('admin.schedules.create', compact('persons'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'persons' => 'array',
        ]);

        try {
            DB::beginTransaction();

            $admin = auth()->user();

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
        $admin = auth()->user();
        if ($schedule->admin_id !== $admin->id) {
            abort(403, 'Anda tidak berhak mengedit jadwal ini.');
        }

        $persons = Person::whereIn('user_id', $admin->users->pluck('id'))->get();
        return view('admin.schedules.edit', compact('schedule', 'persons'));
    }


    public function update(Request $request, Schedule $schedule)
    {
        if ($schedule->admin_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'date' => 'required|date|unique:schedules,date,' . $schedule->id,
            'persons' => 'required|array',
            'persons.*' => 'exists:persons,id',
        ]);

        $schedule->update([
            'date' => $request->date,
            'day_name' => Carbon::parse($request->date)->isoFormat('dddd'),
        ]);

        $schedule->persons()->sync($request->persons);

        // Sync attendance records
        $existingPersonIds = $schedule->attendances->pluck('person_id')->toArray();
        $newPersonIds = $request->persons;

        // Remove attendances for removed persons
        $toRemove = array_diff($existingPersonIds, $newPersonIds);
        Attendance::where('schedule_id', $schedule->id)
            ->whereIn('person_id', $toRemove)
            ->delete();

        // Add attendances for new persons
        $toAdd = array_diff($newPersonIds, $existingPersonIds);
        foreach ($toAdd as $personId) {
            Attendance::create([
                'schedule_id' => $schedule->id,
                'person_id' => $personId,
                'status' => 'alpa',
            ]);
        }

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully');
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->admin_id !== auth()->id()) {
            abort(403);
        }

        $schedule->attendances()->delete();
        $schedule->persons()->detach();
        $schedule->delete();

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully');
    }

    public function showGenerateWeeklyForm()
    {
        $persons = Person::whereIn('user_id', auth()->user()->users->pluck('id'))->get();
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

        $lastSchedule = Schedule::orderBy('date', 'desc')->first();
        $startDate = $lastSchedule ? Carbon::parse($lastSchedule->date)->addDay() : now();
        Log::info('Tanggal mulai generate jadwal: ' . $startDate->toDateString());

        $admin = auth()->user();
        $adminId = $admin && $admin->hasRole('admin') ? $admin->id : null;

        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            $dayName = $date->locale('id')->translatedFormat('l');

            $schedule = Schedule::create([
                'date' => $date,
                'day_name' => $dayName,
                'admin_id' => $adminId, // <- Tambahkan ini
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
