<!-- resources/views/admin/attendances/history.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="flex items-center mb-3">
            <a href="{{ route('admin.dashboard') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">History Presensi</h1>
        </div>
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <form method="GET" action="{{ route('admin.attendances.history') }}"
                class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="year" class="block text-gray-700 text-sm font-bold mb-2">Tahun</label>
                    <select name="year" id="year" class="shadow border rounded w-full py-2 px-3">
                        @for ($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="month" class="block text-gray-700 text-sm font-bold mb-2">Bulan</label>
                    <select name="month" id="month" class="shadow border rounded w-full py-2 px-3">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $i == $month ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label for="range" class="block text-gray-700 text-sm font-bold mb-2">Rentang</label>
                    <select name="range" id="range" class="shadow border rounded w-full py-2 px-3">
                        <option value="weeks" {{ $range == 'weeks' ? 'selected' : '' }}>Per Minggu</option>
                        <option value="month" {{ $range == 'month' ? 'selected' : '' }}>1 Bulan Penuh</option>
                    </select>
                </div>

                <div id="weeks-container" class="{{ $range == 'month' ? 'hidden' : '' }}">
                    <label for="weeks" class="block text-gray-700 text-sm font-bold mb-2">Jumlah Minggu</label>
                    <select name="weeks" id="weeks" class="shadow border rounded w-full py-2 px-3">
                        @for ($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}" {{ $i == $weeks ? 'selected' : '' }}>{{ $i }}
                                Minggu</option>
                        @endfor
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Filter
                    </button>
                </div>
            </form>

        </div>

        @if ($schedules->count() > 0)
            @foreach ($schedules as $schedule)
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">{{ $schedule->day_name }},
                        {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</h2>

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
                                        Keterangan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Validasi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
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
