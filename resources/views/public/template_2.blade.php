<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa <?= $desa->nama_desa ?? '' ?> - Classic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;1,300&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @php
        $theme_primary = '#0f766e';
        $theme_secondary = '#115e59';
        $theme_accent = '#d97706';
    @endphp
    <style>
        h1, h2, h3, h4 { font-family: 'Montserrat', sans-serif; }
        body, p, span, td, th { font-family: 'Merriweather', serif; }
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

        .tab-active, button.tab-active {
            background: var(--theme-primary) !important;
            background-color: var(--theme-primary) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 6px -1px rgba(15, 118, 110, 0.2);
        }
        /* Style integration overrides */
        .max-w-7xl, .max-w-6xl, .max-w-5xl { max-width: 100% !important; width: 100% !important; }
        .rounded-\[3rem\] { border-radius: 1rem !important; border: 1px solid #cbd5e1 !important; box-shadow: none !important; }
        .p-10 { padding: 1.5rem !important; }
        .gap-10 { gap: 1.5rem !important; }
        .bg-slate-50 { background-color: #f8fafc !important; }
        .border-slate-100 { border-color: #e2e8f0 !important; }
    </style>
</head>
<body class="bg-[#fcfbf9] antialiased text-slate-800 w-full p-0 m-0">
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
            
            {{-- HEADER CLASSIC --}}
            <div class="border-b-4 border-double border-teal-800 pb-6 flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-4">
                    <img src="{{ $logoUrl }}" class="h-16 w-16 object-contain" alt="Logo">
                    <div class="text-left">
                        <span class="text-xs uppercase font-bold tracking-widest text-teal-700">Laporan Resmi Sektoral</span>
                        <h1 class="text-2xl md:text-3xl font-bold text-teal-900 leading-tight">Kantor Desa {{ $desa->nama_desa }}</h1>
                    </div>
                </div>
                
                <form method="GET" action="" class="m-0 flex items-center gap-3 border border-teal-800/20 rounded-xl px-4 py-2 bg-teal-50/50">
                    <span class="text-xs font-bold text-teal-800">Tahun:</span>
                    <select name="tahun" onchange="this.form.submit()" class="bg-transparent border-none focus:ring-0 font-bold cursor-pointer text-sm outline-none">
                        @foreach($daftarTahun as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- SUMMARY BOXES --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach([
                    ['label' => 'Total Penduduk', 'val' => $totalJiwa, 'unit' => 'Jiwa'],
                    ['label' => 'Laki-laki', 'val' => $lakiLaki, 'unit' => 'Jiwa'],
                    ['label' => 'Perempuan', 'val' => $perempuan, 'unit' => 'Jiwa'],
                    ['label' => $pekerjaan ? $pekerjaan->name : 'Data Unggulan', 'val' => $topJob ? $topJob->name : 'N/A', 'unit' => $topJob ? 'Komposisi Tertinggi: '.number_format($topJob->total_value,0,',','.').' '.($pekerjaan && $pekerjaan->slug === 'mata-pencaharian' ? 'Orang' : ($topJob->unit ?? 'Jiwa')) : '-']
                ] as $card)
                    <div class="bg-white border border-slate-200 p-5 rounded-2xl flex flex-col justify-between shadow-sm">
                        <span class="text-[9px] uppercase font-bold tracking-widest text-teal-600 border-b border-dashed border-teal-100 pb-1.5">{{ $card['label'] }}</span>
                        <div class="mt-4">
                            <h3 class="text-xl font-bold text-slate-800 tracking-tight">{{ is_numeric($card['val']) ? number_format($card['val'],0,',','.') : $card['val'] }}</h3>
                            <span class="text-slate-400 text-[10px] italic mt-1 block">{{ $card['unit'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- CLASSIC TABS --}}
            <div class="flex flex-wrap gap-2 border-b border-teal-800/10 pb-2">
                @foreach($categories as $cat)
                    <button type="button" @click="activeTab = '{{ $cat->slug }}'"
                        class="px-4 py-2.5 rounded-xl text-xs font-semibold border border-slate-200 transition-all bg-white hover:bg-slate-50 text-slate-600"
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
