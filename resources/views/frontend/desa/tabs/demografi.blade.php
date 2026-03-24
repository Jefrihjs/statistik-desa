@php
    // 1. FILTER: Pisahkan indikator (Penduduk vs KK)
    $indikatorPenduduk = $cat->indicators->filter(function($i) {
        $nama = strtolower($i->name);
        return !str_contains($nama, 'kk') && !str_contains($nama, 'kepala keluarga');
    });

    $indikatorKK = $cat->indicators->filter(function($i) {
        $nama = strtolower($i->name);
        return str_contains($nama, 'kk') || str_contains($nama, 'kepala keluarga');
    });

    // 2. TOTALS
    $totalLK_P = $indikatorPenduduk->flatMap->statistics->where('year', $tahun)->where('gender', 'Laki-laki')->sum('value');
    $totalPR_P = $indikatorPenduduk->flatMap->statistics->where('year', $tahun)->where('gender', 'Perempuan')->sum('value');
    $totalP = $totalLK_P + $totalPR_P;

    $totalLK_K = $indikatorKK->flatMap->statistics->where('year', $tahun)->where('gender', 'Laki-laki')->sum('value');
    $totalPR_K = $indikatorKK->flatMap->statistics->where('year', $tahun)->where('gender', 'Perempuan')->sum('value');
    $totalK = $totalLK_K + $totalPR_K;
@endphp

