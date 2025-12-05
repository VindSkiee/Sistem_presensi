<!-- Enhanced Navbar with Modern Formal Design -->
<nav class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 shadow-xl border-b border-slate-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center space-x-3">
                    <!-- Logo/Icon -->
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-white to-slate-200 bg-clip-text text-transparent">
                            Presensi Sistem
                        </span>
                        <div class="text-xs text-slate-400 font-medium">Platform Manajemen</div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                @auth
                    <!-- User Info -->
                    <div class="hidden sm:flex items-center space-x-3 bg-slate-800/50 rounded-2xl px-4 py-2 border border-slate-700/50">
                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <div class="text-slate-200 font-medium text-sm">{{ auth()->user()->name }}</div>
                            <div class="text-slate-400 text-xs">User Account</div>
                        </div>
                    </div>
                    
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="button" 
                                onclick="confirmLogout()"
                                class="group flex items-center space-x-2 bg-slate-700/50 hover:bg-red-600/20 text-slate-300 hover:text-red-400 px-4 py-2 rounded-xl transition-all duration-300 border border-slate-600/50 hover:border-red-500/50">
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="text-sm font-medium">Logout</span>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>