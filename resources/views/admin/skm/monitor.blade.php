<x-app-layout>
    @php
        $totalResponden = $responses->count();
        $desaMengisi = $responses->pluck('desa_id')->filter()->unique()->count();

        $rataRataKabupaten = $responses->avg('nilai');
        $rataRataKabupaten = $rataRataKabupaten ? round($rataRataKabupaten, 2) : null;
    @endphp

    <div x-data="{ search: '', status: 'semua' }"
         class="py-12 min-h-screen bg-slate-50 theme-bg-main">

        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white p-8 lg:p-10 mb-8 shadow-sm">
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-indigo-500/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-amber-400/10"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-300 mb-3">
                            Kabupaten Belitung Timur • Survei Kepuasan Masyarakat
                        </p>

                        <h1 class="text-3xl font-black uppercase italic tracking-tight">
                            Monitor SKM Desa
                        </h1>

                        <p class="mt-3 text-sm text-slate-300 max-w-3xl leading-relaxed">
                            Pantau jumlah responden dan nilai survei kepuasan masyarakat pada layanan desa.
                        </p>
                    </div>

                    <div class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/10 px-6 py-4">
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-300">
                                Total Responden
                            </p>
                            <p class="text-2xl font-black text-indigo-300">
                                {{ $totalResponden }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Total Responden
                    </p>
                    <p class="text-3xl font-black text-slate-900 theme-text-main">
                        {{ $totalResponden }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-2">
                        Desa Mengisi
                    </p>
                    <p class="text-3xl font-black text-indigo-600">
                        {{ $desaMengisi }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">
                        Rata-Rata Kabupaten
                    </p>
                    <p class="text-3xl font-black text-emerald-600">
                        {{ $rataRataKabupaten ?? '-' }}
                    </p>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 mb-8 shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Cari Desa / Kecamatan
                        </label>

                        <input type="text"
                               x-model="search"
                               placeholder="Ketik nama desa atau kecamatan..."
                               class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-indigo-600">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Status Responden
                        </label>

                        <select x-model="status"
                                class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-indigo-600">
                            <option value="semua">Semua Desa</option>
                            <option value="ada">Sudah Ada Responden</option>
                            <option value="kosong">Belum Ada Responden</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- LIST --}}
            <div class="space-y-5">
                @forelse($rekapDesa as $row)
                    @php
                        $desa = $row['desa'];
                        $total = $row['total_responden'];
                        $rata = $row['rata_rata'];

                        $searchText = strtolower(
                            ($desa->nama_desa ?? '') . ' ' .
                            ($desa->kecamatan ?? '')
                        );
                    @endphp

                    <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm overflow-hidden"
                         x-show="
                            (search === '' || '{{ $searchText }}'.includes(search.toLowerCase())) &&
                            (
                                status === 'semua' ||
                                (status === 'ada' && {{ $total }} > 0) ||
                                (status === 'kosong' && {{ $total }} === 0)
                            )
                         "
                         x-transition>

                        <div class="p-6 lg:p-7 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                            <div class="flex items-center gap-5 min-w-0">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-lg text-white shrink-0
                                    {{ $total > 0 ? 'bg-indigo-600' : 'bg-slate-500' }}">
                                    {{ strtoupper(substr($desa->nama_desa ?? 'D', 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-lg lg:text-xl font-black uppercase italic text-slate-900 theme-text-main truncate">
                                        {{ $desa->nama_desa }}
                                    </h2>

                                    <p class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub truncate">
                                        {{ $desa->kecamatan ?? 'Kabupaten Belitung Timur' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 lg:justify-end">
                                <span class="inline-flex items-center justify-center rounded-2xl bg-indigo-500/10 text-indigo-600 px-5 py-3 text-[10px] font-black uppercase tracking-widest">
                                    {{ $total }} Responden
                                </span>

                                <span class="inline-flex items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600 px-5 py-3 text-[10px] font-black uppercase tracking-widest">
                                    Nilai {{ $rata ?? '-' }}
                                </span>

                                @if($total > 0)
                                    <span class="inline-flex items-center justify-center rounded-2xl bg-emerald-500/10 text-emerald-600 px-5 py-3 text-[10px] font-black uppercase tracking-widest">
                                        Terisi
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center rounded-2xl bg-slate-500/10 text-slate-500 px-5 py-3 text-[10px] font-black uppercase tracking-widest">
                                        Kosong
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] bg-white theme-bg-card border border-dashed border-slate-200 theme-border p-12 text-center">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                            Data SKM belum tersedia.
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>