@extends('layouts.app')

@section('content')
    <div class="py-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Jadwal Baru</h1>

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
