@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
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

        <!-- Header with Gradient -->
        <div class="mb-10 bg-gradient-to-r from-indigo-600 via-purple-600 to-indigo-700 rounded-2xl shadow-xl p-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-extrabold text-white">Dashboard Super Admin</h1>
                        <p class="text-indigo-100 text-sm mt-1">Monitoring Kehadiran RW</p>
                    </div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                    <p class="text-white text-sm font-medium">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 border border-gray-100">
            <form method="GET" action="{{ route('superadmin.dashboard') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Cari Nama User
                    </label>
                    <input type="text" name="search_name" value="{{ $searchName ?? '' }}" placeholder="Masukkan nama user..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Tanggal Mulai
                    </label>
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Tanggal Akhir
                    </label>
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('superadmin.dashboard') }}"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-medium transition-all duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        @forelse($admins as $admin)
            <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 mb-8 border border-indigo-100">
                <details class="group transition-all duration-300">
                    <summary class="flex justify-between items-center cursor-pointer pb-4 border-b-2 border-indigo-100 select-none hover:border-indigo-300 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-3 shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl text-gray-800">{{ $admin->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $admin->email }}</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mt-1">
                                    Admin RW
                                </span>
                            </div>
                        </div>
                        <span class="transform transition-transform duration-300 group-open:rotate-180 bg-gray-100 rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>

                    <div class="mt-6 space-y-4">
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-100">
                            <h2 class="font-bold text-lg text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Daftar User ({{ $admin->users->count() }})
                            </h2>

                            <div class="space-y-3">
                                @forelse($admin->users as $user)
                                    <details class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-200 border border-gray-200">
                                        <summary class="cursor-pointer p-4 select-none hover:bg-gray-50 rounded-xl transition-colors">
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center space-x-3">
                                                    <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg p-2 shadow">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <!-- Statistics Badges -->
                                                    <div class="flex items-center bg-green-50 border border-green-200 rounded-lg px-3 py-1.5">
                                                        <svg class="w-4 h-4 text-green-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="text-xs font-bold text-green-700">{{ $user->total_hadir ?? 0 }}</span>
                                                    </div>
                                                    <div class="flex items-center bg-red-50 border border-red-200 rounded-lg px-3 py-1.5">
                                                        <svg class="w-4 h-4 text-red-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="text-xs font-bold text-red-700">{{ $user->total_alpa ?? 0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </summary>

                                        <!-- Attendance Details -->
                                        <div class="px-4 pb-4 pt-2 border-t border-gray-100">
                                            <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div class="bg-white rounded-lg p-3 border border-green-200">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <p class="text-xs text-gray-600 font-medium">Total Hadir</p>
                                                                <p class="text-2xl font-bold text-green-600">{{ $user->total_hadir ?? 0 }}</p>
                                                            </div>
                                                            <div class="bg-green-100 rounded-full p-3">
                                                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-white rounded-lg p-3 border border-red-200">
                                                        <div class="flex items-center justify-between">
                                                            <div>
                                                                <p class="text-xs text-gray-600 font-medium">Total Alpa</p>
                                                                <p class="text-2xl font-bold text-red-600">{{ $user->total_alpa ?? 0 }}</p>
                                                            </div>
                                                            <div class="bg-red-100 rounded-full p-3">
                                                                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <h4 class="font-semibold text-sm text-gray-700 mb-2 flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                                Riwayat Kehadiran
                                            </h4>
                                            <div class="space-y-2 max-h-96 overflow-y-auto">
                                                @if ($user->person && $user->person->attendances->count())
                                                    @foreach ($user->person->attendances as $attendance)
                                                        <div class="bg-white p-3 rounded-lg border border-gray-200 hover:border-indigo-300 transition-colors">
                                                            <div class="flex justify-between items-center">
                                                                <div class="flex items-center space-x-3">
                                                                    <div class="bg-gray-100 rounded-lg px-3 py-1">
                                                                        <p class="text-xs text-gray-600 font-medium">
                                                                            {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                                                        </p>
                                                                    </div>
                                                                    @if ($attendance->schedule)
                                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
                                                                            {{ $attendance->schedule->is_validated ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                                            {{ $attendance->schedule->is_validated ? '✓ Tervalidasi' : '⏳ Pending' }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold shadow-sm
                                                                    {{ $attendance->status === 'present' ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white' : 'bg-gradient-to-r from-red-500 to-rose-600 text-white' }}">
                                                                    {{ $attendance->status === 'present' ? '✓ Hadir' : '✗ Alpa' }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-center py-8">
                                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <p class="text-gray-500 italic text-sm">Belum ada riwayat kehadiran</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </details>
                                @empty
                                    <div class="text-center py-6 bg-gray-50 rounded-lg">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <p class="text-gray-500 italic">Tidak ada user di admin ini</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </details>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.292A7.962 7.962 0 0112 18c-2.31 0-4.418-.847-5.996-2.208M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <p class="text-gray-600 text-lg">Tidak ada admin ditemukan.</p>
            </div>
        @endforelse
    </div>
@endsection
