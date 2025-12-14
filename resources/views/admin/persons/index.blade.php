<!-- resources/views/admin/persons/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="py-4 sm:py-6">
        <!-- Modern Header -->
        <div class="mb-6 bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 rounded-xl shadow-lg p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="mr-3 sm:mr-4 text-white/80 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-white">Daftar Orang</h1>
                        <p class="text-slate-300 text-sm mt-0.5">Kelola data anggota</p>
                    </div>
                </div>
                <a href="{{ route('admin.persons.create') }}"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-3 sm:px-4 rounded-lg text-sm sm:text-base w-full sm:w-auto text-center shadow-lg transition-all">
                    <span class="flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Orang
                    </span>
                </a>
            </div>
        </div>

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

        <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-gradient-to-r from-slate-50 to-slate-100">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Nama</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Email
                            </th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider hidden sm:table-cell">Telepon
                            </th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        @foreach ($persons as $person)
                            <tr>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm font-medium text-gray-900">{{ $person->name }}</div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <div class="text-xs sm:text-sm text-gray-500">{{ $person->email ?? '-' }}</div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap hidden sm:table-cell">
                                    <div class="text-xs sm:text-sm text-gray-500">{{ $person->phone ?? '-' }}</div>
                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                                    <a href="{{ route('admin.persons.edit', $person) }}"
                                        class="text-blue-600 hover:text-blue-800 font-medium mr-2 sm:mr-3">Ubah</a>
                                    <form action="{{ route('admin.persons.destroy', $person) }}" method="POST" class="inline" id="delete-person-{{ $person->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-person-{{ $person->id }}', 'orang ini')" class="text-red-600 hover:text-red-800 font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-3 sm:px-6 py-3 sm:py-4">
                {{ $persons->links() }}
            </div>
        </div>
    </div>
@endsection
