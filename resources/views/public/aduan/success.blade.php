<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aduan Terkirim - {{ $desa->nama_desa ?? 'Desa' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen">
    @php
        $headerColor = $desa->header_color ?? '#475569';
        $accentColor = $desa->accent_color ?? '#064e3b';
    @endphp

    <main class="min-h-screen py-10 px-4 flex items-center">
        <div class="max-w-2xl mx-auto w-full">

            <div class="relative overflow-hidden rounded-[2.5rem] text-white p-8 lg:p-10 shadow-sm text-center"
                 style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }});">

                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-white/10"></div>
                <div class="absolute -left-16 bottom-0 w-40 h-40 rounded-full bg-white/10"></div>

                <div class="relative z-10">
                    <div class="mx-auto mb-6 w-16 h-16 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center">
                        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2.5"
                                  d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>

                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/80 mb-3">
                        TARSIUS • Layanan Aduan
                    </p>

                    <h1 class="text-3xl font-black uppercase italic tracking-tight">
                        Aduan Berhasil Dikirim
                    </h1>

                    <p class="mt-4 text-sm text-white/85 leading-relaxed">
                        Aduan Anda telah diterima oleh Pemerintah Desa {{ $desa->nama_desa ?? '-' }}.
                        Simpan kode aduan berikut untuk keperluan tindak lanjut.
                    </p>

                    <div class="mt-8 rounded-2xl bg-white/15 border border-white/20 px-6 py-5">
                        <p class="text-[10px] font-black uppercase tracking-widest text-white/70 mb-2">
                            Kode Aduan
                        </p>
                        <p class="text-2xl font-black tracking-widest text-white">
                            {{ $aduan->kode_aduan }}
                        </p>
                        <p class="mt-4 text-xs text-white/75 leading-relaxed">
                            Kode ini dapat disimpan sebagai bukti bahwa aduan telah diterima.
                            Pemerintah desa akan menindaklanjuti sesuai mekanisme layanan aduan yang berlaku.
                        </p>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
                        <a href="{{ route('public.aduan.check-status', $desa->slug) }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-white/15 border border-white/20 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-white/25">
                            Cek Status Aduan
                        </a>

                        <a href="{{ route('public.aduan.create', $desa->slug) }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-900 hover:bg-slate-100">
                            Kirim Aduan Baru
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>