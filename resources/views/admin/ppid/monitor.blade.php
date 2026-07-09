<x-app-layout>
    @php
        $totalPermohonan = $permohonans->count();
        $totalPending = $permohonans->where('status', 'pending')->count();
        $totalSelesai = $permohonans->whereIn('status', ['selesai', 'diterima'])->count();
        $totalDitolak = $permohonans->whereIn('status', ['ditolak', 'tidak_lengkap'])->count();
        $totalKeberatan = $keberatans->count();
    @endphp

    <div x-data="{ search: '', status: 'semua' }"
         class="py-12 min-h-screen bg-slate-50 theme-bg-main">

        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white p-8 lg:p-10 mb-8 shadow-sm">
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-sky-500/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-amber-400/10"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-sky-300 mb-3">
                            Kabupaten Belitung Timur • Monitoring Layanan Informasi Publik
                        </p>

                        <h1 class="text-3xl font-black uppercase italic tracking-tight">
                            Monitor PPID Desa
                        </h1>

                        <p class="mt-3 text-sm text-slate-300 max-w-3xl leading-relaxed">
                            Pantau permohonan informasi, status tindak lanjut, dan keberatan informasi seluruh desa.
                        </p>
                    </div>

                    <div class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/10 px-6 py-4">
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-300">
                                Total Permohonan
                            </p>
                            <p class="text-2xl font-black text-sky-300">
                                {{ $totalPermohonan }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-5 mb-8">
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">Total</p>
                    <p class="text-3xl font-black text-slate-900 theme-text-main">{{ $totalPermohonan }}</p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-500 mb-2">Pending</p>
                    <p class="text-3xl font-black text-amber-500">{{ $totalPending }}</p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">Selesai</p>
                    <p class="text-3xl font-black text-emerald-600">{{ $totalSelesai }}</p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-red-500 mb-2">Ditolak</p>
                    <p class="text-3xl font-black text-red-600">{{ $totalDitolak }}</p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-500 mb-2">Keberatan</p>
                    <p class="text-3xl font-black text-rose-600">{{ $totalKeberatan }}</p>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 mb-8 shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Cari Desa / Pemohon / Status
                        </label>

                        <input type="text"
                               x-model="search"
                               placeholder="Ketik nama desa, pemohon, atau status..."
                               class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-sky-600">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Status
                        </label>

                        <select x-model="status"
                                class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-sky-600">
                            <option value="semua">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="selesai">Selesai / Diterima</option>
                            <option value="ditolak">Ditolak / Tidak Lengkap</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- REKAP PER DESA --}}
            <div class="space-y-5">
                @forelse($rekapDesa as $row)
                    @php
                        $desa = $row['desa'];
                        $statusGroup = 'pending';

                        if ($row['total_permohonan'] === 0) {
                            $statusGroup = 'kosong';
                        } elseif ($row['pending'] === 0 && $row['total_permohonan'] > 0) {
                            $statusGroup = 'selesai';
                        }

                        $searchText = strtolower(
                            ($desa->nama_desa ?? '') . ' ' .
                            ($desa->kecamatan ?? '') . ' ' .
                            $statusGroup
                        );
                    @endphp

                    <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm overflow-hidden"
                         data-search="{{ $searchText }}"
                         x-show="
                            (search === '' || '{{ $searchText }}'.includes(search.toLowerCase())) &&
                            (
                                status === 'semua' ||
                                (status === 'pending' && {{ $row['pending'] }} > 0) ||
                                (status === 'selesai' && {{ $row['pending'] }} === 0 && {{ $row['total_permohonan'] }} > 0) ||
                                (status === 'ditolak' && {{ $row['ditolak'] }} > 0)
                            )
                         "
                         x-transition>

                        <div class="p-6 lg:p-7 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                            <div class="flex items-center gap-5 min-w-0">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-lg text-white shrink-0
                                    {{ $row['pending'] > 0 ? 'bg-amber-500' : ($row['total_permohonan'] > 0 ? 'bg-emerald-500' : 'bg-slate-500') }}">
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

                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                <span class="rounded-2xl bg-sky-500/10 text-sky-600 px-4 py-3 text-center text-[10px] font-black uppercase tracking-widest">
                                    {{ $row['total_permohonan'] }} Total
                                </span>

                                <span class="rounded-2xl bg-amber-500/10 text-amber-600 px-4 py-3 text-center text-[10px] font-black uppercase tracking-widest">
                                    {{ $row['pending'] }} Pending
                                </span>

                                <span class="rounded-2xl bg-emerald-500/10 text-emerald-600 px-4 py-3 text-center text-[10px] font-black uppercase tracking-widest">
                                    {{ $row['selesai'] }} Selesai
                                </span>

                                <span class="rounded-2xl bg-red-500/10 text-red-600 px-4 py-3 text-center text-[10px] font-black uppercase tracking-widest">
                                    {{ $row['ditolak'] }} Ditolak
                                </span>

                                <span class="rounded-2xl bg-rose-500/10 text-rose-600 px-4 py-3 text-center text-[10px] font-black uppercase tracking-widest">
                                    {{ $row['keberatan'] }} Keberatan
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] bg-white theme-bg-card border border-dashed border-slate-200 theme-border p-12 text-center">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                            Data PPID belum tersedia.
                        </p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>