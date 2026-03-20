<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistik Desa {{ $desa->nama_desa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-white p-2" x-data="statistikApp()">

    <div class="max-w-5xl mx-auto border rounded-xl shadow-sm overflow-hidden bg-white">
        <div class="bg-blue-600 p-4 text-white">
            <h1 class="text-lg font-bold uppercase tracking-wide">Grafik Statistik Desa {{ $desa->nama_desa }}</h1>
            <p class="text-xs opacity-75">Tahun Data: 2026</p>
        </div>

        <div class="flex overflow-x-auto bg-gray-50 border-b">
            @foreach($categories as $cat)
            <button 
                @click="switchTab('{{ $cat->slug }}')"
                :class="activeTab === '{{ $cat->slug }}' ? 'border-blue-600 text-blue-600 bg-white' : 'border-transparent text-gray-500 hover:text-gray-700'"
                class="px-6 py-4 text-sm font-bold uppercase border-b-2 transition-all whitespace-nowrap">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        <div class="p-6">
            <div class="relative w-full" style="min-height: 400px;">
                <canvas id="canvasStatistik"></canvas>
            </div>
            
            <template x-if="activeTab === 'kelompok-usia'">
                <div class="mt-4 flex justify-center space-x-6 text-sm font-semibold">
                    <span class="flex items-center"><span class="w-3 h-3 bg-blue-500 mr-2 rounded"></span> Laki-laki</span>
                    <span class="flex items-center"><span class="w-3 h-3 bg-pink-500 mr-2 rounded"></span> Perempuan</span>
                </div>
            </template>
        </div>
    </div>

    <script>
        function statistikApp() {
            return {
                activeTab: '{{ $categories->first()->slug }}',
                chartInstance: null,
                rawCategories: @json($categories),

                init() {
                    // Tunggu DOM siap lalu render grafik pertama
                    this.$nextTick(() => {
                        this.renderChart();
                    });
                },

                switchTab(slug) {
                    this.activeTab = slug;
                    this.renderChart();
                },

                renderChart() {
                    const ctx = document.getElementById('canvasStatistik').getContext('2d');
                    const category = this.rawCategories.find(c => c.slug === this.activeTab);
                    
                    if (this.chartInstance) {
                        this.chartInstance.destroy();
                    }

                    let config = {};

                    // LOGIKA KHUSUS: PIRAMIDA PENDUDUK (Untuk slug 'kelompok-usia')
                    if (this.activeTab === 'kelompok-usia') {
                        const labels = category.indicators.map(i => i.name);
                        const dataLK = category.indicators.map(i => {
                            const stat = i.statistics.find(s => s.gender === 'Laki-laki');
                            return stat ? -Math.abs(stat.value) : 0; // Nilai negatif untuk ke kiri
                        });
                        const dataPR = category.indicators.map(i => {
                            const stat = i.statistics.find(s => s.gender === 'Perempuan');
                            return stat ? Math.abs(stat.value) : 0; // Nilai positif untuk ke kanan
                        });

                        config = {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [
                                    { label: 'Laki-laki', data: dataLK, backgroundColor: '#3b82f6', borderRadius: 5 },
                                    { label: 'Perempuan', data: dataPR, backgroundColor: '#ec4899', borderRadius: 5 }
                                ]
                            },
                            options: {
                                indexAxis: 'y', // Membuat bar menjadi horizontal
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        ticks: {
                                            callback: (value) => Math.abs(value) // Tampilkan angka positif di sumbu X
                                        }
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: (context) => `${context.dataset.label}: ${Math.abs(context.raw)} Jiwa`
                                        }
                                    },
                                    legend: { display: false }
                                }
                            }
                        };
                    } 
                    
                    // LOGIKA UMUM: BAR CHART (Untuk Pendidikan, Agama, Pekerjaan)
                    else {
                        const labels = category.indicators.map(i => i.name);
                        const dataLK = category.indicators.map(i => {
                            const stat = i.statistics.find(s => s.gender === 'Laki-laki');
                            return stat ? stat.value : 0;
                        });
                        const dataPR = category.indicators.map(i => {
                            const stat = i.statistics.find(s => s.gender === 'Perempuan');
                            return stat ? stat.value : 0;
                        });

                        config = {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [
                                    { label: 'Laki-laki', data: dataLK, backgroundColor: '#60a5fa' },
                                    { label: 'Perempuan', data: dataPR, backgroundColor: '#f472b6' }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { position: 'top' }
                                },
                                scales: {
                                    y: { beginAtZero: true }
                                }
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