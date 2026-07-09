<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LAYANAN TARSIUS - Terintegrasi Administrasi, Regulasi, Statistik, Informasi, dan Urusan Desa') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

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

            /* Mobile optimizations */
            @media (max-width: 1024px) {
                body {
                    font-size: 14px;
                }
            }

            .sidebar-nav-item {
                min-height: 64px;
            }

            .sidebar-icon-shell,
            .sidebar-icon-glyph {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                line-height: 1 !important;
            }

            .sidebar-icon-glyph svg {
                width: 100% !important;
                height: 100% !important;
                display: block !important;
                flex-shrink: 0 !important;
            }

            @media (min-width: 1024px) {
                .is-sidebar-collapsed nav {
                    padding-left: 12px !important;
                    padding-right: 12px !important;
                }

                .is-sidebar-collapsed .sidebar-nav-item {
                    justify-content: center !important;
                    padding-left: 8px !important;
                    padding-right: 8px !important;
                }
            }

            @media (min-width: 1024px) {
                .is-sidebar-collapsed nav {
                    padding-left: 12px !important;
                    padding-right: 12px !important;
                }

                .is-sidebar-collapsed .sidebar-nav-item {
                    justify-content: center !important;
                    padding-left: 8px !important;
                    padding-right: 8px !important;
                }
            }

            /* Prevent layout shift on mobile */
            body {
                font-family: 'Inter', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                overflow-x: hidden;
            }

            /* Improve touch targets */
            button, a {
                min-height: 44px;
            }

            /* ================================================================= */
            /* KUNCI DARK MODE TANPA DEPENDENSI NPM BUILD (UNTUK CPANEL SHARED) */
            /* ================================================================= */
            :root {
                --bg-main: #f8fafc;       /* Latar Belakang Terang (bg-gray-50) */
                --bg-card: #ffffff;       /* Card Terang */
                --text-main: #1e293b;     /* Teks Utama Terang */
                --text-sub: #64748b;      /* Teks Sub Terang */
                --border-color: #f8fafc;  /* Border Terang */
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

            .sidebar-nav-item {
                min-height: 64px;
            }

            .sidebar-icon-shell {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-icon-glyph {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-icon-glyph svg {
                width: 100%;
                height: 100%;
                display: block;
            }

            .dark-mode-active {
                background-color: #0f172a !important;
                color: #f8fafc !important;
            }

            /* area halaman */
            .dark-mode-active .bg-slate-50,
            .dark-mode-active .bg-gray-50,
            .dark-mode-active .bg-gray-100 {
                background-color: #0f172a !important;
            }

            /* card putih */
            .dark-mode-active .bg-white {
                background-color: #1e293b !important;
            }

            /* card abu terang */
            .dark-mode-active .bg-slate-100,
            .dark-mode-active .bg-slate-200,
            .dark-mode-active .bg-gray-100,
            .dark-mode-active .bg-gray-200 {
                background-color: #334155 !important;
            }

            /* teks utama */
            .dark-mode-active .text-slate-900,
            .dark-mode-active .text-slate-800,
            .dark-mode-active .text-gray-900,
            .dark-mode-active .text-gray-800 {
                color: #f8fafc !important;
            }

            /* teks sekunder */
            .dark-mode-active .text-slate-700,
            .dark-mode-active .text-slate-600,
            .dark-mode-active .text-slate-500,
            .dark-mode-active .text-gray-700,
            .dark-mode-active .text-gray-600,
            .dark-mode-active .text-gray-500 {
                color: #cbd5e1 !important;
            }

            /* teks muted */
            .dark-mode-active .text-slate-400,
            .dark-mode-active .text-gray-400 {
                color: #94a3b8 !important;
            }

            /* border */
            .dark-mode-active .border-slate-50,
            .dark-mode-active .border-slate-100,
            .dark-mode-active .border-slate-200,
            .dark-mode-active .border-gray-100,
            .dark-mode-active .border-gray-200 {
                border-color: #334155 !important;
            }

            /* input, select, textarea */
            .dark-mode-active input,
            .dark-mode-active select,
            .dark-mode-active textarea {
                background-color: #0f172a !important;
                color: #f8fafc !important;
                border-color: #334155 !important;
            }

            .dark-mode-active input::placeholder,
            .dark-mode-active textarea::placeholder {
                color: #64748b !important;
            }

            /* tabel */
            .dark-mode-active table {
                color: #f8fafc !important;
            }

            .dark-mode-active tbody tr {
                border-color: #334155 !important;
            }

            .dark-mode-active tbody tr:hover {
                background-color: #334155 !important;
            }

            /* chart/map wrapper yang putih */
            .dark-mode-active canvas,
            .dark-mode-active #petaVektor {
                background-color: transparent !important;
            }

            /* overlay empty chart */
            .dark-mode-active [id^="empty-"] {
                background-color: rgba(15, 23, 42, 0.9) !important;
            }

            /* dropdown topbar */
            .dark-mode-active .shadow-sm,
            .dark-mode-active .shadow-md,
            .dark-mode-active .shadow-xl,
            .dark-mode-active .shadow-2xl {
                box-shadow: none !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased font-medium bg-gray-50"
      x-data="{
        sidebarCollapsed: localStorage.getItem('tarsius_sidebar_collapsed') === 'true',
        sidebarHover: false,
        mobileSidebarOpen: false,
        darkMode: localStorage.getItem('tarsius_dark') === 'true',
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('tarsius_sidebar_collapsed', this.sidebarCollapsed);
        },
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('tarsius_dark', this.darkMode);
        }
      }"
      :class="{ 'dark-mode-active': darkMode }"
      x-cloak>

    @php
        $user = auth()->user();
        $desaId = $user->desa_id ?? null;

        $jumlahPermohonanBaru = 0;
        $jumlahKeberatanBaru = 0;

        if ($user->role === 'desa' && $desaId) {
            $jumlahPermohonanBaru = class_exists(\App\Models\PpidPermohonan::class)
                ? \App\Models\PpidPermohonan::where('desa_id', $desaId)->where('status', 'pending')->count()
                : 0;

            $jumlahKeberatanBaru = class_exists(\App\Models\PpidKeberatan::class)
                ? \App\Models\PpidKeberatan::where('desa_id', $desaId)->where('status', 'diajukan')->count()
                : 0;
        }

        $totalAlert = $jumlahPermohonanBaru + $jumlahKeberatanBaru;

        $menuDesa = [
            [
                'label' => 'Dashboard',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 11.5L12 4l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-8.5z"/></svg>',
                'route' => 'desa.dashboard',
                'active' => request()->routeIs('desa.dashboard'),
            ],
            [
                'label' => 'Monitoring Website',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3a9 9 0 100 18 9 9 0 000-18z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 3c2.5 2.5 4 5.5 4 9s-1.5 6.5-4 9M12 3c-2.5 2.5-4 5.5-4 9s1.5 6.5 4 9"/></svg>',
                'active' => request()->routeIs('desa.domain*') || request()->routeIs('desa.ssl*'),
                'children' => [
                    ['label' => 'Domain Desa', 'route' => 'desa.domain.index'],
                    ['label' => 'SSL Desa', 'route' => 'desa.ssl.index'],
                ],
            ],
            [
                'label' => 'Branding Desa',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>',
                'route' => 'desa.settings.edit',
                'active' => request()->routeIs('desa.settings*'),
            ],
        ];

        if ((bool) ($user->is_statistik_active ?? false)) {
            $menuDesa[] = [
                'label' => 'Statistik Desa',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 20V10m7 10V4m7 16v-7"/></svg>',
                'route' => 'desa.statistik',
                'active' => request()->routeIs('desa.statistik*'),
            ];
        }

        if ((bool) ($user->is_ppid_active ?? false)) {
            $menuDesa[] = [
                'label' => 'PPID Desa',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>',
                'active' => request()->routeIs('desa.ppid*'),
                'children' => [
                    ['label' => 'Beranda PPID', 'route' => 'desa.ppid.index'],
                    ['label' => 'Daftar Informasi Publik', 'route' => 'desa.ppid.dip.index'],
                    ['label' => 'Permohonan Informasi', 'route' => 'desa.ppid.permohonan.index'],
                    ['label' => 'Keberatan Informasi', 'route' => 'desa.ppid.keberatan.index'],
                    ['label' => 'Laporan PPID', 'route' => 'desa.ppid.laporan.index'],
                    ['label' => 'Pengaturan PPID', 'route' => 'desa.ppid.pengaturan.edit'],
                ],
            ];
        }

        if ((bool) ($user->is_antikorupsi_active ?? false)) {
            $menuDesa[] = [
                'label' => 'Desa Antikorupsi',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l7 3v5c0 5-3.5 9-7 10-3.5-1-7-5-7-10V6l7-3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>',
                'active' => request()->routeIs('desa.antikorupsi*') || request()->routeIs('desa.master-grup-antikorupsi*'),
                'children' => [
                    ['label' => 'Input Antikorupsi', 'route' => 'desa.antikorupsi.index'],
                    ['label' => 'Master Grup Indikator', 'route' => 'desa.master-grup-antikorupsi.index'],
                ],
            ];
        }

        if ((bool) ($user->is_skm_active ?? false)) {
            $menuDesa[] = [
                'label' => 'SKM Desa',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-4-2-3 2-3-2-4 2V6a2 2 0 012-2z"/></svg>',
                'route' => 'desa.skm.index',
                'active' => request()->routeIs('desa.skm*'),
            ];
        }

        if ((bool) ($user->is_aduan_active ?? false)) {
            $menuDesa[] = [
                'label' => 'Layanan Aduan',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-9 6l2.5-4.5A8 8 0 1120 10a8 8 0 01-12.7 6.5L4 20z"/></svg>',
                'route' => 'desa.aduan.index',
                'active' => request()->routeIs('desa.aduan*'),
            ];
        }
    @endphp

    <div class="min-h-screen bg-gray-50 theme-bg-main">

    {{-- MOBILE BACKDROP --}}
