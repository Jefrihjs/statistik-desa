<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Permohonan Surat - {{ $desa->nama_desa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    @php $slugDesa = $desa->slug ?? $desa->id; @endphp
    <div class="max-w-2xl mx-auto py-10 px-4">
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="flex items-center justify-between gap-2 mb-6">
                <div>
                    <h1 class="text-xl font-black text-slate-800 uppercase">Lacak Status Surat</h1>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $desa->nama_desa }}</p>
                </div>
                <a href="{{ route('public.surat.create', $slugDesa) }}"
                   class="text-xs font-bold text-emerald-700 hover:underline">
                    ← Kembali ke Form
                </a>
            </div>

            <form method="GET" action="{{ route('public.surat.tracking', $slugDesa) }}" class="flex gap-2">
                <input type="text" name="kode" value="{{ request('kode') }}" required
                       placeholder="Masukkan Kode Registrasi (SRT-...) atau NIK"
                       class="flex-1 rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:outline-none">
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold cursor-pointer">
                    Cari
                </button>
            </form>

            @if(request()->filled('kode'))
                @if($permohonan)
                    @php
                        $badgeStatus = match($permohonan->status) {
                            'menunggu' => ['label' => 'Menunggu Verifikasi', 'class' => 'bg-amber-100 text-amber-800'],
                            'berkas_kurang' => ['label' => 'Berkas Kurang / Perlu Perbaikan', 'class' => 'bg-rose-100 text-rose-800'],
                            'diproses' => ['label' => 'Sedang Diproses Desa', 'class' => 'bg-blue-100 text-blue-800'],
                            'selesai' => ['label' => 'Selesai / Siap Diambil', 'class' => 'bg-emerald-100 text-emerald-800'],
                            'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-slate-200 text-slate-700'],
                        };
                    @endphp
                    <div class="mt-6 p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <span class="font-mono text-sm font-black text-slate-800">{{ $permohonan->kode_registrasi }}</span>
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeStatus['class'] }}">
                                {{ $badgeStatus['label'] }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-600 space-y-1">
                            <p><strong>Jenis Layanan:</strong> {{ $permohonan->jenisSurat->nama_layanan }}</p>
                            <p><strong>Pemohon:</strong> {{ $permohonan->nama_pemohon }}</p>
                            <p><strong>Tanggal Pengajuan:</strong> {{ $permohonan->created_at->translatedFormat('d F Y H:i') }}</p>
                        </div>

                        @if($permohonan->catatan_admin)
                            <div class="p-3 rounded-xl bg-white border border-slate-200 text-xs text-slate-700">
                                <strong>Catatan Petugas Desa:</strong><br>
                                {{ $permohonan->catatan_admin }}
                            </div>
                        @endif

                        @if($permohonan->file_surat_jadi)
                            <div class="pt-2">
                                <a href="{{ asset('storage/' . $permohonan->file_surat_jadi) }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-700 text-white text-xs font-bold hover:bg-emerald-800">
                                    📥 Unduh File Surat Selesai (PDF)
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mt-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-700 text-center">
                        Data permohonan dengan kode/NIK <strong>{{ request('kode') }}</strong> tidak ditemukan.
                    </div>
                @endif
            @endif
        </div>
    </div>
</body>
</html>
