<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="transition transform hover:scale-105 flex items-center gap-3">
                        <img src="{{ asset('img/logo-sicantik.png') }}" alt="Logo SiCANTIK" class="block h-12 w-auto object-contain">
                        
                        <div class="hidden md:block border-l-2 border-slate-100 ps-4">
                            <span class="block text-sm font-black text-[#1e3a8a] uppercase italic leading-none">SiCANTIK</span>
                            <span class="block text-[8px] font-bold text-[#f59e0b] uppercase tracking-[0.2em] mt-1">Cinta Statistik</span>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ms-12 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                        class="text-[10px] font-black uppercase tracking-widest italic transition-all active:text-[#f59e0b] hover:text-[#1e3a8a]">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->role === 'admin')
                        {{-- MENU MONITOR DOMAIN DENGAN BADGE --}}
                        @php
                            $kritisCount = \App\Models\DomainTracker::where('status', 'Kritis')->orWhere('status', 'Expired')->count();
                        @endphp

                        <x-nav-link :href="route('admin.domain.monitor')" :active="request()->routeIs('admin.domain.monitor')"
                            class="text-[10px] font-black uppercase tracking-widest italic transition-all active:text-[#f59e0b] hover:text-[#1e3a8a] flex items-center gap-2">
                            {{ __('Monitor Domain') }}
                            
                            @if($kritisCount > 0)
                                <span class="flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[8px] font-black text-white italic animate-pulse shadow-lg shadow-red-500/40">
                                    {{ $kritisCount }}
                                </span>
                            @endif
                        </x-nav-link>

                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')"
                            class="text-[10px] font-black uppercase tracking-widest italic transition-all active:text-[#f59e0b] hover:text-[#1e3a8a]">
                            {{ __('Manajemen User') }}
                        </x-nav-link>
                        
                        <x-nav-link :href="route('admin.status-laporan')" :active="request()->routeIs('admin.status-laporan')"
                            class="text-[10px] font-black uppercase tracking-widest italic transition-all active:text-[#f59e0b] hover:text-[#1e3a8a]">
                            {{ __('Status Laporan Desa') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="flex items-center bg-slate-50 rounded-2xl p-1 border border-slate-100 shadow-inner hover:bg-white transition-all">
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 border border-transparent text-xs leading-4 font-black rounded-xl text-slate-600 uppercase tracking-widest hover:text-[#1e3a8a] focus:outline-none transition ease-in-out duration-150">
                                <div class="w-8 h-8 bg-[#1e3a8a] rounded-lg flex items-center justify-center text-white me-3 shadow-lg shadow-blue-900/20 font-black italic">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="text-start">
                                    <p class="leading-none text-slate-800">{{ Auth::user()->name }}</p>
                                    <p class="text-[8px] text-[#f59e0b] mt-1 italic font-black uppercase">{{ Auth::user()->role }}</p>
                                </div>
                                <div class="ms-2">
                                    <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="p-2">
                                <x-dropdown-link :href="route('profile.edit')" class="rounded-lg font-black uppercase text-[10px] tracking-widest flex items-center gap-2 hover:bg-slate-50 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#1e3a8a]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <div class="border-t border-slate-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="rounded-lg font-black uppercase text-[10px] tracking-widest text-red-500 hover:bg-red-50 transition-all flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-xl text-slate-500 hover:bg-slate-100 focus:outline-none transition-all duration-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-slate-100">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl font-black uppercase text-[10px] tracking-widest italic border-none">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.domain.monitor')" :active="request()->routeIs('admin.domain.monitor')" 
                    class="rounded-xl font-black uppercase text-[10px] tracking-widest italic border-none flex justify-between items-center">
                    <span>{{ __('Monitor Domain Desa') }}</span>
                    @if($kritisCount > 0)
                        <span class="bg-red-500 text-white px-2 py-0.5 rounded-full text-[8px]">{{ $kritisCount }}</span>
                    @endif
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="rounded-xl font-black uppercase text-[10px] tracking-widest italic border-none">
                    {{ __('Manajemen User') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-slate-100 bg-slate-50/50">
            <div class="px-6 flex items-center gap-4">
                <div class="w-10 h-10 bg-[#1e3a8a] rounded-xl flex items-center justify-center text-white font-black italic shadow-lg shadow-blue-900/20">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-black text-[11px] text-slate-800 uppercase italic">{{ Auth::user()->name }}</div>
                    <div class="font-bold text-[9px] text-[#f59e0b] uppercase tracking-widest mt-0.5">{{ Auth::user()->role }}</div>
                </div>
            </div>

            <div class="mt-4 space-y-1 px-4 mb-4">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl font-black uppercase text-[10px] tracking-widest border-none">
                    {{ __('Profile Settings') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-6 py-4 text-red-500 hover:bg-red-50 transition-all font-black uppercase italic tracking-widest text-[10px] rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>KELUAR (LOGOUT)</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>