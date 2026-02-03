<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center px-4 sm:px-6">
        <div class="w-full max-w-md">

            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-200">

                <!-- Header -->
                <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-6 py-8 text-center">
                    <div class="mx-auto w-16 h-16 rounded-xl bg-white/10 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>

                    <h1 class="text-2xl font-bold text-white">Selamat Datang</h1>
                    <p class="text-slate-300 text-sm mt-1">
                        Silakan masuk ke akun Anda
                    </p>
                </div>

                <!-- Form -->
                <div class="px-6 py-6">

                    <!-- Alert Error -->
                    @if ($errors->any())
                        <div
                            class="mb-4 p-3 bg-gradient-to-r from-red-50 to-red-50/50 border-l-4 border-red-500 rounded-lg shadow-sm">
                            <p class="text-red-800 font-bold text-sm mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Terjadi kesalahan:
                            </p>
                            <ul class="text-red-700 text-xs space-y-1 ml-7">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Alert Success -->
                    @if (session('success'))
                        <div
                            class="mb-4 p-3 bg-gradient-to-r from-emerald-50 to-emerald-50/50 border-l-4 border-emerald-500 rounded-lg shadow-sm">
                            <p class="text-emerald-800 font-bold text-sm flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ session('success') }}
                            </p>
                        </div>
                    @endif

                    <!-- Alert Warning -->
                    @if (session('warning'))
                        <div
                            class="mb-4 p-3 bg-gradient-to-r from-yellow-50 to-yellow-50/50 border-l-4 border-yellow-500 rounded-lg shadow-sm">
                            <p class="text-yellow-800 font-bold text-sm flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ session('warning') }}
                            </p>
                        </div>
                    @endif

                    <!-- Alert Error Session -->
                    @if (session('error'))
                        <div
                            class="mb-4 p-3 bg-gradient-to-r from-red-50 to-red-50/50 border-l-4 border-red-500 rounded-lg shadow-sm">
                            <p class="text-red-800 font-bold text-sm flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                {{ session('error') }}
                            </p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="email" class="block text-slate-700 text-sm font-bold mb-2">Email</label>
                            <input type="email" name="email" id="email" required autofocus
                                class="shadow-sm appearance-none border-2 border-slate-300 rounded-lg w-full py-3 px-4 text-slate-700 leading-tight focus:outline-none focus:border-gray-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        </div>

                        <div>
                            <label for="password" class="block text-slate-700 text-sm font-bold mb-2">Password</label>
                            <input type="password" name="password" id="password" required
                                class="shadow-sm appearance-none border-2 border-slate-300 rounded-lg w-full py-3 px-4 text-slate-700 leading-tight focus:outline-none focus:border-gray-500 focus:ring-2 focus:ring-blue-200 transition-all">
                        </div>

                        <div class="pt-2">
                            <button type="submit"
                                class="bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 hover:from-slate-700 hover:via-slate-800 hover:to-slate-900 transform hover:scale-105 hover:shadow-xl text-white font-bold text-sm py-3 px-4 rounded-lg focus:outline-none w-full transition-all duration-200 shadow-lg flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Masuk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
