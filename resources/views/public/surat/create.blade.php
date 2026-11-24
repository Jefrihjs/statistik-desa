<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Surat Online - {{ $desa->nama_desa }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    @php
        $headerColor = $desa->header_color ?? '#065f46';
        $accentColor = $desa->accent_color ?? '#059669';
        $slugDesa = $desa->slug ?? $desa->id;
        $jenisJson = $jenisSurats->map(function ($j) {
            return [
                'id' => $j->id,
                'nama_layanan' => $j->nama_layanan,
                'catatan' => $j->catatan,
                'syarats' => $j->syarats->map(fn($s) => [
                    'id' => $s->id,
                    'nama_syarat' => $s->nama_syarat,
                    'tipe_input' => $s->tipe_input,
                    'is_wajib' => (bool) $s->is_wajib,
                ])->values(),
            ];
        })->values();
    @endphp

    <div class="max-w-5xl mx-auto py-8 px-4"
         x-data="{
            jenisList: @js($jenisJson),
            selectedId: '{{ old('jenis_surat_id', '') }}',
            get selectedJenis() {
                return this.jenisList.find(j => String(j.id) === String(this.selectedId)) || null;
            },
            get fileSyarats() {
                return this.selectedJenis ? this.selectedJenis.syarats.filter(s => s.tipe_input === 'file') : [];
            },
            get fisikSyarats() {
                return this.selectedJenis ? this.selectedJenis.syarats.filter(s => s.tipe_input === 'info_fisik') : [];
            }
         }">

        <div style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }});"
             class="rounded-3xl p-6 sm:p-8 text-white mb-6 shadow-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest opacity-85">
                    Layanan Administrasi Online &bull; {{ $desa->nama_desa }}
                </p>
                <h1 class="text-2xl sm:text-3xl font-black uppercase mt-1">
                    Permohonan Surat Online
                </h1>
                <p class="text-xs sm:text-sm opacity-90 mt-1">
                    Pilih jenis surat untuk melihat persyaratan dan mengunggah dokumen pendukung secara online.
                </p>
            </div>
            <a href="{{ route('public.surat.tracking', $slugDesa) }}"
               class="shrink-0 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/20 hover:bg-white/30 text-white text-xs font-bold backdrop-blur transition">
                🔍 Lacak Status Surat
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl p-4 text-xs">
                <p class="font-bold mb-1">Periksa kembali isian formulir Anda:</p>
                <ul class="list-disc pl-5 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('public.surat.store', $slugDesa) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm h-fit">
                    <h2 class="text-base font-black text-slate-800 pb-3 border-b border-slate-200 mb-5">
                        Data Pemohon
                    </h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="nama_pemohon" value="{{ old('nama_pemohon') }}" required
                                   placeholder="Sesuai KTP"
                                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    NIK (16 digit) <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" minlength="16" required
                                       placeholder="1906xxxxxxxxxxxx"
                                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    No. HP / WhatsApp <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}" required
                                       placeholder="0812xxxxxxxx"
                                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email (Opsional)</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   placeholder="nama@email.com"
                                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                Alamat Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="alamat" rows="3" required
                                      placeholder="Dusun, RT/RW, Desa..."
                                      class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:outline-none">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                        <h2 class="text-base font-black text-slate-800 pb-3 border-b border-slate-200 mb-5">
                            Detail Permohonan
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Jenis Surat <span class="text-rose-500">*</span>
                                </label>
                                <select name="jenis_surat_id" x-model="selectedId" required
                                        class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm bg-white focus:border-emerald-600 focus:outline-none">
                                    <option value="">-- Pilih Jenis Surat --</option>
                                    @foreach($jenisSurats as $jenis)
                                        <option value="{{ $jenis->id }}">{{ $jenis->nama_layanan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <template x-if="selectedJenis && (selectedJenis.catatan || fisikSyarats.length > 0)">
                                <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-900 space-y-2">
                                    <template x-if="selectedJenis.catatan">
                                        <p class="font-bold">⚠️ Catatan: <span class="font-normal" x-text="selectedJenis.catatan"></span></p>
                                    </template>
                                    <template x-if="fisikSyarats.length > 0">
                                        <div>
                                            <p class="font-bold mb-1">📌 Syarat Fisik / Ketentuan Tambahan:</p>
                                            <ul class="list-disc pl-5 space-y-0.5">
                                                <template x-for="f in fisikSyarats" :key="f.id">
                                                    <li x-text="f.nama_syarat"></li>
                                                </template>
                                            </ul>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    Keperluan / Tujuan <span class="text-rose-500">*</span>
                                </label>
                                <textarea name="keperluan" rows="3" required
                                          placeholder="Jelaskan keperluan pembuatan surat ini (atau alamat tujuan pindah jika mengurus surat pindah)..."
                                          class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm focus:border-emerald-600 focus:outline-none">{{ old('keperluan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
                        <div class="pb-3 border-b border-slate-200 mb-5 flex flex-wrap items-baseline justify-between gap-2">
                            <h2 class="text-base font-black text-slate-800">
                                Dokumen Pendukung <span class="text-xs font-normal text-slate-500">(Maks. 2MB, JPG/PNG/PDF)</span>
                            </h2>
                        </div>

                        <template x-if="!selectedJenis">
                            <div class="text-center py-8 px-4 rounded-2xl bg-slate-50 border border-dashed border-slate-300 text-xs text-slate-500">
                                Silakan pilih <strong>Jenis Surat</strong> terlebih dahulu untuk menampilkan daftar dokumen yang wajib diunggah.
                            </div>
                        </template>

                        <template x-if="selectedJenis">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <template x-for="(syarat, index) in fileSyarats" :key="syarat.id">
                                    <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
                                        <label class="block text-xs font-bold text-slate-700 mb-2 leading-snug">
                                            <span x-text="(index + 1) + '. ' + syarat.nama_syarat"></span>
                                            <span x-show="syarat.is_wajib" class="text-rose-600">*</span>
                                            <span x-show="!syarat.is_wajib" class="text-[11px] font-normal text-slate-400">(Opsional)</span>
                                        </label>
                                        <input type="file"
                                               :name="'dokumen[' + syarat.id + ']'"
                                               :required="syarat.is_wajib"
                                               accept=".jpg,.jpeg,.png,.pdf"
                                               class="block w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit"
                        class="flex-1 py-3.5 px-6 rounded-2xl bg-emerald-800 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition cursor-pointer">
                    📎 Kirim Permohonan
                </button>
                <a href="{{ route('public.surat.create', $slugDesa) }}"
                   class="py-3.5 px-6 rounded-2xl bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-bold text-sm transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</body>
</html>
