{{-- <!-- resources/views/user/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Presensi</h1>

        @if ($schedule)
            @if ($schedule->is_validated)
                <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded mb-6">
                    <p>Jadwal ini sudah divalidasi oleh admin. Tidak dapat melakukan perubahan presensi.</p>
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Jadwal Hari Ini ({{ $schedule->day_name }},
                    {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }})</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($schedule->persons as $person)
                                @php
                                    // Cari attendance untuk person ini di schedule ini
                                    $attendance = $schedule->attendances->where('person_id', $person->id)->first();
                                    $status = $attendance ? $attendance->status : 'not_present';
                                    $isCurrentUser = auth()->user()->person && auth()->user()->person->id === $person->id;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">{{ $person->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $status === 'present' ? 'bg-green-100 text-green-800' : 
                                               ($status === 'alpa' ? 'bg-red-100 text-red-800' : 'bg-gray-400 text-gray-700') }}">
                                            {{ $status === 'present' ? 'Hadir' : ($status === 'alpa' ? 'Alpa' : 'Belum absen') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if ($isCurrentUser)
                                            @if (!$schedule->is_validated)
                                                @if (!$attendance || $attendance->status === 'alpa')
                                                    <button onclick="openModal('present', {{ $schedule->id }}, {{ $person->id }})"
                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                        Hadir
                                                    </button>
                                                    <button onclick="openModal('alpa', {{ $schedule->id }}, {{ $person->id }})"
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs ml-2">
                                                        Alpa
                                                    </button>
                                                @else
                                                    <span class="text-gray-400">Sudah absen</span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">Terkunci (sudah divalidasi)</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-600">Tidak ada jadwal untuk hari ini.</p>
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if($schedule && !$schedule->is_validated)
    <div id="attendanceModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <form method="POST" action="{{ route('user.attendance.submit') }}">
                @csrf
                <input type="hidden" name="schedule_id" id="modalScheduleId">
                <input type="hidden" name="person_id" id="modalPersonId">
                <input type="hidden" name="status" id="modalStatus">

                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Konfirmasi Presensi</h3>

                    <div id="descriptionField" class="mb-4 hidden">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Alasan Alpa</label>
                        <textarea name="description" id="description" rows="3"
                            class="block w-full rounded-md border border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                    </div>

                    <p id="confirmationText" class="text-sm text-gray-700">
                        Anda yakin ingin menandai kehadiran sebagai <span id="statusText" class="font-semibold"></span>?
                    </p>
                </div>

                <div class="bg-gray-100 px-6 py-4 flex justify-end gap-3">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Konfirmasi
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white hover:bg-gray-50 px-4 py-2 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
        function openModal(status, scheduleId, personId) {
            const modal = document.getElementById('attendanceModal');
            document.getElementById('modalScheduleId').value = scheduleId;
            document.getElementById('modalPersonId').value = personId;
            document.getElementById('modalStatus').value = status;
            
            const statusText = document.getElementById('statusText');
            const descriptionField = document.getElementById('descriptionField');
            const confirmationText = document.getElementById('confirmationText');

            if (status === 'alpa') {
                statusText.textContent = 'Alpa';
                statusText.className = 'font-semibold text-red-600';
                descriptionField.classList.remove('hidden');
                confirmationText.textContent = 'Silakan berikan alasan alpa:';
            } else {
                statusText.textContent = 'Hadir';
                statusText.className = 'font-semibold text-green-600';
                descriptionField.classList.add('hidden');
                confirmationText.textContent = 'Anda yakin ingin menandai kehadiran sebagai Hadir?';
            }

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            document.getElementById('attendanceModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('description').value = '';
        }
    </script>
@endsection --}}

<!-- Enhanced User Dashboard with Modern Formal Design -->
@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center space-x-4 mb-2">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-800">Dashboard Presensi</h1>
                        <p class="text-slate-600 font-medium">Kelola kehadiran Anda dengan mudah</p>
                    </div>
                </div>
            </div>

            @if ($schedule)
                <!-- Validation Alert -->
                @if ($schedule->is_validated)
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/60 rounded-2xl p-6 mb-8 shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-blue-900 font-semibold">Jadwal Telah Divalidasi</h3>
                                <p class="text-blue-700 text-sm">Jadwal ini sudah divalidasi oleh admin. Tidak dapat melakukan perubahan presensi.</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Schedule Card -->
                <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-3xl overflow-hidden border border-white/20">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-white mb-2">
                                    Jadwal Hari Ini
                                </h2>
                                <div class="flex items-center space-x-4 text-slate-300">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="font-medium">{{ $schedule->day_name }}</span>
                                    </div>
                                    <div class="h-4 w-px bg-slate-500"></div>
                                    <span class="font-medium">{{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="hidden sm:block">
                                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Container -->
                    <div class="p-8">
                        <div class="overflow-hidden rounded-2xl border border-slate-200/60 shadow-sm">
                            <table class="min-w-full divide-y divide-slate-200/60">
                                <thead class="bg-gradient-to-r from-slate-50 to-slate-100/50">
                                    <tr>
                                        <th class="px-8 py-4 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span>Nama</span>
                                            </div>
                                        </th>
                                        <th class="px-8 py-4 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Status</span>
                                            </div>
                                        </th>
                                        <th class="px-8 py-4 text-left text-sm font-bold text-slate-700 uppercase tracking-wider">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                                </svg>
                                                <span>Aksi</span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-slate-100">
                                    @foreach ($schedule->persons as $person)
                                        @php
                                            // Cari attendance untuk person ini di schedule ini
                                            $attendance = $schedule->attendances->where('person_id', $person->id)->first();
                                            $status = $attendance ? $attendance->status : 'not_present';
                                            $isCurrentUser = auth()->user()->person && auth()->user()->person->id === $person->id;
                                        @endphp
                                        <tr class="hover:bg-slate-50/50 transition-colors duration-200 {{ $isCurrentUser ? 'bg-blue-50/30 border-l-4 border-blue-500' : '' }}">
                                            <td class="px-8 py-6 whitespace-nowrap">
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-10 h-10 {{ $isCurrentUser ? 'bg-gradient-to-br from-blue-500 to-indigo-600' : 'bg-gradient-to-br from-slate-400 to-slate-500' }} rounded-full flex items-center justify-center shadow-md">
                                                        <span class="text-white text-sm font-bold">
                                                            {{ strtoupper(substr($person->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="text-base font-semibold text-slate-900">{{ $person->name }}</div>
                                                        @if($isCurrentUser)
                                                            <div class="text-sm text-blue-600 font-medium">Anda</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-8 py-6 whitespace-nowrap">
                                                @if($status === 'present')
                                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200 shadow-sm">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Hadir
                                                    </span>
                                                @elseif($status === 'alpa')
                                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200 shadow-sm">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        Alpa
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-slate-100 to-gray-100 text-slate-700 border border-slate-200 shadow-sm">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Belum absen
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-8 py-6 whitespace-nowrap">
                                                @if ($isCurrentUser)
                                                    @if (!$schedule->is_validated)
                                                        @if (!$attendance || $attendance->status === 'alpa')
                                                            <div class="flex space-x-3">
                                                                <button onclick="openModal('present', {{ $schedule->id }}, {{ $person->id }})"
                                                                    class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                                                    <svg class="w-4 h-4 mr-2 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                    Hadir
                                                                </button>
                                                                <button onclick="openModal('alpa', {{ $schedule->id }}, {{ $person->id }})"
                                                                    class="group inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold rounded-xl text-sm transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                                                    <svg class="w-4 h-4 mr-2 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                    </svg>
                                                                    Alpa
                                                                </button>
                                                            </div>
                                                        @else
                                                            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                Sudah absen
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                            </svg>
                                                            Terkunci
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-slate-400 text-sm font-medium">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Schedule Card -->
                <div class="bg-white/80 backdrop-blur-sm shadow-xl rounded-3xl p-12 text-center border border-white/20">
                    <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-800 mb-3">Tidak Ada Jadwal</h3>
                    <p class="text-slate-600 text-lg">Tidak ada jadwal untuk hari ini. Silakan periksa kembali nanti.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Enhanced Modal -->
    @if($schedule && !$schedule->is_validated)
    <div id="attendanceModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center hidden">
        <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl w-full max-w-lg mx-4 border border-white/20 transform transition-all duration-300">
            <form method="POST" action="{{ route('user.attendance.submit') }}">
                @csrf
                <input type="hidden" name="schedule_id" id="modalScheduleId">
                <input type="hidden" name="person_id" id="modalPersonId">
                <input type="hidden" name="status" id="modalStatus">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-8 py-6 rounded-t-3xl">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Konfirmasi Presensi</h3>
                            <p class="text-slate-300 text-sm">Pastikan data yang Anda masukkan benar</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-8">
                    <div id="descriptionField" class="mb-6 hidden">
                        <label for="description" class="block text-sm font-bold text-slate-700 mb-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m0 0v10a2 2 0 002 2h6a2 2 0 002-2V8m-9 4h4"></path>
                                </svg>
                                <span>Alasan Alpa</span>
                            </div>
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="block w-full rounded-2xl border border-slate-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm p-4 bg-slate-50 transition-all duration-300"
                            placeholder="Jelaskan alasan ketidakhadiran Anda..."></textarea>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                        <p id="confirmationText" class="text-slate-700 text-base leading-relaxed">
                            Anda yakin ingin menandai kehadiran sebagai <span id="statusText" class="font-bold"></span>?
                        </p>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-slate-50/50 px-8 py-6 rounded-b-3xl flex justify-end space-x-4">
                    <button type="button" onclick="closeModal()"
                        class="inline-flex items-center justify-center rounded-2xl border-2 border-slate-300 bg-white hover:bg-slate-50 px-6 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-300 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 px-6 py-3 text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Enhanced JavaScript -->
    <script>
        function openModal(status, scheduleId, personId) {
            const modal = document.getElementById('attendanceModal');
            document.getElementById('modalScheduleId').value = scheduleId;
            document.getElementById('modalPersonId').value = personId;
            document.getElementById('modalStatus').value = status;
            
            const statusText = document.getElementById('statusText');
            const descriptionField = document.getElementById('descriptionField');
            const confirmationText = document.getElementById('confirmationText');

            if (status === 'alpa') {
                statusText.textContent = 'Alpa';
                statusText.className = 'font-bold text-red-600 bg-red-100 px-3 py-1 rounded-lg';
                descriptionField.classList.remove('hidden');
                confirmationText.innerHTML = 'Silakan berikan alasan ketidakhadiran Anda dengan jelas:';
            } else {
                statusText.textContent = 'Hadir';
                statusText.className = 'font-bold text-emerald-600 bg-emerald-100 px-3 py-1 rounded-lg';
                descriptionField.classList.add('hidden');
                confirmationText.innerHTML = 'Anda yakin ingin menandai kehadiran sebagai <span class="font-bold text-emerald-600 bg-emerald-100 px-3 py-1 rounded-lg">Hadir</span>?';
            }

            // Smooth modal appearance
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            
            // Add entrance animation
            setTimeout(() => {
                modal.querySelector('.bg-white\\/95').classList.add('animate-pulse');
            }, 50);
        }

        function closeModal() {
            const modal = document.getElementById('attendanceModal');
            
            // Add exit animation
            modal.querySelector('.bg-white\\/95').classList.remove('animate-pulse');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                document.getElementById('description').value = '';
            }, 150);
        }

        // Close modal when clicking outside
        document.getElementById('attendanceModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Add keyboard support for modal
        document.addEventListener('keydown', function(e) {
            const modal = document.getElementById('attendanceModal');
            if (!modal.classList.contains('hidden')) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            }
        });
    </script>
@endsection