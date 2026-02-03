<nav class="sticky top-0 z-50 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 shadow-xl border-b border-slate-700">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
    
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center space-x-3">
         
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-lg bg-white">
                            <img src="{{ asset('favicon.ico') }}" alt="Logo" class="w-10 h-10 rounded-lg" />
                        </div>
                    <div class="hidden sm:block">
                        <span class="text-2xl font-bold bg-gradient-to-r from-white to-slate-200 bg-clip-text text-transparent">
                            Sistem Absensi Marina
                        </span>
                        <div class="text-xs text-slate-400 font-medium">Platform Manajemen</div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-3 ml-auto">
                @auth
                    <div class="hidden md:flex items-center space-x-3 bg-slate-800/50 rounded-2xl px-4 py-2 border border-slate-700/50">
                        <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <div class="text-slate-200 font-medium text-sm">{{ auth()->user()->name }}</div>
                            <div class="text-slate-400 text-xs">User Account</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="button" 
                                onclick="confirmLogout()"
                                class="group flex items-center space-x-2 bg-slate-700/50 hover:bg-red-600/20 text-slate-300 hover:text-red-400 px-3 sm:px-4 py-2 rounded-xl transition-all duration-300 border border-slate-600/50 hover:border-red-500/50">
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="text-sm font-medium hidden sm:inline">Logout</span>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>