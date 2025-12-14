@extends('layouts.app')

@section('content')
    <div class="py-4 sm:py-6">
        <!-- Modern Header -->
        <div class="mb-6 bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 rounded-xl shadow-lg p-4 sm:p-6">
            <h1 class="text-xl sm:text-2xl font-bold text-white">Tambah Jadwal Baru</h1>
            <p class="text-slate-300 text-sm mt-0.5">Buat jadwal presensi baru</p>
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

        <div class="bg-white shadow-lg rounded-xl p-4 sm:p-6 border border-slate-200">
            <form action="{{ route('admin.schedules.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="date" class="block text-slate-700 text-sm font-bold mb-2">Tanggal</label>
                    <input type="date" name="date" id="date" value="{{ old('date') }}" required
                        class="shadow-sm appearance-none border-2 border-slate-300 rounded-lg w-full py-2.5 px-4 text-slate-700 leading-tight focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                </div>

                <div class="mb-4">
                    <label class="block text-slate-700 text-sm font-bold mb-3">Daftar Orang</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-4 bg-slate-50 rounded-lg border border-slate-200">
                        @foreach ($persons as $person)
                            <div class="flex items-center">
                                <input type="checkbox" name="persons[]" id="person_{{ $person->id }}"
                                    value="{{ $person->id }}"
                                    {{ in_array($person->id, old('persons', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                                <label for="person_{{ $person->id }}"
                                    class="ml-2 text-sm text-slate-700">{{ $person->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2.5 px-6 rounded-lg focus:outline-none shadow-lg transition-all">
                        Simpan
                    </button>
                    <a href="{{ route('admin.schedules.index') }}" class="text-slate-600 hover:text-slate-800 font-medium">Batal</a>
                </div>
            </form>
        </div>

    </div>
@endsection
