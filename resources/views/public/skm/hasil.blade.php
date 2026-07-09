<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil SKM - {{ $desa->nama_desa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body class="bg-slate-100 min-h-screen">

    <div x-data="{ openModal: false, modalData: null }" class="py-6 lg:py-10">
        <div class="max-w-5xl mx-auto px-4">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-2xl text-white p-6 lg:p-8 mb-6 shadow-lg no-print"
                 style="background: #0f172a;">

                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-white/5"></div>
                <div class="absolute right-24 bottom-0 w-32 h-32 rounded-full bg-white/5"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <div>
                        <h1 class="text-base lg:text-lg font-black uppercase leading-snug">
                            Survei Kepuasan Masyarakat<br>
                            Terhadap Layanan Publik di<br>
                            Pemerintah Desa {{ strtoupper($desa->nama_desa) }}<br>
                            Kabupaten Belitung Timur
                        </h1>
                        <p class="mt-3 text-[11px] text-white/50">
                            {{ $desa->kecamatan }}
                        </p>
                    </div>

                    <a href="{{ route('public.skm.create', $desa->slug) }}"
                       class="inline-flex items-center gap-2 rounded-2xl bg-white/10 border border-white/15 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-white/20 transition-all shrink-0">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Isi Survey
                    </a>
                </div>
            </div>

            @if(empty($stats))
                <div class="bg-white rounded-[2rem] border border-slate-200 p-12 text-center shadow-sm">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <p class="text-sm font-black text-slate-400 uppercase tracking-widest">Belum Ada Data Survei</p>
                    <p class="text-xs text-slate-300 mt-2">Hasil survei akan ditampilkan setelah ada responden yang mengisi.</p>
                </div>
            @else

                {{-- ============================================ --}}
                {{-- NILAI IKM TERBARU --}}
                {{-- ============================================ --}}
                @if($latest)
                <div class="bg-white rounded-[2rem] border border-slate-200 p-8 lg:p-10 shadow-sm mb-6">
                    <div class="flex flex-col lg:flex-row items-center gap-8">

                        {{-- Angka IKM --}}
                        <div class="text-center lg:text-left shrink-0">
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-2">
                                Nilai IKM Terbaru
                            </p>
                            <div class="text-6xl lg:text-7xl font-black text-slate-900 leading-none">
                                {{ $latest['ikmTotal'] }}
                            </div>
                            <div class="mt-3 inline-flex rounded-full bg-blue-50 border border-blue-100 px-4 py-1.5">
                                <span class="text-xs font-black text-blue-600">{{ $latest['mutuTotal'] }}</span>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="hidden lg:block w-px h-28 bg-slate-100"></div>

                        {{-- Info Cards --}}
                        <div class="flex-1 grid grid-cols-2 gap-4 w-full">
                            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Responden</p>
                                <p class="text-2xl font-black text-slate-800">{{ $latest['totalResponden'] }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Periode</p>
                                <p class="text-sm font-black text-slate-800">{{ $latest['rekom']->tahun }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $latest['periodeMulai']->format('d M Y') }} – {{ $latest['periodeSelesai']->format('d M Y') }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Kode Survey</p>
                                <p class="text-sm font-black text-slate-800">{{ $latest['rekom']->kode_survey }}</p>
                            </div>
                            <div class="rounded-2xl bg-slate-50 border border-slate-100 p-4">
                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Perubahan</p>
                                @if($perbedaan !== null)
                                    <p class="text-lg font-black {{ $perbedaan >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
                                        {{ $perbedaan >= 0 ? '+' : '' }}{{ $perbedaan }}
                                    </p>
                                    <p class="text-[9px] text-slate-400">vs periode sebelumnya</p>
                                @else
                                    <p class="text-sm font-bold text-slate-300">—</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ============================================ --}}
                {{-- RIWAYAT NILAI IKM --}}
                {{-- ============================================ --}}
                <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden mb-6">
                    <div class="p-6 lg:p-7 border-b border-slate-100">
                        <h2 class="text-sm font-black text-slate-900 uppercase italic">Riwayat Nilai IKM</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="text-left py-3.5 px-6 text-[9px] font-black uppercase tracking-widest text-slate-400 w-12">No</th>
                                    <th class="text-left py-3.5 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Periode Survey</th>
                                    <th class="text-left py-3.5 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Kode</th>
                                    <th class="text-center py-3.5 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Responden</th>
                                    <th class="text-center py-3.5 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Nilai IKM</th>
                                    <th class="text-center py-3.5 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Mutu Pelayanan</th>
                                    <th class="text-center py-3.5 px-6 text-[9px] font-black uppercase tracking-widest text-slate-400">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats as $idx => $s)
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                        <td class="py-4 px-6 font-bold text-slate-400">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td class="py-4 px-4">
                                            <p class="text-sm font-bold text-slate-800">
                                                {{ $s['periodeMulai']->format('d M Y') }} – {{ $s['periodeSelesai']->format('d M Y') }}
                                            </p>
                                            <p class="text-[10px] text-slate-400 mt-0.5">Tahun {{ $s['rekom']->tahun }} · Rekom {{ $s['rekom']->nomor_rekom }}</p>
                                        </td>
                                        <td class="py-4 px-4 font-bold text-slate-600 tracking-wide">{{ $s['rekom']->kode_survey }}</td>
                                        <td class="py-4 px-4 text-center font-black text-slate-700">{{ $s['totalResponden'] }}</td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="text-lg font-black text-blue-600">{{ $s['ikmTotal'] }}</span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="inline-flex rounded-full px-3 py-1 text-[9px] font-black uppercase
                                                {{ $s['ikmTotal'] >= 88.31 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' :
                                                   ($s['ikmTotal'] >= 76.61 ? 'bg-blue-50 text-blue-600 border border-blue-100' :
                                                   ($s['ikmTotal'] >= 62.51 ? 'bg-amber-50 text-amber-600 border border-amber-100' :
                                                   'bg-red-50 text-red-500 border border-red-100')) }}">
                                                {{ $s['mutuTotal'] }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center justify-center gap-2">
                                                <button type="button"
                                                        @click="openModal = true; modalData = {{ json_encode($s) }}"
                                                        class="rounded-xl bg-slate-900 px-3 py-2 text-[9px] font-black uppercase text-white hover:bg-slate-800 transition-colors">
                                                    Detail
                                                </button>
                                                <a href="{{ route('public.skm.responden', [$desa->slug, $s['rekom']->id]) }}"
                                                class="rounded-xl bg-emerald-50 px-3 py-2 text-[9px] font-black uppercase text-emerald-600 hover:bg-emerald-100 transition-colors">
                                                    Responden
                                                </a>
                                                <a href="{{ route('public.skm.cetak', [$desa->slug, $s['rekom']->id]) }}"
                                                target="_blank"
                                                class="rounded-xl bg-blue-50 px-3 py-2 text-[9px] font-black uppercase text-blue-600 hover:bg-blue-100 transition-colors">
                                                    Cetak
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center">
                                            <p class="text-xs font-black uppercase tracking-widest text-slate-300">Belum ada data.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- MODAL DETAIL PER UNSUR --}}
                {{-- ============================================ --}}
                <template x-teleport="body">
                    <div x-show="openModal" x-cloak
                         class="fixed inset-0 z-50 flex items-center justify-center p-4"
                         @keydown.escape.window="openModal = false">

                        {{-- Overlay --}}
                        <div x-show="openModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                             @click="openModal = false"
                             class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

                        {{-- Modal Content --}}
                        <div x-show="openModal"
                             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                             class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">

                            {{-- Modal Header --}}
                            <div class="sticky top-0 bg-white rounded-t-[2rem] border-b border-slate-100 p-6 lg:p-8 z-10">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-blue-600 mb-1">Detail Nilai Per Unsur</p>
                                        <h3 class="text-sm font-black text-slate-900 uppercase">
                                            PEMERINTAH DESA {{ strtoupper($desa->nama_desa) }}
                                        </h3>
                                        <p class="text-[10px] text-slate-400 mt-1">
                                            Survei Kepuasan Masyarakat Terhadap Layanan Publik di Pemerintah Desa {{ strtoupper($desa->nama_desa) }} Kabupaten Belitung Timur
                                        </p>
                                    </div>
                                    <button type="button" @click="openModal = false"
                                            class="shrink-0 w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:bg-slate-200 hover:text-slate-600 transition-colors">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>

                                <div class="mt-4 flex items-center gap-6">
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Periode</p>
                                        <p class="text-xs font-bold text-slate-700" x-text="modalData ? (new Date(modalData.periodeMulai).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'}) + ' — ' + new Date(modalData.periodeSelesai).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'})) : ''"></p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Responden</p>
                                        <p class="text-xs font-bold text-slate-700" x-text="modalData?.totalResponden"></p>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Nilai IKM</p>
                                        <p class="text-lg font-black text-blue-600" x-text="modalData?.ikmTotal"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Body: Tabel Unsur --}}
                            <div class="p-6 lg:p-8">
                                <table class="w-full text-xs">
                                    <thead>
                                        <tr class="border-b-2 border-slate-200">
                                            <th class="text-left py-3 pr-4 text-[9px] font-black uppercase tracking-widest text-slate-400 w-8">No</th>
                                            <th class="text-left py-3 pr-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Unsur Pelayanan</th>
                                            <th class="text-center py-3 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Rata-rata</th>
                                            <th class="text-center py-3 px-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Nilai Konversi</th>
                                            <th class="text-center py-3 pl-4 text-[9px] font-black uppercase tracking-widest text-slate-400">Mutu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(u, i) in modalData?.unsurStats" :key="i">
                                            <tr class="border-b border-slate-50 hover:bg-slate-50/50">
                                                <td class="py-3 pr-4 font-bold text-slate-400" x-text="String(i+1).padStart(2,'0')"></td>
                                                <td class="py-3 pr-4 font-bold text-slate-700" x-text="u.name"></td>
                                                <td class="py-3 px-4 text-center font-bold text-slate-600" x-text="u.avg"></td>
                                                <td class="py-3 px-4 text-center">
                                                    <span class="font-black text-blue-600" x-text="u.ikm"></span>
                                                </td>
                                                <td class="py-3 pl-4 text-center">
                                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[9px] font-black uppercase"
                                                          :class="u.grade === 'A' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : (u.grade === 'B' ? 'bg-blue-50 text-blue-600 border border-blue-100' : (u.grade === 'C' ? 'bg-amber-50 text-amber-600 border border-amber-100' : 'bg-red-50 text-red-500 border border-red-100'))"
                                                          x-text="u.grade + ' - ' + u.gradeLabel"></span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t-2 border-slate-200 bg-slate-50">
                                            <td colspan="2" class="py-3.5 pr-4 text-[9px] font-black uppercase tracking-widest text-slate-500">Nilai IKM</td>
                                            <td class="py-3.5 px-4 text-center font-black text-slate-700" x-text="modalData?.avgTotal"></td>
                                            <td class="py-3.5 px-4 text-center">
                                                <span class="text-lg font-black text-blue-600" x-text="modalData?.ikmTotal"></span>
                                            </td>
                                            <td class="py-3.5 pl-4 text-center">
                                                <span class="inline-flex rounded-full bg-blue-50 border border-blue-100 px-3 py-1 text-[9px] font-black uppercase text-blue-600"
                                                      x-text="modalData?.mutuTotal"></span>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                {{-- Legend --}}
                                <div class="mt-6 rounded-2xl bg-slate-50 border border-slate-100 p-5">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-3">Keterangan Mutu Pelayanan</p>
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                                        <div class="flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full bg-emerald-500 shrink-0"></span>
                                            <span class="text-[10px] font-bold text-slate-500">A (≥ 88,31) Sangat Baik</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full bg-blue-500 shrink-0"></span>
                                            <span class="text-[10px] font-bold text-slate-500">B (76,61 – 88,30) Baik</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full bg-amber-500 shrink-0"></span>
                                            <span class="text-[10px] font-bold text-slate-500">C (62,51 – 76,60) Kurang Baik</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full bg-red-500 shrink-0"></span>
                                            <span class="text-[10px] font-bold text-slate-500">D (< 62,51) Tidak Baik</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

            @endif

            {{-- Footer --}}
            <div class="mt-8 text-center no-print">
                <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">
                    TARSIUS — Survei Kepuasan Masyarakat · Pemerintah Desa {{ strtoupper($desa->nama_desa) }}
                </p>
            </div>

        </div>
    </div>

</body>
</html>