<div x-show="mobileSidebarOpen"
     @click="mobileSidebarOpen = false"
     x-transition.opacity.duration.300ms
     class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"
     style="display:none;">
</div>
        {{-- SIDEBAR --}}
        <aside
    @mouseenter="sidebarHover = true"
    @mouseleave="sidebarHover = false"
    :class="[
        mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        sidebarCollapsed && !sidebarHover ? 'lg:w-[86px] is-sidebar-collapsed' : 'lg:w-[292px]'
    ]"
    class="fixed inset-y-0 left-0 z-50 w-[292px] bg-gradient-to-b from-[#16205a] via-[#1e3a8a] to-[#0f172a] text-white border-r border-white/10 shadow-2xl transition-all duration-300 ease-out overflow-y-auto lg:overflow-visible">

            <div class="h-full flex flex-col">

                {{-- BRAND --}}
                <div class="h-20 px-3 sm:px-5 flex items-center justify-between gap-2 sm:gap-3 border-b border-white/10 flex-shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 sm:w-11 h-10 sm:h-11 bg-white rounded-2xl flex items-center justify-center shadow-lg shrink-0">
                            <img src="{{ asset('img/logo-tarsius-beltim.png') }}"
                                alt="Tarsius"
                                class="h-7 sm:h-8 w-auto object-contain">
                        </div>

                        <div :class="sidebarCollapsed && !sidebarHover ? 'lg:opacity-0 lg:w-0' : 'opacity-100 w-auto'"
                            class="min-w-0 overflow-hidden transition-all duration-300">
                            <div class="text-[9px] sm:text-[10px] font-black uppercase tracking-[0.25em] text-[#f59e0b]">
                                Layanan
                            </div>

                            <div class="text-lg sm:text-xl font-black uppercase tracking-tight leading-none">
                                TAR<span class="text-[#f59e0b]">SIUS</span>
                            </div>
                        </div>
                    </div>

                    <button type="button"
                            @click="mobileSidebarOpen = false"
                            class="lg:hidden w-9 sm:w-10 h-9 sm:h-10 rounded-2xl bg-white/10 hover:bg-white/20 flex items-center justify-center shrink-0 text-white text-sm sm:text-base">
                        ✕
                    </button>
                </div>

                {{-- MENU --}}
                <nav class="flex-1 overflow-y-auto px-3 sm:px-4 py-4 sm:py-5 space-y-1 sm:space-y-2">

                    @if($user->role === 'desa')
                        @foreach($menuDesa as $item)
                            @if(isset($item['children']))
                                <div x-data="{ open: {{ $item['active'] ? 'true' : 'false' }} }">
                                    <button type="button"
                                            @click="open = !open"
                                            class="sidebar-nav-item w-full flex items-center gap-0 px-3 py-3 rounded-2xl transition-all
                                                {{ $item['active'] ? 'bg-[#f59e0b] text-[#1e3a8a] shadow-lg shadow-orange-900/20' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">

                                        <span class="sidebar-icon-shell w-9 sm:w-10 h-9 sm:h-10 flex-shrink-0 rounded-2xl
                                                    {{ $item['active'] ? 'bg-white/20' : 'bg-white/5' }}">
                                            <span class="sidebar-icon-glyph w-5 sm:w-6 h-5 sm:h-6">
                                                {!! $item['icon'] !!}
                                            </span>
                                        </span>

                                        <span :class="sidebarCollapsed && !sidebarHover ? 'lg:opacity-0 lg:w-0 lg:translate-x-2 lg:pl-0' : 'opacity-100 w-auto translate-x-0 pl-3'"
                                            class="text-[10px] sm:text-xs font-black uppercase tracking-widest text-left flex-1 whitespace-nowrap overflow-hidden transition-all duration-300 ease-out">
                                            {{ $item['label'] }}
                                        </span>

                                        <span :class="sidebarCollapsed && !sidebarHover ? 'lg:opacity-0 lg:w-0' : 'opacity-100 w-4'"
                                            class="text-xs font-black overflow-hidden transition-all duration-300 ease-out flex-shrink-0">
                                            <span x-show="!open">+</span>
                                            <span x-show="open">−</span>
                                        </span>
                                    </button>

                                    <div x-show="open && ((!sidebarCollapsed || sidebarHover) || mobileSidebarOpen)"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 -translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-2"
                                        class="mt-1 sm:mt-2 ml-10 sm:ml-11 pl-3 sm:pl-4 border-l border-white/10 space-y-1"
                                        style="display:none;">

                                        @foreach($item['children'] as $child)
                                            @if(\Illuminate\Support\Facades\Route::has($child['route']))
                                                <a href="{{ route($child['route']) }}"
                                                   class="block rounded-xl px-3 py-2 text-[9px] sm:text-[10px] font-black uppercase tracking-widest transition
                                                   {{ request()->routeIs($child['route']) ? 'bg-white/15 text-white' : 'text-white/50 hover:bg-white/10 hover:text-white' }}">
                                                    {{ $child['label'] }}
                                                </a>
                                            @else
                                                <div class="block rounded-xl px-3 py-2 text-[9px] sm:text-[10px] font-black uppercase tracking-widest text-white/20 cursor-not-allowed">
                                                    {{ $child['label'] }}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                @if(\Illuminate\Support\Facades\Route::has($item['route']))
                                    <a href="{{ route($item['route']) }}"
                                        class="sidebar-nav-item flex items-center gap-0 px-3 py-3 rounded-2xl transition-all
                                                {{ $item['active'] ? 'bg-[#f59e0b] text-[#1e3a8a] shadow-lg shadow-orange-900/20' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">

                                            <span class="sidebar-icon-shell w-9 sm:w-10 h-9 sm:h-10 flex-shrink-0 rounded-2xl
                                                        {{ $item['active'] ? 'bg-white/20' : 'bg-white/5' }}">
                                                <span class="sidebar-icon-glyph w-5 sm:w-6 h-5 sm:h-6">
                                                    {!! $item['icon'] !!}
                                                </span>
                                            </span>

                                            <span :class="sidebarCollapsed && !sidebarHover ? 'lg:opacity-0 lg:w-0 lg:translate-x-2 lg:pl-0' : 'opacity-100 w-auto translate-x-0 pl-3'"
                                                class="text-[10px] sm:text-xs font-black uppercase tracking-widest whitespace-nowrap overflow-hidden transition-all duration-300 ease-out">
                                                {{ $item['label'] }}
                                            </span>
                                        </a>
                                @endif
                            @endif
                        @endforeach
                    @endif

                    @if($user->role === 'admin')
                        @php
                            $menuAdmin = [
                                [
                                    'label' => 'Dashboard',
                                    'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 11.5L12 4l9 7.5V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-8.5z"/></svg>',
                                    'route' => 'admin.dashboard',
                                    'active' => request()->routeIs('admin.dashboard'),
                                ],
                                [
                                    'label' => 'Manajemen User',
                                    'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 100-8 4 4 0 000 8zm8 0a4 4 0 100-8 4 4 0 000 8z"/></svg>',
                                    'route' => 'admin.users.index',
                                    'active' => request()->routeIs('admin.users.*'),
                                ],
                                [
                                    'label' => 'Status Laporan',
                                    'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-6m3 6v-4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>',
                                    'route' => 'admin.status-laporan',
                                    'active' => request()->routeIs('admin.status-laporan'),
                                ],
                                [
                                    'label' => 'Monitor Domain',
                                    'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3a9 9 0 100 18 9 9 0 000-18z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 3c2.5 2.5 4 5.5 4 9s-1.5 6.5-4 9M12 3c-2.5 2.5-4 5.5-4 9s1.5 6.5 4 9"/></svg>',
                                    'route' => 'admin.domain.monitor',
                                    'active' => request()->routeIs('admin.domain.monitor'),
                                ],
                                [
                                    'label' => 'Monitor SSL',
                                    'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11V7a4 4 0 118 0v4M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"/></svg>',
                                    'route' => 'admin.ssl.monitor',
                                    'active' => request()->routeIs('admin.ssl.monitor'),
                                ],
                                [
                                    'label' => 'Pengaturan Modul',
                                    'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l7 3v5c0 5-3.5 9-7 10-3.5-1-7-5-7-10V6l7-3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>',
                                    'route' => 'admin.module.setting',
                                    'active' => request()->routeIs('admin.module.*'),
                                ],
                                [
                                    'label' => 'Log Aktivitas',
                                    'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6m-9 4h12a2 2 0 002-2V7l-8-4-8 4v12a2 2 0 002 2z"/></svg>',
                                    'route' => 'admin.activity.logs',
                                    'active' => request()->routeIs('admin.activity.logs'),
                                ],
                            ];
                        @endphp

                        @foreach($menuAdmin as $item)
                            @if(\Illuminate\Support\Facades\Route::has($item['route']))
                                <a href="{{ route($item['route']) }}"
                                class="sidebar-nav-item flex items-center gap-0 px-3 py-3 rounded-2xl transition-all
                                        {{ $item['active'] ? 'bg-[#f59e0b] text-[#1e3a8a] shadow-lg shadow-orange-900/20' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">

                                    <span class="sidebar-icon-shell w-9 sm:w-10 h-9 sm:h-10 flex-shrink-0 rounded-2xl
                                                {{ $item['active'] ? 'bg-white/20' : 'bg-white/5' }}">
                                        <span class="sidebar-icon-glyph w-5 sm:w-6 h-5 sm:h-6">
                                            {!! $item['icon'] !!}
                                        </span>
                                    </span>

                                    <span :class="sidebarCollapsed && !sidebarHover ? 'lg:opacity-0 lg:w-0 lg:translate-x-2 lg:pl-0' : 'opacity-100 w-auto translate-x-0 pl-3'"
                                        class="text-[10px] sm:text-xs font-black uppercase italic tracking-widest whitespace-nowrap overflow-hidden transition-all duration-300 ease-out">
                                        {{ $item['label'] }}
                                    </span>
                                </a>
                            @endif
                        @endforeach
                    @endif
                </nav>
            </div>
        </aside>

        {{-- CONTENT WRAPPER --}}
        <div class="min-h-screen transition-all duration-300"
     :class="sidebarCollapsed ? 'lg:pl-[86px]' : 'lg:pl-[292px]'">

            {{-- TOPBAR --}}
            <header class="sticky top-0 z-40 h-16 sm:h-20 bg-white/90 backdrop-blur-xl border-b border-slate-200 theme-bg-card theme-border">
                <div class="h-full px-3 sm:px-4 lg:px-10 flex items-center justify-between gap-2 sm:gap-3">

                    <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                        <button type="button"
                                @click="window.innerWidth < 1024 ? mobileSidebarOpen = true : toggleSidebar()"
                                class="w-10 sm:w-11 h-10 sm:h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <div class="min-w-0 flex-1">
                            <h1 class="text-xs sm:text-sm lg:text-lg font-black text-slate-900 uppercase theme-text-main truncate">
                                {{ $title ?? ($user->role === 'admin' ? 'Dashboard Kabupaten' : 'Dashboard Desa') }}
                            </h1>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:gap-3">

                        {{-- ALERT CENTER --}}
                        <div x-data="{ openAlert: false }" class="relative">
                            <button type="button"
                                    @click="openAlert = !openAlert"
                                    class="relative w-10 sm:w-11 h-10 sm:h-11 rounded-2xl bg-white border border-slate-200 flex items-center justify-center shadow-sm hover:bg-slate-50 theme-bg-card theme-border flex-shrink-0 text-lg sm:text-xl">
                                🔔

                                @if($totalAlert > 0)
                                    <span class="absolute -top-1 -right-1 min-w-4 h-4 sm:min-w-5 sm:h-5 px-0.5 sm:px-1 rounded-full bg-red-600 text-white text-[8px] sm:text-[10px] font-black flex items-center justify-center">
                                        {{ $totalAlert }}
                                    </span>
                                @endif
                            </button>

                            <div x-show="openAlert"
                                 @click.away="openAlert = false"
                                 x-transition
                                 class="absolute right-0 mt-3 w-[calc(100vw-1rem)] sm:w-96 bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden theme-bg-card theme-border z-50"
                                 style="display:none;">
                                <div class="px-5 py-4 border-b border-slate-100 theme-border">
                                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-800 theme-text-main">
                                        Alert Center
                                    </h3>
                                </div>

                                <div class="p-4 space-y-3 max-h-96 overflow-y-auto">

                        @if($user->role === 'desa')
                            @if(\Illuminate\Support\Facades\Route::has('desa.ppid.permohonan.index'))
                                <a href="{{ route('desa.ppid.permohonan.index') }}"
                                class="block rounded-2xl bg-slate-50 p-4 hover:bg-blue-50">
                                    <div class="text-xs font-black text-slate-900">
                                        Permohonan PPID Baru
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-1">
                                        {{ $jumlahPermohonanBaru }} permohonan menunggu tindak lanjut.
                                    </div>
                                </a>
                            @endif

                            @if(\Illuminate\Support\Facades\Route::has('desa.ppid.keberatan.index'))
                                <a href="{{ route('desa.ppid.keberatan.index') }}"
                                class="block rounded-2xl bg-slate-50 p-4 hover:bg-amber-50">
                                    <div class="text-xs font-black text-slate-900">
                                        Keberatan Informasi Baru
                                    </div>
                                    <div class="text-[11px] text-slate-500 mt-1">
                                        {{ $jumlahKeberatanBaru }} keberatan belum ditanggapi.
                                    </div>
                                </a>
                            @endif
                        @endif

                        @if($totalAlert === 0)
                            <div class="rounded-2xl bg-slate-50 p-4 text-xs font-bold text-slate-400">
                                Belum ada notifikasi baru.
                            </div>
                        @endif

                    </div>
                            </div>
                        </div>

                        {{-- THEME --}}
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" @change="toggleDark()" :checked="darkMode">
                            
                            <div class="w-14 h-8 bg-gray-200 rounded-full peer peer-focus:ring-2 peer-focus:ring-blue-300 
                                /* Saat tidak aktif: ikon bulan */
                                after:content-['☀️'] 
                                after:absolute after:top-1 after:left-1 after:bg-black after:rounded-full after:h-6 after:w-6 after:transition-all
                                
                                /* Saat aktif (peer-checked): geser ke kanan dan ganti ikon jadi matahari */
                                peer-checked:bg-blue-600
                                peer-checked:after:translate-x-full 
                                peer-checked:after:content-['🌙']">
                            </div>
                        </label>

                        {{-- USER DROPDOWN --}}
                        <div x-data="{ openUser: false }" class="relative">
                            <button type="button"
                                    @click="openUser = !openUser"
                                    class="flex items-center gap-2 sm:gap-3 rounded-2xl bg-white border border-slate-200 px-2 sm:px-4 py-2 shadow-sm hover:bg-slate-50 theme-bg-card theme-border flex-shrink-0">
                                <div class="w-8 sm:w-9 h-8 sm:h-9 rounded-xl bg-slate-100 flex items-center justify-center font-black text-xs sm:text-sm">
                                    {{ substr($user->name, 0, 1) }}
                                </div>

                                <div class="hidden md:block text-left">
                                    <div class="text-xs font-black text-slate-800 theme-text-main">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase">
                                        {{ $user->role }}
                                    </div>
                                </div>
                            </button>

                            <div x-show="openUser"
                                 @click.away="openUser = false"
                                 x-transition
                                 class="absolute right-0 mt-3 w-48 sm:w-56 bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden theme-bg-card theme-border z-50"
                                 style="display:none;">
                                <div class="p-3">
                                    @if(\Illuminate\Support\Facades\Route::has('profile.edit'))
                                        <a href="{{ route('profile.edit') }}"
                                           class="block rounded-2xl px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50">
                                            Profil User
                                        </a>
                                    @endif

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full text-left rounded-2xl px-4 py-3 text-xs font-bold text-red-600 hover:bg-red-50">
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </header>

            {{-- PAGE HEADER LAMA JIKA MASIH ADA --}}
            @isset($header)
                <header class="theme-bg-card border-b theme-border">
                    <div class="max-w-[1400px] mx-auto py-4 sm:py-6 px-3 sm:px-6 lg:px-10">
                        <div class="text-lg sm:text-2xl font-black theme-text-main uppercase tracking-tighter">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            {{-- MAIN --}}
            <main class="px-3 sm:px-4 lg:px-10 py-4 sm:py-6 lg:py-10">
                {{ $slot }}
            </main>
        </div>
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
