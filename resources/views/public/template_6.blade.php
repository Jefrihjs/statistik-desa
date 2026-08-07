<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa <?= $desa->nama_desa ?? '' ?> - Profesional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @php
        $theme_primary = '#0891b2';
        $theme_secondary = '#0369a1';
        $theme_accent = '#0d9488';
    @endphp
    <style>
        * { font-family: 'Public Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        
        :root {
            --theme-primary: {{ $theme_primary }};
            --theme-secondary: {{ $theme_secondary }};
            --theme-accent: {{ $theme_accent }};
        }
        
        /* Dynamic Theme Overrides */
        .bg-blue-600 { background-color: var(--theme-primary) !important; }
        .text-blue-600 { color: var(--theme-primary) !important; }
        .border-blue-600 { border-color: var(--theme-primary) !important; }
        .hover\:text-blue-600:hover { color: var(--theme-primary) !important; }
        .bg-blue-50 { background-color: color-mix(in srgb, var(--theme-primary) 10%, transparent) !important; }

        .tab-active {
            background-color: var(--theme-primary);
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(8, 145, 178, 0.25);
        }
        /* Style integration overrides for Professional */
        .max-w-7xl, .max-w-6xl, .max-w-5xl { max-width: 100% !important; width: 100% !important; }
        .rounded-\[3rem\] { border-radius: 0.75rem !important; border: 1px solid #cbd5e1 !important; box-shadow: none !important; }
        .p-10 { padding: 1.5rem !important; }
        .gap-10 { gap: 1.5rem !important; }
        .bg-slate-50 { background-color: #f1f5f9 !important; }
    </style>
</head>
<body class="bg-[#f0f4f8] antialiased text-slate-800 w-full p-0 m-0">
    @php
        $demografi = $categories->where('slug', 'demografi')->first();
        $totalJiwa = 0; $lakiLaki = 0; $perempuan = 0;
        if ($demografi) {
            $totalJiwa = $demografi->indicators->whereIn('name', ['Laki-laki', 'Perempuan'])
                ->sum(function($i) use ($tahun) { return $i->statistics->where('year', $tahun)->sum('value'); });
            $lakiLaki = $demografi->indicators->where('name', 'Laki-laki')
                ->sum(function($i) use ($tahun) { return $i->statistics->where('year', $tahun)->sum('value'); });
            $perempuan = $demografi->indicators->where('name', 'Perempuan')
                ->sum(function($i) use ($tahun) { return $i->statistics->where('year', $tahun)->sum('value'); });
        }
        $unggulanCat = null;
        if ($desa->featured_category_id) {
            $unggulanCat = $categories->where('id', $desa->featured_category_id)->first();
        }
        $pekerjaan = $unggulanCat ?: $categories->where('slug', 'mata-pencaharian')->first();
        $topJob = null;
        if ($pekerjaan) {
            $topJob = $pekerjaan->indicators->filter(function($ind) {
                $nama = strtolower($ind->name);
                return !str_contains($nama, 'belum') && !str_contains($nama, 'tidak') && !str_contains($nama, 'pelajar');
            })->map(function ($ind) use ($tahun) {
                $ind->total_value = $ind->statistics->where('year', $tahun)->sum('value');
                return $ind;
            })->sortByDesc('total_value')->first();
        }
        $logoUrl = $desa->logo_desa ? asset('storage/'.$desa->logo_desa) : ($desa->logo ? asset('storage/'.$desa->logo) : 'https://www.beltim.go.id/images/sekilas_beltim/lambang_daerah/logoBeltim.png');
        $firstTab = $categories->first() ? $categories->first()->slug : '';
    @endphp

    <div class="w-full" x-data="{ activeTab: '{{ $firstTab }}' }">
        <div class="w-full py-6 space-y-6">
            
            {{-- PROFESSIONAL HEADER --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4">
                    <img src="{{ $logoUrl }}" class="h-14 w-14 object-contain" alt="Logo">
                    <div class="text-left border-l-2 border-cyan-600 pl-4">
                        <h1 class="text-xl font-extrabold text-slate-900 tracking-tight uppercase">DESA {{ $desa->nama_desa }}</h1>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-widest mt-0.5">Sistem Informasi Statistik Sektoral</p>
                    </div>
                </div>
                
                <form method="GET" action="" class="m-0 bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 flex items-center gap-2">
                    <span class="text-xs font-semibold text-slate-500">Tahun:</span>
                    <select name="tahun" onchange="this.form.submit()" class="bg-transparent border-none focus:ring-0 font-extrabold cursor-pointer text-sm outline-none text-slate-950 pr-4">
                        @foreach($daftarTahun as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- DENSE SUMMARY GRID --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['label' => 'TOTAL POPULATION', 'val' => $totalJiwa, 'icon' => 'bg-cyan-500', 'icon_svg' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 18a11.374 11.374 0 01-9.333-2.978m14.25-6.216a4.5 4.5 0 00-.43-3.74m-.43 3.74c-.08.18-.19.35-.32.5M14.25 9.07c.5.5 1.124.746 1.75.746A2.25 2.25 0 0018 7.567a2.25 2.25 0 00-2-2.235m-1.75 3.74c-.08-.18-.19-.35-.32-.5m-1.25 1.5c.5.5 1.125.75 1.75.75 1.243 0 2.25-1.007 2.25-2.25a2.25 2.25 0 00-2.25-2.25c-1.243 0-2.25 1.007-2.25 2.25M1.089 15.022a11.374 11.374 0 019.333-2.978c1.379.167 2.658.621 3.74 1.293m0-12.93a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/></svg>'],
                    ['label' => 'MALE CITIZENS', 'val' => $lakiLaki, 'icon' => 'bg-blue-500', 'icon_svg' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>'],
                    ['label' => 'FEMALE CITIZENS', 'val' => $perempuan, 'icon' => 'bg-indigo-500', 'icon_svg' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'],
                    ['label' => $pekerjaan ? strtoupper($pekerjaan->name) : 'FEATURED SECTOR', 'val' => $topJob ? $topJob->name : 'N/A', 'icon' => 'bg-teal-500', 'text' => true, 'icon_svg' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875c-.621 0-1.125-.504-1.125-1.125v-4.25m16.5 0a2.25 2.25 0 00-1.883-2.212c-1.38-.203-2.793-.318-4.237-.344m-5.46 0A22.912 22.912 0 004.883 12c-1.126.166-1.883 1.1-1.883 2.212m16.5 0v-.003c0-.07-.024-.138-.07-.195m0 0a2.25 2.25 0 01-.19-.434m-16.14.629a2.25 2.25 0 01-.19-.434c-.046-.057-.07-.125-.07-.196v.003m16.5 0a2.25 2.25 0 00-2.25-2.25H4.875a2.25 2.25 0 00-2.25 2.25v.003m18 0A2.25 2.25 0 0018 7.5h-3v-1.5A2.25 2.25 0 0012.75 3.75h-1.5A2.25 2.25 0 009 6v1.5H6a2.25 2.25 0 00-2.25 2.25v.003m18 0v-.003c0-.07-.024-.138-.07-.195a2.25 2.25 0 01-.19-.434m-16.14.629a2.25 2.25 0 01-.19-.434c-.046-.057-.07-.125-.07-.196v.003"/></svg>']
                ] as $card)
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-200 flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl shrink-0 {{ $card['icon'] }} flex items-center justify-center text-white text-lg">
                            {!! $card['icon_svg'] !!}
                        </div>
                        <div class="text-left truncate">
                            <span class="text-[9px] font-extrabold tracking-wider text-slate-400 block">{{ $card['label'] }}</span>
                            <h3 class="font-extrabold text-slate-900 tracking-tight mt-1 truncate {{ $card['text'] ?? false ? 'text-sm' : 'text-xl' }}">{{ is_numeric($card['val']) ? number_format($card['val'],0,',','.') : $card['val'] }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- DENSE NAVIGATION TABS --}}
            <div class="bg-white rounded-2xl p-1.5 border border-slate-200 shadow-sm flex flex-wrap gap-1">
                @foreach($categories as $cat)
                    <button type="button" @click="activeTab = '{{ $cat->slug }}'"
                        class="px-4 py-2.5 rounded-xl text-xs font-extrabold transition-all text-slate-500 hover:text-slate-950"
                        :class="activeTab === '{{ $cat->slug }}' ? 'tab-active' : ''">
                        {{ str_replace('Data ', '', $cat->name) }}
                    </button>
                @endforeach
            </div>

            {{-- TAB CONTENTS (PARTIAL COMPONENT) --}}
            <div class="pb-12">
                @include('partials.statistik_komponen')
            </div>
        </div>
    </div>
</body>
</html>
