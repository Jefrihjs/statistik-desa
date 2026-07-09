<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribusi Responden SKM - {{ $desa->nama_desa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="bg-slate-100 min-h-screen">

    <div class="py-6 lg:py-10">
        <div class="max-w-6xl mx-auto px-4">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-2xl text-white p-6 lg:p-8 mb-6 shadow-lg no-print"
                 style="background: #0f172a;">

                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-white/5"></div>
                <div class="absolute right-24 bottom-0 w-32 h-32 rounded-full bg-white/5"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-3">
                        <a href="{{ route('public.skm.hasil', $desa->slug) }}"
                           class="inline-flex items-center gap-1.5 text-[10px] font-bold text-white/50 hover:text-white transition-colors uppercase tracking-widest">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Kembali
                        </a>
                    </div>

                    <h1 class="text-base lg:text-lg font-black uppercase leading-snug">
                        Survei Kepuasan Masyarakat Terhadap Layanan Publik di<br>
                        Pemerintah Desa {{ strtoupper($desa->nama_desa) }} Kabupaten Belitung Timur
                    </h1>

                    <div class="mt-4 flex flex-wrap items-center gap-x-6 gap-y-2 text-[11px] text-white/50">
                        <span>Kode: {{ $rek->kode_survey }}</span>
                        <span>•</span>
                        <span>Rekom: {{ $rek->nomor_rekom }}</span>
                        <span>•</span>
                        <span>{{ $periodeMulai->translatedFormat('d F Y') }} – {{ $periodeSelesai->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- RINGKASAN ATAS --}}
            {{-- ============================================ --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm text-center">
                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-2">Total Responden</p>
                    <div class="text-5xl font-black text-slate-900">{{ $totalResponden }}</div>
                    <p class="text-[10px] text-slate-400 mt-1">orang (100%)</p>
                </div>
                <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm text-center">
                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-2">Nilai IKM</p>
                    <div class="text-5xl font-black text-blue-600">{{ $ikmTotal }}</div>
                    <div class="mt-2 inline-flex rounded-full bg-blue-50 border border-blue-100 px-4 py-1">
                        <span class="text-[10px] font-black text-blue-600">{{ $mutuTotal }}</span>
                    </div>
                </div>
                <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm text-center">
                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-slate-400 mb-2">Periode Survey</p>
                    <div class="text-lg font-black text-slate-900">{{ $periodeMulai->format('d M Y') }}</div>
                    <div class="text-sm font-bold text-slate-400">s/d {{ $periodeSelesai->format('d M Y') }}</div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- DISTRIBUSI (DATA TEKS) --}}
            {{-- ============================================ --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 p-6 lg:p-8 shadow-sm mb-6">
                <h2 class="text-sm font-black text-slate-900 uppercase italic mb-6">Distribusi Responden</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    {{-- USIA --}}
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-blue-600 mb-3">Umur / Usia</p>
                        <div class="space-y-2.5">
                            @foreach($ageDist as $a)
                                @php $pct = $totalResponden > 0 ? round(($a['count'] / $totalResponden) * 100, 1) : 0; @endphp
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[11px] font-bold text-slate-600 shrink-0">{{ $a['label'] }}</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full rounded-full bg-blue-500" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-700 w-16 text-right">{{ $a['count'] }} ({{ $pct }}%)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- JENIS KELAMIN --}}
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-pink-600 mb-3">Jenis Kelamin</p>
                        <div class="space-y-2.5">
                            @foreach($genderDist as $g)
                                @php $pct = $totalResponden > 0 ? round(($g['count'] / $totalResponden) * 100, 1) : 0; @endphp
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[11px] font-bold text-slate-600">{{ $g['label'] }}</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full rounded-full {{ $g['label'] === 'Laki-laki' ? 'bg-blue-500' : 'bg-pink-500' }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-700 w-16 text-right">{{ $g['count'] }} ({{ $pct }}%)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- PENDIDIKAN --}}
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-emerald-600 mb-3">Pendidikan Terakhir</p>
                        <div class="space-y-2.5">
                            @foreach($pendidikanDist as $p)
                                @php $pct = $totalResponden > 0 ? round(($p['count'] / $totalResponden) * 100, 1) : 0; @endphp
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[11px] font-bold text-slate-600">{{ $p['label'] }}</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-700 w-16 text-right">{{ $p['count'] }} ({{ $pct }}%)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- PEKERJAAN --}}
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-amber-600 mb-3">Pekerjaan</p>
                        <div class="space-y-2.5">
                            @foreach($pekerjaanDist as $pk)
                                @php $pct = $totalResponden > 0 ? round(($pk['count'] / $totalResponden) * 100, 1) : 0; @endphp
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-[11px] font-bold text-slate-600 truncate max-w-[100px]">{{ $pk['label'] }}</span>
                                    <div class="flex items-center gap-2">
                                        <div class="w-20 h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full rounded-full bg-amber-500" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-black text-slate-700 w-16 text-right">{{ $pk['count'] }} ({{ $pct }}%)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- LAYANAN (full width) --}}
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-violet-600 mb-3">Jenis Layanan yang Dinilai</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($layananDist as $l)
                            @php $pct = $totalResponden > 0 ? round(($l['count'] / $totalResponden) * 100, 1) : 0; @endphp
                            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-700">{{ $l['label'] }}</span>
                                <div class="text-right">
                                    <span class="text-lg font-black text-violet-600">{{ $l['count'] }}</span>
                                    <span class="text-[10px] text-slate-400 ml-1">({{ $pct }}%)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- GRAFIK DISTRIBUSI --}}
            {{-- ============================================ --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 p-6 lg:p-8 shadow-sm mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-sm font-black text-slate-900 uppercase italic">Grafik Distribusi Responden</h2>
                    <span class="text-[10px] font-bold text-slate-400">Total Sampel: {{ $totalResponden }} (100%)</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    {{-- PIE: USIA --}}
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 mb-4 text-center">Umur / Usia</p>
                        <div class="relative" style="max-width: 280px; margin: 0 auto;">
                            <canvas id="chartUsia"></canvas>
                        </div>
                    </div>

                    {{-- PIE: JENIS KELAMIN --}}
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 mb-4 text-center">Jenis Kelamin</p>
                        <div class="relative" style="max-width: 280px; margin: 0 auto;">
                            <canvas id="chartGender"></canvas>
                        </div>
                    </div>

                    {{-- PIE: PENDIDIKAN --}}
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 mb-4 text-center">Pendidikan Terakhir</p>
                        <div class="relative" style="max-width: 280px; margin: 0 auto;">
                            <canvas id="chartPendidikan"></canvas>
                        </div>
                    </div>

                    {{-- PIE: PEKERJAAN --}}
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-500 mb-4 text-center">Pekerjaan</p>
                        <div class="relative" style="max-width: 280px; margin: 0 auto;">
                            <canvas id="chartPekerjaan"></canvas>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ============================================ --}}
            {{-- NILAI PER UNSUR --}}
            {{-- ============================================ --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="p-6 lg:p-7 border-b border-slate-100">
                    <h2 class="text-sm font-black text-slate-900 uppercase italic">Nilai Per Unsur Pelayanan</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="text-left py-3 px-6 text-[9px] font-black uppercase tracking-widest text-slate-400 w-10">No</th>
                                <th class="text-left py-3 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Unsur Pelayanan</th>
                                <th class="text-center py-3 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Rata-rata</th>
                                <th class="text-center py-3 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Nilai Konversi</th>
                                <th class="text-center py-3 px-6 text-[9px] font-black uppercase tracking-widest text-slate-400">Mutu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unsurStats as $idx => $u)
                                <tr class="border-b border-slate-50 hover:bg-slate-50/50">
                                    <td class="py-3 px-6 font-bold text-slate-400">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-3 px-4 font-bold text-slate-700">{{ $u['name'] }}</td>
                                    <td class="py-3 px-4 text-center font-bold text-slate-600">{{ $u['avg'] }}</td>
                                    <td class="py-3 px-4 text-center font-black text-blue-600">{{ $u['ikm'] }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-[9px] font-black uppercase
                                            {{ $u['grade'] === 'A' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' :
                                               ($u['grade'] === 'B' ? 'bg-blue-50 text-blue-600 border border-blue-100' :
                                               ($u['grade'] === 'C' ? 'bg-amber-50 text-amber-600 border border-amber-100' :
                                               'bg-red-50 text-red-500 border border-red-100')) }}">
                                            {{ $u['grade'] }} - {{ $u['gradeLabel'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-slate-50 border-t-2 border-slate-200">
                                <td colspan="2" class="py-3.5 px-6 text-[9px] font-black uppercase tracking-widest text-slate-500">Nilai IKM</td>
                                <td class="py-3.5 px-4 text-center font-black text-slate-700">{{ $avgTotal }}</td>
                                <td class="py-3.5 px-4 text-center text-lg font-black text-blue-600">{{ $ikmTotal }}</td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex rounded-full bg-blue-50 border border-blue-100 px-3 py-1 text-[9px] font-black uppercase text-blue-600">{{ $mutuTotal }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Footer --}}
            <div class="text-center no-print">
                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">
                    TARSIUS — Distribusi Responden SKM · Pemerintah Desa {{ strtoupper($desa->nama_desa) }}
                </p>
            </div>

        </div>
    </div>

    {{-- ============================================ --}}
    {{-- CHART.JS --}}
    {{-- ============================================ --}}
    <script>
        const chartColors = [
            '#2563eb', '#f59e0b', '#10b981', '#ef4444',
            '#8b5cf6', '#ec4899', '#06b6d4', '#f97316', '#84cc16'
        ];

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 16,
                        usePointStyle: true,
                        pointStyleWidth: 10,
                        font: { size: 10, weight: '700', family: 'Inter' },
                        color: '#64748b',
                    }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { size: 11, weight: '800', family: 'Inter' },
                    bodyFont: { size: 11, weight: '600', family: 'Inter' },
                    padding: 12,
                    cornerRadius: 12,
                    callbacks: {
                        label: function(ctx) {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                            return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                        }
                    }
                }
            }
        };

        // Usia
        new Chart(document.getElementById('chartUsia'), {
            type: 'pie',
            data: {
                labels: @json(array_column($ageDist, 'label')),
                datasets: [{
                    data: @json(array_column($ageDist, 'count')),
                    backgroundColor: chartColors.slice(0, 4),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: chartOptions
        });

        // Gender
        new Chart(document.getElementById('chartGender'), {
            type: 'pie',
            data: {
                labels: @json(array_column($genderDist, 'label')),
                datasets: [{
                    data: @json(array_column($genderDist, 'count')),
                    backgroundColor: ['#2563eb', '#ec4899'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: chartOptions
        });

        // Pendidikan
        @php $pendCount = count($pendidikanDist); @endphp
        new Chart(document.getElementById('chartPendidikan'), {
            type: 'pie',
            data: {
                labels: @json(array_column($pendidikanDist, 'label')),
                datasets: [{
                    data: @json(array_column($pendidikanDist, 'count')),
                    backgroundColor: chartColors.slice(0, {{ $pendCount }}),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: chartOptions
        });

        // Pekerjaan
        @php $pekCount = count($pekerjaanDist); @endphp
        new Chart(document.getElementById('chartPekerjaan'), {
            type: 'pie',
            data: {
                labels: @json(array_column($pekerjaanDist, 'label')),
                datasets: [{
                    data: @json(array_column($pekerjaanDist, 'count')),
                    backgroundColor: chartColors.slice(0, {{ $pekCount }}),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: chartOptions
        });
    </script>

</body>
</html>