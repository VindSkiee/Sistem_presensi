@extends('layouts.app')

@section('content')
    <div class="py-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Jadwal</h1>
            </div>
            <div>
                <a href="{{ route('admin.schedules.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Tambah Jadwal
                </a>
                
                <form action="{{ route('admin.generate.weekly.form') }}" method="GET" class="inline">
                    <button type="submit"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Tambah Jadwal Mingguan
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Hari/Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah
                            Orang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                            Validasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $schedule->day_name }},
                                    {{ \Carbon\Carbon::parse($schedule->date)->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $schedule->persons_count }} orang</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $schedule->is_validated ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $schedule->is_validated ? 'Tervalidasi' : 'Belum divalidasi' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('admin.schedules.edit', $schedule) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                                <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus jadwal ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>
@endsection
