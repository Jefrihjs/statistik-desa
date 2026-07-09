<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKM - {{ $desa->nama_desa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center py-12">

    <div class="max-w-md mx-auto px-4 text-center">
        <div class="w-20 h-20 rounded-full bg-slate-200 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1 class="text-xl font-black uppercase italic text-slate-900 mb-3">
            Survey Belum Tersedia
        </h1>
        <p class="text-sm text-slate-500 leading-relaxed mb-2">
            Survei Kepuasan Masyarakat untuk Desa {{ $desa->nama_desa }} saat ini belum aktif.
        </p>
        <p class="text-xs text-slate-400">
            Hal ini biasanya terjadi karena nomor rekomendasi BPS untuk periode ini belum diterbitkan. Silakan hubungi administrator desa.
        </p>
    </div>

</body>
</html>