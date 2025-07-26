<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\Person;
use App\Models\Attendance;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with('persons')->orderBy('date', 'desc')->paginate(10);
        return view('admin.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $persons = Person::all();
        return view('admin.schedules.create', compact('persons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:schedules,date',
            'persons' => 'required|array',
            'persons.*' => 'exists:persons,id',
        ]);

        $schedule = Schedule::create([
            'date' => $request->date,
            'day_name' => Carbon::parse($request->date)->isoFormat('dddd'),
        ]);

        $schedule->persons()->sync($request->persons);

        // Create empty attendance records
        foreach ($request->persons as $personId) {
            Attendance::create([
                'schedule_id' => $schedule->id,
                'person_id' => $personId,
                'status' => 'alpa',
            ]);
        }

        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully');
    }

    public function edit(Schedule $schedule)
    {
        $persons = Person::all();
        return view('admin.schedules.edit', compact('schedule', 'persons'));
    }

    public function update(Request $request, Schedule $schedule)
    {
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
        $schedule->attendances()->delete();
        $schedule->delete();
        return back()->with('success', 'Schedule deleted successfully');
    }

    public function generateWeekly()
    {
        // Get last week's schedule to copy persons
        $lastWeek = Schedule::orderBy('date', 'desc')->first();
        
        if (!$lastWeek) {
            return back()->with('error', 'No previous schedule found to generate from');
        }

        $startDate = Carbon::now()->startOfWeek();
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startDate->copy()->addDays($i);
            
            // Skip if schedule already exists
            if (Schedule::whereDate('date', $date)->exists()) {
                continue;
            }

            $schedule = Schedule::create([
                'date' => $date,
                'day_name' => $date->isoFormat('dddd'),
            ]);

            $schedule->persons()->sync($lastWeek->persons->pluck('id'));

            // Create empty attendance records
            foreach ($lastWeek->persons as $person) {
                Attendance::create([
                    'schedule_id' => $schedule->id,
                    'person_id' => $person->id,
                    'status' => 'alpa',
                ]);
            }
        }

        return back()->with('success', 'Weekly schedules generated successfully');
    }
}