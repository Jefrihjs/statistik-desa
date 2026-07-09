<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Aduan - {{ $desa->nama_desa ?? 'Desa' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen">
    @php
        $headerColor = $desa->header_color ?? '#475569';
        $accentColor = $desa->accent_color ?? '#064e3b';

        $statusClass = match($aduan->status) {
            'baru' => 'bg-rose-500/10 text-rose-600 border-rose-500/20',
            'diproses' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
            'selesai' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
            'ditolak' => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
            default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
        };
    @endphp

    <main class="min-h-screen py-10 px-4">
        <div class="max-w-3xl mx-auto">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] text-white p-8 lg:p-10 mb-8 shadow-sm"
                 style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }});">

                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-white/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-white/10"></div>

                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/80 mb-3">
                        TARSIUS • Layanan Aduan
                    </p>

                    <h1 class="text-3xl font-black uppercase italic tracking-tight">
                        Status Aduan
                    </h1>

                    <p class="mt-3 text-sm text-white/85 leading-relaxed">
                        Berikut status tindak lanjut aduan Anda pada Pemerintah Desa {{ $desa->nama_desa ?? '-' }}.
                    </p>
                </div>
            </div>

            {{-- CARD STATUS --}}
            <div class="rounded-[2.5rem] bg-white border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 lg:p-8">
                    <div class="flex flex-wrap items-center gap-2 mb-5">
                        <span class="inline-flex rounded-full border px-4 py-2 text-[10px] font-black uppercase tracking-widest {{ $statusClass }}">
                            {{ strtoupper($aduan->status) }}
                        </span>

                        <span class="inline-flex rounded-full bg-slate-500/10 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-slate-500">
                            {{ $aduan->kode_aduan }}
                        </span>

                        <span class="inline-flex rounded-full bg-blue-500/10 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-blue-600">
                            {{ strtoupper($aduan->jenis_identitas ?? 'RAHASIA') }}
                        </span>
                    </div>

                    <h2 class="text-2xl font-black uppercase italic text-slate-900">
                        {{ $aduan->judul }}
                    </h2>

                    <div class="mt-6 rounded-2xl bg-slate-50 border border-slate-100 p-5">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Isi Aduan
                        </p>
                        <p class="text-sm font-bold text-slate-700 leading-relaxed">
                            {{ $aduan->isi_aduan }}
                        </p>
                    </div>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                Tanggal Masuk
                            </p>
                            <p class="text-sm font-black text-slate-800">
                                {{ $aduan->created_at->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 border border-slate-100 p-5">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                Terakhir Ditanggapi
                            </p>
                            <p class="text-sm font-black text-slate-800">
                                {{ $aduan->ditanggapi_pada ? $aduan->ditanggapi_pada->translatedFormat('d F Y H:i') : 'Belum ada tanggapan' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 rounded-2xl bg-emerald-50 border border-emerald-100 p-5">
                        <p class="text-[10px] font-black uppercase tracking-widest text-emerald-600 mb-2">
                            Tanggapan Desa
                        </p>
                        <p class="text-sm font-bold text-emerald-800 leading-relaxed">
                            {{ $aduan->tanggapan ?: 'Aduan sudah diterima dan menunggu tindak lanjut operator desa.' }}
                        </p>
                    </div>
                </div>

                <div class="border-t border-slate-100 bg-slate-50 p-6 flex flex-col sm:flex-row gap-3 sm:justify-between">
                    <a href="{{ route('public.aduan.check-status', $desa->slug) }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-white border border-slate-200 px-6 py-4 text-xs font-black uppercase tracking-widest text-slate-700 hover:bg-slate-100">
                        Cek Kode Lain
                    </a>

                    <a href="{{ route('public.aduan.create', $desa->slug) }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-emerald-700">
                        Kirim Aduan Baru
                    </a>
                </div>
            </div>

        </div>
    </main>
</body>
</html>