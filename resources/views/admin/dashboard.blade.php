<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

        <!-- Alert Error -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800 font-semibold mb-2">❌ Terjadi kesalahan:</p>
                <ul class="text-red-700 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Alert Success -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800 font-semibold">✓ {{ session('success') }}</p>
            </div>
        @endif

        <!-- Alert Warning -->
        @if (session('warning'))
            <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-yellow-800 font-semibold">⚠️ {{ session('warning') }}</p>
            </div>
        @endif

        <!-- Alert Error Session -->
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800 font-semibold">❌ {{ session('error') }}</p>
            </div>
        @endif

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

                    <!-- Photo Section for Validated Schedule -->
                    @if ($schedule && $schedule->photo)
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium text-blue-800">Foto Jadwal Tersedia</span>
                                </div>
                                <button
                                    onclick="openPhotoModal('{{ asset('storage/' . $schedule->photo) }}', 'Foto Jadwal {{ $schedule->day_name }}, {{ $schedule->date }}')"
                                    class="inline-flex items-center px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Lihat Foto
                                </button>
                            </div>
                        </div>
                    @endif

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
                                {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $attendance->status === 'present' ? 'Hadir' : ucfirst($attendance->status ?? 'Alpa') }}
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
                                                    Tervalidasi
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

                        <!-- Photo Section for Unvalidated Schedule -->
                        @if ($schedule && $schedule->photo)
                            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <div>
                                            <span class="text-sm font-medium text-green-800">Foto Jadwal Tersedia</span>
                                            <p class="text-xs text-green-600">Periksa foto sebelum melakukan validasi</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button type="button"
                                            onclick="openPhotoModal('{{ asset('storage/' . $schedule->photo) }}', 'Foto Jadwal {{ $schedule->day_name }}, {{ $schedule->date }}')"
                                            class="inline-flex items-center px-4 py-2 text-sm bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors shadow-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                            Lihat Foto
                                        </button>
                                        <a href="{{ asset('storage/' . $schedule->photo) }}"
                                            download="jadwal_{{ $schedule->date }}.jpg"
                                            class="inline-flex items-center px-2 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="mb-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <div>
                                        <span class="text-sm font-medium text-gray-600">Belum ada foto diupload</span>
                                        <p class="text-xs text-gray-500">Menunggu user mengupload foto jadwal</p>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                                                    {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $attendance->status === 'present' ? 'Hadir' : ucfirst($attendance->status ?? 'Alpa') }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="text-sm text-gray-900">
                                                        @if ($attendance->status === 'alpa' && $attendance->description)
                                                            {{ $attendance->description }}
                                                        @else
                                                            -
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
                                                    <input type="hidden"
                                                        name="attendances[{{ $attendance->id }}][status]"
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

                        <div class="mt-6 flex items-center justify-between">
                            <div class="flex space-x-3">
                                <button type="submit" id="validateButton" disabled
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded opacity-50 cursor-not-allowed transition-all">
                                    Validasi Kehadiran Terpilih dan Kunci Jadwal
                                </button>
                                <button type="button" id="validateAllButton"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-all">
                                    Pilih semua orang
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <p class="text-gray-600">Tidak ada jadwal untuk hari ini, silakan buat jadwal baru di menu <b>Kelola
                            Jadwal</b>.</p>
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
                    class="bg-blue-100 hover:bg-blue-200 p-4 rounded-lg text-center transition-colors">
                    <h3 class="font-medium text-blue-800">Kelola User</h3>
                    <p class="text-sm text-blue-600 mt-1">Kelola daftar orang</p>
                </a>

                <a href="{{ route('admin.schedules.index') }}"
                    class="bg-green-100 hover:bg-green-200 p-4 rounded-lg text-center transition-colors">
                    <h3 class="font-medium text-green-800">Kelola Jadwal</h3>
                    <p class="text-sm text-green-600 mt-1">Tambah/edit jadwal</p>
                </a>

                <a href="{{ route('admin.attendances.history') }}"
                    class="bg-purple-100 hover:bg-purple-200 p-4 rounded-lg text-center transition-colors">
                    <h3 class="font-medium text-purple-800">Lihat History</h3>
                    <p class="text-sm text-purple-600 mt-1">Lihat data historis absensi</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Modal untuk menampilkan foto dalam ukuran penuh -->
    <div id="photoModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90" onclick="closePhotoModal()">
        <div class="absolute inset-0 flex flex-col">
            <!-- Header dengan tombol close dan navigasi -->
            <div class="flex justify-between items-center p-4 text-white bg-black bg-opacity-50">
                <div id="modalTitle" class="text-lg font-medium truncate max-w-md"></div>
                <button onclick="closePhotoModal()"
                    class="p-2 hover:bg-white hover:bg-opacity-20 rounded-full transition-colors" title="Tutup">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Container foto dengan responsive sizing -->
            <div class="flex-grow flex items-center justify-center p-4">
                <div class="relative w-full h-full flex items-center justify-center">
                    <!-- Loading placeholder -->
                    <div id="imageLoader" class="flex items-center justify-center">
                        <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-white"></div>
                        <span class="ml-3 text-white text-lg">Memuat foto...</span>
                    </div>

                    <!-- Zoom indicator -->
                    <div id="zoomIndicator" class="zoom-indicator hidden">
                        <span id="zoomLevel">100%</span>
                    </div>

                    <!-- Foto dalam modal dengan responsive sizing -->
                    <img id="modalImage" src="" alt=""
                        class="max-w-full max-h-full object-contain rounded-lg shadow-2xl hidden transition-all duration-300"
                        onclick="event.stopPropagation()" onload="hideLoader()" onerror="showError()"
                        style="max-width: 95vw; max-height: 85vh; width: auto; height: auto;">

                    <!-- Error state -->
                    <div id="imageError" class="hidden text-white text-center">
                        <svg class="w-20 h-20 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-lg">Foto tidak dapat dimuat</p>
                        <p class="text-sm text-gray-300 mt-2">Pastikan file foto tersedia dan dapat diakses</p>
                    </div>
                </div>
            </div>

            <div id="photoModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90" onclick="closePhotoModal()">
                <div class="absolute inset-0 flex flex-col">
                    <!-- Header dengan tombol close -->
                    <div class="flex justify-between items-center p-4 text-white bg-black bg-opacity-50">
                        <div id="modalTitle" class="text-lg font-medium truncate max-w-md"></div>
                        <button onclick="closePhotoModal()"
                            class="p-2 hover:bg-white hover:bg-opacity-20 rounded-full transition-colors" title="Tutup">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Container foto dengan responsive sizing -->
                    <div class="flex-grow flex items-center justify-center p-4">
                        <div class="relative w-full h-full flex items-center justify-center">
                            <!-- Loading placeholder -->
                            <div id="imageLoader" class="flex items-center justify-center">
                                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-white"></div>
                                <span class="ml-3 text-white text-lg">Memuat foto...</span>
                            </div>

                            <!-- Foto dalam modal dengan responsive sizing -->
                            <img id="modalImage" src="" alt=""
                                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl hidden transition-all duration-300"
                                onclick="event.stopPropagation()" onload="hideLoader()" onerror="showError()"
                                style="max-width: 95vw; max-height: 85vh; width: auto; height: auto;">

                            <!-- Error state -->
                            <div id="imageError" class="hidden text-white text-center">
                                <svg class="w-20 h-20 mx-auto mb-4 text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-lg">Foto tidak dapat dimuat</p>
                                <p class="text-sm text-gray-300 mt-2">Pastikan file foto tersedia dan dapat diakses</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('styles')
            <style>
                /* Custom styles for photo modal */
                #photoModal {
                    backdrop-filter: blur(5px);
                }

                #modalImage {
                    transition: transform 0.3s ease-in-out;
                    user-select: none;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                }

                /* Smooth transitions for modal elements */
                .modal-transition {
                    transition: all 0.3s ease-in-out;
                }

                /* Responsive button sizing */
                @media (max-width: 640px) {
                    #modalCaption .flex {
                        flex-direction: column;
                        gap: 0.5rem;
                    }

                    #modalCaption button {
                        width: 100%;
                        justify-content: center;
                    }
                }

                /* Loading animation improvements */
                #imageLoader {
                    background: rgba(0, 0, 0, 0.3);
                    padding: 2rem;
                    border-radius: 1rem;
                    backdrop-filter: blur(10px);
                }

                /* Error state improvements */
                #imageError {
                    background: rgba(0, 0, 0, 0.3);
                    padding: 2rem;
                    border-radius: 1rem;
                    backdrop-filter: blur(10px);
                }

                /* Smooth scrolling for the entire page */
                html {
                    scroll-behavior: smooth;
                }

                /* Prevent text selection during drag */
                .no-select {
                    user-select: none;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                    -ms-user-select: none;
                }

                /* Zoom indicator */
                .zoom-indicator {
                    position: absolute;
                    top: 1rem;
                    right: 1rem;
                    background: rgba(0, 0, 0, 0.7);
                    color: white;
                    padding: 0.5rem 1rem;
                    border-radius: 0.5rem;
                    font-size: 0.875rem;
                    z-index: 10;
                }
            </style>
        @endpush

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
                            validateButton.classList.add('hover:bg-green-700');
                        } else {
                            validateButton.disabled = true;
                            validateButton.classList.add('opacity-50', 'cursor-not-allowed');
                            validateButton.classList.remove('hover:bg-green-700');
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
                    if (submitBtn) {
                        submitBtn.addEventListener('click', function(e) {
                            e.preventDefault();

                            // Show confirmation dialog
                            Swal.fire({
                                title: 'Konfirmasi Validasi',
                                text: 'Setelah divalidasi, jadwal akan terkunci dan tidak dapat diubah lagi. Apakah Anda yakin?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#10B981',
                                cancelButtonColor: '#6B7280',
                                confirmButtonText: 'Ya, Validasi',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                        });
                    }
                });

                // Photo Modal Functions
                let currentZoom = 1;
                let originalImageSize = {
                    width: 0,
                    height: 0
                };
                let isDragging = false;
                let startX = 0;
                let startY = 0;
                let translateX = 0;
                let translateY = 0;

                function openPhotoModal(imageSrc, caption) {
                    const modal = document.getElementById('photoModal');
                    const modalImage = document.getElementById('modalImage');
                    const modalTitle = document.getElementById('modalTitle');
                    const loader = document.getElementById('imageLoader');
                    const errorDiv = document.getElementById('imageError');

                    // Reset states
                    modalImage.classList.add('hidden');
                    errorDiv.classList.add('hidden');
                    loader.classList.remove('hidden');

                    // Set content
                    modalImage.src = imageSrc;
                    modalImage.alt = caption;
                    modalTitle.textContent = caption;

                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }

                function closePhotoModal() {
                    const modal = document.getElementById('photoModal');
                    modal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                }

                function hideLoader() {
                    const modalImage = document.getElementById('modalImage');
                    const loader = document.getElementById('imageLoader');

                    loader.classList.add('hidden');
                    modalImage.classList.remove('hidden');

                    // Auto-adjust image size based on orientation
                    setTimeout(() => {
                        adjustImageSize(modalImage);
                    }, 100);
                }

                function setupDragEvents(imgElement) {
                    imgElement.addEventListener('mousedown', startDrag);
                    imgElement.addEventListener('mousemove', drag);
                    imgElement.addEventListener('mouseup', endDrag);
                    imgElement.addEventListener('mouseleave', endDrag);

                    // Touch events for mobile
                    imgElement.addEventListener('touchstart', startDragTouch);
                    imgElement.addEventListener('touchmove', dragTouch);
                    imgElement.addEventListener('touchend', endDrag);

                    // Double click to zoom
                    imgElement.addEventListener('dblclick', toggleZoom);

                    // Wheel zoom
                    imgElement.addEventListener('wheel', handleWheel);
                }

                function startDrag(e) {
                    if (currentZoom > 1) {
                        isDragging = true;
                        startX = e.clientX - translateX;
                        startY = e.clientY - translateY;
                        e.preventDefault();
                    }
                }

                function drag(e) {
                    if (isDragging && currentZoom > 1) {
                        translateX = e.clientX - startX;
                        translateY = e.clientY - startY;
                        applyZoom();
                        e.preventDefault();
                    }
                }

                function startDragTouch(e) {
                    if (currentZoom > 1 && e.touches.length === 1) {
                        isDragging = true;
                        startX = e.touches[0].clientX - translateX;
                        startY = e.touches[0].clientY - translateY;
                        e.preventDefault();
                    }
                }

                function dragTouch(e) {
                    if (isDragging && currentZoom > 1 && e.touches.length === 1) {
                        translateX = e.touches[0].clientX - startX;
                        translateY = e.touches[0].clientY - startY;
                        applyZoom();
                        e.preventDefault();
                    }

                    // Handle pinch to zoom
                    if (e.touches.length === 2) {
                        e.preventDefault();
                        const touch1 = e.touches[0];
                        const touch2 = e.touches[1];

                        const currentDistance = Math.hypot(
                            touch2.clientX - touch1.clientX,
                            touch2.clientY - touch1.clientY
                        );

                        if (!window.lastTouchDistance) {
                            window.lastTouchDistance = currentDistance;
                            return;
                        }

                        const scale = currentDistance / window.lastTouchDistance;
                        const newZoom = Math.max(0.5, Math.min(3, currentZoom * scale));

                        if (Math.abs(newZoom - currentZoom) > 0.1) {
                            currentZoom = newZoom;
                            applyZoom();
                        }

                        window.lastTouchDistance = currentDistance;
                    }
                }

                function endDrag() {
                    isDragging = false;
                    window.lastTouchDistance = null;
                }

                function adjustImageSize(imgElement) {
                    const containerWidth = window.innerWidth * 0.95; // 95vw
                    const containerHeight = window.innerHeight * 0.85; // 85vh

                    const imgAspectRatio = imgElement.naturalWidth / imgElement.naturalHeight;
                    const containerAspectRatio = containerWidth / containerHeight;

                    let newWidth, newHeight;

                    if (imgAspectRatio > containerAspectRatio) {
                        // Landscape image - fit to width
                        newWidth = Math.min(containerWidth, imgElement.naturalWidth);
                        newHeight = newWidth / imgAspectRatio;
                    } else {
                        // Portrait image - fit to height
                        newHeight = Math.min(containerHeight, imgElement.naturalHeight);
                        newWidth = newHeight * imgAspectRatio;
                    }

                    // Ensure minimum size for readability
                    const minSize = 200;
                    if (newWidth < minSize || newHeight < minSize) {
                        if (newWidth < newHeight) {
                            newWidth = minSize;
                            newHeight = minSize / imgAspectRatio;
                        } else {
                            newHeight = minSize;
                            newWidth = minSize * imgAspectRatio;
                        }
                    }

                    imgElement.style.width = newWidth + 'px';
                    imgElement.style.height = newHeight + 'px';
                }

                function showError() {
                    const loader = document.getElementById('imageLoader');
                    const errorDiv = document.getElementById('imageError');

                    loader.classList.add('hidden');
                    errorDiv.classList.remove('hidden');
                }

                // Close modal dengan ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closePhotoModal();
                    }
                });

                function applyZoom() {
                    const modalImage = document.getElementById('modalImage');
                    const zoomIndicator = document.getElementById('zoomIndicator');
                    const zoomLevelSpan = document.getElementById('zoomLevel');

                    if (modalImage) {
                        modalImage.style.transform = `scale(${currentZoom}) translate(${translateX}px, ${translateY}px)`;
                        modalImage.style.transformOrigin = 'center center';

                        // Update cursor based on zoom level
                        if (currentZoom > 1) {
                            modalImage.style.cursor = 'grab';
                            if (isDragging) {
                                modalImage.style.cursor = 'grabbing';
                            }
                        } else {
                            modalImage.style.cursor = 'zoom-in';
                        }

                        // Update zoom indicator
                        if (zoomIndicator && zoomLevelSpan) {
                            zoomLevelSpan.textContent = `${Math.round(currentZoom * 100)}%`;
                            zoomIndicator.classList.remove('hidden');
                        }
                    }
                }

                function toggleZoom() {
                    if (currentZoom === 1) {
                        currentZoom = 2;
                    } else {
                        currentZoom = 1;
                        translateX = 0;
                        translateY = 0;
                    }
                    applyZoom();
                }

                function handleWheel(e) {
                    e.preventDefault();
                    const delta = e.deltaY > 0 ? -0.2 : 0.2;
                    const newZoom = Math.max(0.5, Math.min(3, currentZoom + delta));

                    if (newZoom !== currentZoom) {
                        currentZoom = newZoom;
                        applyZoom();
                    }
                }



                // Close modal dengan ESC key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closePhotoModal();
                    }
                });

                window.addEventListener('resize', function() {
                    const modalImage = document.getElementById('modalImage');
                    if (modalImage && !modalImage.classList.contains('hidden')) {
                        adjustImageSize(modalImage);
                    }
                });
            </script>
        @endpush
    @endsection
