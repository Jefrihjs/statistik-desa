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
                            Kabupaten Belitung Timur • Monitoring Infrastruktur
                        </p>

                        <h1 class="text-3xl font-black uppercase italic tracking-tight">
                            Radar Domain Desa
                        </h1>

                        <p class="mt-3 text-sm text-slate-300 max-w-2xl leading-relaxed">
                            Pantau masa aktif nama domain website desa, status kedaluwarsa, dan prioritas perpanjangan domain.
                        </p>
                    </div>

                    <div class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/10 px-6 py-4">
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-300">
                                Total Domain
                            </p>
                            <p class="text-2xl font-black text-amber-400">
                                {{ $domains->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUMMARY --}}
            @php
                $totalDomain = $domains->count();
                $domainSehat = $domains->where('status', 'Sehat')->count();
                $domainKritis = $domains->where('status', 'Kritis')->count();
                $domainExpired = $domains->where('status', 'Expired')->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Total Domain
                    </p>
                    <p class="text-3xl font-black text-slate-900 theme-text-main">
                        {{ $totalDomain }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">
                        Sehat
                    </p>
                    <p class="text-3xl font-black text-emerald-600">
                        {{ $domainSehat }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-500 mb-2">
                        Kritis
                    </p>
                    <p class="text-3xl font-black text-amber-500">
                        {{ $domainKritis }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-red-500 mb-2">
                        Expired
                    </p>
                    <p class="text-3xl font-black text-red-600">
                        {{ $domainExpired }}
                    </p>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 mb-8 shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Cari Nama Desa / Domain
                        </label>

                        <input type="text"
                               id="filterKeywordDomain"
                               placeholder="Ketik nama desa atau domain..."
                               class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Status Domain
                        </label>

                        <select id="filterStatusDomain"
                                class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                            <option value="semua">Semua Status</option>
                            <option value="sehat">Sehat</option>
                            <option value="kritis">Kritis</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- LIST DOMAIN --}}
            <div class="space-y-5" id="domainList">
                @forelse($domains as $domain)
                    @php
                        $status = $domain->status ?? 'Tidak Diketahui';

                        $statusClass = match($status) {
                            'Sehat' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                            'Kritis' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                            'Expired' => 'bg-red-500/10 text-red-600 border-red-500/20',
                            default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                        };

                        $initial = strtoupper(substr($domain->desa->nama_desa ?? $domain->domain_name ?? 'D', 0, 1));

                        $searchText = strtolower(
                            ($domain->desa->nama_desa ?? '') . ' ' .
                            ($domain->domain_name ?? '') . ' ' .
                            ($domain->desa->kecamatan ?? '')
                        );
                    @endphp

                    <div class="domain-row rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm overflow-hidden"
                         data-status="{{ strtolower($status) }}"
                         data-search="{{ $searchText }}">

                        <button type="button"
                                onclick="this.nextElementSibling.classList.toggle('hidden')"
                                class="w-full p-6 lg:p-7 flex items-center justify-between gap-5 text-left hover:bg-slate-50 transition theme-bg-card">

                            <div class="flex items-center gap-5 min-w-0">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-lg shrink-0
                                    {{ $status === 'Sehat' ? 'bg-emerald-500 text-white' : ($status === 'Kritis' ? 'bg-amber-500 text-white' : 'bg-red-500 text-white') }}">
                                    {{ $initial }}
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-lg lg:text-xl font-black uppercase italic text-slate-900 theme-text-main truncate">
                                        {{ $domain->domain_name }}
                                    </h2>

                                    <p class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub truncate">
                                        Desa {{ $domain->desa->nama_desa ?? '-' }}
                                        @if($domain->desa->kecamatan ?? false)
                                            • Kecamatan {{ $domain->desa->kecamatan }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-5 shrink-0">
                                <div class="hidden sm:block text-right">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                                        Sisa Hari
                                    </p>

                                    <p class="text-2xl font-black {{ $status === 'Sehat' ? 'text-emerald-600' : ($status === 'Kritis' ? 'text-amber-500' : 'text-red-600') }}">
                                        {{ $domain->days_left ?? '-' }}
                                    </p>
                                </div>

                                <span class="hidden md:inline-flex rounded-full border px-4 py-2 text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">
                                    {{ $status }}
                                </span>

                                <svg class="w-5 h-5 text-slate-400 theme-text-sub" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        <div class="hidden border-t border-slate-100 theme-border p-6 lg:p-7 bg-slate-50 theme-bg-main">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div class="rounded-2xl bg-white theme-bg-card border border-slate-200 theme-border p-5">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                        Nama Desa
                                    </p>
                                    <p class="text-sm font-black text-slate-800 theme-text-main">
                                        {{ $domain->desa->nama_desa ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white theme-bg-card border border-slate-200 theme-border p-5">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                        Domain
                                    </p>
                                    <p class="text-sm font-black text-slate-800 theme-text-main">
                                        {{ $domain->domain_name ?? '-' }}
                                    </p>
                                </div>

                                <div class="rounded-2xl bg-white theme-bg-card border border-slate-200 theme-border p-5">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                        Masa Berlaku
                                    </p>
                                    <p class="text-sm font-black text-slate-800 theme-text-main">
                                        @if($domain->expiry_date)
                                            {{ \Carbon\Carbon::parse($domain->expiry_date)->translatedFormat('d F Y') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 flex flex-col sm:flex-row gap-3">
                                @if($domain->domain_name)
                                    <a href="https://{{ $domain->domain_name }}"
                                       target="_blank"
                                       class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-blue-700">
                                        Buka Website
                                    </a>

                                    <a href="https://who.is/whois/{{ $domain->domain_name }}"
                                       target="_blank"
                                       class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-slate-800">
                                        Cek Whois
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] bg-white theme-bg-card border border-dashed border-slate-200 theme-border p-12 text-center">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                            Data domain belum tersedia.
                        </p>
                    </div>
                @endforelse
            </div>

            <div id="emptyDomainFilter"
                 class="hidden mt-8 rounded-[2rem] bg-white theme-bg-card border border-dashed border-slate-200 theme-border p-12 text-center">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                    Data domain tidak ditemukan berdasarkan filter.
                </p>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const keywordInput = document.getElementById('filterKeywordDomain');
                const statusInput = document.getElementById('filterStatusDomain');
                const rows = document.querySelectorAll('.domain-row');
                const emptyMessage = document.getElementById('emptyDomainFilter');

                function applyDomainFilter() {
                    const keyword = (keywordInput.value || '').toLowerCase().trim();
                    const status = statusInput.value;
                    let visibleCount = 0;

                    rows.forEach(row => {
                        const rowSearch = row.dataset.search || '';
                        const rowStatus = row.dataset.status || '';

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

                keywordInput.addEventListener('input', applyDomainFilter);
                statusInput.addEventListener('change', applyDomainFilter);
            });
        </script>
    @endpush
</x-app-layout>