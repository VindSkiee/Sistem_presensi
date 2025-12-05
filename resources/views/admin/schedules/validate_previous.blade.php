@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
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

        <div class="flex items-center mb-3">
            <a href="{{ route('admin.dashboard') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Validasi Presensi - Tanggal Sebelumnya</h1>
        </div>

        @foreach ($schedules as $schedule)
            <form action="{{ route('admin.validate.update', $schedule->id) }}" method="POST" class="mb-10">
                @csrf
                @method('PUT')

                <h3 class="font-semibold text-lg mb-2">
                    {{ \Carbon\Carbon::parse($schedule->date)->format('l, d M Y') }}
                </h3>

                <table class="min-w-full divide-y divide-gray-200 border rounded-lg overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Nama</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Deskripsi</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Validasi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($schedule->attendances as $attendance)
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-800">
                                    {{ $attendance->person->name }}
                                </td>
                                <td class="px-4 py-2 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                        {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 
                                           ($attendance->status === 'alpa' ? 'bg-red-100 text-red-800' : 
                                           'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($attendance->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-600">
                                    @if ($attendance->status === 'alpa' && $attendance->description)
                                        {{ $attendance->description }}
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <input type="checkbox" name="attendances[{{ $attendance->id }}][is_validated]"
                                        {{ $attendance->is_validated ? 'checked disabled' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded">
                        Simpan Validasi
                    </button>
                </div>
            </form>
        @endforeach
    </div>
@endsection
