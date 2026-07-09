<x-app-layout>
    @php
        $desaAktif = $desa ?? auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);

        $headerColor = $desaAktif->header_color ?? '#475569';
        $accentColor = $desaAktif->accent_color ?? '#064e3b';

        $totalAduan = $aduans->total();
        $baru = \App\Models\Aduan::where('desa_id', auth()->user()->desa_id)->where('status', 'baru')->count();
        $diproses = \App\Models\Aduan::where('desa_id', auth()->user()->desa_id)->where('status', 'diproses')->count();
        $selesai = \App\Models\Aduan::where('desa_id', auth()->user()->desa_id)->where('status', 'selesai')->count();

        // URL publik
        $aduanUrl = route('public.aduan.create', $desaAktif->slug);
        $cekStatusUrl = route('public.aduan.check-status', $desaAktif->slug);
        $desaSlug = str_replace(' ', '-', $desaAktif->nama_desa);
    @endphp

    <div x-data="{ openModal: false, modalData: null, openQr: false }" class="py-12 min-h-screen bg-slate-50 theme-bg-main">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

            {{-- BACK --}}
            <div class="mb-4">
                <a href="{{ route('desa.dashboard') }}"
                   class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 theme-text-sub hover:text-slate-800 transition-colors">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Hub Utama TARSIUS
                </a>
            </div>

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] text-white p-8 lg:p-10 mb-8 shadow-sm"
                 style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }});">
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-white/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-white/10"></div>
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/80 mb-3">
                            TARSIUS • Layanan Aduan Desa
                        </p>
                        <h1 class="text-3xl font-black uppercase italic tracking-tight">
                            Monitoring Aduan Masyarakat
                        </h1>
                        <p class="mt-3 text-sm text-white/85 max-w-3xl leading-relaxed">
                            Kelola aduan masyarakat, tindak lanjut laporan, dan dokumentasi penyelesaian aduan desa.
                        </p>
                    </div>
                    <a href="{{ $aduanUrl }}" target="_blank"
                       class="inline-flex items-center justify-center rounded-2xl bg-white/15 border border-white/20 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-white/25 shadow-lg">
                        Buka Form Publik
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8 rounded-[2rem] border border-emerald-200 bg-emerald-50 px-6 py-5 text-sm font-bold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ============================================ --}}
            {{-- LINK, EMBED & QR CODE --}}
            {{-- ============================================ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                {{-- FORM ADUAN --}}
                <div class="bg-white theme-bg-card rounded-[2rem] border border-slate-200 theme-border p-7 shadow-sm">
                    <div class="flex items-start gap-4 mb-5">
                        <div class="shrink-0 w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center">
                            <svg width="18" height="18" fill="none" stroke="#e11d48" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-slate-900 theme-text-main uppercase italic">Form Aduan Publik</h2>
                            <p class="text-[10px] text-slate-400 font-bold">Embed form pengaduan masyarakat.</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Link Langsung</label>
                        <div class="flex items-stretch gap-2">
                            <div class="flex-1 rounded-xl border border-slate-200 theme-border bg-slate-50 theme-bg-main px-3 py-2.5 text-xs font-bold text-slate-600 truncate select-all" id="aduanUrlText">{{ $aduanUrl }}</div>
                            <button type="button" onclick="copyText('aduanUrlText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <span>Salin</span>
                            </button>
                            <a href="{{ $aduanUrl }}" target="_blank" class="shrink-0 rounded-xl bg-rose-600 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-rose-700 transition-colors flex items-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Kode Embed (iframe)</label>
                        <div class="flex items-stretch gap-2">
                            <pre class="flex-1 rounded-xl border border-slate-200 theme-border bg-slate-50 theme-bg-main px-3 py-2.5 text-[10px] font-mono text-slate-600 overflow-x-auto whitespace-pre-wrap break-all select-all" id="aduanEmbedText">&lt;iframe src="{{ $aduanUrl }}" width="100%" height="900" frameborder="0" style="border:none; border-radius:12px;"&gt;&lt;/iframe&gt;</pre>
                            <button type="button" onclick="copyText('aduanEmbedText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <span>Salin</span>
                            </button>
                        </div>
                        <p class="text-[8px] text-slate-300 mt-1.5">Height 900px agar form tampil utuh tanpa scroll di dalam iframe.</p>
                    </div>
                </div>

                {{-- CEK STATUS --}}
                <div class="bg-white theme-bg-card rounded-[2rem] border border-slate-200 theme-border p-7 shadow-sm">
                    <div class="flex items-start gap-4 mb-5">
                        <div class="shrink-0 w-10 h-10 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center">
                            <svg width="18" height="18" fill="none" stroke="#d97706" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-slate-900 theme-text-main uppercase italic">Cek Status Aduan</h2>
                            <p class="text-[10px] text-slate-400 font-bold">Embed halaman cek status aduan.</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Link Langsung</label>
                        <div class="flex items-stretch gap-2">
                            <div class="flex-1 rounded-xl border border-slate-200 theme-border bg-slate-50 theme-bg-main px-3 py-2.5 text-xs font-bold text-slate-600 truncate select-all" id="cekUrlText">{{ $cekStatusUrl }}</div>
                            <button type="button" onclick="copyText('cekUrlText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <span>Salin</span>
                            </button>
                            <a href="{{ $cekStatusUrl }}" target="_blank" class="shrink-0 rounded-xl bg-amber-500 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-amber-600 transition-colors flex items-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Kode Embed (iframe)</label>
                        <div class="flex items-stretch gap-2">
                            <pre class="flex-1 rounded-xl border border-slate-200 theme-border bg-slate-50 theme-bg-main px-3 py-2.5 text-[10px] font-mono text-slate-600 overflow-x-auto whitespace-pre-wrap break-all select-all" id="cekEmbedText">&lt;iframe src="{{ $cekStatusUrl }}" width="100%" height="500" frameborder="0" style="border:none; border-radius:12px;"&gt;&lt;/iframe&gt;</pre>
                            <button type="button" onclick="copyText('cekEmbedText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <span>Salin</span>
                            </button>
                        </div>
                        <p class="text-[8px] text-slate-300 mt-1.5">Height 500px untuk form cek kode aduan.</p>
                    </div>
                </div>

                {{-- QR CODE --}}
                <button type="button" @click="openQr = true; generateQr()"
                        class="rounded-[2rem] border-2 border-dashed border-rose-200 bg-white theme-bg-card p-7 shadow-sm hover:bg-rose-50/50 hover:border-rose-300 transition-all text-left group cursor-pointer">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-10 h-10 rounded-2xl bg-rose-50 border border-rose-200 group-hover:bg-rose-100 flex items-center justify-center transition-colors">
                            <svg width="20" height="20" fill="none" stroke="#e11d48" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-sm font-black text-slate-900 theme-text-main uppercase italic">QR Code Aduan</h2>
                            <p class="text-[10px] text-slate-400 font-bold mt-1">Generate & unduh QR code untuk link form aduan publik.</p>
                            <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-rose-100 border border-rose-200 px-3 py-1">
                                <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[9px] font-black text-rose-600">PNG · PNG HD · SVG</span>
                            </div>
                        </div>
                    </div>
                </button>

            </div>

            {{-- ============================================ --}}
            {{-- SUMMARY --}}
            {{-- ============================================ --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">Total Aduan</p>
                    <p class="text-3xl font-black text-slate-900 theme-text-main">{{ $totalAduan }}</p>
                </div>
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-500 mb-2">Baru</p>
                    <p class="text-3xl font-black text-rose-600">{{ $baru }}</p>
                </div>
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-500 mb-2">Diproses</p>
                    <p class="text-3xl font-black text-amber-500">{{ $diproses }}</p>
                </div>
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">Selesai</p>
                    <p class="text-3xl font-black text-emerald-600">{{ $selesai }}</p>
                </div>
            </div>

            {{-- ============================================ --}}
            {{-- FILTER --}}
            {{-- ============================================ --}}
            <form method="GET" class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 mb-8 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-[1fr_220px_160px] gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">Cari Aduan</label>
                        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Ketik judul, nama pelapor, atau kode aduan..."
                               class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-rose-600">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">Status</label>
                        <select name="status" class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-rose-600">
                            <option value="">Semua Status</option>
                            <option value="baru" {{ request('status') === 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-2xl bg-rose-600 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-rose-700">Filter</button>
                    </div>
                </div>
            </form>

            {{-- ============================================ --}}
            {{-- LIST --}}
            {{-- ============================================ --}}
            <div class="space-y-5">
                @forelse($aduans as $aduan)
                    @php
                        $statusClass = match($aduan->status) {
                            'baru' => 'bg-rose-500/10 text-rose-600 border-rose-500/20',
                            'diproses' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                            'selesai' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                            'ditolak' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                            default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                        };

                        $waUrl = null;

                        if ($aduan->jenis_identitas !== 'anonim' && $aduan->no_hp) {
                            $nomorWa = preg_replace('/[^0-9]/', '', $aduan->no_hp);
                            if (str_starts_with($nomorWa, '0')) {
                                $nomorWa = '62' . substr($nomorWa, 1);
                            }
                            $pesanWa = "Halo Bapak/Ibu {$aduan->nama_pelapor},\n\n"
                                . "Aduan Anda dengan kode {$aduan->kode_aduan} saat ini berstatus " . strtoupper($aduan->status) . ".\n\n"
                                . "Tanggapan:\n"
                                . ($aduan->tanggapan ?: 'Aduan sudah diterima dan sedang menunggu tindak lanjut.') . "\n\n"
                                . "Terima kasih.\n"
                                . "Pemerintah Desa " . ($desaAktif->nama_desa ?? '-');
                            $waUrl = 'https://wa.me/' . $nomorWa . '?text=' . rawurlencode($pesanWa);
                        }
                    @endphp

                    <div x-data="{ open: false }" class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm overflow-hidden">

                        {{-- RINGKASAN --}}
                        <div class="p-6 lg:p-7">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-5">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-3">
                                        <span class="inline-flex rounded-full border px-3 py-1 text-[9px] font-black uppercase tracking-widest {{ $statusClass }}">{{ strtoupper($aduan->status) }}</span>
                                        <span class="inline-flex rounded-full bg-slate-500/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-slate-500">{{ $aduan->kode_aduan }}</span>
                                        <span class="inline-flex rounded-full bg-blue-500/10 px-3 py-1 text-[9px] font-black uppercase tracking-widest text-blue-600">{{ strtoupper($aduan->jenis_identitas ?? 'RAHASIA') }}</span>
                                    </div>
                                    <h2 class="text-lg font-black uppercase italic text-slate-900 theme-text-main">{{ $aduan->judul }}</h2>
                                    <p class="mt-2 text-sm text-slate-500 theme-text-sub leading-relaxed">{{ \Illuminate\Support\Str::limit($aduan->isi_aduan, 160) }}</p>
                                    <p class="mt-3 text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                                        @if($aduan->jenis_identitas === 'anonim')
                                            Pelapor Anonim
                                        @elseif($aduan->jenis_identitas === 'rahasia')
                                            Identitas Dirahasiakan
                                        @else
                                            {{ $aduan->nama_pelapor }}
                                        @endif
                                        @if($aduan->jenis_identitas !== 'anonim' && $aduan->no_hp)
                                            • {{ $aduan->no_hp }}
                                        @endif
                                        @if($aduan->jenis_identitas !== 'anonim' && $aduan->email)
                                            • {{ $aduan->email }}
                                        @endif
                                        • {{ $aduan->created_at->translatedFormat('d F Y H:i') }}
                                    </p>
                                </div>

                                <div class="flex flex-col sm:flex-row lg:flex-col gap-3 shrink-0">
                                    <button type="button" @click="open = !open"
                                            class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-slate-800">
                                        <span x-show="!open">Detail / Tanggapi</span>
                                        <span x-show="open">Tutup</span>
                                    </button>
                                    @if($waUrl)
                                        <a href="{{ $waUrl }}" target="_blank"
                                           class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-emerald-700">
                                            Kirim WA
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if($aduan->tanggapan)
                                <div class="mt-5 rounded-2xl bg-slate-50 theme-bg-main border border-slate-100 theme-border p-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">Tanggapan Terakhir</p>
                                    <p class="text-sm font-bold text-slate-700 theme-text-main">{{ $aduan->tanggapan }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- FORM TANGGAPAN --}}
                        <div x-show="open" x-transition class="border-t border-slate-100 theme-border bg-slate-50 theme-bg-main p-6 lg:p-7" style="display: none;">
                            <form action="{{ route('desa.aduan.update-status', $aduan->id) }}" method="POST"
                                  class="grid grid-cols-1 lg:grid-cols-[240px_1fr_180px] gap-4 items-end">
                                @csrf @method('PATCH')
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">Ubah Status</label>
                                    <select name="status" class="w-full rounded-2xl border-slate-200 theme-border bg-white theme-bg-card px-4 py-3 text-sm font-bold text-slate-700 theme-text-main focus:ring-rose-600">
                                        <option value="baru" {{ $aduan->status === 'baru' ? 'selected' : '' }}>Baru</option>
                                        <option value="diproses" {{ $aduan->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="selesai" {{ $aduan->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="ditolak" {{ $aduan->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">Tanggapan</label>
                                    <textarea name="tanggapan" rows="2" placeholder="Tulis tanggapan singkat..."
                                              class="w-full rounded-2xl border-slate-200 theme-border bg-white theme-bg-card px-4 py-3 text-sm font-bold text-slate-700 theme-text-main focus:ring-rose-600">{{ old('tanggapan', $aduan->tanggapan) }}</textarea>
                                </div>
                                <button type="submit" class="w-full inline-flex items-center justify-center rounded-2xl bg-rose-600 px-5 py-4 text-[10px] font-black uppercase tracking-widest text-white hover:bg-rose-700">Simpan</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] bg-white theme-bg-card border border-dashed border-slate-200 theme-border p-12 text-center">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">Belum ada aduan masyarakat.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $aduans->links() }}</div>

        </div>

        {{-- ============================================ --}}
        {{-- MODAL QR CODE --}}
        {{-- ============================================ --}}
        <div x-show="openQr" x-cloak
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
             @keydown.escape.window="openQr = false">

            <div x-show="openQr" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="openQr = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <div x-show="openQr" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden">

                <div class="bg-slate-900 px-7 py-5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-rose-600 flex items-center justify-center">
                            <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </span>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">QR Code Generator</p>
                            <p class="text-white font-black text-sm">Form Aduan Masyarakat</p>
                        </div>
                    </div>
                    <button type="button" @click="openQr = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white/60 hover:bg-white/20 hover:text-white transition-colors">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-7">
                    <div class="flex justify-center mb-6">
                        <div class="p-5 bg-white rounded-2xl border-2 border-slate-100 shadow-sm">
                            <canvas id="qrPreview" width="200" height="200" style="display:block;"></canvas>
                        </div>
                    </div>

                    <div class="rounded-xl bg-slate-50 border border-slate-100 px-4 py-3 mb-6">
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Link Tujuan</p>
                        <p class="text-xs font-bold text-slate-600 break-all">{{ $aduanUrl }}</p>
                    </div>

                    <div class="space-y-3">
                        <button type="button" @click="downloadQr('png')"
                                class="w-full flex items-center gap-4 rounded-2xl bg-blue-600 hover:bg-blue-700 px-5 py-4 text-white transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="text-left flex-1">
                                <p class="text-sm font-black">Unduh PNG</p>
                                <p class="text-[10px] text-blue-200">318 × 316 px</p>
                            </div>
                            <svg class="w-5 h-5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </button>

                        <button type="button" @click="downloadQr('png-hd')"
                                class="w-full flex items-center gap-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 px-5 py-4 text-white transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="text-left flex-1">
                                <p class="text-sm font-black">Unduh PNG HD</p>
                                <p class="text-[10px] text-emerald-200">3000 × 3000 px · cetak banner</p>
                            </div>
                            <svg class="w-5 h-5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </button>

                        <button type="button" @click="downloadQr('svg')"
                                class="w-full flex items-center gap-4 rounded-2xl bg-amber-500 hover:bg-amber-600 px-5 py-4 text-white transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="text-left flex-1">
                                <p class="text-sm font-black">Unduh SVG</p>
                                <p class="text-[10px] text-amber-100">Vector · skala bebas</p>
                            </div>
                            <svg class="w-5 h-5 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ============================================ --}}
    {{-- SCRIPTS --}}
    {{-- ============================================ --}}
    <script src="{{ asset('js/qrcode.min.js') }}"></script>

    <style>[x-cloak] { display: none !important; }</style>

    <script>
        function copyText(elementId, btn) {
            var el = document.getElementById(elementId);
            var text = el.textContent || el.innerText;
            navigator.clipboard.writeText(text).then(function() {
                var span = btn.querySelector('span');
                var original = span.textContent;
                span.textContent = 'Tersalin!';
                btn.classList.remove('bg-slate-900', 'bg-blue-600', 'bg-violet-600', 'bg-rose-600', 'bg-amber-500');
                btn.classList.add('bg-emerald-600');
                setTimeout(function() {
                    span.textContent = original;
                    btn.classList.remove('bg-emerald-600');
                    btn.classList.add('bg-slate-900');
                }, 1500);
            });
        }

        var QR_URL = '{{ $aduanUrl }}';
        var QR_SLUG = '{{ $desaSlug }}';

        function makeCanvas(url, size) {
            return new Promise(function(ok) {
                var c = document.createElement('canvas');
                QRCode.toCanvas(c, url, { width: size, margin: 2, errorCorrectionLevel: 'H' }, function() { ok(c); });
            });
        }

        function makeSvg(url) {
            return new Promise(function(ok) {
                QRCode.toString(url, { type: 'svg', margin: 2, errorCorrectionLevel: 'H', width: 1000 }, function(e, s) { ok(s); });
            });
        }

        function dl(name, href) {
            var a = document.createElement('a');
            a.download = name; a.href = href;
            document.body.appendChild(a); a.click(); document.body.removeChild(a);
        }

        window.generateQr = function() {
            setTimeout(function() {
                var c = document.getElementById('qrPreview');
                if (!c) { console.error('Canvas tidak ditemukan'); return; }
                if (typeof QRCode === 'undefined') { console.error('Library QRCode belum dimuat'); return; }
                QRCode.toCanvas(c, QR_URL, { width: 200, margin: 2, errorCorrectionLevel: 'H' }, function(error) {
                    if (error) console.error('QR render error:', error);
                });
            }, 300);
        };

        window.downloadQr = async function(format) {
            if (typeof QRCode === 'undefined') { alert('Library QR Code belum dimuat. Coba refresh halaman.'); return; }
            var prefix = 'QR-Aduan-' + QR_SLUG;
            if (format === 'png') { var c = await makeCanvas(QR_URL, 318); dl(prefix + '.png', c.toDataURL('image/png')); }
            else if (format === 'png-hd') { var c = await makeCanvas(QR_URL, 3000); dl(prefix + '-HD.png', c.toDataURL('image/png')); }
            else if (format === 'svg') { var svg = await makeSvg(QR_URL); var blob = new Blob([svg], { type: 'image/svg+xml' }); dl(prefix + '.svg', URL.createObjectURL(blob)); }
        };
    </script>
</x-app-layout>