<div class="bg-white rounded-[3rem] shadow-2xl border border-slate-100"
     x-data="{
        selectedItem: 'Semua',
        selectedTahun: '{{ $tahun }}',
        activeDiagram: 'piramida',
        allYearsData: {
            @foreach($cat->indicators as $ind)
                '{{ addslashes($ind->name) }}': {
                    @foreach($ind->statistics->groupBy('year') as $year => $stats)
                        '{{ $year }}': {
                            lk: {{ $stats->where('gender', 'Laki-laki')->first()->value ?? 0 }},
                            pr: {{ $stats->where('gender', 'Perempuan')->first()->value ?? 0 }},
                            total: {{ $stats->sum('value') }}
                        },
                    @endforeach
                },
            @endforeach
        },

        init() {
            this.initChart();
            this.$watch('activeDiagram', () => this.updateChart());
            this.$watch('selectedItem', () => this.updateView());
            this.$watch('selectedTahun', () => this.updateChart());
        },

        get itemList() { return Object.keys(this.allYearsData); },

        // Menghitung angka card berdasarkan pilihan
        get currentStats() {
            let lk = 0, pr = 0, total = 0;
            if (this.selectedItem === 'Semua') {
                this.itemList.forEach(nama => {
                    const dataYear = this.allYearsData[nama][this.selectedTahun];
                    if (dataYear) { lk += dataYear.lk; pr += dataYear.pr; total += dataYear.total; }
                });
            } else {
                const d = this.allYearsData[this.selectedItem][this.selectedTahun];
                if (d) { lk = d.lk; pr = d.pr; total = d.total; }
            }
            return { lk, pr, total };
        },

        get topKelompok() {
            let maxTotal = 0;
            let namaKelompok = '-';
            this.itemList.forEach(nama => {
                const data = this.allYearsData[nama][this.selectedTahun];
                if (data && data.total > maxTotal) {
                    maxTotal = data.total;
                    namaKelompok = nama;
                }
            });
            return { nama: namaKelompok, jumlah: maxTotal };
        },

        // FUNGSI INTI: Menghubungkan klik Tabel & Grafik
        selectIndicator(name) {
            this.selectedItem = (this.selectedItem === name) ? 'Semua' : name;
        },

        updateView() {
            let chart = Chart.getChart('chart-{{ $cat->slug }}');
            if (!chart) return;

            const isPiramida = this.activeDiagram === 'piramida';
            const labels = this.selectedItem === 'Semua' ? this.itemList : [this.selectedItem];
            
            chart.data.labels = labels;
            chart.data.datasets[0].data = labels.map(n => {
                let val = this.allYearsData[n][this.selectedTahun]?.lk || 0;
                return isPiramida ? -val : val;
            });
            chart.data.datasets[1].data = labels.map(n => this.allYearsData[n][this.selectedTahun]?.pr || 0);

            chart.options.scales.x.stacked = isPiramida;
            chart.options.scales.y.stacked = isPiramida;
            chart.update();
        },

        updateChart() {
            let chart = Chart.getChart('chart-{{ $cat->slug }}');
            if (chart) { chart.destroy(); }
            this.initChart();
        },

        initChart() {
            const ctx = document.getElementById('chart-{{ $cat->slug }}');
            if (!ctx) return;
            const self = this; // Simpan konteks Alpine

            const isPiramida = this.activeDiagram === 'piramida';
            const labels = this.selectedItem === 'Semua' ? this.itemList : [this.selectedItem];

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Laki-laki',
                            data: labels.map(n => isPiramida ? -(this.allYearsData[n][this.selectedTahun]?.lk || 0) : (this.allYearsData[n][this.selectedTahun]?.lk || 0)),
                            backgroundColor: '#2563eb',
                            borderRadius: 8
                        },
                        {
                            label: 'Perempuan',
                            data: labels.map(n => (this.allYearsData[n][this.selectedTahun]?.pr || 0)),
                            backgroundColor: '#db2777',
                            borderRadius: 8
                        }
                    ]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    // KLIK PADA GRAFIK -> HUBUNGKAN KE TABEL
                    onClick: (e, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const labelClicked = Chart.getChart('chart-{{ $cat->slug }}').data.labels[index];
                            self.selectIndicator(labelClicked);
                        }
                    },
                    scales: {
                        x: {
                            stacked: isPiramida,
                            ticks: { callback: v => Math.abs(v) },
                            grid: { color: '#f1f5f9' }
                        },
                        y: { stacked: isPiramida, grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: true, position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(c) {
                                    return c.dataset.label + ': ' + Math.abs(c.raw).toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
     }"
     x-init="init()">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-10 items-start">
        <div class="space-y-6">
            <div class="grid grid-cols-2 gap-3 mb-4">
                <button type="button" 
                        onclick="eksporTabelDinamis('tabel-{{ $cat->slug }}', '{{ $cat->name }}')"
                        class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[9px] font-black rounded-xl uppercase transition-all shadow-md active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Export Excel {{ $cat->name }}</span>
                </button>
            </div>
            <div class="flex justify-between items-center mb-4">
                <h3 @click="selectedItem = 'Semua'; updateView()" class="text-lg font-black uppercase italic text-slate-700 cursor-pointer hover:text-blue-600 transition-colors">
                    Detail {{ str_replace('Data ', '', $cat->name) }} 
                    <span x-show="selectedItem !== 'Semua'" class="text-xs text-red-500 animate-pulse ml-2">(RESET)</span>
                </h3>
                <span class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black rounded-xl uppercase italic">STATISTIK AKTIF</span>
            </div>

            <div class="overflow-hidden rounded-[2rem] border border-slate-200 shadow-sm">
                <div class="max-h-[850px] overflow-y-auto">
                    <table id="tabel-{{ $cat->slug }}" class="w-full text-sm">
                        <thead class="bg-slate-900 text-white text-[10px] uppercase font-black tracking-widest text-center sticky top-0 z-10">
                            <tr>
                                <th class="p-4 text-left">Indikator</th>
                                <th class="p-4 italic text-center">LK</th>
                                <th class="p-4 italic text-center">PR</th>
                                <th class="p-4 bg-blue-800 text-center">%</th>
                                <th class="p-4 bg-slate-800 text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-bold text-[11px] uppercase">
                            @foreach($cat->indicators as $ind)
                                <tr x-show="selectedItem === 'Semua' || selectedItem === '{{ addslashes($ind->name) }}'" 
                                    @click="selectIndicator('{{ addslashes($ind->name) }}')"
                                    class="cursor-pointer transition-all duration-200"
                                    :class="selectedItem === '{{ addslashes($ind->name) }}' ? 'bg-blue-600 text-white shadow-inner' : 'hover:bg-blue-50'">
                                    
                                    <td class="p-4 font-black italic text-left">{{ $ind->name }}</td>
                                    <td class="p-4 text-center">{{ number_format($ind->statistics->where('year', $tahun)->where('gender', 'Laki-laki')->first()->value ?? 0, 0, ',', '.') }}</td>
                                    <td class="p-4 text-center">{{ number_format($ind->statistics->where('year', $tahun)->where('gender', 'Perempuan')->first()->value ?? 0, 0, ',', '.') }}</td>
                                    <td class="p-4 text-center bg-blue-50/10">
                                        @php
                                            $totalInd = $ind->statistics->where('year', $tahun)->sum('value');
                                            $totalCat = $cat->indicators->flatMap->statistics->where('year', $tahun)->sum('value');
                                        @endphp
                                        {{ $totalCat > 0 ? number_format(($totalInd / $totalCat) * 100, 1) : 0 }}%
                                    </td>
                                    <td class="p-4 text-center bg-slate-50/10">{{ number_format($totalInd, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-6 bg-blue-50 border border-blue-100 rounded-[2.5rem] p-8 flex items-center gap-6 shadow-sm">
                <div class="bg-blue-600 min-w-[64px] h-[64px] rounded-3xl shadow-lg flex items-center justify-center animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-blue-400 italic tracking-widest mb-1">Informasi Terpilih</p>
                    <h4 class="text-base font-bold text-slate-700 leading-tight">
                        <span x-text="selectedItem === 'Semua' ? 'Total Seluruh Penduduk' : 'Kelompok ' + selectedItem"></span>
                    </h4>
                    <p class="text-2xl font-black italic text-slate-900 mt-2">
                        Tercatat sebanyak <span class="text-blue-600" x-text="currentStats.total.toLocaleString('id-ID')"></span> jiwa.
                    </p>
                </div>
            </div>
        </div>

        <div class="lg:sticky lg:top-6 self-start h-fit flex flex-col items-center">
            <div class="flex bg-slate-100 p-1 rounded-2xl mb-6 shadow-inner border border-slate-200">
                <button @click="activeDiagram = 'piramida'"
                        :class="activeDiagram === 'piramida' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'"
                        class="px-6 py-2 rounded-xl text-[10px] font-black uppercase transition-all">
                    Piramida
                </button>
                <button @click="activeDiagram = 'batang'"
                        :class="activeDiagram === 'batang' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'"
                        class="px-6 py-2 rounded-xl text-[10px] font-black uppercase transition-all">
                    Batang
                </button>
            </div>

            <div class="w-full mb-8 bg-white rounded-[2rem] p-4 border border-slate-100 shadow-sm" style="height: 850px;">
                <canvas id="chart-{{ $cat->slug }}"></canvas>
            </div>

            <div class="grid grid-cols-3 gap-4 w-full">
                <div class="bg-blue-600 p-6 rounded-[2rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic text-center">LK</span>
                    <span class="text-2xl font-black italic" x-text="currentStats.lk.toLocaleString('id-ID')"></span>
                </div>
                <div class="bg-pink-500 p-6 rounded-[2rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic text-center">PR</span>
                    <span class="text-2xl font-black italic" x-text="currentStats.pr.toLocaleString('id-ID')"></span>
                </div>
                <div class="bg-slate-800 p-6 rounded-[2rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic text-center">TOTAL</span>
                    <span class="text-2xl font-black italic" x-text="currentStats.total.toLocaleString('id-ID')"></span>
                </div>
            </div>
        </div>
    </div>
</div>