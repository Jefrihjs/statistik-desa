<x-app-layout>
    <div class="py-12 min-h-screen bg-slate-50 theme-bg-main">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white p-8 lg:p-10 mb-8 shadow-sm">
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-blue-500/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-amber-400/10"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-300 mb-3">
                            Kabupaten Belitung Timur • Audit Aktivitas User
                        </p>

                        <h1 class="text-3xl font-black uppercase italic tracking-tight">
                            Log Aktivitas Desa
                        </h1>

                        <p class="mt-3 text-sm text-slate-300 max-w-3xl leading-relaxed">
                            Pantau aktivitas operator desa pada modul Statistik, PPID, Antikorupsi, SKM, Branding, Domain, dan SSL.
                        </p>
                    </div>

                    <div class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/10 px-6 py-4">
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-300">
                                Total Log
                            </p>
                            <p class="text-2xl font-black text-blue-300">
                                {{ $logs->total() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER --}}
            <form method="GET"
                  class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 mb-8 shadow-sm">

                <div class="grid grid-cols-1 lg:grid-cols-[1fr_260px_240px_160px] gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Cari Aktivitas / User / Desa
                        </label>
                        <input type="text"
                               name="keyword"
                               value="{{ request('keyword') }}"
                               placeholder="Ketik kata kunci..."
                               class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Desa
                        </label>
                        <select name="desa_id"
                                class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                            <option value="">Semua Desa</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id }}" {{ request('desa_id') == $desa->id ? 'selected' : '' }}>
                                    {{ $desa->nama_desa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Modul
                        </label>
                        <select name="module"
                                class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                            <option value="">Semua Modul</option>
                            @foreach($modules as $module)
                                <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>
                                    {{ $module }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center rounded-2xl bg-blue-600 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-blue-700">
                            Filter
                        </button>
                    </div>
                </div>
            </form>

            {{-- LIST --}}
            <div class="space-y-5">
                @forelse($logs as $log)
                    <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm overflow-hidden">
                        <div class="p-6 lg:p-7 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                            <div class="flex items-center gap-5 min-w-0">
                                <div class="w-14 h-14 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-lg shrink-0">
                                    {{ strtoupper(substr($log->desa->nama_desa ?? $log->user->name ?? 'L', 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="inline-flex rounded-full bg-blue-500/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-blue-600">
                                            {{ $log->module ?? 'Sistem' }}
                                        </span>

                                        <span class="inline-flex rounded-full bg-slate-500/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-slate-500">
                                            {{ $log->method ?? '-' }}
                                        </span>
                                    </div>

                                    <h2 class="text-lg font-black uppercase italic text-slate-900 theme-text-main">
                                        {{ $log->action }}
                                    </h2>

                                    <p class="mt-1 text-sm text-slate-500 theme-text-sub">
                                        {{ $log->description ?? '-' }}
                                    </p>

                                    <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                                        {{ $log->user->name ?? 'User tidak ditemukan' }}
                                        •
                                        {{ $log->desa->nama_desa ?? 'Tanpa Desa' }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-left lg:text-right shrink-0">
                                <p class="text-xs font-black text-slate-900 theme-text-main">
                                    {{ $log->created_at->translatedFormat('d F Y') }}
                                </p>

                                <p class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                                    {{ $log->created_at->format('H:i') }} WIB
                                </p>

                                <p class="mt-2 text-[10px] text-slate-400 theme-text-sub">
                                    IP: {{ $log->ip_address ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] bg-white theme-bg-card border border-dashed border-slate-200 theme-border p-12 text-center">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                            Belum ada log aktivitas.
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $logs->links() }}
            </div>

        </div>
    </div>
</x-app-layout>