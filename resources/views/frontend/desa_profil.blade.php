<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa {{ $desa->nama_desa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 antialiased">

@php
    $headerColor = $desa->header_color ?? '#1e3a8a';
    $accentColor = $desa->accent_color ?? '#2563eb';
@endphp

{{-- 1. HEADER PREMIUM DENGAN GRADIENT & PATTERN --}}
{{-- 1. HEADER DENGAN PATTERN BATIK & GRADIENT --}}
<div class="relative z-0 pt-20 pb-32 px-4 shadow-2xl" 
     style="background: linear-gradient(135deg, {{ $headerColor }} 0%, {{ $accentColor }} 100%);">
    
    {{-- Overlay Pattern Batik Lokal --}}
    <div class="absolute inset-0 opacity-10" 
         style="background-image: url('{{ asset('img/batik.png') }}'); background-repeat: repeat; background-size: 300px;">
    </div>

    <div class="relative z-10 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
        <div class="text-center md:text-left">
            <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter italic drop-shadow-xl">
                Profil Desa {{ $desa->nama_desa }}
            </h1>
            <div class="mt-4 flex flex-col md:flex-row items-center gap-4">
                <p class="font-bold text-lg uppercase tracking-[0.3em] text-white/80">
                    Statistik Sektoral Kab. Belitung Timur
                </p>
                
                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-1 rounded-2xl">
                    <form method="GET" action="">
                        <select name="tahun" onchange="this.form.submit()" 
                                class="bg-transparent text-white border-none focus:ring-0 font-black uppercase text-xs cursor-pointer pr-8">
                            @foreach($daftarTahun as $y)
                                <option value="{{ $y }}" {{ (int)$tahun === (int)$y ? 'selected' : '' }} class="text-slate-800 font-bold">
                                    Tahun {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <img src="{{ $desa->logo ? asset('storage/'.$desa->logo) : 'https://www.beltim.go.id/images/sekilas_beltim/lambang_daerah/logoBeltim.png' }}"
             class="h-32 drop-shadow-2xl hover:scale-105 transition-transform duration-500"
             alt="Logo">
    </div>
</div>

{{-- 2. KONTINER KARTU (FIX TERTIMPA & VISIBILITAS) --}}
<div class="relative z-50 max-w-7xl mx-auto px-4 -mt-20">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
        
        @php
            // Hitung Populasi
            $demografi = $categories->where('slug', 'demografi')->first();
            $totalJiwa = 0;
            if ($demografi) {
                $totalJiwa = $demografi->indicators->whereIn('name', ['Laki-laki', 'Perempuan']) 
                    ->sum(fn($i) => $i->statistics->where('year', $tahun)->sum('value'));
            }

            // Hitung Ekonomi
            $pekerjaan = $categories->where('slug', 'mata-pencaharian')->first();
            $topJob = null;
            if ($pekerjaan) {
                $topJob = $pekerjaan->indicators->map(function ($ind) use ($tahun) {
                    $ind->total_value = $ind->statistics->where('year', $tahun)->sum('value');
                    return $ind;
                })->sortByDesc('total_value')->first();
            }
        @endphp

        <!-- Kartu Populasi -->
        <div class="group bg-white p-10 rounded-[3rem] shadow-2xl border-b-8 transition-all hover:-translate-y-2 flex flex-col justify-between" 
             style="border-color: {{ $headerColor }}; min-height: 300px;">
            <div>
                <div class="flex justify-between items-start mb-6">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: {{ $headerColor }};">Kependudukan</span>
                    <div class="p-3 rounded-2xl bg-slate-50 text-slate-400 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <h4 class="text-xl font-bold text-slate-800 leading-tight">Total Penduduk Desa {{ $desa->nama_desa }}</h4>
            </div>
            
            <div class="mt-6">
                @if($totalJiwa > 0)
                    <div class="flex items-baseline gap-2">
                        <span class="text-5xl font-black tracking-tighter" style="color: {{ $headerColor }};">{{ number_format($totalJiwa, 0, ',', '.') }}</span>
                        <span class="text-slate-500 font-bold uppercase text-xs">Jiwa</span>
                    </div>
                    <p class="mt-4 text-slate-500 italic text-sm border-t border-slate-50 pt-4">"Data kependudukan terverifikasi tahun {{ $tahun }}."</p>
                @else
                    <div class="py-3 px-4 bg-amber-50 rounded-2xl border border-amber-100 flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></div>
                        <span class="text-amber-700 text-[10px] font-black uppercase tracking-widest">Sinkronisasi Data {{ $tahun }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kartu Ekonomi (Fix Kontras & Symmetry) -->
        <div class="group bg-white p-10 rounded-[3rem] shadow-2xl border-b-8 transition-all hover:-translate-y-2 flex flex-col justify-between" 
             style="border-color: {{ $accentColor }}; min-height: 300px;">
            <div>
                <div class="flex justify-between items-start mb-6">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]" style="color: {{ $headerColor }};">Sektor Ekonomi</span>
                    <div class="p-3 rounded-2xl bg-slate-50 text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h4 class="text-xl font-bold text-slate-800 leading-tight">Mata Pencaharian Dominan</h4>
            </div>

            <div class="mt-6">
                @if($topJob && $topJob->total_value > 0)
                    <div class="flex justify-between items-start mb-6">
                        <span class="text-4xl font-black tracking-tighter uppercase italic leading-none mb-2" style="color: {{ $accentColor }};">
                            {{ $topJob->name }}
                        </span>
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl font-black tracking-tighter" style="color: {{ $headerColor }};">{{ number_format($topJob->total_value, 0, ',', '.') }} </span>
                            <span class="text-slate-500 font-bold uppercase text-xs">Jiwa</span>
                        </div>
                    </div>
                    <p class="mt-4 text-slate-500 italic text-sm border-t border-slate-50 pt-4">"Sektor penggerak utama ekonomi desa."</p>
                @else
                    <div class="py-4 px-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-center">
                        <span class="text-slate-400 text-[10px] font-black uppercase italic tracking-widest text-center block">Informasi Belum Tersedia</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

    {{-- 3. NAVIGASI DENGAN TRANSISI HALUS --}}
    <div x-data="{ activeTab: '{{ $categories->first()->slug ?? '' }}' }" class="pb-24">
        
        <!-- Pesan Narasi Kades -->
        @if($desa->welcome_message)
            <div class="max-w-3xl mx-auto mb-16 p-8 bg-white rounded-[3rem] border-l-8 shadow-xl italic text-slate-600 leading-relaxed" 
                 style="border-color: {{ $accentColor }}">
                "{{ $desa->welcome_message }}"
            </div>
        @endif

        <div class="flex flex-wrap justify-center gap-6 mb-12">
            @foreach($categories as $cat)
                <button
                    type="button"
                    @click="activeTab = '{{ $cat->slug }}'"
                    :class="activeTab === '{{ $cat->slug }}' ? 'bg-white shadow-2xl scale-110 border-b-4' : 'bg-slate-100 text-slate-400 border-transparent hover:bg-white'"
                    style="border-bottom-color: :activeTab === '{{ $cat->slug }}' ? '{{ $accentColor }}' : 'transparent'"
                    class="group flex flex-col items-center justify-center w-24 h-24 md:w-32 md:h-32 rounded-[2.5rem] transition-all duration-500 border-2"
                >
                    <div class="mb-2 transition-transform duration-500 group-hover:rotate-12"
                        :class="activeTab === '{{ $cat->slug }}' ? 'text-blue-600' : 'text-slate-400'">
                        
                        @if($cat->slug == 'demografi')
                            <!-- Ikon Penduduk (Demografi Umum) -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        
                        @elseif($cat->slug == 'usia-detail' || $cat->slug == 'penduduk-per-tahun-usia')
                            <!-- Ikon Chart Batang (Penduduk per Tahun Usia) -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M9 17V9m4 8V5m4 12v-6"></path></svg>
                        
                        @elseif($cat->slug == 'kelompok-usia')
                            <!-- Ikon Pie Chart (Kelompok Umur) -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v10h10A10 10 0 0011 3z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13H3a10 10 0 1010-10v10z"></path></svg>
                        
                        @elseif($cat->slug == 'mata-pencaharian')
                            <!-- Ikon Ekonomi (Mata Pencaharian) -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        
                        @elseif($cat->slug == 'pendidikan')
                            <!-- Ikon Buku (Pendidikan & Status Sekolah) -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        
                        @elseif($cat->slug == 'agama')
                            <!-- Ikon Sparkles (Agama & Kepercayaan) -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        
                        @elseif($cat->slug == 'tenaga-kerja')
                            <!-- Ikon Tas Kerja (Tenaga Kerja) -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        
                        @elseif($cat->slug == 'etnis')
                            <!-- Ikon Grup/Etnis -->
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3C7.02944 3 3 7.02944 3 12C3 12.8168 3.1088 13.6081 3.31269 14.3603C3.72385 14.0549 4.18033 13.7872 4.67874 13.5718C4.25207 12.9917 3.99999 12.2753 3.99999 11.5C3.99999 9.567 5.56699 8 7.49999 8C9.43298 8 11 9.567 11 11.5C11 12.2753 10.7479 12.9918 10.3212 13.5718C10.7765 13.7685 11.1973 14.009 11.5808 14.2826C11.5933 14.2916 11.6057 14.3008 11.6177 14.3103C12.021 13.878 12.4936 13.4824 13.0284 13.1452C12.0977 12.4128 11.5 11.2762 11.5 10C11.5 7.79086 13.2908 6 15.5 6C17.7091 6 19.5 7.79086 19.5 10C19.5 10.8095 19.2595 11.5629 18.8461 12.1925C19.6192 12.3672 20.3212 12.6528 20.9432 13.0164C20.9807 12.6828 21 12.3436 21 12C21 7.02944 16.9706 3 12 3ZM10.4907 15.9573C10.4664 15.9429 10.4426 15.9274 10.4192 15.9107C9.65816 15.3678 8.67891 15 7.49999 15C6.06158 15 4.91073 15.5491 4.09526 16.3065C5.622 19.1029 8.58946 21 12 21C15.8853 21 19.1956 18.538 20.4559 15.089C20.4386 15.0778 20.4216 15.066 20.4048 15.0536C19.5686 14.4343 18.4544 14 17.0906 14C13.7836 14 12 16.529 12 18C12 18.5523 11.5523 19 11 19C10.4477 19 9.99999 18.5523 9.99999 18C9.99999 17.3385 10.1699 16.6377 10.4907 15.9573ZM1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12ZM15.5 8C14.3954 8 13.5 8.89543 13.5 10C13.5 11.1046 14.3954 12 15.5 12C16.6046 12 17.5 11.1046 17.5 10C17.5 8.89543 16.6046 8 15.5 8ZM5.99999 11.5C5.99999 10.6716 6.67156 10 7.49999 10C8.32841 10 8.99999 10.6716 8.99999 11.5C8.99999 12.3284 8.32841 13 7.49999 13C6.67156 13 5.99999 12.3284 5.99999 11.5Z"/></svg>
                        
                        @else
                            <!-- Ikon Default (Grafik Batang) -->
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        @endif
                    </div>

                    <span class="text-[9px] font-black uppercase tracking-widest text-center px-2"
                          :class="activeTab === '{{ $cat->slug }}' ? 'text-slate-800' : 'text-slate-400'">
                        {{ str_replace('Data ', '', $cat->name) }}
                    </span>
                </button>
            @endforeach
        </div>

        @foreach($categories as $cat)
            {{-- ANIMASI TRANSISI ALPINE JS --}}
            <div x-show="activeTab === '{{ $cat->slug }}'" 
                 x-cloak 
                 x-transition:enter="transition ease-out duration-500"
                 x-transition:enter-start="opacity-0 translate-y-12"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                @php $viewPath = "frontend.desa.tabs." . $cat->slug; @endphp

                @if(view()->exists($viewPath))
                    @include($viewPath, ['cat' => $cat, 'desa' => $desa, 'tahun' => $tahun])
                @else
                    @include('frontend.desa.tabs.default', ['cat' => $cat, 'desa' => $desa, 'tahun' => $tahun])
                @endif
            </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.onload = function () {
    @foreach($categories as $cat)
        @php $chartId = str_replace('-', '_', $cat->slug); @endphp
        const ctx_{{ $chartId }} = document.getElementById('chart-{{ $cat->slug }}');

        if (ctx_{{ $chartId }}) {
            const dataValues_{{ $chartId }} = {!! json_encode($cat->indicators->map(fn($i) => $i->statistics->where('desa_id', $desa->id)->where('year', $tahun)->sum('value'))) !!};

            new Chart(ctx_{{ $chartId }}, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($cat->indicators->pluck('name')) !!},
                    datasets: [{
                        data: dataValues_{{ $chartId }},
                        {{-- 4. SINKRONISASI WARNA GRAFIK DENGAN THEME --}}
                        backgroundColor: [
                            '{{ $headerColor }}', 
                            '{{ $accentColor }}', 
                            '{{ $accentColor }}cc', // Transparansi 80%
                            '{{ $accentColor }}99', // Transparansi 60%
                            '#e2e8f0'
                        ],
                        borderWidth: 4,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: { legend: { display: false } }
                }
            });
        }
    @endforeach
};
</script>
{{-- Script Ekspor Excel tetap sama --}}
</body>
</html>