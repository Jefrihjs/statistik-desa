<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa {{ $desa->nama_desa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
</head>
<body class="bg-slate-100 p-4" x-data="statistikApp()">

    <div class="max-w-5xl mx-auto border-none rounded-3xl shadow-xl overflow-hidden bg-white">
        {{-- HEADER --}}
        <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
            <div>
                <h1 class="text-xl font-black uppercase tracking-tighter italic">Grafik Statistik Desa {{ $desa->nama_desa }}</h1>
                <p class="text-[10px] font-bold opacity-80 uppercase tracking-[0.2em]">Data Sektoral • Tahun 2026</p>
            </div>
            {{-- TOMBOL EXPORT --}}
            <button @click="eksporData()" class="bg-white/20 hover:bg-white/40 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all flex items-center gap-2 border border-white/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export Excel
            </button>
        </div>

        {{-- TABS --}}
        <div class="flex overflow-x-auto bg-slate-50 border-b custom-scrollbar">
            @foreach($categories as $cat)
            <button 
                @click="switchTab('{{ $cat->slug }}')"
                :class="activeTab === '{{ $cat->slug }}' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-400 hover:text-gray-600'"
                class="px-8 py-5 text-xs font-black uppercase border-b-4 transition-all whitespace-nowrap italic">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        <div class="p-8">
            {{-- AREA GRAFIK --}}
            <div class="relative w-full bg-slate-50 rounded-[2rem] p-6 border border-slate-100" style="min-height: 450px;">
                <canvas id="canvasStatistik"></canvas>
            </div>
            
            {{-- KETERANGAN PIRAMIDA --}}
            <template x-if="activeTab === 'kelompok-usia'">
                <div class="mt-6 flex justify-center space-x-8 text-[10px] font-black uppercase italic tracking-widest">
                    <span class="flex items-center gap-2"><span class="w-4 h-4 bg-blue-500 rounded-lg shadow-sm"></span> Laki-laki (Kiri)</span>
                    <span class="flex items-center gap-2"><span class="w-4 h-4 bg-pink-500 rounded-lg shadow-sm"></span> Perempuan (Kanan)</span>
                </div>
            </template>

            {{-- TABEL HIDDEN (Untuk Keperluan Export Excel) --}}
            <div class="hidden">
                <table id="tabelExport">
                    <thead>
                        <tr>
                            <th>Indikator</th>
                            <th>Laki-laki</th>
                            <th>Perempuan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="ind in currentIndicators" :key="ind.name">
                            <tr>
                                <td x-text="ind.name"></td>
                                <td x-text="getValue(ind, 'Laki-laki')"></td>
                                <td x-text="getValue(ind, 'Perempuan')"></td>
                                <td x-text="parseInt(getValue(ind, 'Laki-laki')) + parseInt(getValue(ind, 'Perempuan'))"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function statistikApp() {
            return {
                activeTab: '{{ $categories->first()->slug }}',
                chartInstance: null,
                rawCategories: @json($categories),

                init() {
                    this.$nextTick(() => {
                        this.renderChart();
                    });
                },

                // Helper untuk ambil data di tabel export
                get currentIndicators() {
                    const cat = this.rawCategories.find(c => c.slug === this.activeTab);
                    return cat ? cat.indicators : [];
                },

                getValue(ind, gender) {
                    const s = ind.statistics.find(stat => stat.gender === gender);
                    return s ? s.value : 0;
                },

                switchTab(slug) {
                    this.activeTab = slug;
                    this.renderChart();
                },

                eksporData() {
                    const wb = XLSX.utils.table_to_book(document.getElementById('tabelExport'));
                    XLSX.writeFile(wb, `Statistik-${this.activeTab}-{{ $desa->nama_desa }}.xlsx`);
                },

                renderChart() {
                    const ctx = document.getElementById('canvasStatistik').getContext('2d');
                    const category = this.rawCategories.find(c => c.slug === this.activeTab);
                    
                    if (this.chartInstance) { this.chartInstance.destroy(); }

                    let config = {};
                    const labels = category.indicators.map(i => i.name);
                    const dataLK = category.indicators.map(i => {
                        const s = i.statistics.find(stat => stat.gender === 'Laki-laki');
                        return s ? s.value : 0;
                    });
                    const dataPR = category.indicators.map(i => {
                        const s = i.statistics.find(stat => stat.gender === 'Perempuan');
                        return s ? s.value : 0;
                    });

                    // JIKA KELOMPOK USIA (PIRAMIDA)
                    if (this.activeTab === 'kelompok-usia') {
                        config = {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [
                                    { label: 'Laki-laki', data: dataLK.map(v => -Math.abs(v)), backgroundColor: '#3b82f6', borderRadius: 8 },
                                    { label: 'Perempuan', data: dataPR, backgroundColor: '#ec4899', borderRadius: 8 }
                                ]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: { stacked: true, ticks: { callback: v => Math.abs(v) } },
                                    y: { stacked: true }
                                },
                                plugins: {
                                    tooltip: { callbacks: { label: (c) => `${c.dataset.label}: ${Math.abs(c.raw)} Jiwa` } },
                                    legend: { display: false }
                                }
                            }
                        };
                    } 
                    // JIKA UMUM (BAR)
                    else {
                        config = {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [
                                    { label: 'Laki-laki', data: dataLK, backgroundColor: '#60a5fa', borderRadius: 5 },
                                    { label: 'Perempuan', data: dataPR, backgroundColor: '#f472b6', borderRadius: 5 }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { position: 'top', labels: { font: { weight: 'bold' } } } },
                                scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } } }
                            }
                        };
                    }
                    this.chartInstance = new Chart(ctx, config);
                }
            }
        }
    </script>
</body>
</html>