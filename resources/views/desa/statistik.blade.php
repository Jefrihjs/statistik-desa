<x-app-layout>
    @php
        $desaAktif = $desa ?? auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);
        $headerColor = $desaAktif->header_color ?? '#2563eb';
        $accentColor = $desaAktif->accent_color ?? '#0f766e';

        $totalTerisi = $statusPengisian->sum('terisi');
        $totalIndikator = $statusPengisian->sum('total_indikator');
        $persen = $totalIndikator > 0 ? round(($totalTerisi / $totalIndikator) * 100) : 0;

        $statistikUrl = url('/desa/' . $desaAktif->slug);
        $entriUrl = url('/admin/entri/' . auth()->user()->desa_id . '?tahun=' . $tahunAktif);
        $templateUrl = route('admin.download-template') . '?tahun=' . $tahunAktif;
    @endphp

    <div class="py-12 bg-slate-50 min-h-screen">
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
                            TARSIUS &bull; Sub-Layanan Statistik
                        </p>
                        <h1 style="font-size:26px; font-weight:900; text-transform:uppercase; font-style:italic; line-height:1;">
                            PANEL STATISTIK SEKTORAL
                        </h1>
                        <p style="font-size:12px; margin-top:12px; opacity:.9; max-width:720px;">
                            Kelola data statistik sektoral desa, pantau tren perkembangan, dan sajikan informasi berbasis data secara transparan.
                        </p>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ $statistikUrl }}" target="_blank"
                           class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/15 border border-white/20 px-5 py-3.5 text-[10px] font-black uppercase tracking-widest text-white hover:bg-white/25 shadow-lg transition-colors">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Lihat Statistik
                        </a>
                        <a href="{{ $entriUrl }}"
                           class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/15 border border-white/20 px-5 py-3.5 text-[10px] font-black uppercase tracking-widest text-white hover:bg-white/25 shadow-lg transition-colors">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Input Data
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-emerald-700 font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- LINK & EMBED STATISTIK --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm mb-8">
                <div class="flex items-start gap-4 mb-5">
                    <div class="shrink-0 w-10 h-10 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                        <svg width="18" height="18" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase italic">Statistik Desa Publik</h2>
                        <p class="text-[10px] text-slate-400 font-bold">Embed halaman statistik ke website desa.</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Link Langsung</label>
                    <div class="flex items-stretch gap-2">
                        <div class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-bold text-slate-600 truncate select-all" id="statUrlText">{{ $statistikUrl }}</div>
                        <button type="button" onclick="copyText('statUrlText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span>Salin</span>
                        </button>
                        <a href="{{ $statistikUrl }}" target="_blank" class="shrink-0 rounded-xl bg-blue-600 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-blue-700 transition-colors flex items-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Kode Embed (iframe)</label>
                    <div class="flex items-stretch gap-2">
                        <pre class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[10px] font-mono text-slate-600 overflow-x-auto whitespace-pre-wrap break-all select-all" id="statEmbedText">&lt;iframe src="{{ $statistikUrl }}" width="100%" height="1200" frameborder="0" style="border:none; border-radius:12px;"&gt;&lt;/iframe&gt;</pre>
                        <button type="button" onclick="copyText('statEmbedText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span>Salin</span>
                        </button>
                    </div>
                    <p class="text-[8px] text-slate-300 mt-1.5">Height 1200px agar seluruh konten statistik tampil utuh.</p>
                </div>
            </div>

            {{-- TAHUN & STATUS --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-[0.25em] text-slate-400 mb-4">Pilih Tahun Data</p>
                    <form method="GET" action="" style="margin:0;">
                        <select name="tahunAktif" onchange="this.form.submit()"
                                class="w-full rounded-xl border-2 border-slate-200 bg-slate-50 px-4 py-3 text-lg font-black text-slate-900 cursor-pointer outline-none focus:border-slate-400 transition-colors appearance-none">
                            @for($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ $tahunAktif == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                            @endfor
                        </select>
                    </form>
                </div>
                <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.25em] text-slate-400 mb-2">Status Sinkronisasi</p>
                        <p class="text-2xl font-black" style="color: {{ $headerColor }};">AKTIF</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl" style="background: {{ $headerColor }}10;">✅</div>
                </div>
            </div>

            {{-- PROGRESS DATA --}}
            <div class="bg-white rounded-[2rem] border border-slate-200 p-8 shadow-sm mb-8">
                <div class="flex items-end justify-between mb-5">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-1">Progress Data Tahun {{ $tahunAktif }}</p>
                        <h2 class="text-3xl font-black text-slate-900">
                            {{ $persen }}%
                            <span class="text-xs text-slate-400 font-bold italic ml-1">Selesai</span>
                        </h2>
                    </div>
                    <span class="text-[10px] font-black px-3 py-1.5 rounded-full" style="color: {{ $headerColor }}; background: {{ $headerColor }}10;">
                        {{ $totalTerisi }} / {{ $totalIndikator }} Indikator
                    </span>
                </div>

                <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden mb-8">
                    <div class="h-full rounded-full transition-all duration-500" style="width: {{ $persen }}%; background: linear-gradient(90deg, {{ $headerColor }}, {{ $accentColor }});"></div>
                </div>

                <div class="rounded-2xl border border-slate-100 overflow-hidden">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-left">
                                <th class="px-6 py-3 text-[9px] font-black uppercase tracking-widest text-slate-400">Kategori Indikator</th>
                                <th class="px-6 py-3 text-[9px] font-black uppercase tracking-widest text-slate-400 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statusPengisian as $stat)
                            <tr class="border-t border-slate-50">
                                <td class="px-6 py-4 font-black text-slate-800 uppercase">{{ $stat->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($stat->terisi == $stat->total_indikator)
                                        <span class="inline-block rounded-full bg-emerald-50 border border-emerald-200 px-3 py-1 text-[9px] font-black text-emerald-600">✓ LENGKAP</span>
                                    @elseif($stat->terisi > 0)
                                        <span class="inline-block rounded-full bg-blue-50 border border-blue-200 px-3 py-1 text-[9px] font-black text-blue-600">⚡ {{ $stat->terisi }}/{{ $stat->total_indikator }}</span>
                                    @else
                                        <span class="inline-block rounded-full bg-slate-50 border border-slate-200 px-3 py-1 text-[9px] font-black text-slate-400">○ KOSONG</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <p class="text-center text-[10px] font-black uppercase tracking-[0.4em] text-slate-300 mb-8">
                Diskominfo Belitung Timur &bull; {{ date('Y') }}
            </p>

        </div>
    </div>

    <script>
        function copyText(elementId, btn) {
            var el = document.getElementById(elementId);
            var text = el.textContent || el.innerText;
            navigator.clipboard.writeText(text).then(function() {
                var span = btn.querySelector('span');
                var original = span.textContent;
                span.textContent = 'Tersalin!';
                btn.classList.remove('bg-slate-900');
                btn.classList.add('bg-emerald-600');
                setTimeout(function() {
                    span.textContent = original;
                    btn.classList.remove('bg-emerald-600');
                    btn.classList.add('bg-slate-900');
                }, 1500);
            });
        }
    </script>
</x-app-layout>