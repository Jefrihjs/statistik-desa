<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Status Aduan - {{ $desa->nama_desa ?? 'Desa' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen">
    @php
        $headerColor = $desa->header_color ?? '#475569';
        $accentColor = $desa->accent_color ?? '#064e3b';
    @endphp

    <main class="min-h-screen py-10 px-4">
        <div class="max-w-2xl mx-auto">

            <div class="relative overflow-hidden rounded-[2.5rem] text-white p-8 lg:p-10 mb-8 shadow-sm"
                 style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }});">

                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-white/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-white/10"></div>

                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/80 mb-3">
                        TARSIUS • Layanan Aduan
                    </p>

                    <h1 class="text-3xl font-black uppercase italic tracking-tight">
                        Cek Status Aduan
                    </h1>

                    <p class="mt-3 text-sm text-white/85 leading-relaxed">
                        Masukkan kode aduan untuk melihat status tindak lanjut dari Pemerintah Desa {{ $desa->nama_desa ?? '-' }}.
                    </p>
                </div>
            </div>

            @if(session('error'))
                <div class="mb-6 rounded-[2rem] border border-red-200 bg-red-50 px-6 py-5 text-sm font-bold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-[2rem] border border-red-200 bg-red-50 px-6 py-5 text-sm font-bold text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="rounded-[2.5rem] bg-white border border-slate-200 shadow-sm p-6 lg:p-8">
                <form action="{{ route('public.aduan.show-status', $desa->slug) }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Kode Aduan
                        </label>

                        <input type="text"
                               name="kode_aduan"
                               value="{{ old('kode_aduan') }}"
                               required
                               class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 text-sm font-black uppercase tracking-widest text-slate-700 focus:ring-emerald-600"
                               placeholder="Contoh: ADN-20260310-ABC123">
                    </div>

                    <button type="submit"
                            class="w-full inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-emerald-700 shadow-lg shadow-emerald-900/20">
                        Cek Status
                    </button>
                </form>

                <div class="mt-6 border-t border-slate-100 pt-6 text-center">
                    <a href="{{ route('public.aduan.create', $desa->slug) }}"
                       class="text-xs font-black uppercase tracking-widest text-slate-500 hover:text-emerald-600">
                        Kirim Aduan Baru
                    </a>
                </div>
            </div>

        </div>
    </main>
</body>
</html>