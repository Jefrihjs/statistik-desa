<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa <?= $desa->nama_desa ?? '' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }

        .tab-active {
            background: linear-gradient(135deg, var(--hdr), var(--acc));
            color: #fff !important;
            box-shadow: 0 4px 20px color-mix(in srgb, var(--hdr) 35%, transparent);
        }

        .card-stat {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease;
        }
        .card-stat:hover { transform: translateY(-3px); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .anim-up { animation: fadeUp 0.4s ease both; }
        .anim-d1 { animation-delay: 0.05s; }
        .anim-d2 { animation-delay: 0.1s; }
        .anim-d3 { animation-delay: 0.15s; }
        .anim-d4 { animation-delay: 0.2s; }

        .tab-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 transparent;
        }
        .tab-scroll::-webkit-scrollbar {
            height: 3px;
        }
        .tab-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .tab-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
    </style>
</head>
<body class="antialiased text-slate-800">

    @php
        $headerColor = $desa->header_color ?? '#0f172a';
        $accentColor = $desa->accent_color ?? '#0f766e';
        $layoutType = $desa->layout_type ?? 'default';
    @endphp

<?php
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

    $featuredCategory = null;
    if (!empty($desa->featured_category_id)) {
        $featuredCategory = $categories->where('id', $desa->featured_category_id)->first();
    }

    $logoUrl = $desa->logo_desa
        ? asset('storage/'.$desa->logo_desa)
        : ($desa->logo
            ? asset('storage/'.$desa->logo)
            : 'https://www.beltim.go.id/images/sekilas_beltim/lambang_daerah/logoBeltim.png');
    $firstTab = $categories->first() ? $categories->first()->slug : '';
?>

