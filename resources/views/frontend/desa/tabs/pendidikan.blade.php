<div class="bg-white rounded-[3rem] shadow-2xl border border-slate-100"
     x-data="{
        selectedItem: 'Semua',
        selectedTahun: '{{ $tahun }}',
        chartMode: 'doughnut',
        chart: null,

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
            // Gunakan timeout agar DOM siap
            setTimeout(() => { this.updateChart(); }, 150);

            this.$watch('chartMode', () => this.updateChart());
            this.$watch('selectedItem', () => this.updateChart());
            this.$watch('selectedTahun', () => this.updateChart());
        },

        get itemList() { return Object.keys(this.allYearsData); },

        // FUNGSI YANG TADI HILANG: Untuk handle klik tabel
        selectIndicator(name) {
            this.selectedItem = (this.selectedItem === name) ? 'Semua' : name;
        },

        get currentStats() {
            let lk = 0, pr = 0, total = 0;
            if (this.selectedItem === 'Semua') {
                this.itemList.forEach(nama => {
                    const dataYear = this.allYearsData[nama]?.[this.selectedTahun];
                    if (dataYear) {
                        lk += dataYear.lk || 0;
                        pr += dataYear.pr || 0;
                        total += dataYear.total || 0;
                    }
                });
            } else {
                const dataYear = this.allYearsData[this.selectedItem]?.[this.selectedTahun];
                if (dataYear) {
                    lk = dataYear.lk || 0;
                    pr = dataYear.pr || 0;
                    total = dataYear.total || 0;
                }
            }
            return { lk, pr, total };
        },

        get topPendidikan() {
            let maxTotal = 0; let namaPendidikan = '-';
            this.itemList.forEach(nama => {
                const data = this.allYearsData[nama]?.[this.selectedTahun];
                if (data && data.total > maxTotal) {
                    maxTotal = data.total;
                    namaPendidikan = nama;
                }
            });
            return { nama: namaPendidikan, jumlah: maxTotal };
        },

        updateChart() {
            const canvasId = 'chart-{{ $cat->slug }}';
            const globalChart = Chart.getChart(canvasId);
            if (globalChart) {
                globalChart.destroy();
            }
            this.initChart();
        },

        initChart() {
            const canvasId = 'chart-{{ $cat->slug }}';
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;

            const isPie = this.chartMode === 'doughnut';
            let labels = [];
            let dataValues = [];
            let bgColors = [];

            if (this.selectedItem !== 'Semua') {
                labels = ['Laki-laki', 'Perempuan'];
                dataValues = [this.currentStats.lk, this.currentStats.pr];
                bgColors = ['#10b981', '#3b82f6'];
            } else {
                labels = this.itemList;
                dataValues = labels.map(n => this.allYearsData[n]?.[this.selectedTahun]?.total || 0);
                bgColors = ['#059669', '#10b981', '#34d399', '#6ee7b7', '#3b82f6', '#60a5fa', '#93c5fd', '#f59e0b', '#f87171'];
            }

            this.chart = new Chart(ctx, {
                type: this.chartMode,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jiwa',
                        data: dataValues,
                        backgroundColor: bgColors,
                        hoverOffset: 30,
                        borderWidth: isPie ? 5 : 0,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    indexAxis: isPie ? undefined : 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (e, el) => {
                        if (el.length > 0) {
                            const clickedLabel = labels[el[0].index];
                            // Logic klik grafik: Jika Klik LK/PR di mode filter, jangan reset
                            if (this.selectedItem === 'Semua') {
                                this.selectIndicator(clickedLabel);
                            } else {
                                this.selectedItem = 'Semua';
                            }
                        }
                    },
                    plugins: {
                        legend: { display: true, position: 'bottom' }
                    }
                }
            });
        }
     }">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-10 items-start">
        <div class="space-y-6"> 
            <button type="button" 
                    @click="eksporTabelDinamis('tabel-{{ $cat->slug }}', 'Pendidikan')"
                    class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black rounded-xl uppercase transition-all shadow-lg active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export Excel {{ $cat->name }}</span>
            </button>

            <div class="flex justify-between items-center mb-4">
                <h3 @click="selectedItem = 'Semua'" class="text-lg font-black uppercase italic text-slate-700 cursor-pointer hover:text-emerald-600 transition-all">
                    Detail Jenjang Pendidikan <span x-show="selectedItem !== 'Semua'" class="text-xs text-red-500 animate-pulse ml-2">(RESET)</span>
                </h3>
                <span class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black rounded-xl uppercase italic tracking-widest">STATISTIK AKTIF</span>
            </div>

            <div class="overflow-hidden rounded-[2.5rem] border border-slate-200 shadow-sm bg-white">
                <div class="max-h-[750px] overflow-y-auto custom-scrollbar">
                    <table id="tabel-{{ $cat->slug }}" class="w-full text-sm">
                        <thead class="bg-slate-900 text-white text-[10px] uppercase font-black tracking-widest text-center sticky top-0 z-10">
                            <tr>
                                <th class="p-4 text-left">Jenjang</th>
                                <th class="p-4 italic">LK</th>
                                <th class="p-4 italic">PR</th>
                                <th class="p-4 bg-emerald-800">%</th>
                                <th class="p-4 bg-slate-800">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-bold text-[11px] uppercase">
                            <template x-for="name in itemList" :key="name">
                                <tr x-show="(selectedItem === 'Semua' || selectedItem === name)" 
                                    @click="selectIndicator(name)"
                                    class="cursor-pointer transition-all duration-200"
                                    :class="selectedItem === name ? 'bg-emerald-600 text-white shadow-inner' : 'hover:bg-emerald-50'">
                                    
                                    <td class="p-4 font-black italic text-left" x-text="name"></td>
                                    <td class="p-4 text-center" x-text="allYearsData[name]?.[selectedTahun]?.lk.toLocaleString('id-ID') || 0"></td>
                                    <td class="p-4 text-center" x-text="allYearsData[name]?.[selectedTahun]?.pr.toLocaleString('id-ID') || 0"></td>
                                    <td class="p-4 text-center bg-emerald-50/10 font-black"
                                        x-text="allYearsData[name]?.[selectedTahun] && currentStats.total > 0 ? ((allYearsData[name][selectedTahun].total / currentStats.total) * 100).toFixed(1) + '%' : '0%'">
                                    </td>
                                    <td class="p-4 text-center font-black" :class="selectedItem === name ? 'text-white' : 'text-slate-900'" x-text="allYearsData[name]?.[selectedTahun]?.total.toLocaleString('id-ID') || 0"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-slate-900 rounded-[2.5rem] p-8 flex items-center gap-6 shadow-xl text-white border-b-8 border-emerald-600">
                <div class="bg-emerald-600 p-4 rounded-3xl animate-pulse shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-emerald-400 italic tracking-[0.3em] mb-1">Edukasi Desa</p>
                    <h4 class="text-base font-bold italic mb-1 uppercase tracking-tight">
                        Mayoritas Pendidikan: <span class="text-emerald-400 underline decoration-2" x-text="topPendidikan.nama"></span>
                    </h4>
                    <p class="text-2xl font-black italic tracking-tighter text-emerald-400">
                        Total: <span x-text="topPendidikan.jumlah.toLocaleString('id-ID')"></span> Jiwa
                    </p>
                </div>
            </div>
        </div>

        <div class="lg:sticky lg:top-6 self-start h-fit flex flex-col items-center">
            <div class="flex bg-slate-100 p-1 rounded-2xl mb-6 shadow-inner border border-slate-200">
                <button @click="chartMode = 'doughnut'" :class="chartMode === 'doughnut' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase transition-all">Lingkaran</button>
                <button @click="chartMode = 'bar'" :class="chartMode === 'bar' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase transition-all">Batang</button>
            </div>

            <div class="w-full mb-8 bg-white rounded-[3rem] p-4 border border-slate-100 shadow-sm h-[450px] flex items-center justify-center">
                <canvas id="chart-{{ $cat->slug }}"></canvas>
            </div>

            <div class="grid grid-cols-3 gap-4 w-full font-black italic">
                <div class="bg-emerald-600 p-6 rounded-[2.5rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] uppercase opacity-70 mb-1">LK</span>
                    <span class="text-2xl" x-text="currentStats.lk.toLocaleString('id-ID')"></span>
                </div>
                <div class="bg-blue-500 p-6 rounded-[2.5rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] uppercase opacity-70 mb-1">PR</span>
                    <span class="text-2xl" x-text="currentStats.pr.toLocaleString('id-ID')"></span>
                </div>
                <div class="bg-slate-800 p-6 rounded-[2.5rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] uppercase opacity-70 mb-1">TOTAL</span>
                    <span class="text-2xl" x-text="currentStats.total.toLocaleString('id-ID')"></span>
                </div>
            </div>
        </div>
    </div>
</div>