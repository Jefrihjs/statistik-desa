<x-app-layout>
    @php
        $headerColor = $desa->header_color ?? '#065f46';
        $accentColor = $desa->accent_color ?? '#059669';
        $slugDesa = $desa->slug ?? $desa->id;
        $publicUrl = route('public.surat.create', $slugDesa);
        $trackingUrl = route('public.surat.tracking', $slugDesa);
        $iframeForm = '<iframe src="' . $publicUrl . '" width="100%" height="1100" frameborder="0" style="border:none; border-radius:12px;"></iframe>';
        $iframeTracking = '<iframe src="' . $trackingUrl . '" width="100%" height="700" frameborder="0" style="border:none; border-radius:12px;"></iframe>';
    @endphp

    <div class="py-8 px-4 sm:px-6 lg:px-10 bg-slate-50 theme-bg-main min-h-screen"
         x-data="{
            tab: 'permohonan',
            modalTambah: false,
            newSyarats: [
                { nama: 'Fotokopi Kartu Keluarga (KK)', tipe: 'file', wajib: true },
                { nama: 'Fotokopi Kartu Tanda Penduduk (KTP)', tipe: 'file', wajib: true }
            ]
         }">
        <div class="max-w-7xl mx-auto">

            <div class="mb-4">
                <a href="{{ route('desa.dashboard') }}"
                   class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 theme-text-sub hover:text-slate-800">
                    ← Kembali ke Hub Utama TARSIUS
                </a>
            </div>

            <div style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }});"
                 class="rounded-[2rem] p-7 text-white mb-6 shadow-lg flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-widest opacity-90">
                        TARSIUS &bull; Pelayanan Administrasi Desa
                    </p>
                    <h1 class="text-2xl sm:text-3xl font-black uppercase mt-1">
                        Permohonan Surat Online
                    </h1>
                    <p class="text-xs opacity-90 mt-1">
                        Verifikasi berkas permohonan surat warga dan kelola katalog layanan surat beserta syaratnya.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ $publicUrl }}" target="_blank"
                       class="px-4 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-bold backdrop-blur transition">
                        🔗 Pratinjau Form Warga ↗
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white theme-bg-card rounded-2xl border border-slate-200 theme-border p-4 shadow-sm">
                    <p class="text-[11px] font-bold uppercase text-slate-400 theme-text-sub">Menunggu Verifikasi</p>
                    <p class="text-2xl font-black text-amber-500 mt-1">{{ $stats['menunggu'] }}</p>
                </div>
                <div class="bg-white theme-bg-card rounded-2xl border border-slate-200 theme-border p-4 shadow-sm">
                    <p class="text-[11px] font-bold uppercase text-slate-400 theme-text-sub">Berkas Kurang</p>
                    <p class="text-2xl font-black text-rose-500 mt-1">{{ $stats['berkas_kurang'] }}</p>
                </div>
                <div class="bg-white theme-bg-card rounded-2xl border border-slate-200 theme-border p-4 shadow-sm">
                    <p class="text-[11px] font-bold uppercase text-slate-400 theme-text-sub">Sedang Diproses</p>
                    <p class="text-2xl font-black text-blue-500 mt-1">{{ $stats['diproses'] }}</p>
                </div>
                <div class="bg-white theme-bg-card rounded-2xl border border-slate-200 theme-border p-4 shadow-sm">
                    <p class="text-[11px] font-bold uppercase text-slate-400 theme-text-sub">Selesai</p>
                    <p class="text-2xl font-black text-emerald-500 mt-1">{{ $stats['selesai'] }}</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mb-6">
                <button type="button" @click="tab = 'permohonan'"
                        :class="tab === 'permohonan' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white theme-bg-card text-slate-700 theme-text-main border-slate-200 theme-border'"
                        class="px-4 py-2.5 rounded-xl border text-xs font-bold transition cursor-pointer">
                    📥 Permohonan Masuk ({{ $permohonans->total() }})
                </button>
                <button type="button" @click="tab = 'katalog'"
                        :class="tab === 'katalog' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white theme-bg-card text-slate-700 theme-text-main border-slate-200 theme-border'"
                        class="px-4 py-2.5 rounded-xl border text-xs font-bold transition cursor-pointer">
                    ⚙️ Kelola Jenis Surat & Syarat ({{ $jenisSurats->count() }})
                </button>
                <button type="button" @click="tab = 'embed'"
                        :class="tab === 'embed' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white theme-bg-card text-slate-700 theme-text-main border-slate-200 theme-border'"
                        class="px-4 py-2.5 rounded-xl border text-xs font-bold transition cursor-pointer">
                    🌐 Link Publik & Kode Embed
                </button>
            </div>

            <!-- TAB 1: PERMOHONAN MASUK -->
            <div x-show="tab === 'permohonan'" class="bg-white theme-bg-card rounded-3xl border border-slate-200 theme-border p-6 shadow-sm">
                <form method="GET" class="flex flex-col sm:flex-row gap-3 mb-5">
                    <input type="text" name="q" value="{{ request('q') }}"
                           placeholder="Cari nama, NIK, atau kode registrasi..."
                           class="flex-1 rounded-xl border border-slate-300 theme-border theme-bg-main theme-text-main px-3.5 py-2.5 text-xs">
                    <select name="status" class="rounded-xl border border-slate-300 theme-border theme-bg-main theme-text-main px-3.5 py-2.5 text-xs">
                        <option value="">Semua Status</option>
                        <option value="menunggu" @selected(request('status') === 'menunggu')>Menunggu</option>
                        <option value="berkas_kurang" @selected(request('status') === 'berkas_kurang')>Berkas Kurang</option>
                        <option value="diproses" @selected(request('status') === 'diproses')>Diproses</option>
                        <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                        <option value="ditolak" @selected(request('status') === 'ditolak')>Ditolak</option>
                    </select>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold cursor-pointer">
                        Filter
                    </button>
                </form>

                <div class="space-y-4">
                    @forelse($permohonans as $p)
                        <div class="p-5 rounded-2xl border border-slate-200 theme-border bg-slate-50/60 dark:bg-slate-900/30">
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                                <div class="lg:col-span-8 space-y-2">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-mono text-xs font-black text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg">
                                            {{ $p->kode_registrasi }}
                                        </span>
                                        <span class="text-sm font-black text-slate-800 theme-text-main uppercase">
                                            {{ $p->jenisSurat->nama_layanan ?? '-' }}
                                        </span>
                                        <span class="text-[11px] text-slate-400 theme-text-sub">
                                            &bull; {{ $p->created_at->translatedFormat('d M Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-700 theme-text-main">
                                        <strong>{{ $p->nama_pemohon }}</strong> (NIK: {{ $p->nik }}) &bull; WA: {{ $p->no_hp }}
                                    </p>
                                    <p class="text-xs text-slate-500 theme-text-sub">Alamat: {{ $p->alamat }}</p>
                                    <p class="text-xs text-slate-600 theme-text-main"><strong>Keperluan:</strong> {{ $p->keperluan }}</p>

                                    <div class="pt-2 flex flex-wrap gap-2">
                                        @foreach($p->dokumens as $dok)
                                            <a href="{{ asset('storage/' . $dok->file_path) }}" target="_blank"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white theme-bg-card border border-slate-300 theme-border hover:border-emerald-500 text-[11px] font-semibold text-slate-700 theme-text-main">
                                                📎 {{ $dok->nama_syarat }} ↗
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                                <form action="{{ route('desa.surat.permohonan.status', $p->id) }}" method="POST" enctype="multipart/form-data"
                                      class="lg:col-span-4 bg-white theme-bg-card p-4 rounded-2xl border border-slate-200 theme-border space-y-2.5">
                                    @csrf
                                    <label class="block text-[11px] font-bold text-slate-500 theme-text-sub uppercase">Tindak Lanjut</label>
                                    <select name="status" class="w-full rounded-xl border border-slate-300 theme-border theme-bg-main theme-text-main px-3 py-2 text-xs">
                                        <option value="menunggu" @selected($p->status === 'menunggu')>Menunggu Verifikasi</option>
                                        <option value="berkas_kurang" @selected($p->status === 'berkas_kurang')>Berkas Kurang</option>
                                        <option value="diproses" @selected($p->status === 'diproses')>Sedang Diproses</option>
                                        <option value="selesai" @selected($p->status === 'selesai')>Selesai / Siap Diambil</option>
                                        <option value="ditolak" @selected($p->status === 'ditolak')>Ditolak</option>
                                    </select>
                                    <input type="text" name="catatan_admin" value="{{ $p->catatan_admin }}"
                                           placeholder="Catatan untuk pemohon..."
                                           class="w-full rounded-xl border border-slate-300 theme-border theme-bg-main theme-text-main px-3 py-2 text-xs">
                                    <div>
                                        <label class="block text-[10px] text-slate-400 theme-text-sub mb-1">Upload Surat Jadi PDF (Opsional)</label>
                                        <input type="file" name="file_surat_jadi" accept=".pdf" class="w-full text-[11px] theme-text-sub">
                                    </div>
                                    <button type="submit"
                                            class="w-full py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold cursor-pointer">
                                        Simpan Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs text-slate-400 theme-text-sub py-8">Belum ada permohonan surat masuk.</p>
                    @endforelse
                </div>

                <div class="mt-4">{{ $permohonans->links() }}</div>
            </div>

            <!-- TAB 2: KATALOG JENIS SURAT & SYARAT DINAMIS -->
            <div x-show="tab === 'katalog'" class="space-y-6">
                <div class="bg-white theme-bg-card rounded-3xl border border-slate-200 theme-border p-6 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-base font-black text-slate-800 theme-text-main uppercase">Katalog Jenis Surat Desa</h2>
                        <p class="text-xs text-slate-500 theme-text-sub mt-0.5">
                            Tambah jenis surat baru atau sesuaikan syarat upload dokumen untuk masing-masing layanan surat.
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <form action="{{ route('desa.surat.jenis.import-default') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold cursor-pointer">
                                📥 Tambah Template Default
                            </button>
                        </form>
                        <button type="button" @click="modalTambah = true"
                                class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold cursor-pointer">
                            + Tambah Jenis Surat Baru
                        </button>
                    </div>
                </div>

                <div x-show="modalTambah" x-transition class="bg-white theme-bg-card rounded-3xl border-2 border-emerald-500 p-6 shadow-md">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100 theme-border mb-4">
                        <h3 class="text-sm font-black uppercase text-emerald-500">Tambah Layanan Surat Baru</h3>
                        <button type="button" @click="modalTambah = false" class="text-xs font-bold text-slate-400 hover:text-slate-700">✕ Tutup</button>
                    </div>

                    <form action="{{ route('desa.surat.jenis.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 theme-text-main mb-1">Nama Layanan Surat *</label>
                                <input type="text" name="nama_layanan" required placeholder="Contoh: Surat Keterangan Belum Menikah"
                                       class="w-full rounded-xl border border-slate-300 theme-border theme-bg-main theme-text-main px-3.5 py-2 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 theme-text-main mb-1">Kode Klasifikasi (Opsional)</label>
                                <input type="text" name="kode_surat" placeholder="Contoh: 474.2"
                                       class="w-full rounded-xl border border-slate-300 theme-border theme-bg-main theme-text-main px-3.5 py-2 text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 theme-text-main mb-1">Catatan / Masa Berlaku (Opsional)</label>
                            <input type="text" name="catatan" placeholder="Contoh: Berlaku 3 bulan sejak diterbitkan"
                                   class="w-full rounded-xl border border-slate-300 theme-border theme-bg-main theme-text-main px-3.5 py-2 text-xs">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold text-slate-700 theme-text-main">Daftar Syarat Dokumen *</label>
                                <button type="button"
                                        @click="newSyarats.push({ nama: '', tipe: 'file', wajib: true })"
                                        class="px-3 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold hover:bg-indigo-100">
                                    + Tambah Baris Syarat
                                </button>
                            </div>

                            <div class="space-y-2">
                                <template x-for="(s, idx) in newSyarats" :key="idx">
                                    <div class="flex flex-col sm:flex-row gap-2 items-center bg-slate-50 dark:bg-slate-900/30 p-2.5 rounded-xl border border-slate-200 theme-border">
                                        <input type="text" name="syarat_nama[]" x-model="s.nama" required
                                               placeholder="Nama syarat (misal: Pengantar RT / 2 Lembar Materai Rp10.000)"
                                               class="flex-1 w-full rounded-lg border border-slate-300 theme-border theme-bg-main theme-text-main px-3 py-1.5 text-xs">
                                        <select name="syarat_tipe[]" x-model="s.tipe"
                                                class="rounded-lg border border-slate-300 theme-border theme-bg-main theme-text-main px-2.5 py-1.5 text-xs">
                                            <option value="file">Upload File (JPG/PDF)</option>
                                            <option value="info_fisik">Syarat Fisik / Info Saja</option>
                                        </select>
                                        <label class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 theme-text-main px-2">
                                            <input type="checkbox" :name="'syarat_wajib[' + idx + ']'" value="1" x-model="s.wajib">
                                            Wajib
                                        </label>
                                        <button type="button" x-show="newSyarats.length > 1" @click="newSyarats.splice(idx, 1)"
                                                class="text-rose-500 text-xs font-bold px-2">
                                            Hapus
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold cursor-pointer">
                                Simpan Layanan Surat
                            </button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($jenisSurats as $jenis)
                        <div class="bg-white theme-bg-card rounded-2xl border border-slate-200 theme-border p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="text-sm font-black text-slate-800 theme-text-main uppercase">{{ $jenis->nama_layanan }}</h3>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $jenis->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                        {{ $jenis->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                                @if($jenis->catatan)
                                    <p class="text-[11px] text-amber-800 bg-amber-50 rounded-lg px-2.5 py-1 mt-2">
                                        ⚠️ {{ $jenis->catatan }}
                                    </p>
                                @endif
                                <ol class="mt-3 list-decimal pl-4 space-y-1 text-xs text-slate-600 theme-text-sub">
                                    @foreach($jenis->syarats as $s)
                                        <li>
                                            {{ $s->nama_syarat }}
                                            @if($s->tipe_input === 'info_fisik')
                                                <span class="text-[10px] px-1.5 py-0.2 bg-amber-100 text-amber-800 rounded font-semibold">Fisik</span>
                                            @endif
                                            @if(!$s->is_wajib)
                                                <span class="text-[10px] opacity-70">(Opsional)</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </div>

                            <div class="mt-4 pt-3 border-t border-slate-100 theme-border flex items-center justify-between gap-2">
                                <form action="{{ route('desa.surat.jenis.toggle', $jenis->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-bold text-indigo-400 hover:underline cursor-pointer">
                                        {{ $jenis->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ route('desa.surat.jenis.destroy', $jenis->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus layanan surat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-rose-500 hover:underline cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- TAB 3: LINK & EMBED -->
            <div x-show="tab === 'embed'" class="bg-white theme-bg-card rounded-3xl border border-slate-200 theme-border p-6 shadow-sm">
                <h2 class="text-base font-black text-slate-800 theme-text-main uppercase mb-4">Link Publik & Kode Embed Website Desa</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/30 border border-slate-200 theme-border space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-black uppercase text-slate-800 theme-text-main">Form Permohonan Surat Online</h3>
                            <a href="{{ $publicUrl }}" target="_blank" class="text-xs font-bold text-emerald-500">🔗 Buka ↗</a>
                        </div>
                        <textarea readonly rows="3" onclick="this.select()"
                                  class="w-full text-xs font-mono bg-white theme-bg-main theme-text-main border border-slate-300 theme-border rounded-xl p-2.5">{{ $iframeForm }}</textarea>
                    </div>
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900/30 border border-slate-200 theme-border space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-black uppercase text-slate-800 theme-text-main">Lacak Status Permohonan Surat</h3>
                            <a href="{{ $trackingUrl }}" target="_blank" class="text-xs font-bold text-emerald-500">🔗 Buka ↗</a>
                        </div>
                        <textarea readonly rows="3" onclick="this.select()"
                                  class="w-full text-xs font-mono bg-white theme-bg-main theme-text-main border border-slate-300 theme-border rounded-xl p-2.5">{{ $iframeTracking }}</textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
