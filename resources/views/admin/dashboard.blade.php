<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Jadwal Hari Ini ({{ now()->isoFormat('dddd, D MMMM Y') }})</h2>

                @if ($schedule && $schedule->is_validated)
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
                @elseif ($schedule && $schedule->attendances->count() > 0)
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
