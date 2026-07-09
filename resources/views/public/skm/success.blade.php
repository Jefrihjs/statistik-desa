<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - SKM {{ $desa->nama_desa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-anim { animation: float 3s ease-in-out infinite; }
        @keyframes checkmark {
            0% { transform: scale(0) rotate(-45deg); opacity: 0; }
            50% { transform: scale(1.2) rotate(0deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        .check-anim { animation: checkmark 0.6s ease-out 0.3s both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-up { animation: fadeUp 0.5s ease-out both; }
        .fade-up-1 { animation-delay: 0.4s; }
        .fade-up-2 { animation-delay: 0.6s; }
        .fade-up-3 { animation-delay: 0.8s; }
        .fade-up-4 { animation-delay: 1.0s; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center py-12">

    <div class="max-w-md mx-auto px-4 text-center">
        
        {{-- Animated Checkmark Circle --}}
        <div class="relative inline-flex items-center justify-center w-24 h-24 rounded-full mb-8 float-anim"
             style="background: linear-gradient(135deg, {{ $desa->header_color ?? '#2563eb' }}, {{ $desa->accent_color ?? '#0f766e' }}); box-shadow: 0 20px 40px -10px rgba(37, 99, 235, 0.3);">
            <svg class="check-anim w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="text-2xl font-black uppercase italic text-slate-900 mb-3 fade-up fade-up-1">
            Terima Kasih
        </h1>

        <p class="text-sm text-slate-500 leading-relaxed mb-2 fade-up fade-up-2">
            Survei kepuasan Anda telah berhasil dikirim.
        </p>

        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-10 fade-up fade-up-2">
            Pemerintah Desa {{ strtoupper($desa->nama_desa) }}
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3 fade-up fade-up-3">
            <a href="{{ route('public.skm.create', $desa->slug) }}"
               class="inline-flex items-center gap-2 rounded-2xl border-2 border-slate-200 bg-white px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-600 hover:border-slate-300 hover:bg-slate-50 transition-all">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Isi Lagi
            </a>
        </div>

        <p class="mt-10 text-[10px] text-slate-300 font-bold uppercase tracking-widest fade-up fade-up-4">
            TARSIUS — Survei Kepuasan Masyarakat
        </p>
    </div>

</body>
</html>