<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa <?= $desa->nama_desa ?? '' ?> - Kreatif</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    @php
        $theme_primary = '#ec4899';
        $theme_secondary = '#f43f5e';
        $theme_accent = '#eab308';
    @endphp
    <style>
        * { font-family: 'Fredoka', sans-serif; }
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
            background: linear-gradient(to right, var(--theme-primary), var(--theme-secondary));
            color: #fff !important;
            box-shadow: 0 10px 15px -3px rgba(236, 72, 153, 0.3);
        }
        .bubbly-card {
            border-radius: 2.5rem;
            transition: transform 0.2s ease-out;
        }
        .bubbly-card:hover {
            transform: scale(1.03) rotate(0.5deg);
        }
        /* Style integration overrides for Creative */
        .max-w-7xl, .max-w-6xl, .max-w-5xl { max-width: 100% !important; width: 100% !important; }
        .rounded-\[3rem\] { border-radius: 2rem !important; border: 2px solid #fecdd3 !important; box-shadow: none !important; }
        .p-10 { padding: 1.5rem !important; }
        .gap-10 { gap: 1.5rem !important; }
    </style>
</head>
<body class="bg-rose-50/30 antialiased text-slate-800 w-full p-0 m-0">
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
            
            {{-- CREATIVE HEADER --}}
            <div class="bubbly-card bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500 text-white p-8 shadow-xl flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-5">
                    <div class="h-16 w-16 bg-white rounded-3xl p-2 flex items-center justify-center shadow-lg">
                        <img src="{{ $logoUrl }}" class="h-full w-full object-contain" alt="Logo">
                    </div>
                    <div class="text-left">
                        <span class="text-xs uppercase font-bold tracking-widest text-pink-100">Info Desa Ceria</span>
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-wide mt-1">Desa {{ $desa->nama_desa }}</h1>
                    </div>
                </div>
                
                <form method="GET" action="" class="m-0 bg-white/20 border border-white/30 rounded-full px-4 py-2 flex items-center gap-2">
                    <span class="text-xs font-bold text-pink-100">Pilih Tahun:</span>
                    <select name="tahun" onchange="this.form.submit()" class="bg-transparent border-none focus:ring-0 font-bold cursor-pointer text-sm outline-none appearance-none pr-4">
                        @foreach($daftarTahun as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }} class="text-slate-800">{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- SUMMARY BUBBLES --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach([
                    ['label' => 'Total Warga', 'val' => $totalJiwa, 'desc' => 'Jiwa', 'color' => 'bg-pink-100 text-pink-700'],
                    ['label' => 'Laki-laki', 'val' => $lakiLaki, 'desc' => 'Jiwa', 'color' => 'bg-sky-100 text-sky-700'],
                    ['label' => 'Perempuan', 'val' => $perempuan, 'desc' => 'Jiwa', 'color' => 'bg-rose-100 text-rose-700'],
                    ['label' => $pekerjaan ? $pekerjaan->name : 'Data Unggulan', 'val' => $topJob ? $topJob->name : 'N/A', 'desc' => $topJob ? 'Komposisi Tertinggi: '.number_format($topJob->total_value,0,',','.').' '.($pekerjaan && $pekerjaan->slug === 'mata-pencaharian' ? 'Pekerja' : ($topJob->unit ?? 'Jiwa')) : '-', 'color' => 'bg-amber-100 text-amber-700', 'text' => true]
                ] as $card)
                    <div class="bubbly-card p-6 {{ $card['color'] }} shadow-sm flex flex-col justify-between min-h-[130px]">
                        <span class="text-[9px] uppercase font-bold tracking-wider opacity-85">{{ $card['label'] }}</span>
                        <div class="mt-4">
                            <h3 class="font-extrabold tracking-tight {{ $card['text'] ?? false ? 'text-base' : 'text-2xl' }}">{{ is_numeric($card['val']) ? number_format($card['val'],0,',','.') : $card['val'] }}</h3>
                            <span class="text-[9px] block mt-0.5 opacity-60">{{ $card['desc'] ?? 'Jiwa' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- CREATIVE TABS --}}
            <div class="bg-white rounded-[2rem] p-2 shadow-sm flex flex-wrap gap-1 border border-pink-100">
                @foreach($categories as $cat)
                    <button type="button" @click="activeTab = '{{ $cat->slug }}'"
                        class="px-5 py-3 rounded-[1.5rem] text-xs font-semibold transition-all text-slate-400 hover:text-slate-600"
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
