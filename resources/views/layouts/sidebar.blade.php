<div class="fixed flex flex-col top-0 left-0 w-64 bg-gray-900 h-full border-r border-gray-800 z-50 transition-all duration-300">
    <div class="flex items-center justify-center h-20 border-b border-gray-800 px-6">
        <div class="bg-blue-600 p-2 rounded-xl mr-3">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <span class="text-white font-black text-xl tracking-tighter uppercase">Si<span class="text-blue-500">CANTIK</span></span>
    </div>

    <div class="overflow-y-auto overflow-x-hidden flex-grow px-4 mt-6">
        <ul class="flex flex-col space-y-2">
            <li class="text-[10px] font-black text-gray-500 uppercase px-4 mb-2 tracking-widest">Main Menu</li>
            
            <li>
                <a href="{{ route('admin.dashboard') }}" class="relative flex items-center h-11 focus:outline-none hover:bg-gray-800 text-gray-400 hover:text-white border-l-4 border-transparent hover:border-blue-500 pr-6 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 text-white border-blue-500' : '' }}">
                    <span class="inline-flex justify-center items-center ml-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </span>
                    <span class="ml-3 text-xs font-bold uppercase">Dashboard</span>
                </a>
            </li>

            @if(is_null(auth()->user()->desa_id))
            <li>
                <a href="{{ route('admin.status-laporan') }}" 
                class="relative flex items-center h-11 focus:outline-none hover:bg-gray-800 text-gray-400 hover:text-white border-l-4 border-transparent hover:border-blue-500 pr-6 rounded-lg transition-all {{ request()->routeIs('admin.status-laporan') ? 'bg-gray-800 text-white border-blue-500' : '' }}">
                    <span class="inline-flex justify-center items-center ml-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </span>
                    <span class="ml-3 text-xs font-bold uppercase italic">Status Laporan Desa</span>
                </a>
            </li>
            @endif

            <li>
                <a href="{{ url('/admin/desa') }}" class="relative flex items-center h-11 focus:outline-none hover:bg-gray-800 text-gray-400 hover:text-white border-l-4 border-transparent hover:border-blue-500 pr-6 rounded-lg transition-all {{ request()->routeIs('admin.index') ? 'bg-gray-800 text-white border-blue-500' : '' }}">
                    <span class="inline-flex justify-center items-center ml-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </span>
                    <span class="ml-3 text-xs font-bold uppercase">Input Data Desa</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="p-4 border-t border-gray-800 bg-gray-900/50">
        <div class="flex items-center gap-3 px-2 mb-4">
            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-black text-xs uppercase shadow-lg shadow-blue-900/40">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-xs font-black text-white truncate">{{ Auth::user()->name }}</span>
                <span class="text-[9px] text-gray-500 font-bold uppercase">{{ Auth::user()->role }}</span>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center h-11 focus:outline-none hover:bg-red-900/20 text-gray-400 hover:text-red-500 border-l-4 border-transparent hover:border-red-500 pr-6 rounded-lg transition-all group">
                <span class="inline-flex justify-center items-center ml-4">
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </span>
                <span class="ml-3 text-xs font-black uppercase italic tracking-widest">Logout</span>
            </button>
        </form>
    </div>
</div>