<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa <?= $desa->nama_desa ?? '' ?> - Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @php
        $theme_primary = '#2563eb';
        $theme_secondary = '#1d4ed8';
        $theme_accent = '#f59e0b';
    @endphp
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
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
            background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));
            color: #fff !important;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }
        .card-stat {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-stat:hover {
            transform: translateY(-4px) scale(1.01);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }
        /* Style integration overrides */
        .max-w-7xl, .max-w-6xl, .max-w-5xl { max-width: 100% !important; width: 100% !important; }
        .rounded-\[3rem\] { border-radius: 1.5rem !important; }
        .p-10 { padding: 1.5rem !important; }
        .gap-10 { gap: 1.5rem !important; }
    </style>
</head>
<body class="bg-slate-50 antialiased text-slate-800 w-full p-0 m-0">
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
            
            {{-- HERO HEADER --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-700 via-indigo-800 to-purple-900 text-white p-8 md:p-10 shadow-xl">
                <div class="absolute right-0 top-0 -mt-10 -mr-10 w-80 h-80 rounded-full bg-white/5 blur-2xl"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="h-16 w-16 shrink-0 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 p-2 shadow-lg">
                            <img src="{{ $logoUrl }}" class="h-full w-full object-contain rounded-lg" alt="Logo">
                        </div>
                        <div>
                            <span class="text-blue-300 text-[10px] font-bold uppercase tracking-widest">Portal Data Sektoral</span>
                            <h1 class="text-2xl md:text-3xl font-extrabold uppercase tracking-tight leading-none mt-1">Desa {{ $desa->nama_desa }}</h1>
                            <p class="text-blue-100 text-xs mt-2 font-medium">Kabupaten Belitung Timur &bull; Terbuka, Transparan, Akuntabel</p>
                        </div>
                    </div>
                    
                    <form method="GET" action="" class="m-0 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl px-4 py-3 flex items-center gap-2 text-white">
                        <span class="text-xs font-bold text-blue-200">Tahun Laporan:</span>
                        <select name="tahun" onchange="this.form.submit()" class="bg-transparent border-none focus:ring-0 font-black cursor-pointer text-sm outline-none appearance-none pr-4 [&>option]:text-slate-800">
                            @foreach($daftarTahun as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            {{-- SUMMARY STATS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach([
                    ['label' => 'Total Penduduk', 'val' => $totalJiwa, 'desc' => 'Jiwa Terdaftar', 'bg' => 'bg-white text-slate-800 border-l-4 border-blue-600'],
                    ['label' => 'Laki-laki', 'val' => $lakiLaki, 'desc' => 'Jiwa', 'bg' => 'bg-white text-slate-800 border-l-4 border-indigo-600'],
                    ['label' => 'Perempuan', 'val' => $perempuan, 'desc' => 'Jiwa', 'bg' => 'bg-white text-slate-800 border-l-4 border-pink-500'],
                    ['label' => $pekerjaan ? $pekerjaan->name : 'Data Unggulan', 'val' => $topJob ? $topJob->name : 'N/A', 'desc' => $topJob ? 'Komposisi Tertinggi: '.number_format($topJob->total_value,0,',','.').' '.($pekerjaan && $pekerjaan->slug === 'mata-pencaharian' ? 'Pekerja' : ($topJob->unit ?? 'Jiwa')) : '-', 'bg' => 'bg-white text-slate-800 border-l-4 border-amber-500']
                ] as $card)
                    <div class="card-stat rounded-3xl p-6 bg-white shadow-sm border border-slate-100 flex flex-col justify-between min-h-[140px] {{ $card['bg'] }}">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $card['label'] }}</p>
                        <div class="mt-4">
                            <h3 class="text-2xl font-black text-slate-900 tracking-tight">{{ is_numeric($card['val']) ? number_format($card['val'],0,',','.') : $card['val'] }}</h3>
                            <p class="text-slate-400 text-[10px] font-semibold mt-1">{{ $card['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- TABS NAVIGATION --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-2 shadow-sm flex flex-wrap gap-1">
                @foreach($categories as $cat)
                    <button type="button" @click="activeTab = '{{ $cat->slug }}'"
                        class="flex items-center gap-2 px-5 py-3 rounded-2xl text-xs font-bold transition-all duration-300"
                        :class="activeTab === '{{ $cat->slug }}' ? 'tab-active' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-700'">
                        <span>{{ str_replace('Data ', '', $cat->name) }}</span>
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
