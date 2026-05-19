<nav x-data="{ open: false }" class="bg-white border-b border-slate-100 sticky top-0 z-50 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            
            <!-- BAGIAN KIRI: Logo & Judul Aplikasi -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="transition transform hover:scale-105 flex items-center gap-3">
                    <img src="{{ asset('img/logo-tarsius-beltim.png') }}" alt="Logo tarsius" class="block h-12 w-auto object-contain drop-shadow-sm">
                    
                    <div class="border-l-2 border-slate-200 ps-4">
                        <span class="block text-lg font-black text-[#1e3a8a] uppercase italic leading-none tracking-tighter">LAYANAN TARSIUS</span>
                        <span class="block text-[8px] sm:text-[9px] font-bold text-[#f59e0b] uppercase tracking-[0.2em] mt-1">Terintegrasi Administrasi, Regulasi, Statistik, Informasi, dan Urusan Desa</span>
                    </div>
                </a>
            </div>

            <!-- BAGIAN KANAN: Profil & Logout (Desktop) -->
            <div class="hidden sm:flex sm:items-center">
                <div class="flex items-center bg-slate-50/80 rounded-2xl p-1.5 border border-slate-100 shadow-sm hover:bg-white transition-all">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-black rounded-xl text-slate-600 uppercase tracking-widest hover:text-[#1e3a8a] focus:outline-none transition ease-in-out duration-150">
                                <div class="w-9 h-9 bg-gradient-to-br from-[#1e3a8a] to-blue-800 rounded-lg flex items-center justify-center text-white me-3 shadow-md shadow-blue-900/20 font-black italic text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="text-start pe-2 border-r border-slate-200">
                                    <p class="leading-none text-slate-800 text-[11px]">{{ Auth::user()->name }}</p>
                                    <p class="text-[9px] text-[#f59e0b] mt-1 italic font-black uppercase">{{ Auth::user()->role }}</p>
                                </div>
                                <div class="ms-3 text-slate-400">
                                    <svg class="fill-current h-4 w-4 transition-transform group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="p-1">
                                <!-- Link Profil -->
                                <x-dropdown-link :href="route('profile.edit')" class="rounded-lg font-black uppercase text-[10px] tracking-widest flex items-center gap-3 hover:bg-slate-50 transition-all py-3">
                                    <div class="p-1.5 bg-blue-50 rounded-md text-[#1e3a8a]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    {{ __('Profile Saya') }}
                                </x-dropdown-link>

                                <div class="border-t border-slate-100 my-1"></div>

                                <!-- Tombol Logout -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="rounded-lg font-black uppercase text-[10px] tracking-widest text-red-600 hover:bg-red-50 hover:text-red-700 transition-all flex items-center gap-3 py-3">
                                        <div class="p-1.5 bg-red-100 rounded-md text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        </div>
                                        {{ __('Keluar (Log Out)') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger Button (Hanya tampil di HP) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-600 focus:outline-none transition-all duration-300">
                    <svg class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- BAGIAN MOBILE: Profil & Logout -->
    <!-- Menu lain TIDAK ADA di sini, karena sudah dicover oleh Sidebar/Bottom Nav -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-50 border-t border-slate-200 shadow-inner">
        
        <div class="px-6 py-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-[#1e3a8a] to-blue-800 rounded-xl flex items-center justify-center text-white font-black italic shadow-lg shadow-blue-900/20 text-lg">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div>
                <div class="font-black text-xs text-[#1e3a8a] uppercase italic">{{ Auth::user()->name }}</div>
                <div class="font-bold text-[10px] text-[#f59e0b] uppercase tracking-widest mt-1">{{ Auth::user()->role }}</div>
            </div>
        </div>

        <div class="px-4 pb-4 space-y-2">
            <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl font-black uppercase text-[11px] tracking-widest border-none bg-white shadow-sm flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#1e3a8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                {{ __('Profile Settings') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 bg-red-50 text-red-600 hover:bg-red-100 transition-all font-black uppercase italic tracking-widest text-[11px] rounded-xl border border-red-100 mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>KELUAR (LOGOUT)</span>
                </button>
            </form>
        </div>

    </div>
</nav>