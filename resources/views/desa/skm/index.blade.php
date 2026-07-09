<x-app-layout>
    @php
        $desaAktif = $desa ?? auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);
        $headerColor = $desaAktif->header_color ?? '#2563eb';
        $accentColor = $desaAktif->accent_color ?? '#0f766e';

        // Auto-generate kode survey: SKM + YYMM + 6 digit acak
        $kodeSurvey = 'SKM' . date('ym') . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // URL publik
        $formUrl = route('public.skm.create', $desaAktif->slug);
        $hasilUrl = route('public.skm.hasil', $desaAktif->slug);
        $desaSlug = str_replace(' ', '-', $desaAktif->nama_desa);
    @endphp

    {{-- x-data di sini, tanpa qrUrl (url statis pakai blade langsung) --}}
    <div x-data="{ openModal: false, modalData: null, openQr: false }" class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-6xl mx-auto px-4">

            <div class="mb-4">
                <a href="{{ route('desa.dashboard') }}"
                   class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
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
                        <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.2em; margin-bottom:2px; opacity:0.9;">
                            TARSIUS &bull; Survei Kepuasan Masyarakat
                        </p>
                        <h1 style="font-size:26px; font-weight:900; text-transform:uppercase; font-style:italic; line-height:1;">
                            MODUL SKM DESA
                        </h1>
                        <p style="font-size:12px; margin-top:12px; opacity:.9; max-width:720px;">
                            Pantau penilaian layanan dan rekap hasil evaluasi pelayanan publik desa berdasarkan 9 unsur standar.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ $formUrl }}" target="_blank"
                           class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/15 border border-white/20 px-5 py-3.5 text-[10px] font-black uppercase tracking-widest text-white hover:bg-white/25 shadow-lg transition-colors">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Form Publik
                        </a>
                        <a href="{{ $hasilUrl }}" target="_blank"
                           class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/15 border border-white/20 px-5 py-3.5 text-[10px] font-black uppercase tracking-widest text-white hover:bg-white/25 shadow-lg transition-colors">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Hasil SKM
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-emerald-700 font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @error('error')
                <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 px-5 py-4 text-red-700 font-bold text-sm">
                    {{ $message }}
                </div>
            @enderror

            {{-- ============================================ --}}
            {{-- LINK, EMBED & QR CODE --}}
            {{-- ============================================ --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                {{-- FORM SKM --}}
                <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                    <div class="flex items-start gap-4 mb-5">
                        <div class="shrink-0 w-10 h-10 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                            <svg width="18" height="18" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-slate-900 uppercase italic">Form SKM Publik</h2>
                            <p class="text-[10px] text-slate-400 font-bold">Embed form pengisian survei.</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Link Langsung</label>
                        <div class="flex items-stretch gap-2">
                            <div class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-bold text-slate-600 truncate select-all" id="formUrlText">{{ $formUrl }}</div>
                            <button type="button" onclick="copyText('formUrlText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <span>Salin</span>
                            </button>
                            <a href="{{ $formUrl }}" target="_blank" class="shrink-0 rounded-xl bg-blue-600 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-blue-700 transition-colors flex items-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Kode Embed (iframe)</label>
                        <div class="flex items-stretch gap-2">
                            <pre class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[10px] font-mono text-slate-600 overflow-x-auto whitespace-pre-wrap break-all select-all" id="formEmbedText">&lt;iframe src="{{ $formUrl }}" width="100%" height="900" frameborder="0" style="border:none; border-radius:12px;"&gt;&lt;/iframe&gt;</pre>
                            <button type="button" onclick="copyText('formEmbedText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <span>Salin</span>
                            </button>
                        </div>
                        <p class="text-[8px] text-slate-300 mt-1.5">Height 900px agar form tampil utuh tanpa scroll di dalam iframe.</p>
                    </div>
                </div>

                {{-- HASIL SKM --}}
                <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                    <div class="flex items-start gap-4 mb-5">
                        <div class="shrink-0 w-10 h-10 rounded-2xl bg-violet-50 border border-violet-100 flex items-center justify-center">
                            <svg width="18" height="18" fill="none" stroke="#7c3aed" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-sm font-black text-slate-900 uppercase italic">Hasil SKM Publik</h2>
                            <p class="text-[10px] text-slate-400 font-bold">Embed halaman rekap survei.</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Link Langsung</label>
                        <div class="flex items-stretch gap-2">
                            <div class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-bold text-slate-600 truncate select-all" id="hasilUrlText">{{ $hasilUrl }}</div>
                            <button type="button" onclick="copyText('hasilUrlText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <span>Salin</span>
                            </button>
                            <a href="{{ $hasilUrl }}" target="_blank" class="shrink-0 rounded-xl bg-violet-600 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-violet-700 transition-colors flex items-center">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Kode Embed (iframe)</label>
                        <div class="flex items-stretch gap-2">
                            <pre class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[10px] font-mono text-slate-600 overflow-x-auto whitespace-pre-wrap break-all select-all" id="hasilEmbedText">&lt;iframe src="{{ $hasilUrl }}" width="100%" height="800" frameborder="0" style="border:none; border-radius:12px;"&gt;&lt;/iframe&gt;</pre>
                            <button type="button" onclick="copyText('hasilEmbedText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                <span>Salin</span>
                            </button>
                        </div>
                        <p class="text-[8px] text-slate-300 mt-1.5">Height 800px untuk ringkasan IKM dan tabel riwayat.</p>
                    </div>
                </div>

                {{-- QR CODE --}}
                <button type="button" @click="openQr = true; generateQr()"
                        class="rounded-[2rem] border-2 border-dashed border-violet-200 bg-white p-7 shadow-sm hover:bg-violet-50/50 hover:border-violet-300 transition-all text-left group cursor-pointer">
                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-10 h-10 rounded-2xl bg-violet-50 border border-violet-200 group-hover:bg-violet-100 flex items-center justify-center transition-colors">
                            <svg width="20" height="20" fill="none" stroke="#7c3aed" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        </div>
                        <div class="flex-1">
                            <h2 class="text-sm font-black text-slate-900 uppercase italic">QR Code Survey</h2>
                            <p class="text-[10px] text-slate-400 font-bold mt-1">Generate & unduh QR code untuk link form survei publik.</p>
                            <div class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-violet-100 border border-violet-200 px-3 py-1">
                                <svg class="w-3 h-3 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-[9px] font-black text-violet-600">PNG · PNG HD · SVG</span>
                            </div>
                        </div>
                    </div>
                </button>

            </div>

            {{-- ============================================ --}}
            {{-- STATISTIK --}}
            {{-- ============================================ --}}
            @if(!$layananFilter)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                        <p class="text-[10px] font-black uppercase tracking-[0.25em] text-slate-400 mb-4">Total Responden</p>
                        <div class="text-4xl font-black text-slate-900">{{ $totalResponden }}</div>
                        @if($rekomFilter)
                            <p class="mt-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                                Periode {{ $rekomendasi->firstWhere('id', $rekomFilter)?->tahun ?? '—' }}
                            </p>
                        @endif
                    </div>
                    <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                        <p class="text-[10px] font-black uppercase tracking-[0.25em] text-slate-400 mb-4">Nilai IKM</p>
                        <div class="text-4xl font-black text-blue-600">{{ $ikm ?? '-' }}</div>
                        <p class="mt-2 text-[9px] font-bold text-slate-400 uppercase tracking-widest">Skala 25.00 – 100.00</p>
                    </div>
                    <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                        <p class="text-[10px] font-black uppercase tracking-[0.25em] text-slate-400 mb-4">Mutu Layanan</p>
                        <div class="text-2xl font-black text-emerald-600">{{ $mutu }}</div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-[2rem] border border-slate-200 p-6 mb-8 shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <div>
                            <p class="text-sm font-black text-slate-900 uppercase">Filter Aktif: {{ $layananFilter }}</p>
                            <p class="text-[10px] text-slate-400 font-bold">Menampilkan data per layanan. Nilai IKM dihitung secara global (semua layanan).</p>
                        </div>
                        <a href="{{ route('desa.skm.index') }}" class="ml-auto rounded-xl bg-slate-100 px-4 py-2 text-[10px] font-black uppercase text-slate-500 hover:bg-slate-200 transition-colors">
                            Reset Filter
                        </a>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM KIRI --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- REKOMENDASI BPS --}}
                    <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                        <h2 class="text-sm font-black text-slate-900 uppercase italic mb-5">Rekomendasi BPS</h2>

                        <div class="space-y-4 mb-6">
                            @forelse($rekomendasi as $rek)
                                <div class="rounded-2xl border {{ $rek->is_active ? 'border-blue-200 bg-blue-50/50' : 'border-slate-100 bg-slate-50' }} p-4">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        <span class="text-xs font-black text-slate-800 tracking-wide">{{ $rek->kode_survey }}</span>
                                        @if($rek->is_active)
                                            <span class="shrink-0 rounded-full bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-[8px] font-black uppercase text-emerald-600">Aktif</span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-blue-500 font-bold mb-1">Rekom BPS: {{ $rek->nomor_rekom }}</p>
                                    <p class="text-[10px] text-slate-400 font-bold mb-0.5">
                                        @if($rek->tanggal_mulai && $rek->tanggal_selesai)
                                            {{ $rek->tanggal_mulai->format('d M Y') }} – {{ $rek->tanggal_selesai->format('d M Y') }}
                                        @else
                                            Tahun {{ $rek->tahun }}
                                        @endif
                                    </p>
                                    <div class="flex gap-2 mt-3">
                                        @if(!$rek->is_active)
                                            <form action="{{ route('desa.skm.rekomendasi.toggle', $rek->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="rounded-xl bg-blue-50 px-3 py-1.5 text-[9px] font-black uppercase text-blue-600 hover:bg-blue-100">Aktifkan</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('desa.skm_rekomendasi.destroy', $rek->id) }}" method="POST" onsubmit="return confirm('Hapus rekomendasi ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-xl bg-red-50 px-3 py-1.5 text-[9px] font-black uppercase text-red-600 hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 font-bold text-center py-4">Belum ada rekomendasi BPS.</p>
                            @endforelse
                        </div>

                        <form action="{{ route('desa.skm.rekomendasi.store') }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Kode Survey Desa</label>
                                <input type="text" name="kode_survey" required readonly value="{{ $kodeSurvey }}"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2.5 text-xs font-bold text-slate-500 cursor-not-allowed focus:border-slate-300 focus:ring-0 outline-none"
                                    onclick="this.select(); document.execCommand('copy');">
                                <p class="text-[8px] text-slate-300 mt-1">Auto-generate · Klik untuk salin</p>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Rekom BPS</label>
                                <input type="text" name="nomor_rekom" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-bold focus:border-blue-500 focus:ring-0 outline-none" placeholder="V-25.1906.004">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Tahun</label>
                                <input type="number" name="tahun" required min="2000" max="2100" value="{{ date('Y') }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-bold focus:border-blue-500 focus:ring-0 outline-none">
                            </div>
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1">Periode Survey</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="relative">
                                        <label class="absolute -top-1.5 left-2 text-[7px] font-bold text-slate-400 bg-white px-1">Mulai</label>
                                        <input type="date" name="tanggal_mulai" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-bold focus:border-blue-500 focus:ring-0 outline-none">
                                    </div>
                                    <div class="relative">
                                        <label class="absolute -top-1.5 left-2 text-[7px] font-bold text-slate-400 bg-white px-1">Selesai</label>
                                        <input type="date" name="tanggal_selesai" required class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-bold focus:border-blue-500 focus:ring-0 outline-none">
                                    </div>
                                </div>
                                <p class="text-[8px] text-slate-300 mt-1">Biasanya per semester (±6 bulan)</p>
                            </div>
                            <button type="submit" class="w-full rounded-xl bg-slate-900 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-slate-800">Tambah Rekomendasi</button>
                        </form>
                    </div>

                    {{-- FILTER --}}
                    <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                        <h2 class="text-sm font-black text-slate-900 uppercase italic mb-5">Filter Data</h2>
                        <form method="GET" class="space-y-4">
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">Jenis Layanan</label>
                                <select name="layanan" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-xs font-bold focus:border-blue-500 focus:ring-0 outline-none appearance-none">
                                    <option value="">Semua Layanan</option>
                                    @foreach($layananOptions as $opt)
                                        <option value="{{ $opt }}" {{ $layananFilter === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2">Periode Rekomendasi</label>
                                <select name="rekom" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-xs font-bold focus:border-blue-500 focus:ring-0 outline-none appearance-none">
                                    <option value="">Semua Periode</option>
                                    @foreach($rekomendasi as $rek)
                                        <option value="{{ $rek->id }}" {{ $rekomFilter == $rek->id ? 'selected' : '' }}>{{ $rek->tahun }} — {{ $rek->nomor_rekom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-blue-700">Terapkan Filter</button>
                            @if($layananFilter || $rekomFilter)
                                <a href="{{ route('desa.skm.index') }}" class="block w-full text-center rounded-xl border border-slate-200 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-500 hover:bg-slate-50">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="text-sm font-black text-slate-900 uppercase italic">Data Responden</h2>
                            <span class="text-[10px] font-bold text-slate-400">{{ $responses->total() }} data</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr class="border-b-2 border-slate-100">
                                        <th class="text-left py-3 pr-3 text-[9px] font-black uppercase tracking-widest text-slate-400">#</th>
                                        <th class="text-left py-3 pr-3 text-[9px] font-black uppercase tracking-widest text-slate-400">Responden</th>
                                        <th class="text-left py-3 pr-3 text-[9px] font-black uppercase tracking-widest text-slate-400">Layanan</th>
                                        <th class="text-left py-3 pr-3 text-[9px] font-black uppercase tracking-widest text-slate-400">Nilai</th>
                                        <th class="text-left py-3 pr-3 text-[9px] font-black uppercase tracking-widest text-slate-400">IKM</th>
                                        <th class="text-left py-3 text-[9px] font-black uppercase tracking-widest text-slate-400">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($responses as $idx => $r)
                                        <tr class="border-b border-slate-50 hover:bg-slate-50/50">
                                            <td class="py-3 pr-3 text-slate-400 font-bold">{{ $responses->firstItem() + $idx }}</td>
                                            <td class="py-3 pr-3">
                                                <p class="font-bold text-slate-800">{{ $r->jenis_kelamin }} · {{ $r->usia }} thn</p>
                                                <p class="text-[10px] text-slate-400">{{ $r->pendidikan }} · {{ $r->pekerjaan }}</p>
                                            </td>
                                            <td class="py-3 pr-3">
                                                <span class="inline-block rounded-lg bg-slate-100 px-2 py-1 text-[9px] font-bold text-slate-600 max-w-[180px] truncate">{{ $r->layanan_yang_dinilai }}</span>
                                            </td>
                                            <td class="py-3 pr-3 font-black text-slate-700">{{ $r->nilai_rata_rata }}</td>
                                            <td class="py-3 pr-3"><span class="font-black text-blue-600">{{ $r->ikm }}</span></td>
                                            <td class="py-3 text-slate-400 font-bold whitespace-nowrap">{{ $r->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="py-12 text-center"><p class="text-xs font-black uppercase tracking-widest text-slate-300">Belum ada data responden.</p></td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">{{ $responses->links() }}</div>
                    </div>
                </div>

            </div>

            {{-- ============================================ --}}
            {{-- MODAL QR CODE — TANPA x-teleport, tetap di dalam x-data --}}
            {{-- ============================================ --}}
            <template x-if="true">
                <div x-show="openQr" x-cloak
                     class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
                     @keydown.escape.window="openQr = false" style="display: none;">

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
                            <span class="w-8 h-8 rounded-xl bg-violet-600 flex items-center justify-center">
                                <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            </span>
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">QR Code Generator</p>
                                <p class="text-white font-black text-sm">Form Survei SKM</p>
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
                            <p class="text-xs font-bold text-slate-600 break-all">{{ $formUrl }}</p>
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
    </div>
            </template>

        </div>
    </div>

        {{-- ============================================ --}}
    {{-- SCRIPTS --}}
    {{-- ============================================ --}}
    <script src="{{ asset('js/qrcode.min.js') }}"></script>

    <style>[x-cloak] { display: none !important; }</style>

    <script>
        // --- Copy Text ---
        function copyText(elementId, btn) {
            var el = document.getElementById(elementId);
            var text = el.textContent || el.innerText;
            navigator.clipboard.writeText(text).then(function() {
                var span = btn.querySelector('span');
                var original = span.textContent;
                span.textContent = 'Tersalin!';
                btn.classList.remove('bg-slate-900', 'bg-blue-600', 'bg-violet-600');
                btn.classList.add('bg-emerald-600');
                setTimeout(function() {
                    span.textContent = original;
                    btn.classList.remove('bg-emerald-600');
                    btn.classList.add('bg-slate-900');
                }, 1500);
            });
        }

        // --- QR Code ---
        var QR_URL = '{{ $formUrl }}';
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
            a.download = name;
            a.href = href;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        window.generateQr = function() {
            setTimeout(function() {
                var c = document.getElementById('qrPreview');
                if (!c) { console.error('Canvas tidak ditemukan'); return; }
                if (typeof QRCode === 'undefined') { console.error('Library QRCode belum dimuat'); return; }
                QRCode.toCanvas(c, QR_URL, {
                    width: 200,
                    margin: 2,
                    errorCorrectionLevel: 'H'
                }, function(error) {
                    if (error) console.error('QR render error:', error);
                });
            }, 300);
        };

        window.downloadQr = async function(format) {
            if (typeof QRCode === 'undefined') { alert('Library QR Code belum dimuat. Coba refresh halaman.'); return; }

            var prefix = 'QR-SKM-' + QR_SLUG;

            if (format === 'png') {
                var c = await makeCanvas(QR_URL, 318);
                dl(prefix + '.png', c.toDataURL('image/png'));
            } else if (format === 'png-hd') {
                var c = await makeCanvas(QR_URL, 3000);
                dl(prefix + '-HD.png', c.toDataURL('image/png'));
            } else if (format === 'svg') {
                var svg = await makeSvg(QR_URL);
                var blob = new Blob([svg], { type: 'image/svg+xml' });
                dl(prefix + '.svg', URL.createObjectURL(blob));
            }
        };
    </script>
</x-app-layout>