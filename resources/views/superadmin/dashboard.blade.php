@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-10 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 mr-3 text-indigo-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Dashboard RW
        </h1>

        @forelse($admins as $admin)
            <div
                class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 p-6 mb-8 border border-gray-100">
                <details class="group transition-all duration-300">
                    <summary
                        class="flex justify-between items-center cursor-pointer font-semibold text-xl text-gray-800 pb-4 border-b border-gray-200 select-none">
                        <span>
                            {{ $admin->name }}
                            <span class="text-sm font-normal text-gray-500">({{ $admin->email }})</span>
                        </span>
                        <span class="transform transition-transform duration-300 group-open:rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>

                    <div class="mt-6 space-y-6">
                        <!-- Users Dropdown -->
                        <div>
                            <h2 class="font-bold text-lg text-gray-700 mb-4">Users</h2>

                            @forelse($admin->users as $user)
                                <details class="bg-gray-50 rounded-lg p-4 mb-3 border border-gray-200 hover:bg-gray-100">
                                    <summary
                                        class="flex justify-between items-center cursor-pointer font-medium text-gray-800 select-none">
                                        <span>{{ $user->name }} <span
                                                class="text-sm text-gray-500">({{ $user->email }})</span></span>
                                        <span class="transform transition-transform duration-300 group-open:rotate-180">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </span>
                                    </summary>

                                    <!-- Jadwal User -->
                                    <div class="mt-4 space-y-4">
                                        @if ($user->person && $user->person->attendances->count())
                                            @foreach ($user->person->attendances as $attendance)
                                                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <p class="text-xs uppercase text-gray-500">Tanggal</p>
                                                            <p class="font-semibold text-gray-800">
                                                                {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                                            </p>
                                                            @if ($attendance->schedule)
                                                                <p class="text-sm text-gray-600 mt-1">
                                                                    <span class="font-medium">Validasi:</span>
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-1 rounded text-xs mt-1
                                                                    {{ $attendance->schedule->is_validated ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                        {{ $attendance->schedule->is_validated ? '✓ Tervalidasi' : '⏳ Belum Validasi' }}
                                                                    </span>
                                                                </p>
                                                            @endif
                                                        </div>
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                                        {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $attendance->status === 'present' ? 'Hadir' : ucfirst($attendance->status ?? 'Alpa') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-gray-500 italic">Belum ada kehadiran untuk user ini.</p>
                                        @endif
                                    </div>
                                </details>
                            @empty
                                <p class="text-gray-500 italic">Tidak ada user di admin ini.</p>
                            @endforelse
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
