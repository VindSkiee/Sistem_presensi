@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <h2 class="text-xl font-bold mb-6">Validasi Presensi - Tanggal Sebelumnya</h2>

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
