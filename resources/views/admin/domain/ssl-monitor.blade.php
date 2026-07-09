<x-app-layout>
    @php
        $nomorHelpdeskSpbe = '628111189349';

        $sslExpired = collect($sslRows)->filter(function ($row) {
            return strtolower($row['ssl_status'] ?? '') === 'expired';
        })->values();

        $pesanHelpdesk = "Yth. @Layanan Helpdesk Kementerian Komunikasi dan Digital RI Aplikasi SPBE,\n\n";
        $pesanHelpdesk .= "Mohon bantuan pengecekan/pembaruan SSL HTTPS untuk domain desa berikut:\n\n";

        foreach ($sslExpired as $index => $row) {
            $domain = $row['domain_name'] ?? '-';
            $status = strtoupper($row['ssl_status'] ?? 'EXPIRED');

            $pesanHelpdesk .= ($index + 1) . ". Domain: {$domain}\n";
            $pesanHelpdesk .= "   Status SSL: {$status}\n\n";
        }

        $pesanHelpdesk .= "Mohon bantuannya untuk tindak lanjut. Terima kasih.";

        $waHelpdeskUrl = 'https://wa.me/' . $nomorHelpdeskSpbe . '?text=' . rawurlencode($pesanHelpdesk);

        $totalSsl = collect($sslRows)->count();

        $sslActive = collect($sslRows)->filter(function ($row) {
            return strtolower($row['ssl_status'] ?? '') === 'active'
                && ($row['days_left'] ?? null) !== null
                && $row['days_left'] > 30;
        })->count();

        $sslKritis = collect($sslRows)->filter(function ($row) {
            return strtolower($row['ssl_status'] ?? '') === 'active'
                && ($row['days_left'] ?? null) !== null
                && $row['days_left'] <= 30;
        })->count();

        $sslExpiredCount = collect($sslRows)->filter(function ($row) {
            return strtolower($row['ssl_status'] ?? '') === 'expired';
        })->count();

        $sslInactive = collect($sslRows)->filter(function ($row) {
            return in_array(strtolower($row['ssl_status'] ?? ''), ['inactive', 'unknown']);
        })->count();
    @endphp

    <div class="py-12 min-h-screen bg-slate-50 theme-bg-main">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white p-8 lg:p-10 mb-8 shadow-sm">
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-rose-500/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-amber-400/10"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-rose-300 mb-3">
                            Kabupaten Belitung Timur • Monitoring Keamanan Website
                        </p>

                        <h1 class="text-3xl font-black uppercase italic tracking-tight">
                            Radar SSL / HTTPS Desa
                        </h1>

                        <p class="mt-3 text-sm text-slate-300 max-w-2xl leading-relaxed">
                            Pantau status sertifikat SSL/HTTPS website desa, masa berlaku, issuer, dan prioritas pembaruan SSL.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/10 px-6 py-4">
                            <div class="text-right">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-300">
                                    Total SSL
                                </p>
                                <p class="text-2xl font-black text-rose-300">
                                    {{ $totalSsl }}
                                </p>
                            </div>
                        </div>

                        @if($sslExpired->count() > 0)
                            <a href="{{ $waHelpdeskUrl }}"
                               target="_blank"
                               class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-emerald-700 shadow-lg shadow-emerald-900/20">
                                WA SSL Expired
                            </a>
                        @else
                            <button type="button"
                                    disabled
                                    class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/10 px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-400 cursor-not-allowed">
                                Tidak Ada SSL Expired
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-5 mb-8">
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Total SSL
                    </p>
                    <p class="text-3xl font-black text-slate-900 theme-text-main">
                        {{ $totalSsl }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">
                        Aktif
                    </p>
                    <p class="text-3xl font-black text-emerald-600">
                        {{ $sslActive }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-500 mb-2">
                        Kritis
                    </p>
                    <p class="text-3xl font-black text-amber-500">
                        {{ $sslKritis }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-red-500 mb-2">
                        Expired
                    </p>
                    <p class="text-3xl font-black text-red-600">
                        {{ $sslExpiredCount }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Tidak Aktif
                    </p>
                    <p class="text-3xl font-black text-slate-500">
                        {{ $sslInactive }}
                    </p>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 mb-8 shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Cari Nama Desa / Domain / Issuer
                        </label>

                        <input type="text"
                               id="filterKeyword"
                               placeholder="Ketik nama desa, domain, atau issuer..."
                               class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-rose-600">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Status SSL
                        </label>

                        <select id="filterStatus"
                                class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-rose-600">
                            <option value="semua">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="kritis">Kritis ≤ 30 Hari</option>
                            <option value="expired">Expired</option>
                            <option value="inactive">Tidak Aktif</option>
                            <option value="unknown">Tidak Diketahui</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- LIST SSL --}}
            <div class="space-y-5" id="sslList">
                @forelse($sslRows as $row)
                    @php
                        $sslStatus = strtolower($row['ssl_status'] ?? 'unknown');
                        $daysLeft = $row['days_left'];

                        if ($sslStatus === 'active' && $daysLeft !== null && $daysLeft <= 30) {
                            $filterStatus = 'kritis';
                            $displayStatus = 'Kritis';
                        } else {
                            $filterStatus = $sslStatus;
                            $displayStatus = match($sslStatus) {
                                'active' => 'Aktif',
                                'expired' => 'Expired',
                                'inactive' => 'Tidak Aktif',
                                default => 'Tidak Diketahui',
                            };
                        }

                        $statusClass = match($filterStatus) {
                            'active' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                            'kritis' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                            'expired' => 'bg-red-500/10 text-red-600 border-red-500/20',
                            'inactive' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                            default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                        };

                        $iconBg = match($filterStatus) {
                            'active' => 'bg-emerald-500',
                            'kritis' => 'bg-amber-500',
                            'expired' => 'bg-red-500',
                            default => 'bg-slate-500',
                        };

                        $daysColor = match($filterStatus) {
                            'active' => 'text-emerald-600',
                            'kritis' => 'text-amber-500',
                            'expired' => 'text-red-600',
                            default => 'text-slate-500',
                        };

                        $initial = strtoupper(substr($row['desa']->nama_desa ?? $row['domain_name'] ?? 'S', 0, 1));

                        $searchText = strtolower(
                            ($row['desa']->nama_desa ?? '') . ' ' .
                            ($row['domain_name'] ?? '') . ' ' .
                            ($row['issuer'] ?? '')
                        );
                    @endphp

                    <div class="ssl-row rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm overflow-hidden"
                         data-status="{{ $filterStatus }}"
                         data-search="{{ $searchText }}">

                        <button type="button"
                                onclick="this.nextElementSibling.classList.toggle('hidden')"
                                class="w-full p-6 lg:p-7 flex items-center justify-between gap-5 text-left hover:bg-slate-50 transition theme-bg-card">

                            <div class="flex items-center gap-5 min-w-0">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-lg text-white shrink-0 {{ $iconBg }}">
                                    {{ $initial }}
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-lg lg:text-xl font-black uppercase italic text-slate-900 theme-text-main truncate">
                                        {{ $row['domain_name'] ?? '-' }}
                                    </h2>

                                    <p class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub truncate">
                                        Desa {{ $row['desa']->nama_desa ?? '-' }}
                                        @if($row['issuer'] ?? false)
                                            • {{ $row['issuer'] }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-5 shrink-0">
                                <div class="hidden sm:block text-right">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                                        Sisa Hari SSL
                                    </p>

                                    <p class="text-2xl font-black {{ $daysColor }}">
                                        {{ $row['days_left'] !== null ? $row['days_left'] : '-' }}
                                    </p>
                                </div>

                                <span class="hidden md:inline-flex rounded-full border px-4 py-2 text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">
                                    {{ $displayStatus }}
                                </span>

                                <svg class="w-5 h-5 text-slate-400 theme-text-sub" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        <div class="hidden border-t border-slate-100 theme-border p-6 lg:p-7 bg-slate-50 theme-bg-main">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                                <div class="rounded-2xl bg-white theme-bg-card border border-slate-200 theme-border p-5">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                        Nama Desa
                                    </p>
                                    <p class="text-sm font-black text-slate-800 theme-text-main">
                                        {{ $row['desa']->nama_desa ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white theme-bg-card border border-slate-200 theme-border p-5">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                        Domain
                                    </p>
                                    <p class="text-sm font-black text-slate-800 theme-text-main">
                                        {{ $row['domain_name'] ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white theme-bg-card border border-slate-200 theme-border p-5">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                        Issuer SSL
                                    </p>
                                    <p class="text-sm font-black text-slate-800 theme-text-main">
                                        {{ $row['issuer'] ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white theme-bg-card border border-slate-200 theme-border p-5">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                        Berlaku Sampai
                                    </p>
                                    <p class="text-sm font-black text-slate-800 theme-text-main">
                                        {{ $row['valid_to'] ? $row['valid_to']->translatedFormat('d F Y') : '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-col sm:flex-row gap-3">
                                @if($row['domain_name'])
                                    <a href="https://{{ $row['domain_name'] }}"
                                       target="_blank"
                                       class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-blue-700">
                                        Buka Website
                                    </a>

                                    <a href="https://www.ssllabs.com/ssltest/analyze.html?d={{ $row['domain_name'] }}"
                                       target="_blank"
                                       class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-slate-800">
                                        Cek SSL Labs
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] bg-white theme-bg-card border border-dashed border-slate-200 theme-border p-12 text-center">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                            Data SSL belum tersedia.
                        </p>
                    </div>
                @endforelse
            </div>

            <div id="emptyFilterMessage"
                 class="hidden mt-8 rounded-[2rem] bg-white theme-bg-card border border-dashed border-slate-200 theme-border p-12 text-center">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                    Data SSL tidak ditemukan berdasarkan filter.
                </p>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const filterKeyword = document.getElementById('filterKeyword');
                const filterStatus = document.getElementById('filterStatus');
                const rows = document.querySelectorAll('.ssl-row');
                const emptyMessage = document.getElementById('emptyFilterMessage');

                function applySslFilter() {
                    const keyword = (filterKeyword.value || '').toLowerCase().trim();
                    const status = filterStatus.value;
                    let visibleCount = 0;

                    rows.forEach(row => {
                        const rowStatus = row.dataset.status || '';
                        const rowSearch = row.dataset.search || '';

                        const matchKeyword = keyword === '' || rowSearch.includes(keyword);
                        const matchStatus = status === 'semua' || rowStatus === status;

                        if (matchKeyword && matchStatus) {
                            row.classList.remove('hidden');
                            visibleCount++;
                        } else {
                            row.classList.add('hidden');
                        }
                    });

                    if (emptyMessage) {
                        emptyMessage.classList.toggle('hidden', visibleCount > 0);
                    }
                }

                filterKeyword.addEventListener('input', applySslFilter);
                filterStatus.addEventListener('change', applySslFilter);
            });
        </script>
    @endpush
</x-app-layout>