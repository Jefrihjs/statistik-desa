<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa <?= $desa->nama_desa ?? '' ?> - Minimalis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @php
        $theme_primary = '#1c1917';
        $theme_secondary = '#44403c';
        $theme_accent = '#78716c';
    @endphp
    <style>
        h1, h2, h3 { font-family: 'Cinzel', serif; }
        * { font-family: 'Inter', sans-serif; }
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
            border-bottom: 2px solid var(--theme-primary);
            color: #000 !important;
            font-weight: 700;
        }
        /* Style integration overrides for Minimalist */
        .max-w-7xl, .max-w-6xl, .max-w-5xl { max-width: 100% !important; width: 100% !important; }
        .shadow-2xl, .shadow-sm { box-shadow: none !important; }
        .border-slate-100, .border-slate-200 { border-color: #e5e5e0 !important; }
        .rounded-\[3rem\] { border-radius: 0px !important; border: 1px solid #e5e5e0 !important; }
        .p-10 { padding: 1.5rem !important; }
        .gap-10 { gap: 1.5rem !important; }
        .bg-slate-50 { background-color: #fafaf9 !important; }
    </style>
</head>
<body class="bg-white antialiased text-stone-900 w-full p-0 m-0">
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
            
            {{-- MINIMAL HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-stone-200 pb-6">
                <div>
                    <h1 class="text-3xl font-bold uppercase tracking-wide text-stone-950">Desa {{ $desa->nama_desa }}</h1>
                    <p class="text-stone-400 text-xs font-semibold uppercase tracking-widest mt-1">Data Sektoral Desa</p>
                </div>
                
                <form method="GET" action="" class="m-0 border-b border-stone-900 pb-1">
                    <select name="tahun" onchange="this.form.submit()" class="bg-transparent border-none focus:ring-0 font-bold cursor-pointer text-sm outline-none">
                        @foreach($daftarTahun as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- MINIMAL METRICS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach([
                    ['label' => 'TOTAL RESIDENTS', 'val' => $totalJiwa],
                    ['label' => 'MALE', 'val' => $lakiLaki],
                    ['label' => 'FEMALE', 'val' => $perempuan],
                    ['label' => 'PRIMARY SECTOR', 'val' => $topJob ? $topJob->name : 'N/A']
                ] as $card)
                    <div class="border border-stone-200 p-6 rounded-none flex flex-col justify-between min-h-[120px]">
                        <span class="text-[8px] font-bold tracking-widest text-stone-400 block">{{ $card['label'] }}</span>
                        <h3 class="text-xl font-bold text-stone-900 mt-4 tracking-tight">{{ is_numeric($card['val']) ? number_format($card['val'],0,',','.') : $card['val'] }}</h3>
                    </div>
                @endforeach
            </div>

            {{-- TEXT-ONLY TABS --}}
            <div class="flex flex-wrap gap-6 border-b border-stone-200">
                @foreach($categories as $cat)
                    <button type="button" @click="activeTab = '{{ $cat->slug }}'"
                        class="pb-3 text-xs font-semibold text-stone-400 hover:text-stone-900 transition-colors uppercase tracking-widest"
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
