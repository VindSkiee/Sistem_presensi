<!-- resources/views/user/dashboard.blade.php -->
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($schedule->persons as $person)
                                @php
                                    // Cari attendance untuk person ini di schedule ini
                                    $attendance = $schedule->attendances->where('person_id', $person->id)->first();
                                    $status = $attendance ? $attendance->status : 'not_present';
                                    $isCurrentUser =
                                        auth()->user()->person && auth()->user()->person->id === $person->id;
                                @endphp
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">{{ $person->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $status === 'present'
                                                ? 'bg-green-100 text-green-800'
                                                : ($status === 'alpa'
                                                    ? 'bg-red-100 text-red-800'
                                                    : 'bg-gray-400 text-gray-700') }}">
                                            {{ $status === 'present' ? 'Hadir' : ($status === 'alpa' ? 'Alpa' : 'Belum absen') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if ($isCurrentUser)
                                            @if (!$schedule->is_validated)
                                                @if (!$attendance || $attendance->status === 'alpa')
                                                    <button
                                                        onclick="openModal('present', {{ $schedule->id }}, {{ $person->id }})"
                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                        Hadir
                                                    </button>
                                                    <button
                                                        onclick="openModal('alpa', {{ $schedule->id }}, {{ $person->id }})"
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
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-2">Bukti Foto Kehadiran</h3>
                    @if ($schedule->photo)
                        <img src="{{ asset('storage/' . $schedule->photo) }}" alt="Foto Kehadiran"
                            class="w-64 rounded shadow mb-3">
                    @else
                        <form action="{{ route('user.schedules.uploadPhoto', $schedule->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="photo" accept="image/*"
                                class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md mb-2">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Upload Foto
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-600">Tidak ada jadwal untuk hari ini.</p>
            </div>
        @endif
    </div>

    <!-- Modal -->
    @if ($schedule && !$schedule->is_validated)
        <div id="attendanceModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                <form method="POST" action="{{ route('user.attendance.submit') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="schedule_id" id="modalScheduleId">
                    <input type="hidden" name="person_id" id="modalPersonId">
                    <input type="hidden" name="status" id="modalStatus">

                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Konfirmasi Presensi</h3>

                        <div id="descriptionField" class="mb-4 hidden">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Alasan
                                Alpa</label>
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
@endsection
