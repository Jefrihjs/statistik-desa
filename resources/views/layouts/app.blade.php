<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SiCANTIK - Cinta Statistik') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
            /* Style Custom Label Peta Bapak */
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
        </style>
        <style>
            /* Paksa hapus ikon dropdown bawaan di semua browser */
            select {
                -webkit-appearance: none !important;
                -moz-appearance: none !important;
                appearance: none !important;
                background-image: none !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased" 
          x-data="{ 
            layout: localStorage.getItem('sicantik_layout') || 'navbar',
            toggleLayout() {
                this.layout = this.layout === 'navbar' ? 'sidebar' : 'navbar';
                localStorage.setItem('sicantik_layout', this.layout);
            }
          }" x-cloak>

        <div class="min-h-screen bg-gray-100 flex" :class="layout === 'navbar' ? 'flex-col' : 'flex-row'">
            
            <aside x-show="layout === 'sidebar'" 
                   class="fixed inset-y-0 left-0 w-64 bg-[#1e3a8a] border-r border-white/10 z-50 transition-all duration-300 shadow-2xl overflow-hidden flex flex-col">
                
                <div class="flex items-center justify-center h-24 border-b border-white/10 px-6 mb-6">
                    <div class="bg-white p-2 rounded-2xl shadow-lg shadow-black/20 transform -rotate-3 hover:rotate-0 transition-transform duration-500">
                        <img src="{{ asset('img/logo-sicantik.png') }}" alt="Logo SiCANTIK" class="h-10 w-auto object-contain">
                    </div>
                    
                    <div class="ml-3">
                        <span class="block text-white font-black text-xl italic tracking-tighter uppercase leading-none">Si<span class="text-[#f59e0b]">CANTIK</span></span>
                        <span class="block text-[7px] font-bold text-white/40 uppercase tracking-[0.2em] mt-1">Belitung Timur</span>
                    </div>
                </div>

                <nav class="flex-grow px-4 space-y-2">
                    <p class="text-[10px] font-black text-white/30 uppercase px-4 mb-2 tracking-[0.2em]">Menu Utama</p>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white hover:bg-white/10 transition-all {{ request()->routeIs('dashboard') ? 'bg-[#f59e0b] shadow-lg shadow-orange-900/20' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span class="text-xs font-black uppercase italic tracking-widest">Dashboard</span>
                    </a>
                    @if(auth()->user()->role === 'admin')
                    <li>
                        <a href="{{ route('admin.index') }}" 
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white/70 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.index') ? 'bg-[#f59e0b] text-white shadow-lg shadow-orange-900/20' : '' }}">
                            
                            <span class="inline-flex justify-center items-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </span>
                            <span class="text-xs font-black uppercase italic tracking-widest">Status Laporan Desa</span>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->role === 'admin')
                        <p class="pt-6 text-[10px] font-black text-white/30 uppercase px-4 mb-2 tracking-[0.2em]">Admin</p>
                        <a href="{{ route('admin.kategori.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-white/70 hover:bg-white/10 hover:text-white transition-all {{ request()->routeIs('admin.kategori.*') ? 'bg-[#f59e0b] text-white' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <span class="text-xs font-black uppercase italic tracking-widest text-left">Kelola Kategori</span>
                        </a>
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
                                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                <span class="ml-3 text-[10px] font-black uppercase italic tracking-widest">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="flex-grow min-w-0" :class="layout === 'sidebar' ? 'ml-64 transition-all duration-300' : ''">
                
                <template x-if="layout === 'navbar'">
                    @include('layouts.navigation')
                </template>

                <div class="fixed bottom-6 right-6 z-[60]">
                    <button @click="toggleLayout()" 
                            class="group relative flex items-center justify-center w-14 h-14 bg-[#1e3a8a] text-white rounded-[1.5rem] shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300">
                        <svg x-show="layout === 'navbar'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <svg x-show="layout === 'sidebar'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        
                        <span class="absolute right-16 px-4 py-2 bg-slate-900 text-white text-[10px] font-black uppercase rounded-xl whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none tracking-widest">
                            Tukar Ke <span x-text="layout === 'navbar' ? 'Sidebar' : 'Navbar'"></span>
                        </span>
                    </button>
                </div>

                @isset($header)
                    <header class="bg-white border-b border-slate-100">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                            <div class="text-2xl font-black text-[#1e3a8a] uppercase italic tracking-tighter">{{ $header }}</div>
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

        {{-- Scripts Utama --}}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

            {{-- Tempat naruh script khusus dari halaman (Dashboard/Peta) --}}
            @stack('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    </body>
</html>