@php
    // Pisahkan indikator untuk tampilan tabel
    $indikatorPenduduk = $cat->indicators->filter(fn($i) => !str_contains(strtolower($i->name), 'kk'));
    $indikatorKK = $cat->indicators->filter(fn($i) => str_contains(strtolower($i->name), 'kk'));
@endphp

<div class="bg-white rounded-[3rem] shadow-2xl border border-slate-100"
     x-data="{ 
        selectedItem: 'Semua',
        selectedTahun: '{{ $tahun }}',
        chartMode: 'bar',
        allYearsData: {
            @foreach($cat->indicators as $ind)
                '{{ addslashes($ind->name) }}': {
                    @foreach($ind->statistics->groupBy('year') as $year => $stats)
                        '{{ $year }}': {
                            lk: {{ $stats->where('gender', 'Laki-laki')->first()->value ?? 0 }},
                            pr: {{ $stats->where('gender', 'Perempuan')->first()->value ?? 0 }},
                            total: {{ $stats->sum('value') }},
                            is_kk: {{ str_contains(strtolower($ind->name), 'kk') ? 'true' : 'false' }}
                        },
                    @endforeach
                },
            @endforeach
        },

        init() {
            this.initChart(this.chartMode);
            this.$watch('chartMode', () => this.updateChart());
            this.$watch('selectedItem', () => this.updateView());
            this.$watch('selectedTahun', () => this.updateChart());
        },

        get itemList() {
            // Hanya tampilkan yang bukan KK di grafik utama
            return Object.keys(this.allYearsData).filter(n => !this.allYearsData[n][this.selectedTahun]?.is_kk);
        },

        get currentStats() {
            let lk = 0, pr = 0, total = 0, kk = 0;
            
            // Hitung KK Global dulu
            Object.keys(this.allYearsData).forEach(n => {
                const d = this.allYearsData[n][this.selectedTahun];
                if (d && d.is_kk) kk += d.total;
            });

            if (this.selectedItem === 'Semua') {
                this.itemList.forEach(nama => {
                    const dataYear = this.allYearsData[nama][this.selectedTahun];
                    if (dataYear) {
                        lk += dataYear.lk;
                        pr += dataYear.pr;
                        total += dataYear.total;
                    }
                });
            } else {
                const dataYear = this.allYearsData[this.selectedItem]?.[this.selectedTahun];
                if (dataYear) {
                    lk = dataYear.lk;
                    pr = dataYear.pr;
                    total = dataYear.total;
                }
            }
            return { lk, pr, total, kk };
        },

        updateView() {
            this.updateChart();
        },

        updateChart() {
            let chart = Chart.getChart('chart-{{ $cat->slug }}');
            if (chart) chart.destroy();
            this.initChart(this.chartMode);
        },

        initChart(type) {
            const ctx = document.getElementById('chart-{{ $cat->slug }}');
            if (!ctx) return;

            const labels = this.selectedItem === 'Semua' ? this.itemList : [this.selectedItem];

            new Chart(ctx, {
                type: type,
                data: {
                    labels: labels,
                    datasets: type === 'bar'
                        ? [
                            { label: 'Laki-laki', data: labels.map(n => this.allYearsData[n][this.selectedTahun]?.lk || 0), backgroundColor: '#2563eb', borderRadius: 8 },
                            { label: 'Perempuan', data: labels.map(n => this.allYearsData[n][this.selectedTahun]?.pr || 0), backgroundColor: '#db2777', borderRadius: 8 }
                        ]
                        : [
                            { data: labels.map(n => this.allYearsData[n][this.selectedTahun]?.total || 0), backgroundColor: ['#1e3a8a', '#2563eb', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe', '#dbeafe'] }
                        ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true, position: 'top' } }
                }
            });
        }
     }"
     x-init="setTimeout(() => init(), 100)">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-10 items-start">
        <div class="space-y-8">
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 @click="selectedItem = 'Semua'; updateView()" class="text-lg font-black uppercase italic text-slate-700 cursor-pointer hover:text-blue-600">
                        01. Rincian Penduduk <span x-show="selectedItem !== 'Semua'" class="text-[10px] text-red-500 animate-pulse">(RESET)</span>
                    </h3>
                </div>
                <div class="overflow-hidden rounded-[2rem] border border-slate-200 shadow-sm">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-900 text-white text-[10px] uppercase font-black tracking-widest text-center sticky top-0 z-10">
                            <tr>
                                <th class="p-4 text-left">Indikator</th>
                                <th class="p-4 italic">LK</th>
                                <th class="p-4 italic">PR</th>
                                <th class="p-4 bg-slate-800">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-bold text-[11px] uppercase text-center">
                            @foreach($indikatorPenduduk as $ind)
                                <tr x-show="selectedItem === 'Semua' || selectedItem === '{{ addslashes($ind->name) }}'" 
                                    @click="selectedItem = '{{ addslashes($ind->name) }}'; updateView()"
                                    class="hover:bg-blue-50 cursor-pointer transition-all"
                                    :class="selectedItem === '{{ addslashes($ind->name) }}' ? 'bg-blue-600 text-white' : ''">
                                    <td class="p-4 text-left pl-6">{{ $ind->name }}</td>
                                    <td class="p-4">{{ number_format($ind->statistics->where('year', $tahun)->where('gender', 'Laki-laki')->first()->value ?? 0, 0, ',', '.') }}</td>
                                    <td class="p-4">{{ number_format($ind->statistics->where('year', $tahun)->where('gender', 'Perempuan')->first()->value ?? 0, 0, ',', '.') }}</td>
                                    <td class="p-4 font-black" :class="selectedItem === '{{ addslashes($ind->name) }}' ? 'text-white' : 'text-slate-900'">
                                        {{ number_format($ind->statistics->where('year', $tahun)->sum('value'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($indikatorKK->count() > 0)
            <div class="space-y-4">
                <h3 class="text-lg font-black uppercase italic text-emerald-700">02. Data Keluarga (KK)</h3>
                <div class="overflow-hidden rounded-[2rem] border border-emerald-100 shadow-sm bg-emerald-50/20">
                    <table class="w-full text-sm">
                        <thead class="bg-emerald-800 text-white text-[10px] uppercase font-black tracking-widest text-center">
                            <tr>
                                <th class="p-4 text-left">Indikator</th>
                                <th class="p-4">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-100 font-bold text-[11px] uppercase text-center">
                            @foreach($indikatorKK as $ind)
                                <tr>
                                    <td class="p-4 text-left pl-6 text-emerald-900">{{ $ind->name }}</td>
                                    <td class="p-4 text-lg font-black text-emerald-600">{{ number_format($ind->statistics->where('year', $tahun)->sum('value'), 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="bg-slate-900 rounded-[2.5rem] p-8 flex items-center gap-6 shadow-xl text-white">
                <div class="bg-indigo-600 p-4 rounded-3xl animate-pulse">
                    <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-indigo-400 italic tracking-[0.2em] mb-1 flex items-center gap-2">
                        <span class="w-2 h-2 bg-indigo-400 rounded-full animate-ping"></span> Insight Digital
                    </p>
                    <h4 class="text-base font-bold italic mb-1 uppercase">Dominasi: <span class="text-indigo-400" x-text="currentStats.lk > currentStats.pr ? 'Laki-laki' : 'Perempuan'"></span></h4>
                    <p class="text-2xl font-black italic">Total: <span class="text-indigo-400" x-text="currentStats.total.toLocaleString('id-ID')"></span> Jiwa</p>
                </div>
            </div>
        </div>

        <div class="lg:sticky lg:top-6 self-start space-y-6">
            <div class="flex bg-slate-100 p-1 rounded-2xl shadow-inner border border-slate-200">
                <button @click="chartMode = 'bar'" :class="chartMode === 'bar' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'" class="flex-1 px-4 py-2 rounded-xl text-[10px] font-black uppercase italic transition-all">Batang</button>
                <button @click="chartMode = 'doughnut'" :class="chartMode === 'doughnut' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'" class="flex-1 px-4 py-2 rounded-xl text-[10px] font-black uppercase italic transition-all">Lingkaran</button>
            </div>

            <div class="bg-white rounded-[2rem] p-4 border border-slate-100 shadow-sm h-[320px]">
                <canvas id="chart-{{ $cat->slug }}"></canvas>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-600 text-white p-5 rounded-[2rem] text-center shadow-lg transform hover:scale-105 transition-all">
                    <div class="text-[9px] font-black opacity-70 uppercase italic">Laki-laki</div>
                    <div class="text-xl font-black italic mt-1" x-text="currentStats.lk.toLocaleString('id-ID')"></div>
                </div>
                <div class="bg-pink-500 text-white p-5 rounded-[2rem] text-center shadow-lg transform hover:scale-105 transition-all">
                    <div class="text-[9px] font-black opacity-70 uppercase italic">Perempuan</div>
                    <div class="text-xl font-black italic mt-1" x-text="currentStats.pr.toLocaleString('id-ID')"></div>
                </div>
                <div class="bg-slate-800 text-white p-5 rounded-[2rem] text-center shadow-lg transform hover:scale-105 transition-all">
                    <div class="text-[9px] font-black opacity-70 uppercase italic">Total Jiwa</div>
                    <div class="text-xl font-black italic mt-1" x-text="currentStats.total.toLocaleString('id-ID')"></div>
                </div>
                <div class="bg-emerald-600 text-white p-5 rounded-[2rem] text-center shadow-lg transform hover:scale-105 transition-all">
                    <div class="text-[9px] font-black opacity-70 uppercase italic">Keluarga (KK)</div>
                    <div class="text-xl font-black italic mt-1" x-text="currentStats.kk.toLocaleString('id-ID')"></div>
                </div>
            </div>
        </div>
    </div>
</div>