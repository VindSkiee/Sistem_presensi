@extends('layouts.app')

@section('content')
    <div class="py-4 sm:py-6">
        <!-- Modern Header -->
        <div class="mb-6 bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 rounded-xl shadow-lg p-4 sm:p-6">
            <h1 class="text-xl sm:text-2xl font-bold text-white">Tambah Orang Baru</h1>
            <p class="text-slate-300 text-sm mt-0.5">Isi formulir untuk menambah anggota baru</p>
        </div>

        <!-- Alert Error -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-red-800 font-semibold mb-2">Terjadi kesalahan:</p>
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

        <div class="bg-white shadow-lg rounded-xl p-4 sm:p-6 border border-slate-200">
            <form action="{{ route('admin.persons.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-slate-700 text-sm font-bold mb-2">Nama</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="shadow-sm appearance-none border-2 border-slate-300 rounded-lg w-full py-2.5 px-4 text-slate-700 leading-tight focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all
                        {{ $errors->has('name') ? 'border-red-500' : '' }}">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline
                        {{ $errors->has('email') ? 'border-red-500' : '' }}">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password Akun</label>
                    <input type="password" name="password" id="password" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline
                        {{ $errors->has('password') ? 'border-red-500' : '' }}">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline
                        {{ $errors->has('phone') ? 'border-red-500' : '' }}">
                    @error('phone')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2.5 px-6 rounded-lg focus:outline-none shadow-lg transition-all">
                        Simpan
                    </button>
                    <a href="{{ route('admin.persons.index') }}" class="text-slate-600 hover:text-slate-800 font-medium">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection