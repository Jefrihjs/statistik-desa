<x-app-layout>
    @php
        $totalDesa = count($desas);
        $totalTerisi = collect($desas)->filter(fn ($desa) => ($desa->total_input ?? 0) > 0)->count();
        $totalBelumInput = $totalDesa - $totalTerisi;
    @endphp

    <div x-data="{
            search: '',
            kecamatan: '',
            tahun: '{{ date('Y') }}'
        }"
        class="py-12 min-h-screen bg-slate-50 theme-bg-main">

        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white p-8 lg:p-10 mb-8 shadow-sm">
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-blue-500/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-amber-400/10"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-300 mb-3">
                            Kabupaten Belitung Timur • Monitoring Statistik
                        </p>

                        <h1 class="text-3xl font-black uppercase italic tracking-tight">
                            Status Laporan Desa
                        </h1>

                        <p class="mt-3 text-sm text-slate-300 max-w-2xl leading-relaxed">
                            Pantau progres input data statistik sektoral setiap desa berdasarkan tahun laporan.
                        </p>
                    </div>

                    <div class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/10 px-6 py-4">
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-300">
                                Total Wilayah
                            </p>
                            <p class="text-2xl font-black text-blue-300">
                                {{ $totalDesa }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Total Desa
                    </p>
                    <p class="text-3xl font-black text-slate-900 theme-text-main">
                        {{ $totalDesa }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">
                        Sudah Input
                    </p>
                    <p class="text-3xl font-black text-emerald-600">
                        {{ $totalTerisi }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-orange-500 mb-2">
                        Belum Input
                    </p>
                    <p class="text-3xl font-black text-orange-500">
                        {{ $totalBelumInput }}
                    </p>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 mb-8 shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_260px_220px] gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Cari Desa
                        </label>

                        <input type="text"
                               x-model="search"
                               placeholder="Ketik nama desa..."
                               class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Kecamatan
                        </label>

                        <select x-model="kecamatan"
                                class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                            <option value="">Semua Kecamatan</option>
                            @foreach($mapping as $kec => $daftarNamaDesa)
                                <option value="{{ $kec }}">{{ str_replace('KECAMATAN ', '', $kec) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Tahun
                        </label>

                        <select x-model="tahun"
                                class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                            @foreach($listTahun as $th)
                                <option value="{{ $th }}">Tahun {{ $th }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- LIST DESA --}}
            <div class="space-y-5">
                @foreach($desas as $index => $desa)
                    @php
                        $isTerisi = ($desa->total_input ?? 0) > 0;
                        $initial = strtoupper(substr($desa->nama_desa ?? 'D', 0, 1));
                    @endphp

                    <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm overflow-hidden"
                         x-show="(search === '' || '{{ strtoupper($desa->nama_desa) }}'.includes(search.toUpperCase())) &&
                                 (kecamatan === '' || '{{ strtoupper($desa->kecamatan) }}'.includes(kecamatan.toUpperCase()))"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0">

                        <div class="p-6 lg:p-7 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                            <div class="flex items-center gap-5 min-w-0">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-lg text-white shrink-0
                                    {{ $isTerisi ? 'bg-emerald-500' : 'bg-orange-500' }}">
                                    {{ $initial }}
                                </div>

                                <div class="min-w-0">
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>

                                        <span class="hidden sm:inline-flex w-1.5 h-1.5 rounded-full bg-slate-300"></span>

                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub truncate">
                                            {{ $desa->kecamatan ?? 'Kabupaten Belitung Timur' }}
                                        </span>
                                    </div>

                                    <h2 class="text-lg lg:text-xl font-black uppercase italic text-slate-900 theme-text-main truncate">
                                        {{ $desa->nama_desa }}
                                    </h2>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 lg:justify-end">
                                @if($isTerisi)
                                    <span class="inline-flex items-center justify-center rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-emerald-600">
                                        Terisi {{ $desa->total_input }} Data
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center rounded-2xl border border-orange-500/20 bg-orange-500/10 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-orange-500">
                                        Belum Input
                                    </span>
                                @endif

                                <a href="{{ route('admin.atur-form', $desa->id) }}"
                                   class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-slate-800">
                                    Atur Form
                                </a>

                                <a :href="'{{ url('/admin/entri') }}/' + '{{ $desa->id }}' + '?tahun=' + tahun"
                                   class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-blue-700 shadow-lg shadow-blue-900/20">
                                    Entri Data
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- FOOTER INFO --}}
            <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-2">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 theme-text-sub">
                    Total {{ $totalDesa }} wilayah desa
                </p>

                <div class="flex flex-wrap gap-4">
                    <span class="inline-flex items-center gap-2 text-[10px] font-black text-emerald-600 uppercase tracking-widest">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        Data Terisi
                    </span>

                    <span class="inline-flex items-center gap-2 text-[10px] font-black text-orange-500 uppercase tracking-widest">
                        <span class="w-2 h-2 bg-orange-400 rounded-full"></span>
                        Belum Input
                    </span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>