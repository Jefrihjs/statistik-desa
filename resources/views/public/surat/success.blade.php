<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Terkirim - {{ $desa->nama_desa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex items-center justify-center p-4">
    @php $slugDesa = $desa->slug ?? $desa->id; @endphp
    <div class="max-w-lg w-full bg-white rounded-3xl border border-slate-200 p-8 text-center shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-3xl mx-auto mb-4">
            ✅
        </div>
        <h1 class="text-xl font-black text-slate-800 uppercase">Permohonan Berhasil Dikirim</h1>
        <p class="text-xs text-slate-500 mt-2">
            Simpan kode registrasi di bawah ini untuk memantau status pembuatan surat Anda.
        </p>

        <div class="my-6 p-4 rounded-2xl bg-slate-50 border border-slate-200">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Kode Registrasi</span>
            <p class="text-2xl font-mono font-black text-emerald-700 mt-1 select-all">{{ $permohonan->kode_registrasi }}</p>
            <p class="text-xs text-slate-600 mt-2 font-semibold">{{ $permohonan->jenisSurat->nama_layanan }}</p>
            <p class="text-xs text-slate-500">Pemohon: {{ $permohonan->nama_pemohon }} ({{ $permohonan->nik }})</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('public.surat.tracking', [$slugDesa, 'kode' => $permohonan->kode_registrasi]) }}"
               class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition">
                🔍 Cek Status Permohonan
            </a>
            <a href="{{ route('public.surat.create', $slugDesa) }}"
               class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                + Ajukan Surat Lain
            </a>
        </div>
    </div>
</body>
</html>
