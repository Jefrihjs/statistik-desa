<x-app-layout>
    <div class="py-12 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tighter uppercase italic">Statistik Kabupaten</h2>
                    <p class="text-slate-500 font-bold text-sm tracking-widest uppercase">Monitoring Sektoral Belitung Timur</p>
                </div>
                
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2 italic">Tahun Data:</label>
                    <select name="tahun" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl text-xs font-black uppercase focus:ring-blue-600 cursor-pointer">
                        @foreach($daftarTahun as $y)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-200 relative overflow-hidden group">
                    <div class="relative z-10">
                        <p class="text-blue-100 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Total Penduduk ({{ $tahun }})</p>
                        <h3 class="text-5xl font-black italic">{{ number_format($totalPenduduk, 0, ',', '.') }}</h3>
                        <p class="text-blue-200 text-xs font-bold mt-2 uppercase">Jiwa Terdata</p>
                    </div>
                    <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-blue-500 opacity-20 transform rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Progress Laporan</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-5xl font-black text-slate-800 italic">{{ $desaSudahInput }}</h3>
                        <p class="text-slate-400 font-black uppercase text-xs">/ {{ $totalDesa }} Desa</p> 
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full mt-4 overflow-hidden">
                        <div class="bg-green-500 h-full rounded-full" style="width: {{ $persenProgres }}%"></div>
                    </div>
                    <p class="text-[9px] text-green-600 font-black mt-2 uppercase italic">{{ number_format($persenProgres, 1) }}% Selesai</p>
                </div>

                <div class="bg-slate-800 rounded-[2.5rem] p-8 text-white flex flex-col justify-center shadow-xl relative overflow-hidden group">
                    <a href="{{ route('admin.kategori.index') }}" class="relative z-10 flex justify-between items-center group">
                        <div>
                            <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Pengaturan</p>
                            <h4 class="text-2xl font-black uppercase italic leading-tight">Manajemen<br>Kategori</h4>
                        </div>
                        <div class="bg-slate-700 p-4 rounded-3xl group-hover:bg-blue-600 transition-all">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                        </div>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                @php $agamaData = $categories->where('slug', 'agama')->first(); @endphp
                @if($agamaData)
                <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                        <h3 class="text-sm font-black text-slate-800 uppercase italic tracking-tighter">Sebaran Agama Kabupaten</h3>
                    </div>
                    <div class="relative" style="height: 300px;">
                        <canvas id="agamaChart"></canvas>
                    </div>
                </div>
                @endif

                @foreach($categories as $category)
                    @if($category->slug !== 'agama')
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                            <h3 class="text-sm font-black text-slate-800 uppercase italic tracking-tighter">
                                TOTAL {{ $category->name }}
                            </h3>
                        </div>
                        <div class="relative h-[300px]">
                            <canvas id="chart-{{ $category->id }}"></canvas>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Script Agama (Doughnut)
        @if($agamaData)
        new Chart(document.getElementById('agamaChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($agamaData->indicators->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($agamaData->indicators->map(fn($i) => $i->statistics->sum('value'))) !!},
                    backgroundColor: ['#1e3a8a', '#2563eb', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe', '#dbeafe', '#f1f5f9'],
                    borderWidth: 4, borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: { legend: { position: 'bottom', labels: { font: { size: 9, weight: 'bold' } } } }
            }
        });
        @endif

        // Script Looping Kategori Lain (Bar Chart)
        @foreach($categories as $category)
            @if($category->slug !== 'agama')
            new Chart(document.getElementById('chart-{{ $category->id }}').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($category->indicators->pluck('name')) !!},
                    datasets: [{
                        label: 'Jiwa',
                        data: {!! json_encode($category->indicators->map(fn($i) => $i->statistics->sum('value'))) !!},
                        backgroundColor: '#3b82f6', borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                        x: { grid: { display: false }, ticks: { font: { size: 8, weight: 'bold' } } }
                    }
                }
            });
            @endif
        @endforeach
    </script>
</x-app-layout>