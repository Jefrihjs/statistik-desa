<div class="bg-white rounded-[3rem] shadow-2xl border border-slate-100"
     x-data="piramidaUsiaFull()"
     x-init="init()">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-10 items-start">
        {{-- KOLOM KIRI: TABEL & INSIGHT --}}
        <div class="space-y-6">
            <div class="flex justify-between items-center mb-4">
                <div class="text-left">
                    <h3 @click="resetSelection()" class="text-xl font-black uppercase italic text-slate-700 cursor-pointer hover:text-blue-600 transition-colors">
                        Detail {{ str_replace('Data ', '', $cat->name) }} 
                        <span x-show="selectedItem !== 'Semua'" class="text-[10px] text-red-500 animate-pulse ml-2 uppercase font-black">(KLIK UNTUK RESET)</span>
                    </h3>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Gunakan tombol di kanan untuk membalik urutan grafik</p>
                </div>
                
                <div class="flex gap-2">
                    <button @click="toggleSort()" class="px-4 py-2 bg-slate-100 hover:bg-blue-600 hover:text-white text-[9px] font-black rounded-xl uppercase transition-all flex items-center gap-2 shadow-sm border border-slate-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                        Balik Urutan
                    </button>
                    <button type="button" onclick="eksporTabelDinamis('tabel-{{ $cat->slug }}', '{{ $cat->name }}')"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[9px] font-black rounded-xl uppercase transition-all shadow-md flex items-center gap-2">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Export Excel {{ $cat->name }}
                    </button>
                </div>
            </div>

            {{-- TABEL DATA --}}
            <div class="overflow-hidden rounded-[2rem] border border-slate-200 shadow-sm bg-white">
                <div class="max-h-[500px] overflow-y-auto custom-scrollbar">
                    <table id="tabel-{{ $cat->slug }}" class="w-full text-sm text-left">
                        <thead class="bg-slate-900 text-white text-[10px] uppercase font-black tracking-widest sticky top-0 z-10">
                            <tr>
                                <th class="p-4">Indikator</th>
                                <th class="p-4 text-center italic text-blue-300">LK</th>
                                <th class="p-4 text-center italic text-pink-300">PR</th>
                                <th class="p-4 text-center bg-blue-800">%</th>
                                <th class="p-4 text-center bg-slate-800">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-bold text-[11px] uppercase">
                            <template x-for="name in itemList" :key="name">
                                <tr x-show="selectedItem === 'Semua' || selectedItem === name"
                                    @click="selectIndicator(name)"
                                    class="cursor-pointer transition-all duration-200"
                                    :class="selectedItem === name ? 'bg-blue-600 text-white shadow-inner scale-[1.01]' : 'hover:bg-blue-50'">
                                    
                                    <td class="p-4 font-black italic" x-text="name"></td>
                                    <td class="p-4 text-center" x-text="formatNumber(allYearsData[name].years[selectedTahun]?.lk)"></td>
                                    <td class="p-4 text-center" x-text="formatNumber(allYearsData[name].years[selectedTahun]?.pr)"></td>
                                    <td class="p-4 text-center" x-text="calculatePercent(name) + '%'"></td>
                                    <td class="p-4 text-center bg-slate-50/10" x-text="formatNumber(allYearsData[name].years[selectedTahun]?.total)"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- INSIGHT CARD --}}
            <div class="mt-6 bg-blue-50 border border-blue-100 rounded-[2.5rem] p-8 flex items-center gap-6 shadow-sm">
                <div class="bg-blue-600 min-w-[64px] h-[64px] rounded-3xl shadow-lg flex items-center justify-center animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-black uppercase text-blue-400 italic tracking-widest mb-1">Insight Terpilih</p>
                    <h4 class="text-base font-bold text-slate-700 leading-tight">
                        <span x-text="selectedItem === 'Semua' ? 'Total Seluruh Kelompok Usia' : 'Kelompok ' + selectedItem"></span>
                    </h4>
                    <p class="text-2xl font-black italic text-slate-900 mt-2">
                        Tercatat sebanyak <span class="text-blue-600" x-text="formatNumber(currentStats.total)"></span> jiwa.
                    </p>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: GRAFIK & CARDS --}}
        <div class="lg:sticky lg:top-6 self-start h-fit flex flex-col items-center w-full">
            <div class="flex bg-slate-100 p-1.5 rounded-2xl mb-8 shadow-inner border border-slate-200">
                <button @click="changeDiagram('piramida')" :class="activeDiagram === 'piramida' ? 'bg-white text-blue-600 shadow-md' : 'text-slate-400'" class="px-8 py-2.5 rounded-xl text-[10px] font-black uppercase transition-all">Piramida</button>
                <button @click="changeDiagram('batang')" :class="activeDiagram === 'batang' ? 'bg-white text-blue-600 shadow-md' : 'text-slate-400'" class="px-8 py-2.5 rounded-xl text-[10px] font-black uppercase transition-all">Batang</button>
            </div>

            <div class="w-full mb-8 bg-white rounded-[3rem] p-8 border border-slate-100 shadow-sm" style="height: 600px;">
                <canvas id="chart-{{ $cat->slug }}"></canvas>
            </div>

            {{-- 3 CARDS STATISTIK BAWAH --}}
            <div class="grid grid-cols-3 gap-4 w-full">
                <div class="bg-blue-600 p-6 rounded-[2rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic">Laki-laki</span>
                    <span class="text-2xl font-black italic" x-text="formatNumber(currentStats.lk)"></span>
                </div>
                <div class="bg-pink-500 p-6 rounded-[2rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic">Perempuan</span>
                    <span class="text-2xl font-black italic" x-text="formatNumber(currentStats.pr)"></span>
                </div>
                <div class="bg-slate-800 p-6 rounded-[2rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic">TOTAL</span>
                    <span class="text-2xl font-black italic" x-text="formatNumber(currentStats.total)"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function piramidaUsiaFull() {
    return {
        selectedItem: 'Semua',
        selectedTahun: '{{ $tahun }}',
        activeDiagram: 'piramida',
        sortDesc: true,
        allYearsData: {
            @foreach($cat->indicators as $ind)
                '{{ addslashes($ind->name) }}': {
                    sort: @php preg_match('/\d+/', $ind->name, $matches); echo (int)($matches[0] ?? 999); @endphp,
                    years: {
                        @foreach($ind->statistics->groupBy('year') as $year => $stats)
                            '{{ $year }}': {
                                lk: {{ $stats->where('gender', 'Laki-laki')->first()->value ?? 0 }},
                                pr: {{ $stats->where('gender', 'Perempuan')->first()->value ?? 0 }},
                                total: {{ $stats->sum('value') }}
                            },
                        @endforeach
                    }
                },
            @endforeach
        },

        init() {
            this.initChart();
            this.$watch('selectedItem', () => this.updateView());
        },

        get itemList() {
            return Object.keys(this.allYearsData).sort((a, b) => {
                return this.sortDesc 
                    ? this.allYearsData[b].sort - this.allYearsData[a].sort 
                    : this.allYearsData[a].sort - this.allYearsData[b].sort;
            });
        },

        get currentStats() {
            let lk = 0, pr = 0, total = 0;
            if (this.selectedItem === 'Semua') {
                this.itemList.forEach(name => {
                    const d = this.allYearsData[name].years[this.selectedTahun];
                    if (d) { lk += d.lk; pr += d.pr; total += d.total; }
                });
            } else {
                const d = this.allYearsData[this.selectedItem].years[this.selectedTahun];
                if (d) { lk = d.lk; pr = d.pr; total = d.total; }
            }
            return { lk, pr, total };
        },

        calculatePercent(name) {
            const totalItem = this.allYearsData[name].years[this.selectedTahun]?.total || 0;
            let totalAll = 0;
            Object.keys(this.allYearsData).forEach(k => {
                totalAll += this.allYearsData[k].years[this.selectedTahun]?.total || 0;
            });
            return totalAll > 0 ? ((totalItem / totalAll) * 100).toFixed(1) : 0;
        },

        formatNumber(val) { return (val || 0).toLocaleString('id-ID'); },
        toggleSort() { this.sortDesc = !this.sortDesc; this.updateChart(); },
        resetSelection() { this.selectedItem = 'Semua'; this.updateView(); },
        changeDiagram(type) { this.activeDiagram = type; this.updateChart(); },
        selectIndicator(name) { this.selectedItem = (this.selectedItem === name) ? 'Semua' : name; },

        updateView() {
            let chart = Chart.getChart('chart-{{ $cat->slug }}');
            if (!chart) return;
            const labels = this.selectedItem === 'Semua' ? this.itemList : [this.selectedItem];
            const isPiramida = this.activeDiagram === 'piramida';
            chart.data.labels = labels;
            chart.data.datasets[0].data = labels.map(n => {
                let v = this.allYearsData[n].years[this.selectedTahun]?.lk || 0;
                return isPiramida ? -v : v;
            });
            chart.data.datasets[1].data = labels.map(n => this.allYearsData[n].years[this.selectedTahun]?.pr || 0);
            chart.update();
        },

        updateChart() {
            let chart = Chart.getChart('chart-{{ $cat->slug }}');
            if (chart) chart.destroy();
            this.initChart();
        },

        initChart() {
            const ctx = document.getElementById('chart-{{ $cat->slug }}');
            if (!ctx) return;
            const labels = this.selectedItem === 'Semua' ? this.itemList : [this.selectedItem];
            const isPiramida = this.activeDiagram === 'piramida';
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Laki-laki', data: labels.map(n => { let v = this.allYearsData[n].years[this.selectedTahun]?.lk || 0; return isPiramida ? -v : v; }), backgroundColor: '#2563eb', borderRadius: 6 },
                        { label: 'Perempuan', data: labels.map(n => this.allYearsData[n].years[this.selectedTahun]?.pr || 0), backgroundColor: '#db2777', borderRadius: 6 }
                    ]
                },
                options: {
                    indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true, ticks: { callback: v => Math.abs(v).toLocaleString('id-ID') }, grid: { color: '#f1f5f9' } },
                        y: { stacked: true, grid: { display: false } }
                    },
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: { callbacks: { label: (c) => c.dataset.label + ': ' + Math.abs(c.raw).toLocaleString('id-ID') } }
                    },
                    onClick: (e, elements) => { if (elements.length > 0) this.selectIndicator(Chart.getChart('chart-{{ $cat->slug }}').data.labels[elements[0].index]); }
                }
            });
        }
    };
}
</script>