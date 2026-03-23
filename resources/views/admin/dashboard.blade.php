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

    <div class="py-12 px-4 bg-slate-50 min-h-screen text-left">
        <div class="max-w-7xl mx-auto">
            
            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tighter uppercase italic text-left">Statistik Kabupaten</h2>
                    <p class="text-slate-500 font-bold text-sm tracking-widest uppercase text-left">Monitoring Sektoral Belitung Timur</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <button onclick="window.resetKeKabupaten()" class="bg-white border border-slate-200 px-4 py-2 rounded-xl text-[10px] font-black uppercase text-blue-600 shadow-sm hover:bg-blue-50 transition-all">
                        Reset Data Kabupaten
                    </button>
                    <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
                        <label class="text-[10px] font-black uppercase text-slate-400 ml-2 italic text-left">Tahun:</label>
                        <select name="tahun" onchange="this.form.submit()" class="bg-slate-50 border-none rounded-xl text-xs font-black uppercase focus:ring-blue-600 cursor-pointer">
                            @foreach($daftarTahun as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            {{-- MAP SECTION --}}
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 mb-8 overflow-hidden relative text-left">
                <div class="flex items-center gap-3 mb-6 relative z-20 text-left">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase italic tracking-tighter text-left">Radar Visualisasi Wilayah</h3>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest italic text-left">Klik area desa untuk memfilter statistik di bawah</p>
                    </div>
                </div>
                <div class="h-[500px] w-full flex items-center justify-center bg-[#f8fafc] rounded-[2rem] border border-slate-100 overflow-hidden relative">
                    <div id="petaVektor" class="w-full h-full cursor-move"></div>
                    
                    {{-- TOMBOL RESET MAP --}}
                    <button id="btnResetPeta" title="Reset Posisi Peta" class="absolute bottom-6 right-6 z-50 bg-white hover:bg-blue-600 hover:text-white p-4 rounded-2xl shadow-lg border border-slate-100 text-slate-600 transition-all active:scale-90">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- CHARTS SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-left">
                @foreach($categories as $category)
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative text-left">
                    <div class="flex items-center gap-3 mb-6 text-left">
                        <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                        <h3 class="text-sm font-black text-slate-800 uppercase italic tracking-tighter chart-title-{{ $category->id }} text-left">
                            TOTAL {{ $category->name }} (KABUPATEN)
                        </h3>
                    </div>
                    <div class="relative h-[300px]">
                        <canvas id="chart-{{ $category->id }}"></canvas>
                        <div id="empty-{{ $category->id }}" class="hidden absolute inset-0 flex flex-col items-center justify-center bg-white/90 z-10 rounded-2xl text-center">
                            <p class="text-slate-400 font-black italic uppercase text-[10px] tracking-widest text-center">Data Belum Diinput Desa</p>
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
        
        // Variabel Global untuk D3
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

            // FUNGSI TOMBOL RESET PETA & DATA
            d3.select("#btnResetPeta").on("click", function() {
                // 1. Reset Posisi Peta (Zoom & Pan ke posisi awal)
                mainSvg.transition()
                    .duration(750)
                    .call(mainZoom.transform, d3.zoomIdentity);

                // 2. KEMBALIKAN WARNA PETA (Hapus Highlight)
                // Semua desa dibuat terang kembali dan garis putih tipis
                mainG.selectAll("path")
                    .transition().duration(750)
                    .attr("fill-opacity", 1)       // Normal lagi (tidak pudar)
                    .attr("stroke", "#ffffff")    // Garis putih lagi
                    .attr("stroke-width", 1)      // Ketebalan normal
                    .style("filter", "none")      // Hapus bayangan penonjol
                    .style("transform", "scale(1)"); // Kembalikan ukuran normal

                // 3. Reset Data Grafik ke Kabupaten
                window.resetKeKabupaten();
                
                // 4. Sembunyikan tooltip jika masih nyangkut
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
                        // 1. IDENTIFIKASI UKURAN (Agar tidak error)
                        const container = d3.select("#petaVektor");
                        const w = container.node().getBoundingClientRect().width;
                        const h = container.node().getBoundingClientRect().height;

                        // 2. EFEK VISUAL: TANDA DESA TERPILIH (Highlight)
                        // Buat semua desa pudar dulu
                        mainG.selectAll("path")
                            .transition().duration(500)
                            .attr("fill-opacity", 0.1)
                            .attr("stroke", "#e2e8f0")
                            .attr("stroke-width", 0.5);

                        // Buat desa yang diklik jadi terang dan garisnya tebal
                        d3.select(this)
                            .raise()
                            .transition().duration(500)
                            .attr("fill-opacity", 1)
                            .attr("stroke", "#1e3a8a") // Warna Biru Tua sebagai penanda
                            .attr("stroke-width", 3)
                            .style("filter", "url(#drop-shadow)");

                        // 3. LOGIKA ZOOM KE DESA
                        const bounds = path.bounds(d);
                        const x = (bounds[0][0] + bounds[1][0]) / 2;
                        const y = (bounds[0][1] + bounds[1][1]) / 2;
                        const dx = bounds[1][0] - bounds[0][0];
                        const dy = bounds[1][1] - bounds[0][1];
                        
                        // Hitung skala (pake variabel w dan h yang baru kita ambil di atas)
                        const scale = Math.max(1, Math.min(8, 0.85 / Math.max(dx / w, dy / h)));
                        const translate = [w / 2 - scale * x, h / 2 - scale * y];

                        mainSvg.transition()
                            .duration(750)
                            .call(
                                mainZoom.transform,
                                d3.zoomIdentity.translate(translate[0], translate[1]).scale(scale)
                            );

                        // 4. LOGIKA UPDATE DATA GRAFIK
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