<div class="bg-white rounded-[3rem] shadow-2xl border border-slate-100"
     x-data="{ 
        selectedItem: 'Semua',
        selectedTahun: '{{ $tahun }}',
        chartMode: 'doughnut',
        isShowingKK: false,
        
        allYearsData: {
            @foreach($cat->indicators as $ind)
                @php 
                    $namaLower = strtolower($ind->name);
                    $isKK = str_contains($namaLower, 'kk') || str_contains($namaLower, 'kepala keluarga'); 
                @endphp
                '{{ addslashes($ind->name) }}': {
                    @foreach($ind->statistics->groupBy('year') as $year => $stats)
                        '{{ $year }}': {
                            lk: {{ $stats->where('gender', 'Laki-laki')->first()->value ?? 0 }},
                            pr: {{ $stats->where('gender', 'Perempuan')->first()->value ?? 0 }},
                            total: {{ $stats->sum('value') }},
                            is_kk: {{ $isKK ? 'true' : 'false' }}
                        },
                    @endforeach
                },
            @endforeach
        },

        init() {
            this.initChart();
            this.$watch('chartMode', () => this.updateChart());
            this.$watch('selectedItem', () => this.updateChart());
        },

        get currentStats() {
            let lk = 0, pr = 0, total = 0, kk = {{ $totalK }};
            if (this.selectedItem === 'Semua') {
                lk = {{ $totalLK_P }}; pr = {{ $totalPR_P }}; total = {{ $totalP }};
            } else {
                const d = this.allYearsData[this.selectedItem]?.[this.selectedTahun];
                if (d) { lk = d.lk; pr = d.pr; total = d.total; if(d.is_kk) kk = d.total; }
            }
            return { lk, pr, total, kk };
        },

        updateChart() {
            let chart = Chart.getChart('chart-{{ $cat->slug }}');
            if (chart) chart.destroy();
            this.initChart();
        },

        initChart() {
            const ctx = document.getElementById('chart-{{ $cat->slug }}');
            if (!ctx) return;
            
            let labels = [];
            let dataValues = [];
            let bgColors = [];

            if (this.selectedItem !== 'Semua') {
                labels = ['Laki-laki', 'Perempuan'];
                dataValues = [this.currentStats.lk, this.currentStats.pr];
                bgColors = this.isShowingKK ? ['#059669', '#10b981'] : ['#2563eb', '#db2777'];
            } else {
                const targetItems = Object.keys(this.allYearsData).filter(n => this.allYearsData[n][this.selectedTahun]?.is_kk === this.isShowingKK);
                labels = targetItems;
                dataValues = targetItems.map(n => this.allYearsData[n][this.selectedTahun]?.total || 0);
                bgColors = ['#1e3a8a', '#2563eb', '#3b82f6', '#60a5fa', '#059669', '#10b981', '#fbbf24', '#f59e0b'];
            }

            new Chart(ctx, {
                type: this.chartMode,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah',
                        data: dataValues,
                        backgroundColor: bgColors,
                        borderWidth: 5,
                        borderColor: '#ffffff',
                        hoverOffset: 30, {{-- INI PERMINTAAN BAPAK --}}
                        borderRadius: this.chartMode === 'bar' ? 8 : 0
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: true, position: 'bottom' }
                    }
                }
            });
        }
     }"
     x-init="setTimeout(() => init(), 100)">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 p-10 items-start">
        <div class="space-y-10">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 @click="selectedItem = 'Semua'; isShowingKK = false; updateChart()" class="text-xl font-black uppercase italic text-slate-800 cursor-pointer hover:text-blue-600 flex items-center gap-2">
                        <span class="bg-blue-600 text-white w-8 h-8 flex items-center justify-center rounded-lg italic text-sm shadow-md">01</span>
                        Data Penduduk
                    </h3>
                    <button type="button" 
                        onclick="eksporTabelDinamis('tabel-p-{{ $cat->slug }}', '{{ $cat->name }}')"
                        class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[9px] font-black rounded-xl uppercase transition-all shadow-md active:scale-95">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        <span>Export Excel Data Penduduk</span>
				    </button>
                </div>
                <div class="overflow-hidden rounded-[2.5rem] border border-blue-200 shadow-sm bg-blue-50/20">
                    <table id="tabel-p-{{ $cat->slug }}" class="w-full text-sm">
                        <thead class="bg-blue-800 text-white text-[10px] uppercase font-black text-center sticky top-0 z-10">
                            <tr>
                                <th class="p-4 text-left">Indikator</th>
                                <th class="p-4">LK</th>
                                <th class="p-4 bg-blue-700">%</th>
                                <th class="p-4">PR</th>
                                <th class="p-4 bg-blue-600">%</th>
                                <th class="p-4 bg-blue-900">Total</th>
                            </tr>
                        </thead>
                        <tbody class="font-bold text-[11px] uppercase text-center italic bg-white/50">
                            @php
                                $pLK_P = $totalP > 0 ? ($totalLK_P / $totalP) * 100 : 0;
                                $pPR_P = $totalP > 0 ? ($totalPR_P / $totalP) * 100 : 0;
                            @endphp
                            <tr @click="selectedItem = 'Semua'; isShowingKK = false; updateChart()" class="cursor-pointer hover:bg-blue-100" :class="!isShowingKK && selectedItem === 'Semua' ? 'bg-blue-100' : ''">
                                <td class="p-5 text-left pl-8 text-blue-900 font-black leading-tight">Jumlah Penduduk</td>
                                <td class="p-5">{{ number_format($totalLK_P, 0, ',', '.') }}</td>
                                <td class="p-5"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-lg">{{ number_format($pLK_P, 1) }}%</span></td>
                                <td class="p-5">{{ number_format($totalPR_P, 0, ',', '.') }}</td>                                
                                <td class="p-5"><span class="bg-blue-200 text-blue-800 px-2 py-1 rounded-lg">{{ number_format($pPR_P, 1) }}%</span></td>
                                <td class="p-5 text-2xl font-black text-blue-700 bg-blue-100/30">{{ number_format($totalP, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if($indikatorKK->count() > 0)
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-black uppercase italic text-emerald-800 flex items-center gap-2">
                        <span class="bg-emerald-600 text-white w-8 h-8 flex items-center justify-center rounded-lg italic text-sm shadow-md">02</span>
                        Data Keluarga (KK)
                    </h3>
                    <button type="button" 
                        onclick="eksporTabelDinamis('tabel-kk-{{ $cat->slug }}', 'KK')" 
                        class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[9px] font-black rounded-xl uppercase transition-all shadow-md active:scale-95">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        <span>Export Excel Data Keluarga</span>
                        </button>
                </div>
                <div class="overflow-hidden rounded-[2.5rem] border border-emerald-200 shadow-sm bg-emerald-50/20">
                    <table id="tabel-kk-{{ $cat->slug }}" class="w-full text-sm">
                        <thead class="bg-emerald-800 text-white text-[10px] uppercase font-black text-center sticky top-0 z-10">
                            <tr>
                                <th class="p-4 text-left">Indikator</th>
                                <th class="p-4">LK</th>
                                <th class="p-4 bg-emerald-700">%</th>
                                <th class="p-4">PR</th>
                                <th class="p-4 bg-emerald-600">%</th>
                                <th class="p-4 bg-emerald-900">Total KK</th>
                            </tr>
                        </thead>
                        <tbody class="font-bold text-[11px] uppercase text-center italic bg-white/50">
                            @foreach($indikatorKK as $ind)
                            @php
                                $kk_lk = $ind->statistics->where('year',$tahun)->where('gender','Laki-laki')->first()->value ?? 0;
                                $kk_pr = $ind->statistics->where('year',$tahun)->where('gender','Perempuan')->first()->value ?? 0;
                                $kk_total = $ind->statistics->where('year',$tahun)->sum('value');
                                $pKK_LK = $kk_total > 0 ? ($kk_lk / $kk_total) * 100 : 0;
                                $pKK_PR = $kk_total > 0 ? ($kk_pr / $kk_total) * 100 : 0;
                            @endphp
                                <tr @click="selectedItem = '{{ addslashes($ind->name) }}'; isShowingKK = true; updateChart()" 
                                    :class="selectedItem === '{{ addslashes($ind->name) }}' ? 'bg-emerald-600 text-white shadow-inner' : 'hover:bg-emerald-100'"
                                    class="cursor-pointer transition-all border-b border-slate-50">
                                    <td class="p-5 text-left pl-8 text-emerald-900 font-black leading-tight">{{ $ind->name }}</td>
                                    <td class="p-5">{{ number_format($kk_lk, 0, ',', '.') }}</td>
                                    <td class="p-5"><span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded-lg" :class="selectedItem === '{{ addslashes($ind->name) }}' ? 'bg-white/20 text-white' : ''">{{ number_format($pKK_LK, 1) }}%</span></td>
                                    <td class="p-5">{{ number_format($kk_pr, 0, ',', '.') }}</td>                                    
                                    <td class="p-5"><span class="bg-emerald-200 text-emerald-900 px-2 py-1 rounded-lg" :class="selectedItem === '{{ addslashes($ind->name) }}' ? 'bg-white/20 text-white' : ''">{{ number_format($pKK_PR, 1) }}%</span></td>
                                    <td class="p-5 text-2xl font-black text-emerald-700" :class="selectedItem === '{{ addslashes($ind->name) }}' ? 'text-white' : ''">{{ number_format($kk_total, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="bg-slate-900 rounded-[3rem] p-8 flex items-center gap-6 shadow-2xl text-white border-b-8 border-indigo-600">
                <div class="bg-indigo-600 p-4 rounded-3xl animate-pulse shadow-lg"><svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                <div>
                    <p class="text-[10px] font-black uppercase text-indigo-400 italic tracking-[0.3em] mb-1 flex items-center gap-2"><span class="w-2.5 h-2.5 bg-indigo-500 rounded-full animate-ping"></span> Insight Data</p>
                    <h4 class="text-base font-bold italic mb-1 uppercase tracking-tight">Kategori Terpilih: <span class="text-indigo-400" x-text="isShowingKK ? 'Kepala Keluarga' : 'Penduduk'"></span></h4>
                    <p class="text-3xl font-black italic tracking-tighter">Total: <span class="text-indigo-400" x-text="(isShowingKK ? currentStats.kk : currentStats.total).toLocaleString('id-ID')"></span></p>
                </div>
            </div>
        </div>

        <div class="lg:sticky lg:top-10 space-y-6">
            <div class="flex bg-slate-100 p-1 rounded-2xl border border-slate-200 shadow-inner">
                <button @click="chartMode = 'bar'" :class="chartMode === 'bar' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'" class="flex-1 px-4 py-2 rounded-xl text-[10px] font-black uppercase italic transition-all">Batang</button>
                <button @click="chartMode = 'doughnut'" :class="chartMode === 'doughnut' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'" class="flex-1 px-4 py-2 rounded-xl text-[10px] font-black uppercase italic transition-all">Lingkaran</button>
            </div>
            <div class="bg-white rounded-[3rem] p-6 border border-slate-50 shadow-sm h-[420px] flex items-center justify-center"><canvas id="chart-{{ $cat->slug }}"></canvas></div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="bg-blue-600 text-white p-5 rounded-[2.2rem] text-center shadow-lg"><div class="text-[9px] font-black opacity-70 uppercase mb-1">LK</div><div class="text-xl font-black italic" x-text="currentStats.lk.toLocaleString('id-ID')"></div></div>
                <div class="bg-pink-500 text-white p-5 rounded-[2.2rem] text-center shadow-lg"><div class="text-[9px] font-black opacity-70 uppercase mb-1">PR</div><div class="text-xl font-black italic" x-text="currentStats.pr.toLocaleString('id-ID')"></div></div>
                <div class="bg-slate-800 text-white p-5 rounded-[2.2rem] text-center shadow-lg"><div class="text-[9px] font-black opacity-70 uppercase mb-1">TOTAL</div><div class="text-xl font-black italic" x-text="currentStats.total.toLocaleString('id-ID')"></div></div>
                <div class="bg-emerald-600 text-white p-5 rounded-[2.2rem] text-center shadow-lg transform hover:scale-105 transition-all"><div class="text-[9px] font-black opacity-70 uppercase mb-1">KK</div><div class="text-xl font-black italic" x-text="currentStats.kk.toLocaleString('id-ID')"></div></div>
            </div>
        </div>
    </div>
</div>