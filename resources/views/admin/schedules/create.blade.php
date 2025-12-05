@extends('layouts.app')

@section('content')
    <div class="py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Jadwal Baru</h1>

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

        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.schedules.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                    <input type="date" name="date" id="date" value="{{ old('date') }}" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Daftar Orang</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach ($persons as $person)
                            <div class="flex items-center">
                                <input type="checkbox" name="persons[]" id="person_{{ $person->id }}"
                                    value="{{ $person->id }}"
                                    {{ in_array($person->id, old('persons', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="person_{{ $person->id }}"
                                    class="ml-2 text-sm text-gray-700">{{ $person->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Simpan
                    </button>
                    <a href="{{ route('admin.schedules.index') }}" class="text-gray-600 hover:text-gray-800">Batal</a>
                </div>
            </form>
        </div>

    </div>
@endsection
