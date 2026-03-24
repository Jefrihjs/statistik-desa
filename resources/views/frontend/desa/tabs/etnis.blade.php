<div class="bg-white rounded-[3rem] shadow-2xl border border-slate-100"
     x-data="{
        selectedItems: [],
        selectedTahun: '{{ $tahun }}',
        chartMode: 'doughnut',

        allYearsData: {
            @foreach($cat->indicators->where('is_active', true) as $ind)
                @if(!in_array($ind->id, $hiddenIndIds ?? []))
                    '{{ addslashes($ind->name) }}': {
                        @foreach($ind->statistics->groupBy('year') as $year => $stats)
                            '{{ $year }}': {
                                lk: {{ $stats->where('gender', 'Laki-laki')->first()->value ?? 0 }},
                                pr: {{ $stats->where('gender', 'Perempuan')->first()->value ?? 0 }},
                                total: {{ $stats->sum('value') }}
                            },
                        @endforeach
                    },
                @endif
            @endforeach
        },

        init() {
            setTimeout(() => { this.initChart(); }, 150);
            this.$watch('chartMode', () => this.updateChart());
            this.$watch('selectedItems', () => this.updateChart());
            this.$watch('selectedTahun', () => this.updateChart());
        },

        get itemList() { return Object.keys(this.allYearsData); },

        isSelected(item) {
            return this.selectedItems.length === 0 || this.selectedItems.includes(item);
        },

        toggleItem(item) {
            if (this.selectedItems.includes(item)) {
                this.selectedItems = this.selectedItems.filter(i => i !== item);
            } else {
                this.selectedItems.push(item);
            }
        },

        get currentStats() {
            let lk = 0, pr = 0, total = 0;
            this.itemList.forEach(nama => {
                if (this.isSelected(nama)) {
                    const d = this.allYearsData[nama]?.[this.selectedTahun];
                    if (d) {
                        lk += d.lk || 0;
                        pr += d.pr || 0;
                        total += d.total || 0;
                    }
                }
            });
            return { lk, pr, total };
        },

        get topEtnis() {
            let max = 0, nama = '-';
            this.itemList.forEach(n => {
                const d = this.allYearsData[n]?.[this.selectedTahun];
                if (d && d.total > max) {
                    max = d.total;
                    nama = n;
                }
            });
            return { nama: nama, jumlah: max };
        },

        updateChart() {
            let chart = Chart.getChart('chart-{{ $cat->slug }}');
            if (chart) chart.destroy();
            this.initChart();
        },

        initChart() {
            const canvasId = 'chart-{{ $cat->slug }}';
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;

            const self = this;
            const isPie = this.chartMode === 'doughnut';

            // Filter labels berdasarkan yang dipilih
            let labels = this.itemList.filter(n => self.isSelected(n));
            if (labels.length === 0) labels = this.itemList;

            new Chart(ctx, {
                type: this.chartMode,
                data: {
                    labels: labels,
                    datasets: isPie ? [{
                        data: labels.map(n => self.allYearsData[n]?.[self.selectedTahun]?.total || 0),
                        backgroundColor: ['#1e3a8a','#2563eb','#3b82f6','#60a5fa','#f59e0b','#fbbf24','#fcd34d'],
                        hoverOffset: 30,
                        borderWidth: 5,
                        borderColor: '#ffffff'
                    }] : [
                        {
                            label: 'Laki-laki',
                            data: labels.map(n => self.allYearsData[n]?.[self.selectedTahun]?.lk || 0),
                            backgroundColor: '#2563eb',
                            borderRadius: 8
                        },
                        {
                            label: 'Perempuan',
                            data: labels.map(n => self.allYearsData[n]?.[self.selectedTahun]?.pr || 0),
                            backgroundColor: '#db2777',
                            borderRadius: 8
                        }
                    ]
                },
                options: {
                    indexAxis: isPie ? undefined : 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            onClick: (e, item) => { self.toggleItem(item.text); }
                        }
                    },
                    onClick: (evt, elements) => {
                        if (elements.length > 0) {
                            const i = elements[0].index;
                            self.toggleItem(labels[i]);
                        } else {
                            self.selectedItems = [];
                        }
                    }
                }
            });
        }
    }"
    x-init="init()">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-10 items-start">
        <div class="space-y-6">
            <div class="flex justify-between items-center bg-slate-50 p-4 rounded-[2rem] border border-slate-100 shadow-sm">
                <button type="button" 
                        onclick="eksporTabelDinamis('tabel-{{ $cat->slug }}', '{{ $cat->name }}')"
                        class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-black rounded-xl uppercase transition-all shadow-lg active:scale-95 shadow-emerald-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Export Excel {{ $cat->name }}</span>
                </button>
                <button x-show="selectedItems.length > 0" @click="selectedItems = []" class="text-[10px] font-black text-red-500 uppercase italic">Reset Pilihan</button>
            </div>

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-black uppercase italic text-slate-700">Data Sebaran Etnis</h3>
                <span class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black rounded-xl uppercase italic tracking-widest">STATISTIK AKTIF</span>
            </div>

            <div class="overflow-hidden rounded-[2.5rem] border border-slate-200 shadow-sm bg-white">
                <div class="max-h-[600px] overflow-y-auto custom-scrollbar">
                    <table id="tabel-{{ $cat->slug }}" class="w-full text-sm">
                        <thead class="bg-slate-900 text-white text-[10px] uppercase font-black tracking-widest text-center sticky top-0 z-10">
                            <tr>
                                <th class="p-4 text-left">Suku / Etnis</th>
                                <th class="p-4 italic">LK</th>
                                <th class="p-4 italic">PR</th>
                                <th class="p-4 bg-blue-800">%</th>
                                <th class="p-4 bg-slate-800">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-bold text-[11px] uppercase text-center">
                            <template x-for="name in itemList" :key="name">
                                <tr @click="toggleItem(name)"
                                    class="cursor-pointer transition-all duration-200"
                                    :class="selectedItems.includes(name) ? 'bg-blue-600 text-white shadow-inner' : 'hover:bg-blue-50'">
                                    <td class="p-4 font-black italic text-left" x-text="name"></td>
                                    <td class="p-4" x-text="allYearsData[name]?.[selectedTahun]?.lk.toLocaleString('id-ID') || 0"></td>
                                    <td class="p-4" x-text="allYearsData[name]?.[selectedTahun]?.pr.toLocaleString('id-ID') || 0"></td>
                                    <td class="p-4 font-black" :class="selectedItems.includes(name) ? 'text-white' : 'text-blue-700'"
                                        x-text="allYearsData[name]?.[selectedTahun] && currentStats.total > 0 ? ((allYearsData[name][selectedTahun].total / currentStats.total) * 100).toFixed(1) + '%' : '0%'">
                                    </td>
                                    <td class="p-4 font-black" :class="selectedItems.includes(name) ? 'text-white' : 'text-slate-900'" x-text="allYearsData[name]?.[selectedTahun]?.total.toLocaleString('id-ID') || 0"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 bg-amber-900 rounded-[2.5rem] p-8 flex items-center gap-6 shadow-xl text-white border-b-8 border-amber-600">
                <div class="bg-amber-600 p-4 rounded-3xl animate-pulse shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-amber-400 italic tracking-widest mb-1">Etnis Mayoritas</p>
                    <h4 class="text-base font-bold italic mb-1 uppercase tracking-tight">Suku Terbanyak: <span class="text-amber-400 underline decoration-2" x-text="topEtnis.nama"></span></h4>
                    <p class="text-2xl font-black italic tracking-tighter text-amber-400">Total: <span x-text="topEtnis.jumlah.toLocaleString('id-ID')"></span> Jiwa</p>
                </div>
            </div>
        </div>

        <div class="lg:sticky lg:top-6 self-start h-fit flex flex-col items-center">
            <div class="flex bg-slate-100 p-1 rounded-2xl mb-6 shadow-inner border border-slate-200">
                <button @click="chartMode = 'doughnut'" :class="chartMode === 'doughnut' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase transition-all">Lingkaran</button>
                <button @click="chartMode = 'bar'" :class="chartMode === 'bar' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'" class="px-6 py-2 rounded-xl text-[10px] font-black uppercase transition-all">Batang</button>
            </div>

            <div class="w-full mb-8 bg-white rounded-[3rem] p-4 border border-slate-100 shadow-sm h-[450px] flex items-center justify-center">
                <canvas id="chart-{{ $cat->slug }}"></canvas>
            </div>

            <div class="grid grid-cols-3 gap-4 w-full">
                <div class="p-6 rounded-[2.5rem] text-white flex flex-col items-center shadow-lg transition-all" 
                     :class="selectedItems.length > 0 ? 'bg-amber-600 ring-4 ring-amber-300' : 'bg-blue-600'">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic">LK</span>
                    <span class="text-2xl font-black italic" x-text="currentStats.lk.toLocaleString('id-ID')"></span>
                </div>
                <div class="p-6 rounded-[2.5rem] text-white flex flex-col items-center shadow-lg transition-all" 
                     :class="selectedItems.length > 0 ? 'bg-amber-600 ring-4 ring-amber-300' : 'bg-pink-500'">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic">PR</span>
                    <span class="text-2xl font-black italic" x-text="currentStats.pr.toLocaleString('id-ID')"></span>
                </div>
                <div class="p-6 rounded-[2.5rem] text-white flex flex-col items-center shadow-lg transition-all" 
                     :class="selectedItems.length > 0 ? 'bg-amber-600 ring-4 ring-amber-300' : 'bg-slate-800'">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic">TOTAL</span>
                    <span class="text-2xl font-black italic" x-text="currentStats.total.toLocaleString('id-ID')"></span>
                </div>
            </div>
        </div>
    </div>
</div>