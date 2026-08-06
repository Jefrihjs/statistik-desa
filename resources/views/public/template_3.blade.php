<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa <?= $desa->nama_desa ?? '' ?> - Elegan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @php
        $theme_primary = '#3b82f6';
        $theme_secondary = '#1d4ed8';
        $theme_accent = '#ec4899';
    @endphp
    <style>
        * { font-family: 'Outfit', sans-serif; }
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
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }
        /* Custom dark theme colors */
        .theme-dark-bg { background-color: #0f172a; }
        .theme-card-bg { background-color: #1e293b; border-color: #334155; }
        
        /* Style integration overrides for Dark Mode */
        .max-w-7xl, .max-w-6xl, .max-w-5xl { max-width: 100% !important; width: 100% !important; }
        .bg-white { background-color: #1e293b !important; }
        .shadow-2xl, .shadow-sm { box-shadow: none !important; }
        .border-slate-100, .border-slate-200 { border-color: #334155 !important; }
        .text-slate-800, .text-slate-900 { color: #f8fafc !important; }
        .text-slate-500, .text-slate-400 { color: #94a3b8 !important; }
        .bg-slate-50 { background-color: #0f172a !important; }
        .hover\:bg-slate-50\/50:hover { background-color: #0f172a !important; }
        .rounded-\[3rem\] { border-radius: 1.5rem !important; }
        .p-10 { padding: 1.5rem !important; }
        .gap-10 { gap: 1.5rem !important; }
        
        /* Table and inputs integration in dark mode */
        table { border-collapse: collapse; }
        thead, .bg-slate-100 { background-color: #0f172a !important; }
        th { background-color: #0f172a !important; color: #f8fafc !important; }
        
        /* Force table cells to be transparent so they inherit tr backgrounds and hover colors */
        td { background-color: transparent !important; color: #e2e8f0 !important; }
        
        /* Alternating row colors for table readability */
        tr:nth-child(even) { background-color: #1e293b !important; }
        tr:nth-child(odd) { background-color: #111827 !important; }
        
        /* Interactive hover background */
        tr:hover, tr.hover\:bg-slate-50\/50:hover { 
            background-color: #334155 !important; 
        }
        
        /* Selected row override inside tables - forces all cell text/badges to white */
        tr.bg-blue-600, tr.bg-blue-600 td, tr.bg-blue-600 span, tr.bg-blue-600 p,
        tr[class*="bg-blue-"] td, tr[class*="bg-blue-"] span, tr[class*="bg-blue-"] p {
            background-color: var(--theme-primary) !important;
            color: #ffffff !important;
        }

        /* Specific text and badge contrast overrides for Dark Mode */
        .text-slate-800, .text-slate-900, td.text-slate-800, td.text-slate-900 { color: #f8fafc !important; }
        .text-slate-500, .text-slate-400, td.text-slate-500 { color: #94a3b8 !important; }
        .text-blue-700, .text-blue-600 { color: #60a5fa !important; }
        .text-pink-700, .text-pink-600 { color: #f472b6 !important; }
        .text-emerald-700, .text-emerald-800 { color: #34d399 !important; }
        
        /* Badges/Percentage background overrides */
        .bg-blue-100, .bg-emerald-100, .bg-pink-100 { background-color: #1e3a8a !important; }
        .bg-blue-200, .bg-emerald-200, .bg-pink-200 { background-color: #312e81 !important; }

        .tab-active, button.tab-active {
            background-color: var(--theme-primary) !important;
            color: #ffffff !important;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }

        input, select, button.bg-slate-100 { 
            background-color: #0f172a !important; 
            border-color: #334155 !important; 
            color: #ffffff !important; 
        }
        button.bg-slate-100:hover { background-color: #1e293b !important; }
    </style>
</head>
<body class="theme-dark-bg antialiased text-slate-100 w-full p-0 m-0">
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
        $pekerjaan = $categories->where('slug', 'mata-pencaharian')->first();
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
            
            {{-- GLASSMORPHIC HERO HEADER --}}
            <div class="relative overflow-hidden rounded-3xl theme-card-bg border p-8 md:p-10 shadow-2xl flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-5">
                    <div class="h-16 w-16 shrink-0 rounded-2xl bg-slate-800/80 border border-slate-700 p-2">
                        <img src="{{ $logoUrl }}" class="h-full w-full object-contain rounded-lg" alt="Logo">
                    </div>
                    <div>
                        <span class="text-blue-400 text-[10px] font-black uppercase tracking-widest">Premium Dark Analytics</span>
                        <h1 class="text-2xl md:text-3xl font-black text-white leading-tight uppercase mt-1">Desa {{ $desa->nama_desa }}</h1>
                    </div>
                </div>
                
                <form method="GET" action="" class="m-0 bg-slate-800/80 border border-slate-700 rounded-2xl px-4 py-2 flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-400">Tahun:</span>
                    <select name="tahun" onchange="this.form.submit()" class="bg-transparent border-none focus:ring-0 font-black cursor-pointer text-sm outline-none text-white appearance-none pr-4">
                        @foreach($daftarTahun as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- GLOWING SUMMARY CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['label' => 'Total Penduduk', 'val' => $totalJiwa, 'icon' => '<svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 18a11.374 11.374 0 01-9.333-2.978m14.25-6.216a4.5 4.5 0 00-.43-3.74m-.43 3.74c-.08.18-.19.35-.32.5M14.25 9.07c.5.5 1.124.746 1.75.746A2.25 2.25 0 0018 7.567a2.25 2.25 0 00-2-2.235m-1.75 3.74c-.08-.18-.19-.35-.32-.5m-1.25 1.5c.5.5 1.125.75 1.75.75 1.243 0 2.25-1.007 2.25-2.25a2.25 2.25 0 00-2.25-2.25c-1.243 0-2.25 1.007-2.25 2.25M1.089 15.022a11.374 11.374 0 019.333-2.978c1.379.167 2.658.621 3.74 1.293m0-12.93a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/></svg>', 'color' => 'from-blue-500/20 to-indigo-500/20 border-blue-500/30'],
                    ['label' => 'Laki-laki', 'val' => $lakiLaki, 'icon' => '<svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>', 'color' => 'from-cyan-500/20 to-blue-500/20 border-cyan-500/30'],
                    ['label' => 'Perempuan', 'val' => $perempuan, 'icon' => '<svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>', 'color' => 'from-pink-500/20 to-rose-500/20 border-pink-500/30'],
                    ['label' => 'Sektor Unggulan', 'val' => $topJob ? $topJob->name : 'N/A', 'icon' => '<svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875c-.621 0-1.125-.504-1.125-1.125v-4.25m16.5 0a2.25 2.25 0 00-1.883-2.212c-1.38-.203-2.793-.318-4.237-.344m-5.46 0A22.912 22.912 0 004.883 12c-1.126.166-1.883 1.1-1.883 2.212m16.5 0v-.003c0-.07-.024-.138-.07-.195m0 0a2.25 2.25 0 01-.19-.434m-16.14.629a2.25 2.25 0 01-.19-.434c-.046-.057-.07-.125-.07-.196v.003m16.5 0a2.25 2.25 0 00-2.25-2.25H4.875a2.25 2.25 0 00-2.25 2.25v.003m18 0A2.25 2.25 0 0018 7.5h-3v-1.5A2.25 2.25 0 0012.75 3.75h-1.5A2.25 2.25 0 009 6v1.5H6a2.25 2.25 0 00-2.25 2.25v.003m18 0v-.003c0-.07-.024-.138-.07-.195a2.25 2.25 0 01-.19-.434m-16.14.629a2.25 2.25 0 01-.19-.434c-.046-.057-.07-.125-.07-.196v.003"/></svg>', 'color' => 'from-amber-500/20 to-orange-500/20 border-amber-500/30', 'text' => true]
                ] as $card)
                    <div class="relative overflow-hidden rounded-3xl border p-6 bg-gradient-to-br {{ $card['color'] }} theme-card-bg flex flex-col justify-between min-h-[140px]">
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ $card['label'] }}</span>
                            <span class="text-lg">{!! $card['icon'] !!}</span>
                        </div>
                        <div class="mt-4">
                            <h3 class="font-black text-white tracking-tight {{ $card['text'] ?? false ? 'text-lg' : 'text-2xl' }}">{{ is_numeric($card['val']) ? number_format($card['val'],0,',','.') : $card['val'] }}</h3>
                            <span class="text-slate-400 text-[10px] block mt-0.5">Jiwa Tercatat</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- DARK TABS --}}
            <div class="bg-slate-800/50 rounded-2xl p-1.5 border border-slate-700 flex flex-wrap gap-1">
                @foreach($categories as $cat)
                    <button type="button" @click="activeTab = '{{ $cat->slug }}'"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all text-slate-400 hover:text-white"
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
