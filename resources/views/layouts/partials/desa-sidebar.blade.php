@php
    $menu = [
        [
            'label' => 'Dashboard',
            'icon' => '🏠',
            'route' => 'desa.dashboard',
            'active' => request()->routeIs('desa.dashboard'),
        ],
        [
            'label' => 'Statistik Desa',
            'icon' => '📊',
            'route' => 'desa.statistik',
            'active' => request()->routeIs('desa.statistik*'),
        ],
        [
            'label' => 'PPID Desa',
            'icon' => '🗂️',
            'active' => request()->routeIs('desa.ppid*'),
            'children' => [
                ['label' => 'Beranda PPID', 'route' => 'desa.ppid.index'],
                ['label' => 'Daftar Informasi Publik', 'route' => 'desa.ppid.dip.index'],
                ['label' => 'Permohonan Informasi', 'route' => 'desa.ppid.permohonan.index'],
                ['label' => 'Keberatan Informasi', 'route' => 'desa.ppid.keberatan.index'],
                ['label' => 'Laporan PPID', 'route' => 'desa.ppid.laporan.index'],
                ['label' => 'Pengaturan PPID', 'route' => 'desa.ppid.pengaturan.edit'],
            ],
        ],
        [
            'label' => 'Desa Antikorupsi',
            'icon' => '🛡️',
            'route' => 'desa.antikorupsi.index',
            'active' => request()->routeIs('desa.antikorupsi*'),
        ],
        [
            'label' => 'SKM Desa',
            'icon' => '📝',
            'active' => request()->routeIs('desa.skm*'),
            'children' => [
                ['label' => 'Dashboard SKM', 'route' => 'desa.skm.index'],
                ['label' => 'Pertanyaan SKM', 'route' => 'desa.skm.index'],
                ['label' => 'Responden SKM', 'route' => 'desa.skm.index'],
                ['label' => 'Rekap SKM', 'route' => 'desa.skm.index'],
            ],
        ],
        [
            'label' => 'Monitoring Website',
            'icon' => '🌐',
            'active' => request()->routeIs('desa.domain*') || request()->routeIs('desa.ssl*'),
            'children' => [
                ['label' => 'Domain Desa', 'route' => 'desa.domain.index'],
                ['label' => 'SSL Desa', 'route' => 'desa.ssl.index'],
            ],
        ],
        [
            'label' => 'Branding Desa',
            'icon' => '🎨',
            'route' => 'desa.settings.edit',
            'active' => request()->routeIs('desa.settings*'),
        ],
    ];
@endphp

<aside
    x-data="{ collapsed: false, hover: false }"
    @mouseenter="hover = true"
    @mouseleave="hover = false"
    :class="collapsed && !hover ? 'w-[86px]' : 'w-[300px]'"
    class="fixed left-0 top-0 z-40 h-screen bg-slate-950 text-white transition-all duration-300 border-r border-white/10">

    <div class="h-full flex flex-col">

        {{-- BRAND --}}
        <div class="h-20 px-5 flex items-center justify-between border-b border-white/10">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-11 h-11 rounded-2xl bg-white/10 flex items-center justify-center font-black">
                    T
                </div>

                <div x-show="!collapsed || hover" x-transition class="min-w-0">
                    <div class="text-sm font-black uppercase tracking-widest leading-none">
                        TARSIUS
                    </div>
                    <div class="text-[10px] text-slate-400 font-bold uppercase mt-1">
                        Admin Desa
                    </div>
                </div>
            </div>

            <button type="button"
                    @click="collapsed = !collapsed"
                    x-show="!collapsed || hover"
                    x-transition
                    class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-xs">
                ⇆
            </button>
        </div>

        {{-- MENU --}}
        <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-2">

            @foreach($menu as $item)
                @if(isset($item['children']))
                    <div x-data="{ open: {{ $item['active'] ? 'true' : 'false' }} }">
                        <button type="button"
                                @click="open = !open"
                                class="w-full flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition
                                {{ $item['active'] ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">

                            <span class="w-8 h-8 rounded-xl flex items-center justify-center text-lg shrink-0">
                                {{ $item['icon'] }}
                            </span>

                            <span x-show="!collapsed || hover" x-transition class="flex-1 text-left whitespace-nowrap">
                                {{ $item['label'] }}
                            </span>

                            <span x-show="!collapsed || hover" x-transition class="text-xs">
                                <span x-show="!open">+</span>
                                <span x-show="open">−</span>
                            </span>
                        </button>

                        <div x-show="open && (!collapsed || hover)"
                             x-transition
                             class="mt-2 ml-14 space-y-1">
                            @foreach($item['children'] as $child)
                                @php
                                    $routeExists = \Illuminate\Support\Facades\Route::has($child['route']);
                                @endphp

                                @if($routeExists)
                                    <a href="{{ route($child['route']) }}"
                                       class="block rounded-xl px-4 py-2 text-xs font-bold
                                       {{ request()->routeIs($child['route']) ? 'bg-white/15 text-white' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                                        {{ $child['label'] }}
                                    </a>
                                @else
                                    <div class="block rounded-xl px-4 py-2 text-xs font-bold text-slate-600 cursor-not-allowed">
                                        {{ $child['label'] }}
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    @php
                        $routeExists = \Illuminate\Support\Facades\Route::has($item['route']);
                    @endphp

                    @if($routeExists)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold transition
                           {{ $item['active'] ? 'bg-white text-slate-950' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">

                            <span class="w-8 h-8 rounded-xl flex items-center justify-center text-lg shrink-0">
                                {{ $item['icon'] }}
                            </span>

                            <span x-show="!collapsed || hover" x-transition class="whitespace-nowrap">
                                {{ $item['label'] }}
                            </span>
                        </a>
                    @else
                        <div class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-slate-600 cursor-not-allowed">
                            <span class="w-8 h-8 rounded-xl flex items-center justify-center text-lg shrink-0">
                                {{ $item['icon'] }}
                            </span>

                            <span x-show="!collapsed || hover" x-transition class="whitespace-nowrap">
                                {{ $item['label'] }}
                            </span>
                        </div>
                    @endif
                @endif
            @endforeach
        </nav>

        {{-- FOOTER --}}
        <div class="p-4 border-t border-white/10">
            <div class="rounded-2xl bg-white/5 px-4 py-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-sm">
                        👤
                    </div>

                    <div x-show="!collapsed || hover" x-transition class="min-w-0">
                        <div class="text-xs font-black truncate">
                            {{ auth()->user()->name ?? 'Admin Desa' }}
                        </div>
                        <div class="text-[10px] text-slate-400 truncate">
                            {{ auth()->user()->email ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</aside>