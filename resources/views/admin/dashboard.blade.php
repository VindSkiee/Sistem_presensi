<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Jadwal Hari Ini ({{ now()->isoFormat('dddd, D MMMM Y') }})</h2>

                @if ($schedule->is_validated)
                    <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Jadwal ini sudah divalidasi pada {{ $schedule->updated_at->format('d M Y H:i') }} dan
                                    terkunci.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Kehadiran
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status Validasi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($schedule->attendances as $attendance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $attendance->person->name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $attendance->status === 'present'
                                    ? 'bg-green-100 text-green-800'
                                    : ($attendance->status === 'alpa'
                                        ? 'bg-red-100 text-red-800'
                                        : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                @if ($attendance->status === 'alpa' && $attendance->description)
                                                    {{ $attendance->description }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($attendance->is_validated)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    ✓ Valid
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Tidak Valid
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif ($schedule->attendances->count() > 0)
                    <form action="{{ route('admin.attendance.validate') }}" method="POST" id="validationForm">
                        @csrf
                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nama
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Deskripsi
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Validasi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($schedule->attendances as $attendance)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $attendance->person->name }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $attendance->status === 'present'
                                                        ? 'bg-green-100 text-green-800'
                                                        : ($attendance->status === 'alpa'
                                                            ? 'bg-red-100 text-red-800'
                                                            : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        @if ($attendance->status === 'alpa' && $attendance->description)
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ $attendance->description }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if (!$attendance->is_validated)
                                                    <input type="checkbox"
                                                        name="attendances[{{ $attendance->id }}][is_validated]"
                                                        value="1"
                                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded attendance-checkbox">
                                                    <input type="hidden" name="attendances[{{ $attendance->id }}][id]"
                                                        value="{{ $attendance->id }}">
                                                    <input type="hidden" name="attendances[{{ $attendance->id }}][status]"
                                                        value="{{ $attendance->status }}">
                                                @else
                                                    <span class="text-green-500">✓ Valid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            <button type="submit" id="validateButton" disabled
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded opacity-50 cursor-not-allowed">
                                Validasi Kehadiran Terpilih dan Kunci Jadwal
                            </button>
                            <button type="button" id="validateAllButton"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                                Validasi Semua dan Kunci Jadwal
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-gray-600">Tidak ada jadwal untuk hari ini.</p>
                    <div class="mt-4">
                        <form action="{{ route('admin.generate.weekly.form') }}" method="GET">
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
                                        Hari/Tanggal
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
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
                                            <a href="{{ route('admin.validate.previous', $unvalidatedSchedule) }}"
                                                class="text-blue-600 hover:text-blue-900">Validasi</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-600">Tidak ada jadwal yang belum divalidasi sebelum hari ini.</p>
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkboxes = document.querySelectorAll('.attendance-checkbox');
                const validateButton = document.getElementById('validateButton');
                const validateAllButton = document.getElementById('validateAllButton');

                // Enable/disable validate button based on checkbox selection
                function updateValidateButton() {
                    const checkedBoxes = document.querySelectorAll('.attendance-checkbox:checked');
                    if (checkedBoxes.length > 0) {
                        validateButton.disabled = false;
                        validateButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        validateButton.disabled = true;
                        validateButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }

                // Check all checkboxes
                if (validateAllButton) {
                    validateAllButton.addEventListener('click', function() {
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = true;
                        });
                        updateValidateButton();
                    });
                }

                // Add event listeners to checkboxes
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateValidateButton);
                });

                // Initial button state
                updateValidateButton();
            });

            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('validationForm');
                const submitBtn = document.getElementById('validateButton');

                // Form submission handler
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault(); 

                    // Show confirmation dialog
                    Swal.fire({
                        title: 'Konfirmasi Perubahan',
                        text: 'Apakah Anda yakin?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3B82F6',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
{{-- 
<!-- Enhanced Admin Dashboard - Based on Original Code -->
@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800">Dashboard Admin</h1>
                        <p class="text-slate-600 font-medium">Kelola sistem presensi dengan mudah</p>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Today's Schedule Card -->
                <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-3xl overflow-hidden border border-white/20">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Jadwal Hari Ini ({{ now()->isoFormat('dddd, D MMMM Y') }})</h2>
                    </div>

                    <!-- Card Content -->
                    <div class="p-6">
                        @if ($schedule)
                            <!-- Generate Button -->
                            <div class="mb-4">
                                <form action="{{ route('admin.generate.weekly.form') }}" method="GET">
                                    <button type="submit"
                                        class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Generate Jadwal Mingguan
                                    </button>
                                </form>
                            </div>

                            <!-- Table -->
                            <div class="overflow-x-auto">
                                <div class="overflow-hidden rounded-2xl border border-slate-200/60 shadow-sm">
                                    <table class="min-w-full divide-y divide-slate-200">
                                        <thead class="bg-gradient-to-r from-slate-50 to-slate-100/50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Nama</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Deskripsi</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Validasi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-200">
                                            @foreach ($schedule->attendances as $attendance)
                                                <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 bg-gradient-to-br from-slate-400 to-slate-500 rounded-full flex items-center justify-center shadow-md mr-3">
                                                                <span class="text-white text-sm font-bold">
                                                                    {{ strtoupper(substr($attendance->person->name, 0, 1)) }}
                                                                </span>
                                                            </div>
                                                            <div class="text-sm font-medium text-slate-900">
                                                                {{ $attendance->person->name }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $attendance->status === 'present'
                                                                ? 'bg-green-100 text-green-800'
                                                                : ($attendance->status === 'alpa'
                                                                    ? 'bg-red-100 text-red-800'
                                                                    : 'bg-gray-100 text-gray-800') }}">
                                                            {{ ucfirst($attendance->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="text-sm font-medium text-slate-900">
                                                                @if ($attendance->status === 'alpa' && $attendance->description)
                                                                    <div class="bg-red-50 border border-red-200 rounded-lg p-2 max-w-xs">
                                                                        <p class="text-xs text-red-800">{{ $attendance->description }}</p>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                                        <input type="checkbox" name="attendances[{{ $attendance->id }}][is_validated]"
                                                            {{ $attendance->is_validated ? 'checked disabled' : '' }}
                                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Validation Actions -->
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
                                            <input type="hidden" name="attendances[{{ $attendance->id }}][is_validated]"
                                                value="1">
                                        @endforeach

                                        <button type="submit"
                                            class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-700 hover:from-green-600 hover:to-green-800 text-white font-bold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                            <svg class="w-4 h-4 mr-2 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Validasi Kehadiran
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="mt-4 text-green-600 font-medium">
                                    <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 font-bold rounded-xl border border-green-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Jadwal ini sudah divalidasi
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-600 mb-4">Tidak ada jadwal untuk hari ini.</p>
                                <div class="mt-4">
                                    <form action="{{ route('admin.generate.weekly.form') }}" method="GET">
                                        <button type="submit"
                                            class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                            <svg class="w-4 h-4 mr-2 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            Generate Jadwal Mingguan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Unvalidated Schedules Card -->
                <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-3xl overflow-hidden border border-white/20">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">Jadwal Belum Divalidasi</h2>
                    </div>

                    <!-- Card Content -->
                    <div class="p-6">
                        @if ($unvalidatedSchedules->count() > 0)
                            <div class="overflow-x-auto">
                                <div class="overflow-hidden rounded-2xl border border-slate-200/60 shadow-sm">
                                    <table class="min-w-full divide-y divide-slate-200">
                                        <thead class="bg-gradient-to-r from-slate-50 to-slate-100/50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Hari/Tanggal</th>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-slate-200">
                                            @foreach ($unvalidatedSchedules as $unvalidatedSchedule)
                                                <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center shadow-md mr-3">
                                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="text-sm font-medium text-slate-900">
                                                                {{ $unvalidatedSchedule->day_name }},
                                                                {{ \Carbon\Carbon::parse($unvalidatedSchedule->date)->format('d M Y') }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                                        <a href="{{ route('admin.validate.previous', $unvalidatedSchedule) }}"
                                                            class="group inline-flex items-center px-3 py-1 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold rounded-lg text-xs transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                                            <svg class="w-3 h-3 mr-1 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            Validasi
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-slate-600">Tidak ada jadwal yang belum divalidasi sebelum hari ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Admin Menu Section -->
            <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-3xl overflow-hidden border border-white/20">
                <!-- Section Header -->
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-white">Menu Admin</h2>
                    </div>
                </div>

                <!-- Menu Grid -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.persons.index') }}"
                            class="group bg-gradient-to-br from-blue-100 to-blue-200 hover:from-blue-200 hover:to-blue-300 p-4 rounded-lg text-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                            <h3 class="font-medium text-blue-800">Tambah Orang</h3>
                            <p class="text-sm text-blue-600 mt-1">Kelola daftar orang</p>
                        </a>

                        <a href="{{ route('admin.schedules.index') }}"
                            class="group bg-gradient-to-br from-green-100 to-green-200 hover:from-green-200 hover:to-green-300 p-4 rounded-lg text-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="font-medium text-green-800">Kelola Jadwal</h3>
                            <p class="text-sm text-green-600 mt-1">Tambah/edit jadwal</p>
                        </a>

                        <a href="{{ route('admin.attendances.history') }}"
                            class="group bg-gradient-to-br from-purple-100 to-purple-200 hover:from-purple-200 hover:to-purple-300 p-4 rounded-lg text-center transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="font-medium text-purple-800">History Presensi</h3>
                            <p class="text-sm text-purple-600 mt-1">Lihat data historis</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
