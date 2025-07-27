@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6">Generate Jadwal Mingguan</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.generate.weekly.form') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mb-4">
                @foreach ($persons as $person)
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="persons[]" value="{{ $person->id }}" class="form-checkbox">
                        <span class="ml-2 text-sm">{{ $person->name }}</span>
                    </label>
                @endforeach
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                Generate Jadwal Mingguan
            </button>
        </form>
    </div>
@endsection
