<style>
    /* ================================================================= */
    /* NATIVE CSS FOR TARSIUS SIDEBAR (ANTI-GAGAL DI SERVER CPANEL)      */
    /* ================================================================= */
    
    /* Lebar default saat sidebar mengecil (5rem = 80px) */
    .tarsius-sidebar-container {
        width: 5rem;
        background: linear-gradient(to bottom, #020617, #0f172a, #1e293b) !important;
        border-right: 1px solid #334155 !important;
        transition: all 0.3s ease-in-out !important;
    }

    /* Lebar saat kursor diarahkan ke sidebar (Hover Melebar) */
    .tarsius-sidebar-container:hover {
        width: 18rem;
    }

    /* Sembunyikan elemen teks saat mengecil, munculkan dengan transisi saat hover */
    .tarsius-sidebar-container .sidebar-text,
    .tarsius-sidebar-container .sidebar-brand,
    .tarsius-sidebar-container .sidebar-user-info,
    .tarsius-sidebar-container .sub-menu-indicator {
        opacity: 0;
        transform: translateX(-8px);
        pointer-events: none;
        transition: all 0.2s ease-in-out;
    }

    .tarsius-sidebar-container:hover .sidebar-text,
    .tarsius-sidebar-container:hover .sidebar-brand,
    .tarsius-sidebar-container:hover .sidebar-user-info,
    .tarsius-sidebar-container:hover .sub-menu-indicator {
        opacity: 1;
        transform: translateX(0);
        pointer-events: auto;
    }

    /* Posisi Ikon: Pusatkan saat mengecil, geser ke kiri saat melebar */
    .tarsius-sidebar-container .menu-icon-wrapper {
        margin-left: auto;
        margin-right: auto;
        transition: all 0.2s;
        color: #22d3ee; /* Warna Cyan Default */
    }

    .tarsius-sidebar-container:hover .menu-icon-wrapper {
        margin-left: 0;
        margin-right: 0;
    }

    /* Judul Grup Menu (Kategori) */
    .tarsius-sidebar-container .menu-group-title {
        text-align: center;
        letter-spacing: .1em;
        color: #475569;
        transition: all 0.2s;
    }

    .tarsius-sidebar-container:hover .menu-group-title {
        text-align: left;
        letter-spacing: .2em;
        color: #94a3b8;
    }

    /* Tombol Menu Bergaya Neon Slate */
    .tarsius-btn-link {
        display: flex;
        align-items: center;
        height: 3rem;
        border-radius: 1rem;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        text-decoration: none;
        transition: all 0.2s;
        background-color: #1e293b !important; /* Latar belakang item menu */
        color: #22d3ee !important; /* Teks warna cyan */
        margin-bottom: 0.5rem;
    }

    .tarsius-btn-link:hover {
        background-color: #334155 !important;
        color: #67e8f9 !important;
    }

    /* Gaya Ketika Menu Sedang Aktif (Active State) */
    .tarsius-btn-link.menu-aktif {
        background-color: #06b6d4 !important; /* Warna Cyan Terang */
        color: #ffffff !important;
        box-shadow: 0 10px 15px -3px rgba(6, 182, 212, 0.3) !important;
    }

    .tarsius-btn-link.menu-aktif .menu-icon-wrapper {
        color: #ffffff !important;
    }
</style>

<aside x-show="layout === 'sidebar'"
       class="tarsius-sidebar-container fixed inset-y-0 left-0 z-50 flex flex-col overflow-hidden shadow-2xl">

    <div class="flex items-center h-24 border-b border-slate-700 px-4 shrink-0">
        <div class="bg-indigo-600 p-2 rounded-2xl shadow-lg shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>

        <div class="sidebar-brand ml-4 whitespace-nowrap">
            <div class="text-white text-sm font-black uppercase tracking-widest leading-none">
                TARSIUS
            </div>
            <div class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.2em] mt-1">
                Statistik Desa
            </div>
        </div>
    </div>

    <div class="overflow-y-auto overflow-x-hidden flex-grow px-3 mt-6 pb-4 no-scrollbar">
        <ul>
            <li class="mb-3">
                <div class="menu-group-title text-[10px] font-black uppercase transition-all duration-300">
                    Menu
                </div>
            </li>

            <li>
                <a href="{{ route('dashboard') }}"
                   class="tarsius-btn-link {{ request()->routeIs('dashboard', 'admin.dashboard', 'desa.dashboard') ? 'menu-aktif' : '' }}">
                    <div class="menu-icon-wrapper shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <span class="sidebar-text ml-4 text-sm font-bold whitespace-nowrap">
                        Dashboard
                    </span>
                </a>
            </li>

            @if(is_null(auth()->user()->desa_id))
                <li>
                    <a href="{{ route('admin.status-laporan') }}"
                       class="tarsius-btn-link {{ request()->routeIs('admin.status-laporan') ? 'menu-aktif' : '' }}">
                        <div class="menu-icon-wrapper shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="sidebar-text ml-4 text-sm font-bold whitespace-nowrap">
                            Status Laporan Desa
                        </span>
                    </a>
                </li>
            @endif

            <li>
                <a href="{{ url('/admin/desa') }}"
                   class="tarsius-btn-link {{ request()->routeIs('admin.index') ? 'menu-aktif' : '' }}">
                    <div class="menu-icon-wrapper shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="sidebar-text ml-4 text-sm font-bold whitespace-nowrap">
                        Input Data Desa
                    </span>
                </a>
            </li>

            @if(auth()->user()->role === 'desa' && auth()->user()->is_antikorupsi_active)
                <li class="pt-2">
                    <a href="{{ route('desa.antikorupsi.index') }}"
                       class="tarsius-btn-link {{ request()->routeIs('desa.antikorupsi.*') ? 'menu-aktif' : '' }}">
                        <div class="menu-icon-wrapper shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <span class="sidebar-text ml-4 text-sm font-bold whitespace-nowrap">
                            Antikorupsi
                        </span>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <div class="p-3 border-t border-slate-700 bg-slate-950 shrink-0">
        <div class="flex items-center h-12 rounded-2xl px-3 bg-slate-800/70 mb-3 overflow-hidden">
            <div class="menu-icon-wrapper w-8 h-8 rounded-xl bg-cyan-500 flex items-center justify-center text-slate-950 font-black text-sm uppercase shadow shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="sidebar-user-info ml-4 whitespace-nowrap overflow-hidden">
                <div class="text-white text-xs font-black leading-none truncate max-w-[140px]">
                    {{ Auth::user()->name }}
                </div>
                <div class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-1">
                    {{ ucfirst(Auth::user()->role) }}
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center h-12 rounded-2xl bg-slate-800 text-red-400 hover:bg-red-950 hover:text-red-300 transition-all duration-200 px-3 focus:outline-none">
                <div class="menu-icon-wrapper text-red-400 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
                <span class="sidebar-text ml-4 text-sm font-bold whitespace-nowrap">
                    Logout
                </span>
            </button>
        </form>
    </div>
</aside>