<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LAYANAN TARSIUS - Terintegrasi Administrasi, Regulasi, Statistik, Informasi, dan Urusan Desa') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
            .leaflet-tooltip {
                background: #1e3a8a !important;
                color: white !important;
                border: none !important;
                border-radius: 8px !important;
                padding: 4px 10px !important;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1) !important;
                font-weight: 900;
                text-transform: uppercase;
            }
            select {
                -webkit-appearance: none !important;
                -moz-appearance: none !important;
                appearance: none !important;
                background-image: none !important;
            }

            /* ================================================================= */
            /* KUNCI DARK MODE TANPA DEPENDENSI NPM BUILD (UNTUK CPANEL SHARED) */
            /* ================================================================= */
            :root {
                --bg-main: #f1f5f9;       /* Latar Belakang Terang */
                --bg-card: #ffffff;       /* Card Terang */
                --text-main: #1e293b;     /* Teks Utama Terang */
                --text-sub: #64748b;      /* Teks Sub Terang */
                --border-color: #f1f5f9;  /* Border Terang */
            }

            .dark-mode-active {
                --bg-main: #0f172a !important;       /* Latar Belakang Gelap (Slate 900) */
                --bg-card: #1e293b !important;       /* Card Gelap (Slate 800) */
                --text-main: #f8fafc !important;     /* Teks Utama Gelap (Slate 50) */
                --text-sub: #94a3b8 !important;      /* Teks Sub Gelap (Slate 400) */
                --border-color: #334155 !important;  /* Border Gelap (Slate 700) */
            }

            /* Custom Selector Kelas Mandiri */
            .theme-bg-main { background-color: var(--bg-main) !important; }
            .theme-bg-card { background-color: var(--bg-card) !important; }
            .theme-text-main { color: var(--text-main) !important; }
            .theme-text-sub { color: var(--text-sub) !important; }
            .theme-border { border-color: var(--border-color) !important; }

            /* Animasi perpindahan warna halus agar premium */
            body, div, header, main, h1, h2, h3, h4, p, span, table, tr, td {
                transition: background-color 0.25s ease, border-color 0.25s ease, color 0.25s ease;
            }
        </style>
    </head>
    <body class="font-sans antialiased font-medium" 
          x-data="{ 
            layout: localStorage.getItem('tarsius_layout') || 'navbar',
            darkMode: localStorage.getItem('tarsius_dark') === 'true',
            toggleLayout() {
                this.layout = this.layout === 'navbar' ? 'sidebar' : 'navbar';
                localStorage.setItem('tarsius_layout', this.layout);
            },
            toggleDark() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('tarsius_dark', this.darkMode);
            }
          }" 
          :class="{ 'dark-mode-active': darkMode }" 
          x-cloak>

        <div class="min-h-screen flex theme-bg-main" :class="layout === 'navbar' ? 'flex-col' : 'flex-row'">
            
            <aside x-show="layout === 'sidebar'" 
                   class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-[#16205a] via-[#1e3a8a] to-[#0f172a] border-r border-white/10 z-50 transition-all duration-300 shadow-2xl overflow-hidden flex flex-col">
                
                <div class="flex items-center justify-center h-24 border-b border-white/10 px-6 mb-6">
                    <div class="bg-white p-2 rounded-3xl shadow-xl shadow-slate-900/25 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                        <img src="{{ asset('img/logo-tarsius-beltim.png') }}" alt="Logo tarsius" class="h-10 w-auto object-contain">
                    </div>
                    
                    <div class="ml-3">
                        <span class="text-[#f59e0b]">LAYANAN</span>
                        <span class="block text-white font-black text-xl italic tracking-tighter uppercase leading-none">TAR<span class="text-[#f59e0b]">SIUS</span></span>
                    </div>
                </div>

                <nav class="flex-grow px-4 space-y-2 overflow-y-auto pb-4 custom-scrollbar">
                    <p class="text-[10px] font-black text-white/30 uppercase px-4 mb-2 tracking-[0.2em]">Menu Utama</p>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white hover:bg-white/10 transition-all {{ request()->routeIs('dashboard') ? 'bg-[#f59e0b] shadow-lg shadow-orange-900/20' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span class="text-xs font-black uppercase italic tracking-widest">Dashboard</span>
                    </a>

                    @if(auth()->user()->role === 'admin')
                        @php
                            $kritisCount = \App\Models\DomainTracker::where('status', 'Kritis')->orWhere('status', 'Expired')->count();
                        @endphp
                        
                        <p class="pt-6 text-[10px] font-black text-white/30 uppercase px-4 mb-2 tracking-[0.2em]">Admin</p>
                        
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white/70 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.users.*') ? 'bg-[#f59e0b] text-white shadow-lg shadow-orange-900/20' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="text-xs font-black uppercase italic tracking-widest">Manajemen User</span>
                        </a>

                        <a href="{{ route('admin.status-laporan') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white/70 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.status-laporan', 'admin.index') ? 'bg-[#f59e0b] text-white shadow-lg shadow-orange-900/20' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-xs font-black uppercase italic tracking-widest">Status Laporan Desa</span>
                        </a>

                        <a href="{{ route('admin.kategori.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white/70 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.kategori.*') ? 'bg-[#f59e0b] text-white shadow-lg shadow-orange-900/20' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="text-xs font-black uppercase italic tracking-widest text-left">Kelola Kategori</span>
                        </a>

                        <div x-data="{ open: {{ request()->routeIs('admin.antikorupsi.*', 'admin.domain.*') ? 'true' : 'false' }} }" class="mt-1">
                            <button @click="open = ! open" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-white/70 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.antikorupsi.*', 'admin.domain.*') ? 'bg-white/10 text-white' : '' }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    <span class="text-xs font-black uppercase italic tracking-widest text-left">Menu Lainnya</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($kritisCount > 0)
                                        <span class="flex h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
                                    @endif
                                    <svg class="w-4 h-4 transform transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="mt-2 ml-4 pl-4 border-l-2 border-white/10 space-y-4 py-2" style="display: none;">
                                
                                <div>
                                    <p class="text-[9px] font-black text-[#f59e0b] uppercase tracking-[0.2em] italic mb-2">Desa Antikorupsi</p>
                                    <div class="space-y-3">
                                        <a href="{{ route('admin.antikorupsi.setting') }}" class="block px-2 text-[10px] font-bold uppercase tracking-widest text-white/60 hover:text-white transition-colors {{ request()->routeIs('admin.antikorupsi.setting') ? 'text-white' : '' }}">
                                            - Pengaturan Akses
                                        </a>
                                    </div>
                                </div>

                                <div class="pt-1">
                                    <p class="text-[9px] font-black text-[#f59e0b] uppercase tracking-[0.2em] italic mb-2">Layanan Domain</p>
                                    <div class="space-y-3">
                                        <a href="{{ route('admin.domain.monitor') }}" class="flex items-center justify-between px-2 text-[10px] font-bold uppercase tracking-widest text-white/60 hover:text-white transition-colors {{ request()->routeIs('admin.domain.monitor') ? 'text-white' : '' }}">
                                            <span>- Monitor Domain</span>
                                            @if($kritisCount > 0)
                                                <span class="flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[8px] font-black text-white italic animate-pulse shadow-lg">
                                                    {{ $kritisCount }}
                                                </span>
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->role === 'desa' && auth()->user()->is_antikorupsi_active)
                        <p class="pt-6 text-[10px] font-black text-white/30 uppercase px-4 mb-2 tracking-[0.2em]">Desa</p>
                        <div x-data="{ open: {{ request()->routeIs('desa.antikorupsi.*', 'desa.master-grup-antikorupsi.*') ? 'true' : 'false' }} }" class="mt-1">
                            <button @click="open = ! open" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-white/70 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('desa.antikorupsi.*', 'desa.master-grup-antikorupsi.*') ? 'bg-white/10 text-white' : '' }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    <span class="text-xs font-black uppercase italic tracking-widest text-left">Menu Lainnya</span>
                                </div>
                                <svg class="w-4 h-4 transform transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="mt-2 ml-4 pl-4 border-l-2 border-white/10 space-y-4 py-2" style="display: none;">
                                <div>
                                    <p class="text-[9px] font-black text-[#f59e0b] uppercase tracking-[0.2em] italic mb-2">Desa Antikorupsi</p>
                                    <div class="space-y-3">
                                        <a href="{{ route('desa.master-grup-antikorupsi.index') }}" class="block px-2 text-[10px] font-bold uppercase tracking-widest text-white/60 hover:text-white transition-colors {{ request()->routeIs('desa.master-grup-antikorupsi.*') ? 'text-white' : '' }}">
                                            - Master Grup Indikator
                                        </a>
                                        <a href="{{ route('desa.antikorupsi.index') }}" class="block px-2 text-[10px] font-bold uppercase tracking-widest text-white/60 hover:text-white transition-colors {{ request()->routeIs('desa.antikorupsi.*') ? 'text-white' : '' }}">
                                            - Input Antikorupsi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </nav>

                <div class="p-4 bg-black/20 border-t border-white/10">
                    <div class="p-4 border-t border-blue-800 bg-[#1e3a8a]/50">
                        <div class="flex items-center gap-3 px-2 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-[#f59e0b] flex items-center justify-center text-[#1e3a8a] font-black text-xs shadow-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-white uppercase">{{ Auth::user()->name }}</span>
                                <span class="text-[8px] text-blue-300 font-bold uppercase tracking-widest">{{ Auth::user()->role }}</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center h-10 hover:bg-white/10 text-blue-200 hover:text-white px-4 rounded-xl transition-all group focus:outline-none">
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <span class="ml-3 text-[10px] font-black uppercase italic tracking-widest">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
            <div class="flex-grow min-w-0 theme-bg-main transition-all duration-300" :class="layout === 'sidebar' ? 'ml-64' : ''">
                
                <template x-if="layout === 'navbar'">
                    @include('layouts.navigation')
                </template>

                @isset($header)
                    <header class="theme-bg-card border-b theme-border">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                            <div class="text-2xl font-black theme-text-main uppercase italic tracking-tighter">{{ $header }}</div>
                        </div>
                    </header>
                @endisset

                <main class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        <div class="fixed bottom-6 right-6 z-[60] flex flex-col gap-3">
            
            <button @click="toggleDark()" 
                    class="group relative flex items-center justify-center w-14 h-14 rounded-[1.5rem] shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300 border"
                    :class="darkMode ? 'bg-slate-800 border-slate-700 text-yellow-400' : 'bg-white border-slate-200 text-slate-600'">
                
                <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>

                <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>

                <span class="absolute right-16 px-4 py-2 bg-slate-900 text-white text-[10px] font-black uppercase rounded-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none tracking-widest">
                    Tema <span x-text="darkMode ? 'Terang' : 'Gelap'"></span>
                </span>
            </button>

            <button @click="toggleLayout()" 
                    class="group relative flex items-center justify-center w-14 h-14 bg-[#f59e0b] text-[#1e3a8a] rounded-[1.5rem] shadow-2xl shadow-orange-500/20 hover:scale-110 active:scale-95 transition-all duration-300">
                <svg x-show="layout === 'navbar'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="layout === 'sidebar'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                
                <span class="absolute right-16 px-4 py-2 bg-slate-900 text-white text-[10px] font-black uppercase rounded-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none tracking-widest">
                    Tukar Ke <span x-text="layout === 'navbar' ? 'Sidebar' : 'Navbar'"></span>
                </span>
            </button>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

        <script>
            window.eksporTabelDinamis = function(idTabel, namaKategori) {
                console.log("Memulai Export untuk:", idTabel);
                const table = document.getElementById(idTabel);
                if (!table) {
                    alert("Tabel " + idTabel + " tidak ditemukan! Cek ID tabel di HTML.");
                    return;
                }
                const wb = XLSX.utils.table_to_book(table, { sheet: "Data Statistik" });
                const fileName = "Data-" + namaKategori.replace(/\s+/g, '-') + "-2026.xlsx";
                XLSX.writeFile(wb, fileName);
            };
        </script>

        @stack('scripts')
        
    </body>
</html>