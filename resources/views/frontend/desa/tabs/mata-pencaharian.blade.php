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

        get topPekerjaanAktif() {
            let maxTotal = 0;
            let namaPekerjaan = '-';
            
            this.itemList.forEach(nama => {
                // Logika Skip: Abaikan kategori non-pekerjaan aktif
                const namaKecil = nama.toLowerCase();
                if (namaKecil.includes('belum') || 
                    namaKecil.includes('tidak') || 
                    namaKecil.includes('pelajar') || 
                    namaKecil.includes('mahasiswa')) return;

                const data = this.allYearsData[nama]?.[this.selectedTahun];
                if (data && data.total > maxTotal) {
                    maxTotal = data.total;
                    namaPekerjaan = nama;
                }
            });
            
            return { nama: namaPekerjaan, jumlah: maxTotal };
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
     }"
     x-init="init()">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-10 items-start text-left">
        <div class="space-y-6">
            {{-- TOMBOL EXPORT --}}
            <button type="button" 
                    onclick="eksporTabelDinamis('tabel-{{ $cat->slug }}', '{{ $cat->name }}')"
                    class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[9px] font-black rounded-xl uppercase transition-all shadow-md active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                <span>Export Excel {{ $cat->name }}</span>
            </button>

            <div class="flex justify-between items-center mb-4">
                <h3 @click="selectedItem = 'Semua'" class="text-lg font-black uppercase italic text-slate-700 cursor-pointer hover:text-blue-600 transition-all">
                    Detail Mata Pencaharian <span x-show="selectedItem !== 'Semua'" class="text-[10px] text-red-500 animate-pulse ml-2 font-black">(KLIK UNTUK RESET)</span>
                </h3>
                <span class="px-4 py-2 bg-slate-900 text-white text-[10px] font-black rounded-xl uppercase italic tracking-widest">STATISTIK AKTIF</span>
            </div>

            <div class="overflow-hidden rounded-[2rem] border border-slate-200 shadow-sm bg-white">
                <div class="max-h-[600px] overflow-y-auto">
                    {{-- FIX: TAMBAHKAN ID TABEL DI SINI --}}
                    <table id="tabel-{{ $cat->slug }}" class="w-full text-sm">
                        <thead class="bg-slate-900 text-white text-[10px] uppercase font-black tracking-widest text-center sticky top-0 z-10">
                            <tr>
                                <th class="p-4 text-left">Pekerjaan</th>
                                <th class="p-4 italic text-blue-300">LK</th>
                                <th class="p-4 italic text-pink-300">PR</th>
                                <th class="p-4 bg-blue-800">%</th>
                                <th class="p-4 bg-slate-800">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-bold text-[11px] uppercase">
                            <template x-for="(data, name) in allYearsData" :key="name">
                                <tr x-show="(selectedItem === 'Semua' || selectedItem === name) && data[selectedTahun]" 
                                    @click="selectIndicator(name)"
                                    class="cursor-pointer transition-all duration-200"
                                    :class="selectedItem === name ? 'bg-blue-600 text-white shadow-inner scale-[1.01]' : 'hover:bg-blue-50'">
                                    
                                    <td class="p-4 font-black italic text-left" x-text="name"></td>
                                    <td class="p-4 text-center" x-text="data[selectedTahun]?.lk.toLocaleString('id-ID')"></td>
                                    <td class="p-4 text-center" x-text="data[selectedTahun]?.pr.toLocaleString('id-ID')"></td>
                                    <td class="p-4 text-center font-black"
                                        :class="selectedItem === name ? 'text-blue-200' : 'text-blue-600'"
                                        x-text="data[selectedTahun] && currentStats.total > 0 ? ((data[selectedTahun].total / currentStats.total) * 100).toFixed(1) + '%' : '0%'">
                                    </td>
                                    <td class="p-4 text-center font-black" x-text="data[selectedTahun]?.total.toLocaleString('id-ID')"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- INSIGHT BOX --}}
            <div class="mt-6 bg-slate-900 rounded-[2.5rem] p-8 flex items-center gap-6 shadow-xl text-white border-b-8 border-indigo-600">
                <div class="bg-indigo-600 p-4 rounded-3xl animate-pulse shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-[10px] font-black uppercase text-indigo-400 italic tracking-widest mb-1">INSIGHT EKONOMI</p>
                    <h4 class="text-base font-bold italic mb-1 uppercase tracking-tight">
                        Pekerjaan Terbanyak: <span class="text-indigo-400 underline decoration-2" x-text="topPekerjaanAktif.nama"></span>
                    </h4>
                    <p class="text-2xl font-black italic tracking-tighter text-indigo-400">
                        Total: <span x-text="topPekerjaanAktif.jumlah.toLocaleString('id-ID')"></span> Jiwa
                    </p>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: GRAFIK --}}
        <div class="lg:sticky lg:top-6 self-start h-fit flex flex-col items-center w-full">
            <div class="flex bg-slate-100 p-1.5 rounded-2xl mb-6 shadow-inner border border-slate-200">
                <button @click="chartMode = 'doughnut'" :class="chartMode === 'doughnut' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'" class="px-8 py-2.5 rounded-xl text-[10px] font-black uppercase transition-all">Lingkaran</button>
                <button @click="chartMode = 'bar'" :class="chartMode === 'bar' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-400'" class="px-8 py-2.5 rounded-xl text-[10px] font-black uppercase transition-all">Batang</button>
            </div>

            <div class="w-full mb-8 bg-white rounded-[3rem] p-8 border border-slate-100 shadow-sm h-[500px] flex items-center justify-center relative">
                <canvas id="chart-{{ $cat->slug }}"></canvas>
            </div>

            <div class="grid grid-cols-3 gap-4 w-full">
                <div class="bg-blue-600 p-6 rounded-[2.5rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic">LK</span>
                    <span class="text-2xl font-black italic" x-text="currentStats.lk.toLocaleString('id-ID')"></span>
                </div>
                <div class="bg-pink-500 p-6 rounded-[2.5rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic">PR</span>
                    <span class="text-2xl font-black italic" x-text="currentStats.pr.toLocaleString('id-ID')"></span>
                </div>
                <div class="bg-slate-800 p-6 rounded-[2.5rem] text-white flex flex-col items-center shadow-lg transition-transform hover:scale-105">
                    <span class="text-[9px] font-black uppercase opacity-70 mb-1 italic">TOTAL</span>
                    <span class="text-2xl font-black italic" x-text="currentStats.total.toLocaleString('id-ID')"></span>
                </div>
            </div>
        </div>
    </div>
</div>