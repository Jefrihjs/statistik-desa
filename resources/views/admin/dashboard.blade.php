<x-app-layout>
    <style>
        .d3-tooltip {
            position: absolute;
            z-index: 9999;
            background: #1e3a8a;
            color: white;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            pointer-events: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
            opacity: 0;
            transition: opacity 0.15s ease;
            border: 1px solid rgba(255,255,255,0.2);
        }
        path.cursor-pointer {
            transition: transform 0.2s ease-out;
            outline: none;
        }
    </style>

    <div class="py-12 min-h-screen bg-slate-50 theme-bg-main text-left">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

        @if (session('success'))
            <div class="mb-8 p-6 bg-emerald-50 border-l-4 border-emerald-500 rounded-3xl flex items-center gap-4 text-emerald-950 font-black text-xs sm:text-sm uppercase tracking-wider shadow-sm">
                <span>🟢</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white p-8 lg:p-10 mb-8 shadow-sm">
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-blue-500/10"></div>
            <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-amber-400/10"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-300 mb-3">
                        Kabupaten Belitung Timur • Pusat Kendali TARSIUS
                    </p>

                    <h1 class="text-3xl font-black uppercase italic tracking-tight">
                        Dashboard Kabupaten
                    </h1>

                    <p class="mt-3 text-sm text-slate-300 max-w-3xl leading-relaxed">
                        Pusat kendali administrasi, regulasi, statistik, informasi, keamanan website, dan layanan desa terintegrasi.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <form action="{{ route('admin.sync-all-demografi') }}" method="POST" class="inline-flex">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-2xl bg-blue-600 hover:bg-blue-700 border border-transparent px-6 py-4 text-xs font-black uppercase tracking-widest text-white transition-all shadow-lg shadow-blue-500/20">
                            Sinkronisasi Semua Data Demografi
                        </button>
                    </form>

                    <button onclick="window.resetKeKabupaten()"
                            class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/10 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-white/20">
                        Reset Kabupaten
                    </button>

                    <form action="{{ route('admin.dashboard') }}"
                          method="GET"
                          class="inline-flex items-center gap-3 rounded-2xl bg-white/10 border border-white/10 px-5 py-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-300">
                            Tahun
                        </label>

                        <select name="tahun"
                                onchange="this.form.submit()"
                                class="rounded-xl border-white/10 bg-white/10 text-white text-xs font-black uppercase focus:ring-blue-400">
                            @foreach($daftarTahun as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>

        {{-- KPI --}}
        @php
            $totalDesa = count($desas) > 0 ? count($desas) : 1;

            $trackers = \App\Models\DomainTracker::with('desa')->get();

            $domainSehat = $trackers->where('status', 'Sehat')->count();
            $domainKritis = $trackers->whereIn('status', ['Kritis', 'Expired'])->count();

            $sslChecker = app(\App\Services\SslCertificateService::class);

            $sslResults = $trackers->map(function ($tracker) use ($sslChecker) {
                return $sslChecker->check($tracker->domain_name);
            });

            $sslAman = $sslResults
                ->filter(function ($ssl) {
                    return $ssl['status'] === 'active'
                        && $ssl['days_left'] !== null
                        && $ssl['days_left'] > 30;
                })
                ->count();

            $sslKritis = $sslResults
                ->filter(function ($ssl) {
                    return in_array($ssl['status'], ['inactive', 'expired', 'unknown'])
                        || (
                            $ssl['status'] === 'active'
                            && $ssl['days_left'] !== null
                            && $ssl['days_left'] <= 30
                        );
                })
                ->count();

            $totalTargetDokumen = $totalDesa * 11;
            $totalDokumenTerisi = \DB::table('dokumen_antikorupsi')->whereNotNull('link_drive')->count();
            $persenAntikorupsi = $totalTargetDokumen > 0 ? round(($totalDokumenTerisi / $totalTargetDokumen) * 100) : 0;

            $skmTotalResponden = \Illuminate\Support\Facades\Schema::hasTable('skm_responses')
                ? \DB::table('skm_responses')->count()
                : 0;

            $skmDesaAktif = \Illuminate\Support\Facades\Schema::hasTable('skm_responses')
                ? \DB::table('skm_responses')->distinct('desa_id')->count('desa_id')
                : 0;

            $ppidTotalPermohonan = \Illuminate\Support\Facades\Schema::hasTable('ppid_permohonans')
                ? \DB::table('ppid_permohonans')->count()
                : 0;

            $ppidPending = \Illuminate\Support\Facades\Schema::hasTable('ppid_permohonans')
                ? \DB::table('ppid_permohonans')->where('status', 'pending')->count()
                : 0;

            $ppidKeberatan = \Illuminate\Support\Facades\Schema::hasTable('ppid_keberatans')
                ? \DB::table('ppid_keberatans')->count()
                : 0;
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6 gap-5 mb-8">
            <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Statistik Sektoral
                        </p>

                        <p class="text-3xl font-black text-slate-900 theme-text-main">
                            {{ $totalDesa }}
                        </p>

                        <p class="mt-2 text-[10px] font-bold text-blue-600 uppercase tracking-widest">
                            Wilayah Desa
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 20V10m7 10V4m7 16v-7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.domain.monitor') }}"
               class="group rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm hover:border-amber-500 transition">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Masa Aktif Domain
                        </p>

                        <p class="text-3xl font-black text-slate-900 theme-text-main group-hover:text-amber-600">
                            {{ $domainSehat }}
                        </p>

                        <p class="mt-2 text-[10px] font-bold text-rose-600 uppercase tracking-widest">
                            {{ $domainKritis }} Desa Kritis
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center group-hover:scale-105 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 3c2.5 2.5 4 5.5 4 9s-1.5 6.5-4 9M12 3c-2.5-2.5-4-5.5-4-9s1.5-6.5 4-9"/>
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('admin.ssl.monitor') }}"
               class="group rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm hover:border-rose-500 transition">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Enkripsi SSL
                        </p>

                        <p class="text-3xl font-black text-slate-900 theme-text-main group-hover:text-rose-600">
                            {{ $sslAman }}
                        </p>

                        <p class="mt-2 text-[10px] font-bold text-rose-600 uppercase tracking-widest">
                            {{ $sslKritis }} Butuh Pembaruan
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 flex items-center justify-center group-hover:scale-105 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11V7a4 4 0 118 0v4M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"/>
                        </svg>
                    </div>
                </div>
            </a>

            <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Kepatuhan Antikorupsi
                        </p>

                        <p class="text-3xl font-black text-slate-900 theme-text-main">
                            {{ $persenAntikorupsi }}%
                        </p>

                        <p class="mt-2 text-[10px] font-bold text-emerald-600 uppercase tracking-widest">
                            Berkas Terunggah
                        </p>
                    </div>

                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l7 3v5c0 5-3.5 9-7 10-3.5-1-7-5-7-10V6l7-3z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.skm.monitor') }}"
                class="group rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm hover:border-indigo-500 transition">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                Survei Kepuasan
                            </p>

                            <p class="text-3xl font-black text-slate-900 theme-text-main group-hover:text-indigo-600">
                                {{ $skmTotalResponden }}
                            </p>

                            <p class="mt-2 text-[10px] font-bold text-indigo-600 uppercase tracking-widest">
                                {{ $skmDesaAktif }} Desa Mengisi SKM
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-600 flex items-center justify-center group-hover:scale-105 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-4-2-3 2-3-2-4 2V6a2 2 0 012-2z"/>
                            </svg>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.ppid.monitor') }}"
                class="group rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm hover:border-sky-500 transition">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                Layanan PPID
                            </p>

                            <p class="text-3xl font-black text-slate-900 theme-text-main group-hover:text-sky-600">
                                {{ $ppidTotalPermohonan }}
                            </p>

                            <p class="mt-2 text-[10px] font-bold text-sky-600 uppercase tracking-widest">
                                {{ $ppidPending }} Pending • {{ $ppidKeberatan }} Keberatan
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-600 flex items-center justify-center group-hover:scale-105 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 7h10M7 11h10M7 15h6M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                            </svg>
                        </div>
                    </div>
                </a>
        </div>

        {{-- MAP SECTION --}}
        <div class="rounded-[2.5rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm p-6 lg:p-8 mb-8 overflow-hidden relative text-left">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6 relative z-20">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 theme-text-main uppercase italic tracking-tight">
                            Radar Visualisasi Wilayah
                        </h3>
                        <p class="text-[10px] text-slate-400 theme-text-sub font-black uppercase tracking-widest">
                            Klik area desa untuk memfilter statistik di bawah
                        </p>
                    </div>
                </div>

                <button onclick="window.resetKeKabupaten()"
                        class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-slate-800">
                    Reset Data
                </button>
            </div>

            <div class="h-[500px] w-full flex items-center justify-center bg-slate-50 theme-bg-main rounded-[2rem] border border-slate-200 theme-border overflow-hidden relative">
                <div id="petaVektor" class="w-full h-full cursor-move"></div>

                <button id="btnResetPeta"
                        title="Reset Posisi Peta"
                        class="absolute bottom-6 right-6 z-50 bg-white theme-bg-card hover:bg-blue-600 hover:text-white p-4 rounded-2xl shadow-lg border border-slate-200 theme-border text-slate-600 theme-text-main transition-all active:scale-90">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2.5"
                              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- CHARTS SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-left">
            @foreach($categories as $category)
                <div class="rounded-[2.5rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm p-6 lg:p-8 relative text-left">
                    <div class="flex items-center gap-3 mb-6 text-left">
                        <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>

                        <div>
                            <h3 class="text-sm font-black text-slate-900 theme-text-main uppercase italic tracking-tight chart-title-{{ $category->id }}">
                                TOTAL {{ $category->name }} (KABUPATEN)
                            </h3>

                            <p class="text-[10px] text-slate-400 theme-text-sub font-black uppercase tracking-widest">
                                Tahun {{ $tahun }}
                            </p>
                        </div>
                    </div>

                    <div class="relative h-[300px]">
                        <canvas id="chart-{{ $category->id }}"></canvas>

                        <div id="empty-{{ $category->id }}"
                             class="hidden absolute inset-0 flex flex-col items-center justify-center bg-white/90 theme-bg-card z-10 rounded-2xl text-center">
                            <p class="text-slate-400 theme-text-sub font-black italic uppercase text-[10px] tracking-widest text-center">
                                Data Belum Diinput Desa
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3@7"></script>

    <script>
        const allStats = @json($allStats);
        const categories = @json($categories);
        const desas = @json($desas);
        const charts = {};
        
        let mainSvg, mainZoom, mainG;

        function initCharts() {
            categories.forEach(cat => {
                const ctx = document.getElementById(`chart-${cat.id}`).getContext('2d');
                const labels = cat.indicators.map(i => i.name);
                const dataValues = cat.indicators.map(i => {
                    let sum = 0;
                    i.statistics.forEach(s => { if(s.year == {{ $tahun }}) sum += parseInt(s.value); });
                    return sum;
                });

                charts[cat.id] = new Chart(ctx, {
                    type: (cat.slug === 'agama' ? 'doughnut' : 'bar'),
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jiwa',
                            data: dataValues,
                            backgroundColor: (cat.slug === 'agama' ? ['#1e3a8a', '#2563eb', '#3b82f6', '#60a5fa', '#93c5fd'] : '#3b82f6'),
                            borderRadius: 8
                        }]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        plugins: { legend: { display: (cat.slug === 'agama') } }
                    }
                });
            });
        }

        window.updateAllCharts = function(idDesa, namaDesa) {
            const statsDesa = allStats[idDesa] || [];
            categories.forEach(cat => {
                const labels = cat.indicators.map(i => i.name);
                const newData = labels.map(label => {
                    return statsDesa
                        .filter(s => s.indicator && s.indicator.name === label)
                        .reduce((acc, curr) => acc + parseInt(curr.value), 0);
                });
                document.querySelector(`.chart-title-${cat.id}`).innerText = `TOTAL ${cat.name} (${namaDesa})`;
                charts[cat.id].data.datasets[0].data = newData;
                charts[cat.id].update();
                const emptyOverlay = document.getElementById(`empty-${cat.id}`);
                if (newData.every(v => v === 0)) emptyOverlay.classList.remove('hidden');
                else emptyOverlay.classList.add('hidden');
            });
        };

        window.resetKeKabupaten = function() {
            categories.forEach(cat => {
                const kabData = cat.indicators.map(i => {
                    let sum = 0;
                    i.statistics.forEach(s => { if(s.year == {{ $tahun }}) sum += parseInt(s.value); });
                    return sum;
                });
                document.querySelector(`.chart-title-${cat.id}`).innerText = `TOTAL ${cat.name} (KABUPATEN)`;
                charts[cat.id].data.datasets[0].data = kabData;
                charts[cat.id].update();
                document.getElementById(`empty-${cat.id}`).classList.add('hidden');
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            initCharts();

            const container = d3.select("#petaVektor");
            const tooltip = d3.select("body").append("div").attr("class", "d3-tooltip");

            let width = container.node().getBoundingClientRect().width || 800;
            let height = container.node().getBoundingClientRect().height || 500;

            mainSvg = container.append("svg").attr("width", "100%").attr("height", "100%").attr("viewBox", `0 0 ${width} ${height}`);
            
            const defs = mainSvg.append("defs");
            const filter = defs.append("filter").attr("id", "drop-shadow").attr("height", "130%");
            filter.append("feGaussianBlur").attr("in", "SourceAlpha").attr("stdDeviation", 3);
            filter.append("feOffset").attr("dx", 2).attr("dy", 2).attr("result", "offsetBlur");
            const feMerge = filter.append("feMerge");
            feMerge.append("feMergeNode").attr("in", "offsetBlur");
            feMerge.append("feMergeNode").attr("in", "SourceGraphic");

            mainG = mainSvg.append("g");
            mainZoom = d3.zoom().scaleExtent([1, 15]).on("zoom", (e) => mainG.attr("transform", e.transform));
            mainSvg.call(mainZoom);

            d3.select("#btnResetPeta").on("click", function() {
                mainSvg.transition()
                    .duration(750)
                    .call(mainZoom.transform, d3.zoomIdentity);

                mainG.selectAll("path")
                    .transition().duration(750)
                    .attr("fill-opacity", 1)      
                    .attr("stroke", "#ffffff")   
                    .attr("stroke-width", 1)     
                    .style("filter", "none")    
                    .style("transform", "scale(1)"); 

                window.resetKeKabupaten();
                d3.select(".d3-tooltip").style("opacity", 0);
            });

            const projection = d3.geoMercator();
            const path = d3.geoPath().projection(projection);

            d3.json("/maps/19.06_Belitung_Timur.geojson").then(function(data) {
                projection.fitSize([width - 40, height - 40], data);

                mainG.selectAll("path")
                    .data(data.features)
                    .enter()
                    .append("path")
                    .attr("d", path)
                    .attr("fill", (d, i) => ['#60a5fa', '#34d399', '#fbbf24', '#f87171', '#a78bfa'][i % 5])
                    .attr("stroke", "#ffffff")
                    .attr("stroke-width", 1)
                    .attr("class", "cursor-pointer")
                    .style("transform-origin", "center")
                    .on("mouseover", function(event, d) {
                        const namaPeta = (d.properties.nm_kelurahan || d.properties.NAMOBJ).toUpperCase();
                        tooltip.html(`DESA ${namaPeta}`).style("opacity", 1);
                        d3.select(this).raise().transition().duration(200).attr("stroke", "#1e3a8a").attr("stroke-width", 2).style("filter", "url(#drop-shadow)").style("transform", "scale(1.02)");
                    })
                    .on("mousemove", function(event) {
                        tooltip.style("left", (event.pageX + 15) + "px").style("top", (event.pageY - 35) + "px");
                    })
                    .on("mouseout", function() {
                        tooltip.style("opacity", 0);
                        d3.select(this).transition().duration(200).attr("stroke", "#ffffff").attr("stroke-width", 1).style("filter", "none").style("transform", "scale(1)");
                    })
                    .on("click", function(event, d) {
                        const container = d3.select("#petaVektor");
                        const w = container.node().getBoundingClientRect().width;
                        const h = container.node().getBoundingClientRect().height;

                        mainG.selectAll("path")
                            .transition().duration(500)
                            .attr("fill-opacity", 0.1)
                            .attr("stroke", "#e2e8f0")
                            .attr("stroke-width", 0.5);

                        d3.select(this)
                            .raise()
                            .transition().duration(500)
                            .attr("fill-opacity", 1)
                            .attr("stroke", "#1e3a8a") 
                            .attr("stroke-width", 3)
                            .style("filter", "url(#drop-shadow)");

                        const bounds = path.bounds(d);
                        const x = (bounds[0][0] + bounds[1][0]) / 2;
                        const y = (bounds[0][1] + bounds[1][1]) / 2;
                        const dx = bounds[1][0] - bounds[0][0];
                        const dy = bounds[1][1] - bounds[0][1];
                        
                        const scale = Math.max(1, Math.min(8, 0.85 / Math.max(dx / w, dy / h)));
                        const translate = [w / 2 - scale * x, h / 2 - scale * y];

                        mainSvg.transition()
                            .duration(750)
                            .call(
                                mainZoom.transform,
                                d3.zoomIdentity.translate(translate[0], translate[1]).scale(scale)
                            );

                        const namaPeta = (d.properties.nm_kelurahan || d.properties.NAMOBJ).toUpperCase();
                        let idFound = null; 
                        let nmResmi = namaPeta;
                        
                        desas.forEach(ds => { 
                            if (namaPeta.includes(ds.nama_desa.toUpperCase())) { 
                                idFound = ds.id; 
                                nmResmi = ds.nama_desa; 
                            } 
                        });

                        if (idFound) window.updateAllCharts(idFound, nmResmi);
                        else window.updateAllCharts(0, namaPeta);
                    });
            });
        });
    </script>
</x-app-layout>