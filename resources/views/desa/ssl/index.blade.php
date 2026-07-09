<x-app-layout>
    @php
        $desaAktif = $desa ?? auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);

        $headerColor = $desaAktif->header_color ?? '#2563eb';
        $accentColor = $desaAktif->accent_color ?? '#0f766e';
    @endphp

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">


            <div class="mb-4">
                <a href="{{ route('desa.dashboard') }}"
                class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Hub Utama TARSIUS
                </a>
            </div>
            
            <div style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }}); border-radius: 2.5rem; padding: 35px; color: white; margin-bottom: 2rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative; overflow: hidden;">
                <div style="position:absolute; right:-50px; top:-50px; width:200px; height:200px; background:rgba(255,255,255,0.1); border-radius:50%;"></div>

                <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:10; gap:20px;">
                    <div>
                        <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.2em; margin-bottom:2px; opacity:0.9;">
                            TARSIUS &bull; Monitoring Keamanan Website
                        </p>

                        <h1 style="font-size:26px; font-weight:900; text-transform:uppercase; font-style:italic; line-height:1;">
                            SSL DESA
                        </h1>

                        <p style="font-size:12px; margin-top:12px; opacity:.9; max-width:720px;">
                            Pantau sertifikat keamanan HTTPS website desa dan lakukan koordinasi pembaruan SSL.
                        </p>
                    </div>

                    <span style="font-size:24px; background:rgba(255,255,255,0.2); padding:10px; border-radius:1rem;">
                        🔒
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-200 shadow-sm p-8">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-rose-600 mb-3">
                        Informasi SSL
                    </p>

                    <h2 class="text-2xl font-black text-slate-900 uppercase italic mb-8">
                        Status Sertifikat Keamanan
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Domain</p>
                            <p class="text-lg font-black text-slate-900">{{ $tracker->domain_name ?? 'Belum Terdata' }}</p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                Status SSL
                            </p>
                            <p class="text-lg font-black text-slate-900">
                                {{ strtoupper($sslInfo['status'] ?? 'UNKNOWN') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                Berlaku Sampai
                            </p>
                            <p class="text-lg font-black text-slate-900">
                                {{ !empty($sslInfo['valid_to']) ? \Carbon\Carbon::parse($sslInfo['valid_to'])->translatedFormat('d F Y') : 'Belum tersedia' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                Sisa Hari SSL
                            </p>
                            <p class="text-lg font-black text-slate-900">
                                {{ $sslInfo['days_left'] !== null ? (int) $sslInfo['days_left'] . ' Hari' : 'Belum tersedia' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm p-8">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-600 mb-3">
                        Aksi
                    </p>

                    <h2 class="text-xl font-black text-slate-900 uppercase italic mb-4">
                        Koordinasi SSL
                    </h2>

                    <p class="text-sm text-slate-500 leading-relaxed mb-8">
                        Gunakan tombol berikut untuk menghubungi admin kabupaten terkait pembaruan SSL website desa.
                    </p>

                    <a href="{{ $waUrl }}"
                       target="_blank"
                       class="w-full inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-emerald-700">
                        Koordinasi via WhatsApp
                    </a>

                    @if($domain)
                        <a href="https://www.ssllabs.com/ssltest/analyze.html?d={{ urlencode($domain) }}"
                        target="_blank"
                        class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-slate-800">
                            Cek SSL Labs
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>