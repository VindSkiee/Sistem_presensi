<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
        <div class="p-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Login</h1>
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

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" id="email" required autofocus
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection