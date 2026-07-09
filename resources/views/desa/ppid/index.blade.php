<x-app-layout>
    @php
        $desaAktif = $desa ?? auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);

        $headerColor = $desaAktif->header_color ?? '#2563eb';
        $accentColor = $desaAktif->accent_color ?? '#0f766e';
    @endphp
    <div class="py-10 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto">

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

            <div style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }}); border-radius: 2.5rem; padding: 35px; color: white; margin-bottom: 2rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); position: relative; overflow: hidden;">
                <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 10;">
                <div>
                <p style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 2px; opacity: 0.9;">
                    TARSIUS &bull; Layanan Informasi Publik
                </p>

                <h1 style="font-size: 28px; font-weight: 900; text-transform: uppercase;  line-height: 1;">
                    PPID DESA
                </h1>

                <p style="font-size: 12px; margin-top: 12px; opacity: .9; max-width: 720px;">
                    Kelola Daftar Informasi Publik desa dan pantau permohonan informasi dari masyarakat.
                </p>
            </div>

            <span style="font-size: 24px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 1rem;">
                🗂️
            </span>
        </div>
    </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="card-glowing transition-transform duration-300 hover:-translate-y-1">
                    <a href="{{ route('desa.ppid.dip.index') }}"
                    class="relative z-10 group block w-full h-full bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l5 5v9a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-black text-slate-800 uppercase group-hover:text-indigo-700">
                            Daftar Informasi Publik
                        </h2>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                            Kelola informasi berkala, serta merta, setiap saat, dan informasi dikecualikan.
                        </p>
                        <div class="mt-6 text-xs font-black text-indigo-600 uppercase tracking-widest">
                            Masuk DIP →
                        </div>
                    </a>
                </div>

                <div class="card-glowing transition-transform duration-300 hover:-translate-y-1">
                    <a href="{{ route('desa.ppid.permohonan.index') }}"
                    class="relative z-10 group block w-full h-full bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                        @if(($jumlahPermohonanMasuk ?? 0) > 0)
                            <span class="absolute top-6 right-6 inline-flex items-center justify-center min-w-[28px] h-7 px-2 rounded-full bg-emerald-500 text-white text-xs font-black shadow-lg shadow-emerald-100">
                                {{ $jumlahPermohonanMasuk }}
                            </span>
                        @endif
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center mb-5">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4-.8L3 20l1.3-3.5A7.6 7.6 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-black text-slate-800 uppercase group-hover:text-emerald-700">
                            Permohonan Informasi
                        </h2>
                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                            Lihat, pantau, dan tindak lanjuti permohonan informasi yang dikirim masyarakat.
                        </p>
                        <div class="mt-6 text-xs font-black text-blue-600 uppercase tracking-widest">
                            Lihat Permohonan →
                        </div>
                    </a>
                </div>

                <div class="card-glowing transition-transform duration-300 hover:-translate-y-1">
                    <a href="{{ route('desa.ppid.keberatan.index') }}"
                    class="relative z-10 group block w-full h-full bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                        @if(($jumlahKeberatanMasuk ?? 0) > 0)
                            <span class="absolute top-6 right-6 inline-flex items-center justify-center min-w-[28px] h-7 px-2 rounded-full bg-rose-600 text-white text-xs font-black shadow-lg shadow-rose-100">
                                {{ $jumlahKeberatanMasuk }}
                            </span>
                        @endif
                        <div class="w-14 h-14 rounded-2xl bg-rose-500 text-white-600 flex items-center justify-center mb-5">
                            !
                        </div>
                        <h3 class="text-lg font-black text-slate-800 uppercase">
                            Keberatan Informasi
                        </h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                            Lihat dan tindak lanjuti keberatan informasi yang diajukan masyarakat.
                        </p>
                         <div class="mt-6 text-xs font-black text-blue-600 uppercase tracking-widest">
                            Lihat Keberatan →
                        </div>
                    </a>
                </div>

                <div class="card-glowing transition-transform duration-300 hover:-translate-y-1">
                    <a href="{{ route('desa.ppid.laporan.index') }}"
                    class="relative z-10 group block w-full h-full bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-5">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-6h6v6m2 4H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-800 uppercase">
                            Laporan PPID
                        </h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                            Cetak register permohonan informasi dan register keberatan berdasarkan tahun laporan.
                        </p>
                         <div class="mt-6 text-xs font-black text-blue-600 uppercase tracking-widest">
                            Lihat Laporan →
                        </div>
                    </a>
                </div>

                <div class="card-glowing transition-transform duration-300 hover:-translate-y-1">
                    <a href="{{ route('desa.pengaturan.edit') }}"
                    class="relative z-10 group block w-full h-full bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm">
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center mb-5">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.607 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-800 uppercase">
                            Pengaturan PPID Desa
                        </h3>
                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                            Atur logo, alamat, kontak, dan pejabat PPID untuk kop surat dokumen PDF.
                        </p>
                        <div class="mt-6 text-xs font-black text-blue-600 uppercase tracking-widest">
                            Masuk Pengaturan →
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </div>

    <style>
    @property --angle {
        syntax: "<angle>";
        initial-value: 0deg;
        inherits: false;
    }

    .card-glowing {
        position: relative;
        z-index: 1;
        padding: 3px; 
        border-radius: 2.1rem; 
    }

    .card-glowing::after, 
    .card-glowing::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: conic-gradient(from var(--angle), transparent 70%, #2563eb);
        
        z-index: -1;
        border-radius: inherit;
        animation: 3s spin linear infinite;
        
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .card-glowing:hover::after {
        opacity: 1; 
    }
    
    .card-glowing:hover::before {
        filter: blur(1.5rem);
        opacity: 0.5; 
    }

    @keyframes spin {
        from { --angle: 0deg; }
        to { --angle: 360deg; }
    }
</style>
</x-app-layout>