<div class="w-full" x-data="{ activeTab: '<?= $firstTab ?>' }" style="--hdr: <?= $headerColor ?>; --acc: <?= $accentColor ?>;">

    <div class="w-full px-4 sm:px-6 py-5 space-y-4">

        {{-- HEADER BAR --}}
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 shrink-0 rounded-xl bg-white/15 backdrop-blur-md border border-white/20 p-1 shadow" style="background: color-mix(in srgb, <?= $headerColor ?> 10%, white); border-color: color-mix(in srgb, <?= $headerColor ?> 15%, transparent);">
                    <img src="<?= $logoUrl ?>" class="h-full w-full object-contain rounded-lg" alt="Logo">
                </div>
                <div>
                    <h1 class="text-base lg:text-lg font-black uppercase tracking-tight leading-tight" style="color: <?= $headerColor ?>;">
                        Statistik Desa <?= $desa->nama_desa ?? '' ?>
                    </h1>
                    <p class="text-slate-400 text-[10px] font-semibold tracking-wide">Statistik Sektoral Kab. Belitung Timur</p>
                </div>
            </div>
            <form method="GET" action="" class="m-0 flex items-center gap-2 text-xs font-semibold rounded-xl px-3 py-2 border" style="border-color: color-mix(in srgb, <?= $headerColor ?> 15%, transparent); color: <?= $headerColor ?>;">
                <svg class="w-3.5 h-3.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <select name="tahun" onchange="this.form.submit()" class="bg-transparent border-none focus:ring-0 font-bold cursor-pointer p-0 appearance-none [&>option]:text-slate-800">
                    @foreach($daftarTahun as $y)
                        <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>>Tahun <?= $y ?></option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- SUMMARY CARDS --}}
        @if($layoutType == 'infographic')
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                @foreach([
                    ['label' => 'Total Penduduk', 'val' => $totalJiwa, 'unit' => 'Jiwa', 'bg' => "linear-gradient(145deg, $headerColor, color-mix(in srgb, $headerColor 70%, $accentColor))"],
                    ['label' => 'Laki-laki', 'val' => $lakiLaki, 'unit' => 'Jiwa', 'bg' => 'linear-gradient(145deg, #0f766e, #065f46)'],
                    ['label' => 'Perempuan', 'val' => $perempuan, 'unit' => 'Jiwa', 'bg' => 'linear-gradient(145deg, #7c3aed, #5b21b6)'],
                    ['label' => 'Sektor Dominan', 'val' => $topJob ? $topJob->name : 'Menunggu Data', 'unit' => $topJob ? number_format($topJob->total_value,0,',','.').' Jiwa' : '-', 'bg' => 'linear-gradient(145deg, #ea580c, #b91c1c)', 'small' => true]
                ] as $i => $c)
                <div class="card-stat rounded-2xl text-white p-4 flex flex-col justify-between min-h-[130px] shadow-lg anim-up anim-d{{ $i+1 }}" style="background: {{ $c['bg'] }};">
                    <div class="absolute -right-6 -bottom-6 w-20 h-20 rounded-full bg-white/[0.07]"></div>
                    <p class="text-[8px] font-black uppercase tracking-[0.18em] text-white/55 relative z-10">{{ $c['label'] }}</p>
                    <div class="relative z-10 mt-auto">
                        <h2 class="font-black tracking-tight leading-none {{ $c['small'] ?? false ? 'text-base' : 'text-3xl' }}"><?= is_numeric($c['val']) ? number_format($c['val'],0,',','.') : $c['val'] ?></h2>
                        <p class="text-white/50 text-[9px] font-semibold mt-1">{{ $c['unit'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                @php
                    $cards = [
                        ['label' => 'Total Penduduk', 'val' => $totalJiwa, 'color' => $headerColor, 'pct' => 100],
                        ['label' => 'Laki-laki', 'val' => $lakiLaki, 'color' => $accentColor, 'pct' => $totalJiwa > 0 ? round(($lakiLaki/$totalJiwa)*100) : 0],
                        ['label' => 'Perempuan', 'val' => $perempuan, 'color' => '#7c3aed', 'pct' => $totalJiwa > 0 ? round(($perempuan/$totalJiwa)*100) : 0],
                        ['label' => 'Sektor Dominan', 'val' => $topJob ? $topJob->name : 'Menunggu Data', 'color' => '#ea580c', 'pct' => 100, 'sub' => $topJob ? number_format($topJob->total_value,0,',','.').' Jiwa' : '-', 'text' => true]
                    ];
                @endphp
                @foreach($cards as $i => $c)
                <div class="card-stat rounded-2xl bg-white border p-4 flex flex-col gap-2.5 shadow-sm anim-up anim-d{{ $i+1 }}" style="border-color: color-mix(in srgb, {{ $c['color'] }} 10%, transparent);">
                    <div class="flex items-center justify-between">
                        <p class="text-[8px] font-black uppercase tracking-[0.15em]" style="color: color-mix(in srgb, {{ $c['color'] }} 65%, black);">{{ $c['label'] }}</p>
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: color-mix(in srgb, {{ $c['color'] }} 8%, white); color: {{ $c['color'] }};">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h2 class="{{ $c['text'] ?? false ? 'text-base' : 'text-2xl' }} font-black text-slate-900 tracking-tight leading-none truncate" title="{{ is_numeric($c['val']) ? '' : $c['val'] }}">
                            @if(is_numeric($c['val']))
                                {{ number_format($c['val'], 0, ',', '.') }}
                            @else
                                {{ $c['val'] }}
                            @endif
                        </h2>
                        <p class="text-slate-400 text-[9px] font-semibold mt-0.5">{{ $c['sub'] ?? 'Jiwa' }}</p>
                    </div>
                    <div class="h-1 rounded-full overflow-hidden" style="background: color-mix(in srgb, {{ $c['color'] }} 8%, white);">
                        <div class="h-full rounded-full" style="width: {{ $c['pct'] }}%; background: {{ $c['color'] }};"></div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif

        {{-- DATA UNGGULAN --}}
        @if($featuredCategory)
        <div class="rounded-2xl bg-white border shadow-sm p-5 relative overflow-hidden anim-up" style="border-color: color-mix(in srgb, <?= $headerColor ?> 10%, transparent);">
            <div class="absolute right-0 top-0 text-[8px] font-black uppercase tracking-[0.18em] px-4 py-1.5 rounded-bl-xl shadow-sm text-white flex items-center gap-1" style="background: linear-gradient(135deg, <?= $accentColor ?>, color-mix(in srgb, <?= $accentColor ?> 70%, <?= $headerColor ?>));">
                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                Unggulan
            </div>
            <div class="mb-4 pr-28">
                <h3 class="text-xs font-black text-slate-700 uppercase tracking-wide">{{ $featuredCategory->name }}</h3>
            </div>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-2">
                @foreach($featuredCategory->indicators as $ind)
                    @php $indVal = $ind->statistics->where('year', $tahun)->sum('value'); @endphp
                    <div class="rounded-xl p-2.5 text-center border hover:shadow-sm transition-all" style="background: color-mix(in srgb, <?= $headerColor ?> 3%, white); border-color: color-mix(in srgb, <?= $headerColor ?> 8%, transparent);">
                        <p class="text-[8px] font-bold truncate mb-1" style="color: color-mix(in srgb, <?= $headerColor ?> 50%, black);">{{ $ind->name }}</p>
                        <p class="text-base font-black text-slate-900 tracking-tight"><?= number_format($indVal, 0, ',', '.') ?></p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- TABS --}}
        <div class="rounded-2xl bg-white border border-slate-100 shadow-sm anim-up tab-scroll">
                <div class="flex min-w-max px-2 py-1.5 gap-1">
                @foreach($categories as $cat)
                    <button type="button"
                        @click="activeTab = '<?= $cat->slug ?>'"
                        class="flex items-center gap-1.5 px-3.5 py-2 rounded-xl min-w-max text-[10px] font-bold transition-all duration-300 whitespace-nowrap"
                        :class="activeTab === '<?= $cat->slug ?>' ? 'tab-active' : 'text-slate-400 hover:bg-slate-50 hover:text-slate-600'">
                        <div class="w-5 h-5 rounded-lg flex items-center justify-center shrink-0 transition-all duration-300"
                             :class="activeTab === '<?= $cat->slug ?>' ? '' : 'bg-slate-100'"
                             :style="activeTab === '<?= $cat->slug ?>' ? 'background:rgba(255,255,255,0.2);color:white;' : ''">
                            @if($cat->slug == 'demografi')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            @elseif($cat->slug == 'mata-pencaharian')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            @elseif($cat->slug == 'pendidikan')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                            @elseif($cat->slug == 'agama')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9 9 0 100-18 9 9 0 000 18z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                            @else
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            @endif
                        </div>
                        <span><?= str_replace('Data ', '', $cat->name) ?></span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- TAB CONTENT --}}
        <div class="pb-4">
            @foreach($categories as $cat)
                <div x-show="activeTab === '<?= $cat->slug ?>'" x-cloak>
                    <?php $viewPath = "frontend.desa.tabs." . $cat->slug; ?>
                    @if(view()->exists($viewPath))
                        @include($viewPath, ['cat' => $cat, 'desa' => $desa, 'tahun' => $tahun])
                    @else
                        <div class="rounded-2xl bg-white border border-slate-100 p-10 text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tab ini belum tersedia.</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    </div>

</div>

</body>
</html>