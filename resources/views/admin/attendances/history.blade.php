<!-- resources/views/admin/attendances/history.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="py-4 sm:py-6">
        <!-- Modern Header -->
        <div class="mb-6 bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 rounded-xl shadow-lg p-4 sm:p-6">
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="mr-3 sm:mr-4 text-white/80 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-white">History Presensi</h1>
                    <p class="text-slate-300 text-sm mt-0.5">Lihat riwayat kehadiran</p>
                </div>
            </div>
        </div>
        
        <!-- Alert Error -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-gradient-to-r from-red-50 to-red-50/50 border-l-4 border-red-500 rounded-lg shadow-sm">
                <p class="text-red-800 font-bold text-sm sm:text-base mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    Terjadi kesalahan:
                </p>
                <ul class="text-red-700 text-xs sm:text-sm space-y-1 ml-7">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Alert Success -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-gradient-to-r from-emerald-50 to-emerald-50/50 border-l-4 border-emerald-500 rounded-lg shadow-sm">
                <p class="text-emerald-800 font-bold text-sm sm:text-base flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        <!-- Alert Warning -->
        @if (session('warning'))
            <div class="mb-4 p-4 bg-gradient-to-r from-yellow-50 to-yellow-50/50 border-l-4 border-yellow-500 rounded-lg shadow-sm">
                <p class="text-yellow-800 font-bold text-sm sm:text-base flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    {{ session('warning') }}
                </p>
            </div>
        @endif

        <!-- Alert Error Session -->
        @if (session('error'))
            <div class="mb-4 p-4 bg-gradient-to-r from-red-50 to-red-50/50 border-l-4 border-red-500 rounded-lg shadow-sm">
                <p class="text-red-800 font-bold text-sm sm:text-base flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    {{ session('error') }}
                </p>
            </div>
        @endif
        <!-- Filter Card -->
        <div class="bg-white shadow-lg rounded-xl p-4 sm:p-6 mb-6 border border-slate-200">
            <div class="border-b border-slate-200 pb-3 mb-4">
                <h3 class="text-base sm:text-lg font-bold text-slate-800">Filter Data</h3>
            </div>
            <form method="GET" action="{{ route('admin.attendances.history') }}"
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label for="year" class="block text-slate-700 text-sm font-bold mb-2">Tahun</label>
                    <select name="year" id="year" class="shadow-sm border-2 border-slate-300 rounded-lg w-full py-2.5 px-3 text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="month" class="block text-slate-700 text-sm font-bold mb-2">Bulan</label>
                    <select name="month" id="month" class="shadow-sm border-2 border-slate-300 rounded-lg w-full py-2.5 px-3 text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="range" class="block text-slate-700 text-sm font-bold mb-2">Rentang</label>
                    <select name="range" id="range" class="shadow-sm border-2 border-slate-300 rounded-lg w-full py-2.5 px-3 text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        <option value="weeks" {{ $range == 'weeks' ? 'selected' : '' }}>Per Minggu</option>
                        <option value="month" {{ $range == 'month' ? 'selected' : '' }}>1 Bulan Penuh</option>
                    </select>
                </div>

                <div id="weeks-container" class="{{ $range == 'month' ? 'hidden' : '' }}">
                    <label for="weeks" class="block text-slate-700 text-sm font-bold mb-2">Jumlah Minggu</label>
                    <select name="weeks" id="weeks" class="shadow-sm border-2 border-slate-300 rounded-lg w-full py-2.5 px-3 text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        @for ($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}" {{ $i == $weeks ? 'selected' : '' }}>{{ $i }}
                                Minggu</option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2.5 px-4 rounded-lg w-full shadow-lg transition-all flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                    </button>
                </div>
            </form>

        </div>

        @if ($schedules->count() > 0)
            @foreach ($schedules as $schedule)
                <div class="bg-white shadow-lg rounded-xl p-4 sm:p-6 mb-6 border border-slate-200">
                    <div class="border-b border-slate-200 pb-3 mb-4">
                        <h2 class="text-lg sm:text-xl font-bold text-slate-800">{{ $schedule->day_name }},
                            {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</h2>
                    </div>

                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden border border-slate-200 rounded-lg">
                                <table class="min-w-full divide-y divide-slate-200">
                                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
                                        <tr>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                                Nama</th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                                Status</th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider hidden sm:table-cell">
                                                Keterangan</th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                                Validasi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-slate-100">
                                @foreach ($schedule->attendances as $attendance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $attendance->person->name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $attendance->status === 'present' ? 'Hadir' : ucfirst($attendance->status ?? 'Alpa') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $attendance->description ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $attendance->is_validated ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $attendance->is_validated ? 'Valid' : 'Tidak Valid' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-600">Tidak ada data presensi untuk periode yang dipilih.</p>
            </div>
        @endif
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const rangeSelect = document.getElementById('range');
                const weeksContainer = document.getElementById('weeks-container');

                function toggleWeeks() {
                    if (rangeSelect.value === 'month') {
                        weeksContainer.classList.add('hidden');
                    } else {
                        weeksContainer.classList.remove('hidden');
                    }
                }

                // Trigger on load and on change
                toggleWeeks();
                rangeSelect.addEventListener('change', toggleWeeks);
            });
        </script>
    @endpush

@endsection
