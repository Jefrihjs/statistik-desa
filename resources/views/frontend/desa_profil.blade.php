<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa <?= $desa->nama_desa ?? '' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        [x-cloak] { display: none !important; }
        /* Scrollbar custom untuk tabel di dalam tab */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
    <style>
    .stat-card {
        position: relative;
        overflow: hidden;
        border-radius: 1.25rem;
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .stat-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 2px;
        background: linear-gradient(
            120deg,
            transparent,
            var(--card-glow),
            transparent
        );
        background-size: 220% 220%;
        opacity: 0;
        transition: opacity .25s ease;
        animation: borderDraw 2.5s linear infinite;

        -webkit-mask:
            linear-gradient(#fff 0 0) content-box,
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 22px 45px rgba(15, 23, 42, .12);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    @keyframes borderDraw {
        0% {
            background-position: 0% 50%;
        }
        100% {
            background-position: 220% 50%;
        }
    }
</style>
</head>
<body class="antialiased text-slate-800">
    @php
        $headerColor = $desa->header_color ?? '#2563eb';
        $accentColor = $desa->accent_color ?? '#10b981';
    @endphp
<?php
    // KALKULASI DATA SUMMARY KARTU ATAS (Murni PHP agar tidak ParseError di Blade)
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
    
    $logoUrl = $desa->logo ? asset('storage/'.$desa->logo) : 'https://www.beltim.go.id/images/sekilas_beltim/lambang_daerah/logoBeltim.png';
    $firstTab = $categories->first() ? $categories->first()->slug : '';
?>

<div class="w-full space-y-6" x-data="{ activeTab: '<?= $firstTab ?>' }">

    <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col md:flex-row justify-between items-center gap-4 border border-slate-100"style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }}); border-radius: 2.5rem; padding: 35px; color: white; margin-bottom: 2rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); position: relative; overflow: hidden;">
        <div style="position: absolute; right: -50px; top: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div class="flex items-center gap-4">
            <img src="<?= $logoUrl ?>" class="h-14 w-14 object-contain shrink-0 drop-shadow-md" alt="Logo">
            <div>
                <h1 class="text-white text-2xl font-bold text-slate-900">Statistik Desa <?= $desa->nama_desa ?? '' ?></h1>
                <p class="text-white text-sm mt-1">Statistik Sektoral Kab. Belitung Timur</p>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <div class="flex items-center gap-2 text-sm font-semibold bg-slate-50 border border-slate-200 py-2 px-4 rounded-lg text-slate-700">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <form method="GET" action="" class="m-0">
                    <select name="tahun" onchange="this.form.submit()" class="bg-transparent border-none focus:ring-0 font-bold cursor-pointer text-slate-700 p-0 pl-1">
                        @foreach($daftarTahun as $y)
                            <option value="<?= $y ?>" <?= $tahun == $y ? 'selected' : '' ?>>Tahun <?= $y ?></option>
                        @endforeach
                    </select>
                </form>
            </div>
            <button class="flex items-center px-4 py-2 bg-blue-50 text-blue-600 font-semibold rounded-lg hover:bg-blue-100 transition border border-blue-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Unduh Data
            </button>
        </div>
    </div>

    @if($desa->welcome_message)
    <div class="p-5 bg-blue-50/50 border border-blue-100 rounded-2xl flex items-start gap-4">
        <div class="text-blue-500 mt-0.5"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
        <div>
            <h3 class="font-bold text-slate-800 text-base mb-1">Informasi Desa</h3>
            <p class="text-slate-600 text-sm italic">"<?= $desa->welcome_message ?>"</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card bg-blue-50 border border-blue-100 rounded-2xl p-5 flex justify-between items-center"
     style="--card-glow:#2563eb;">
            <div>
                <p class="text-blue-600 font-semibold text-sm mb-1">Total Penduduk</p>
                <h2 class="text-3xl font-bold text-slate-900"><?= number_format($totalJiwa, 0, ',', '.') ?></h2>
                <p class="text-slate-500 text-xs mt-1">Jiwa</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-2xl p-5 flex justify-between items-center">
            <div>
                <p class="text-green-600 font-semibold text-sm mb-1">Laki-laki</p>
                <h2 class="text-3xl font-bold text-slate-900"><?= number_format($lakiLaki, 0, ',', '.') ?></h2>
                <p class="text-slate-500 text-xs mt-1">Jiwa</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>
        <div class="bg-purple-50 border border-purple-100 rounded-2xl p-5 flex justify-between items-center">
            <div>
                <p class="text-purple-600 font-semibold text-sm mb-1">Perempuan</p>
                <h2 class="text-3xl font-bold text-slate-900"><?= number_format($perempuan, 0, ',', '.') ?></h2>
                <p class="text-slate-500 text-xs mt-1">Jiwa</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 flex justify-between items-center">
            <div class="overflow-hidden">
                <p class="text-amber-700 font-semibold text-sm mb-1 truncate">Sektor Dominan</p>
                <h2 class="text-xl md:text-2xl font-bold text-slate-900 truncate"><?= $topJob ? $topJob->name : 'Menunggu Data' ?></h2>
                <p class="text-slate-500 text-xs mt-1"><?= $topJob ? number_format($topJob->total_value, 0, ',', '.') . ' Jiwa' : '-' ?></p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-x-auto">
    <div class="flex min-w-max px-2 py-2 gap-1">
        @foreach($categories as $cat)
            <button type="button"
                @click="activeTab = '<?= $cat->slug ?>'"
                :class="activeTab === '<?= $cat->slug ?>'
                    ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20'
                    : 'bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-700'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl min-w-max transition duration-300 group">

                <div class="shrink-0 transition-transform duration-300 group-hover:scale-105"
                    :class="activeTab === '<?= $cat->slug ?>' ? 'text-white' : 'text-slate-400 group-hover:text-slate-600'">

                    @if($cat->slug == 'demografi')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>

                    @elseif($cat->slug == 'usia-detail' || $cat->slug == 'penduduk-per-tahun-usia')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M9 17V9m4 8V5m4 12v-6"></path>
                        </svg>

                    @elseif($cat->slug == 'kelompok-usia')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v10h10A10 10 0 0011 3z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13H3a10 10 0 1010-10v10z"></path>
                        </svg>

                    @elseif($cat->slug == 'mata-pencaharian')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>

                    @elseif($cat->slug == 'pendidikan')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>

                    @elseif($cat->slug == 'agama')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>

                    @elseif($cat->slug == 'tenaga-kerja')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>

                    @elseif($cat->slug == 'etnis')
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3C7.02944 3 3 7.02944 3 12C3 12.8168 3.1088 13.6081 3.31269 14.3603C3.72385 14.0549 4.18033 13.7872 4.67874 13.5718C4.25207 12.9917 3.99999 12.2753 3.99999 11.5C3.99999 9.567 5.56699 8 7.49999 8C9.43298 8 11 9.567 11 11.5C11 12.2753 10.7479 12.9918 10.3212 13.5718C10.7765 13.7685 11.1973 14.009 11.5808 14.2826C11.5933 14.2916 11.6057 14.3008 11.6177 14.3103C12.021 13.878 12.4936 13.4824 13.0284 13.1452C12.0977 12.4128 11.5 11.2762 11.5 10C11.5 7.79086 13.2908 6 15.5 6C17.7091 6 19.5 7.79086 19.5 10C19.5 10.8095 19.2595 11.5629 18.8461 12.1925C19.6192 12.3672 20.3212 12.6528 20.9432 13.0164C20.9807 12.6828 21 12.3436 21 12C21 7.02944 16.9706 3 12 3ZM10.4907 15.9573C10.4664 15.9429 10.4426 15.9274 10.4192 15.9107C9.65816 15.3678 8.67891 15 7.49999 15C6.06158 15 4.91073 15.5491 4.09526 16.3065C5.622 19.1029 8.58946 21 12 21C15.8853 21 19.1956 18.538 20.4559 15.089C20.4386 15.0778 20.4216 15.066 20.4048 15.0536C19.5686 14.4343 18.4544 14 17.0906 14C13.7836 14 12 16.529 12 18C12 18.5523 11.5523 19 11 19C10.4477 19 9.99999 18.5523 9.99999 18C9.99999 17.3385 10.1699 16.6377 10.4907 15.9573ZM1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12ZM15.5 8C14.3954 8 13.5 8.89543 13.5 10C13.5 11.1046 14.3954 12 15.5 12C16.6046 12 17.5 11.1046 17.5 10C17.5 8.89543 16.6046 8 15.5 8ZM5.99999 11.5C5.99999 10.6716 6.67156 10 7.49999 10C8.32841 10 8.99999 10.6716 8.99999 11.5C8.99999 12.3284 8.32841 13 7.49999 13C6.67156 13 5.99999 12.3284 5.99999 11.5Z"/>
                        </svg>

                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    @endif

                </div>

                <span class="text-xs font-bold whitespace-nowrap">
                    <?= str_replace('Data ', '', $cat->name) ?>
                </span>
            </button>
        @endforeach
    </div>
</div>

    <div class="pb-12 mt-6">
        @foreach($categories as $cat)
            <div x-show="activeTab === '<?= $cat->slug ?>'" x-cloak>
                <?php $viewPath = "frontend.desa.tabs." . $cat->slug; ?>
                @if(view()->exists($viewPath))
                    @include($viewPath, ['cat' => $cat, 'desa' => $desa, 'tahun' => $tahun])
                @else
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-10 text-center text-slate-500">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <p class="font-semibold">Data tab belum tersedia.</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

</div>

</body>
</html>