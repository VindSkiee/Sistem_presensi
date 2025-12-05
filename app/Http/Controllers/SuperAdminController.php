<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $searchName = $request->input('search_name');

        // Ambil semua admin dengan users (role=user), persons, schedules, dan attendances mereka
        $admins = User::where('role', 'admin')
            ->with([
                'users' => function ($query) use ($startDate, $endDate, $searchName) {
                    $query->where('role', 'user');
                    
                    // Filter by name if search is provided
                    if ($searchName) {
                        $query->where('name', 'like', '%' . $searchName . '%');
                    }
                    
                    $query->with([
                        'person' => function ($personQuery) use ($startDate, $endDate) {
                            $personQuery->with([
                                'schedules' => function ($scheduleQuery) use ($startDate, $endDate) {
                                    $scheduleQuery->with('persons.user');
                                    if ($startDate && $endDate) {
                                        $scheduleQuery->whereBetween('date', [$startDate, $endDate]);
                                    }
                                    $scheduleQuery->orderBy('date', 'desc');
                                },
                                'attendances' => function ($attendanceQuery) use ($startDate, $endDate) {
                                    $attendanceQuery->with(['schedule' => function ($scheduleQuery) use ($startDate, $endDate) {
                                        if ($startDate && $endDate) {
                                            $scheduleQuery->whereBetween('date', [$startDate, $endDate]);
                                        }
                                    }]);
                                }
                            ]);
                        }
                    ]);
                },
            ])
            ->get();
        
        // Filter out admins with no users (after search filter)
        if ($searchName) {
            $admins = $admins->filter(function ($admin) {
                return $admin->users->count() > 0;
            });
        }

        // Calculate statistics for each user and filter/sort attendances
        foreach ($admins as $admin) {
            foreach ($admin->users as $user) {
                if ($user->person) {
                    // Filter attendances by date range if provided
                    $attendances = $user->person->attendances;
                    
                    if ($startDate && $endDate) {
                        $attendances = $attendances->filter(function ($attendance) use ($startDate, $endDate) {
                            if ($attendance->schedule) {
                                $scheduleDate = $attendance->schedule->date;
                                return $scheduleDate >= $startDate && $scheduleDate <= $endDate;
                            }
                            return false;
                        });
                    }
                    
                    // Sort attendances by schedule date (descending)
                    $sortedAttendances = $attendances->sortByDesc(function ($attendance) {
                        return $attendance->schedule ? $attendance->schedule->date : null;
                    })->values();
                    
                    // Replace the attendances collection with sorted and filtered one
                    $user->person->setRelation('attendances', $sortedAttendances);
                    
                    // Calculate statistics
                    $user->total_hadir = $sortedAttendances->where('status', 'present')->count();
                    $user->total_alpa = $sortedAttendances->where('status', 'alpa')->count();
                }
            }
        }

        return view('superadmin.dashboard', compact('admins', 'startDate', 'endDate', 'searchName'));
    }
}