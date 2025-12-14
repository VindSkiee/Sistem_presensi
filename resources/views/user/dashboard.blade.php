@extends('layouts.app')

@section('content')
    <div class="py-4 sm:py-6">
        <!-- Modern Header -->
        <div class="mb-6 bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 rounded-xl shadow-lg p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Dashboard Presensi</h1>
                    <p class="text-slate-300 text-sm">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
                <div class="hidden sm:block">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2 border border-white/20">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-white text-sm font-medium">Aktif</span>
                        </div>
                    </div>
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

        @if ($schedule)
            @if ($schedule->is_validated)
                <div class="bg-gradient-to-r from-blue-50 to-blue-50/50 border-l-4 border-blue-500 text-blue-800 px-4 py-3 rounded-lg mb-6 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                        <p class="text-sm font-medium">Jadwal ini sudah divalidasi oleh admin. Tidak dapat melakukan perubahan presensi.</p>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-xl p-4 sm:p-6 mb-4 sm:mb-6 border border-slate-200">
                <div class="border-b border-slate-200 pb-3 mb-4">
                    <h2 class="text-lg sm:text-xl font-bold text-slate-800">Jadwal Hari Ini</h2>
                    <p class="text-sm text-slate-600 mt-1">{{ $schedule->day_name }}, {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</p>
                </div>

                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden border border-slate-200 rounded-lg">
                            <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Nama</th>
                                <th class="px-3 sm:px-6 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-3 sm:px-6 py-3 text-center text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Pilih Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse ($schedule->persons as $person)
                                @php
                                    $attendance = $schedule->attendances->where('person_id', $person->id)->first();
                                    $status = $attendance ? $attendance->status : 'not_present';
                                    $isCurrentUser = auth()->user()->person && auth()->user()->person->id === $person->id;
                                @endphp
                                <tr>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $person->name }}</div>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-center">
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
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-500 text-center">
                                        @if ($isCurrentUser)
                                            @if (!$schedule->is_validated)
                                                @if (!$attendance || $attendance->status === 'alpa')
                                                    <button
                                                        onclick="openModal('present', {{ $schedule->id }}, {{ $person->id }})"
                                                        class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold py-1 px-2 sm:px-3 rounded-lg text-xs shadow-sm transition-all">
                                                        Hadir
                                                    </button>
                                                    <button
                                                        onclick="openModal('alpa', {{ $schedule->id }}, {{ $person->id }})"
                                                        class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-1 px-2 sm:px-3 rounded-lg text-xs ml-1 sm:ml-2 shadow-sm transition-all">
                                                        Alpa
                                                    </button>
                                                @else
                                                    <span class="text-gray-400">✓ Sudah absen</span>
                                                @endif
                                            @else
                                                <span class="text-gray-400">🔒 Terkunci</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada orang di jadwal ini.
                                    </td>
                                </tr>
                            @endforelse
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
                <div class="flex items-center justify-center py-12">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-gray-600 text-lg font-medium">Tidak ada jadwal untuk hari ini</p>
                        <p class="text-gray-500 text-sm mt-1">Silakan tunggu jadwal berikutnya</p>
                    </div>
                </div>
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
@endsection