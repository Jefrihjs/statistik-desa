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

        .halaman-publik {
            --theme-color: {{ $desa->warna_tema ?? '#2563eb' }};
        }
        
        .halaman-publik .bg-custom { background-color: var(--theme-color) !important; }
        .halaman-publik .text-custom { color: var(--theme-color) !important; }
    </style>
</head>
<body class="bg-slate-50 antialiased">

@php
    $headerColor = $desa->header_color ?? '#1e3a8a';
    $accentColor = $desa->accent_color ?? '#2563eb';
@endphp

<div style="background-color: {{ $headerColor }};" class="pt-16 pb-24 px-4">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <h1 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter italic">
                Profil Desa {{ $desa->nama_desa }}
            </h1>

            <div class="mt-3 flex flex-col sm:flex-row sm:items-center gap-3">
                <p style="color: {{ $accentColor }};" class="font-bold text-lg uppercase tracking-widest">
                    Statistik Sektoral Kab. Belitung Timur
                </p>

                <form method="GET" action="" class="inline-block">
                    <select name="tahun"
                            onchange="this.form.submit()"
                            class="bg-white/10 text-white border border-white/20 rounded-2xl px-4 py-2 text-sm font-black uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-white/40">
                        @foreach($daftarTahun as $y)
                            <option value="{{ $y }}" {{ (int)$tahun === (int)$y ? 'selected' : '' }} class="text-slate-800">
                                Tahun {{ $y }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <img src="{{ $desa->logo ? asset('storage/'.$desa->logo) : 'https://www.beltim.go.id/images/sekilas_beltim/lambang_daerah/logoBeltim.png' }}"
             class="h-28 drop-shadow-2xl"
             alt="Logo Beltim">
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 -mt-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border-l-8 border-blue-600">
            <h4 class="text-[10px] font-black uppercase tracking-widest mb-2 text-blue-600">Populasi</h4>
            <p class="text-xl font-bold text-slate-800">
                Berapa jumlah penduduk Desa {{ $desa->nama_desa }} pada tahun {{ $tahun }}?
            </p>

            @php
                $demografi = $categories->where('slug', 'demografi')->first();
                $totalJiwa = 0;

                if ($demografi) {
                    // Hanya menjumlahkan Laki-laki dan Perempuan agar tidak double count dengan KK
                    $totalJiwa = $demografi->indicators
                        ->whereIn('name', ['Laki-laki', 'Perempuan']) 
                        ->sum(function($indicator) use ($tahun) {
                            return $indicator->statistics->where('year', $tahun)->sum('value');
                        });
                }
            @endphp

            <p class="mt-4 text-slate-600 italic">
                "Tercatat sebanyak <span class="font-black text-blue-600">{{ number_format($totalJiwa, 0, ',', '.') }}</span> jiwa."
                
                @if($totalJiwa == 0 && $demografi)
                    <br><span class="text-[10px] text-red-400 italic">Data tahun {{ $tahun }} belum tersedia.</span>
                @endif
            </p>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border-l-8 border-pink-500"
            @php
                
                $pekerjaan = $categories->where('slug', 'mata-pencaharian')->first();
                $topJob = null;

                if ($pekerjaan) {
                    $topJob = $pekerjaan->indicators->map(function ($ind) use ($tahun) {
                        $ind->total_value = $ind->statistics->where('year', $tahun)->sum('value');
                        return $ind;
                    })->sortByDesc('total_value')->first();
                }
            @endphp
            
            x-data="{
                {{-- Kita buat object data dari Laravel ke Alpine --}}
                allData: {
                    @if($pekerjaan)
                        @foreach($pekerjaan->indicators as $ind)
                            '{{ addslashes($ind->name) }}': {{ $ind->statistics->where('year', $tahun)->sum('value') }},
                        @endforeach
                    @endif
                },
                get topMurni() {
                    let max = -1; let nama = '-';
                    Object.keys(this.allData).forEach(k => {
                        if(this.allData[k] > max) { max = this.allData[k]; nama = k; }
                    });
                    return nama;
                }
            }">
            
            <h4 class="text-[10px] font-black uppercase tracking-widest mb-2 text-pink-500 tracking-[0.2em]">Ekonomi</h4>
            <p class="text-xl font-bold text-slate-800 leading-tight">
                Mata pencaharian dominan di Desa {{ $desa->nama_desa }} pada tahun {{ $tahun }}?
            </p>

            <p class="mt-4 text-slate-600 italic">
                @if($topJob && $topJob->total_value > 0)
                    "Mata pencaharian yang paling dominan pada tahun {{ $tahun }} adalah
                    <span class="font-black text-pink-600">{{ $topJob->name }}</span>
                    dengan total <span class="font-black text-pink-600">{{ number_format($topJob->total_value, 0, ',', '.') }}</span> jiwa."
                @else
                    <span class="text-red-500 font-bold text-xs uppercase italic">Data belum tersedia</span>
                @endif
            </p>

            <p class="mt-4 text-[9px] text-slate-400 italic leading-relaxed border-t border-slate-100 pt-3" 
            x-show="topMurni.toLowerCase().includes('belum') || topMurni.toLowerCase().includes('tidak')"
            x-transition>
                *Catatan: Kategori 'Belum/Tidak Bekerja' mencakup penduduk usia non-produktif (anak-anak), pelajar, serta penduduk yang belum memiliki pekerjaan tetap sesuai dengan data KTP/KK.
            </p>
        </div>
    </div>

    <div x-data="{ activeTab: '{{ $categories->first()->slug ?? '' }}' }" class="pb-24">
        <div class="flex flex-wrap justify-center gap-4 mb-10">
            @foreach($categories as $cat)
                <button
                    type="button"
                    @click="activeTab = '{{ $cat->slug }}'"
                    :class="activeTab === '{{ $cat->slug }}' ? 'bg-blue-600 text-white shadow-blue-200' : 'bg-white text-slate-600 hover:bg-blue-50 border-slate-100'"
                    class="group flex flex-col items-center justify-center w-24 h-24 md:w-28 md:h-28 rounded-[2rem] shadow-lg transition-all duration-300 border-2"
                >
                    <div class="mb-2 transition-transform group-hover:scale-110">
                        @if($cat->slug == 'demografi')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @elseif($cat->slug == 'usia-detail')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3v18h18M9 17V9m4 8V5m4 12v-6" />
                            </svg>
                        @elseif($cat->slug == 'kelompok-usia')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 3v10h10A10 10 0 0011 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 13H3a10 10 0 1010-10v10z" />
                            </svg>
                        @elseif($cat->slug == 'mata-pencaharian')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @elseif($cat->slug == 'pendidikan')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        @elseif($cat->slug == 'agama')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        @elseif($cat->slug == 'tenaga-kerja')
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        @elseif($cat->slug == 'etnis')
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 3C7.02944 3 3 7.02944 3 12C3 12.8168 3.1088 13.6081 3.31269 14.3603C3.72385 14.0549 4.18033 13.7872 4.67874 13.5718C4.25207 12.9917 3.99999 12.2753 3.99999 11.5C3.99999 9.567 5.56699 8 7.49999 8C9.43298 8 11 9.567 11 11.5C11 12.2753 10.7479 12.9918 10.3212 13.5718C10.7765 13.7685 11.1973 14.009 11.5808 14.2826C11.5933 14.2916 11.6057 14.3008 11.6177 14.3103C12.021 13.878 12.4936 13.4824 13.0284 13.1452C12.0977 12.4128 11.5 11.2762 11.5 10C11.5 7.79086 13.2908 6 15.5 6C17.7091 6 19.5 7.79086 19.5 10C19.5 10.8095 19.2595 11.5629 18.8461 12.1925C19.6192 12.3672 20.3212 12.6528 20.9432 13.0164C20.9807 12.6828 21 12.3436 21 12C21 7.02944 16.9706 3 12 3ZM10.4907 15.9573C10.4664 15.9429 10.4426 15.9274 10.4192 15.9107C9.65816 15.3678 8.67891 15 7.49999 15C6.06158 15 4.91073 15.5491 4.09526 16.3065C5.622 19.1029 8.58946 21 12 21C15.8853 21 19.1956 18.538 20.4559 15.089C20.4386 15.0778 20.4216 15.066 20.4048 15.0536C19.5686 14.4343 18.4544 14 17.0906 14C13.7836 14 12 16.529 12 18C12 18.5523 11.5523 19 11 19C10.4477 19 9.99999 18.5523 9.99999 18C9.99999 17.3385 10.1699 16.6377 10.4907 15.9573ZM1 12C1 5.92487 5.92487 1 12 1C18.0751 1 23 5.92487 23 12C23 18.0751 18.0751 23 12 23C5.92487 23 1 18.0751 1 12ZM15.5 8C14.3954 8 13.5 8.89543 13.5 10C13.5 11.1046 14.3954 12 15.5 12C16.6046 12 17.5 11.1046 17.5 10C17.5 8.89543 16.6046 8 15.5 8ZM5.99999 11.5C5.99999 10.6716 6.67156 10 7.49999 10C8.32841 10 8.99999 10.6716 8.99999 11.5C8.99999 12.3284 8.32841 13 7.49999 13C6.67156 13 5.99999 12.3284 5.99999 11.5Z"/>
                            </svg>
                        @else
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        @endif
                    </div>

                    <span class="text-[10px] font-black uppercase tracking-tighter text-center leading-none">
                        {{ str_replace('Data ', '', $cat->name) }}
                    </span>
                </button>
            @endforeach
        </div>

        @foreach($categories as $cat)
            <div x-show="activeTab === '{{ $cat->slug }}'" x-cloak x-transition>
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
            // LOGIKA PENTING: Filter data berdasarkan desa_id dan tahun yang aktif
            const dataValues_{{ $chartId }} = {!! json_encode($cat->indicators->map(fn($i) => $i->statistics->where('desa_id', $desa->id)->where('year', $tahun)->sum('value'))) !!};

            new Chart(ctx_{{ $chartId }}, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($cat->indicators->pluck('name')) !!},
                    datasets: [{
                        data: dataValues_{{ $chartId }},
                        backgroundColor: ['#1e3a8a', '#2563eb', '#3b82f6', '#60a5fa', '#f59e0b'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { legend: { display: false } }
                }
            });
        }
    @endforeach
};
</script>

</body>
</html>