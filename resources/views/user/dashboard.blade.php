<!-- resources/views/user/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Presensi</h1>

        @if ($schedule)
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
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">{{ $person->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $attendance = $schedule->attendances
                                                ->where('person_id', $person->id)
                                                ->first();
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $attendance && $attendance->status === 'hadir'
                                            ? 'bg-green-100 text-green-800'
                                            : ($attendance && $attendance->status === 'alpa'
                                                ? 'bg-red-100 text-red-800'
                                                : 'bg-gray-100 text-gray-800') }}">
                                            {{ $attendance ? ucfirst($attendance->status) : 'Belum absen' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $loggedInPerson = auth()->user()->person;
                                        @endphp

                                        @if ($loggedInPerson && $loggedInPerson->id === $person->id)
                                            @if (!$attendance || $attendance->status === 'alpa')
                                                <button onclick="openModal('hadir')"
                                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                    Hadir
                                                </button>
                                                <button onclick="openModal('alpa')"
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs ml-2">
                                                    Alpa
                                                </button>
                                            @endif
                                        @else
                                            -
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
    <div id="attendanceModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="attendanceForm" method="POST" action="{{ route('user.attendance.submit') }}">
                    @csrf
                    <input type="hidden" name="schedule_id" value="{{ $schedule ? $schedule->id : '' }}">
                    @php
                        $person = auth()->user()->person;
                    @endphp

                    @if ($person)
                        <input type="hidden" name="person_id" value="{{ $person->id }}">
                    @else
                        <div class="text-red-600 font-semibold p-4">Akun Anda belum terhubung ke data Person. Silakan
                            hubungi admin.</div>
                    @endif

                    <input type="hidden" id="statusInput" name="status" value="">

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modalTitle">Konfirmasi Presensi
                        </h3>

                        <div id="descriptionField" class="mb-4 hidden">
                            <label for="description" class="block text-sm font-medium text-gray-700">Alasan Alpa</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>

                        <p id="confirmationText">Anda yakin ingin menandai kehadiran sebagai <span id="statusText"></span>?
                        </p>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Konfirmasi
                        </button>
                        <button type="button" onclick="closeModal()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(status) {
            document.getElementById('statusInput').value = status;
            document.getElementById('statusText').textContent = status;

            if (status === 'alpa') {
                document.getElementById('descriptionField').classList.remove('hidden');
                document.getElementById('confirmationText').textContent = 'Silakan berikan alasan alpa:';
            } else {
                document.getElementById('descriptionField').classList.add('hidden');
                document.getElementById('confirmationText').textContent =
                    'Anda yakin ingin menandai kehadiran sebagai hadir?';
            }

            document.getElementById('attendanceModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('attendanceModal').classList.add('hidden');
        }
    </script>
@endsection
