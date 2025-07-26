<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Jadwal Hari Ini ({{ now()->isoFormat('dddd, D MMMM Y') }})</h2>

                @if ($schedule)
                    <div class="mb-4">
                        <form action="{{ route('admin.schedules.generate') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Generate Jadwal Mingguan
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Validasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($schedule->attendances as $attendance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $attendance->person->name }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $attendance->status === 'hadir'
                                                ? 'bg-green-100 text-green-800'
                                                : ($attendance->status === 'alpa'
                                                    ? 'bg-red-100 text-red-800'
                                                    : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                            @if ($attendance->status === 'alpa' && $attendance->description)
                                                <p class="text-xs text-gray-500 mt-1">{{ $attendance->description }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <input type="checkbox" name="attendances[{{ $attendance->id }}][is_validated]"
                                                {{ $attendance->is_validated ? 'checked' : '' }}
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if (!$schedule->is_validated)
                        <div class="mt-4">
                            <form action="{{ route('admin.attendance.validate') }}" method="POST">
                                @csrf
                                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                @foreach ($schedule->attendances as $attendance)
                                    <input type="hidden" name="attendances[{{ $attendance->id }}][id]"
                                        value="{{ $attendance->id }}">
                                    <input type="hidden" name="attendances[{{ $attendance->id }}][status]"
                                        value="{{ $attendance->status }}">
                                @endforeach

                                <button type="submit"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Validasi Kehadiran
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-4 text-green-600 font-medium">
                            Jadwal ini sudah divalidasi
                        </div>
                    @endif
                @else
                    <p class="text-gray-600">Tidak ada jadwal untuk hari ini.</p>
                    <div class="mt-4">
                        <form action="{{ route('admin.schedules.generate') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Generate Jadwal Mingguan
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Jadwal Belum Divalidasi</h2>

                @if ($unvalidatedSchedules->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Hari/Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($unvalidatedSchedules as $unvalidatedSchedule)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $unvalidatedSchedule->day_name }},
                                                {{ \Carbon\Carbon::parse($unvalidatedSchedule->date)->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <a href="{{ route('admin.schedules.edit', $unvalidatedSchedule) }}"
                                                class="text-blue-600 hover:text-blue-900">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">Tidak ada jadwal yang belum divalidasi.</p>
                @endif
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Menu Admin</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.persons.index') }}"
                    class="bg-blue-100 hover:bg-blue-200 p-4 rounded-lg text-center">
                    <h3 class="font-medium text-blue-800">Tambah Orang</h3>
                    <p class="text-sm text-blue-600 mt-1">Kelola daftar orang</p>
                </a>

                <a href="{{ route('admin.schedules.index') }}"
                    class="bg-green-100 hover:bg-green-200 p-4 rounded-lg text-center">
                    <h3 class="font-medium text-green-800">Kelola Jadwal</h3>
                    <p class="text-sm text-green-600 mt-1">Tambah/edit jadwal</p>
                </a>

                <a href="{{ route('admin.attendances.history') }}"
                    class="bg-purple-100 hover:bg-purple-200 p-4 rounded-lg text-center">
                    <h3 class="font-medium text-purple-800">History Presensi</h3>
                    <p class="text-sm text-purple-600 mt-1">Lihat data historis</p>
                </a>
            </div>
        </div>
    </div>
@endsection
