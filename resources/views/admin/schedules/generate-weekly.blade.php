@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto py-10">
        {{-- Header --}}
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.dashboard') }}" class="mr-4 text-gray-500 hover:text-gray-900 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" 
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                          stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Buat Jadwal Mingguan</h1>
        </div>

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded">
                <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Card Form --}}
        <div class="bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.generate.weekly.form') }}" method="POST">
                @csrf

                <h2 class="text-lg font-semibold text-gray-700 mb-4">Pilih Orang</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mb-6">
                    @foreach ($persons as $person)
                        <label 
                            class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-blue-50 transition">
                            <input type="checkbox" name="persons[]" value="{{ $person->id }}" 
                                   class="form-checkbox h-4 w-4 text-blue-600">
                            <span class="ml-3 text-sm font-medium text-gray-700">{{ $person->name }}</span>
                        </label>
                    @endforeach
                </div>

                <button type="submit" 
                        class="w-full sm:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 
                               text-white text-sm font-semibold rounded-lg shadow transition">
                    Generate Jadwal Mingguan
                </button>
            </form>
        </div>
    </div>
@endsection
