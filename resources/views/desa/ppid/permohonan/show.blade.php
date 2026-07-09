<x-app-layout>
    <div class="py-10 px-4 bg-slate-50 min-h-screen"
         x-data="{
            openPemberitahuan: false,
            openTidakLengkap: false,
            openUploadSelesai: false,
            openUpdateStatus: false
         }">

        <div class="max-w-6xl mx-auto">

            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-black text-slate-800 uppercase italic tracking-widest">
                        Detail Permohonan Informasi
                    </h1>
                    <div class="font-black text-slate-900 text-lg">
                        {{ $permohonan->nomor_pendaftaran }}
                    </div>
                </div>
                <div class="mt-3 inline-flex flex-col gap-1 rounded-2xl bg-blue-50 border border-blue-100 px-5 py-3">
                    <span class="text-[10px] font-black uppercase tracking-[0.25em] text-blue-400">
                        Kode Permohonan
                    </span>

                    <span class="text-2xl font-black tracking-[0.25em] text-blue-600">
                        {{ strtoupper($permohonan->kode_permohonan ?? '-') }}
                    </span>
                </div>

                <a href="{{ route('desa.ppid.permohonan.index') }}"
                   class="bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-2xl font-bold hover:bg-slate-50 transition-all text-sm shadow-sm">
                    ← Kembali
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- KONTEN --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM KIRI --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- IDENTITAS --}}
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="bg-slate-900 px-8 py-5 flex justify-between items-center">
                            <span class="text-slate-400 font-black tracking-[0.2em] text-[10px] uppercase">
                                Data Identitas Pemohon
                            </span>

                            @php
                                $statusClass = match($permohonan->status) {
                                    'selesai' => 'bg-emerald-500',
                                    'diproses' => 'bg-blue-600',
                                    'ditolak' => 'bg-rose-600',
                                    'tidak_lengkap' => 'bg-amber-500',
                                    default => 'bg-orange-500',
                                };
                            @endphp

                            <span class="px-4 py-1.5 {{ $statusClass }} text-white text-[10px] font-black rounded-full uppercase tracking-widest">
                                {{ strtoupper(str_replace('_', ' ', $permohonan->status)) }}
                            </span>
                        </div>

                        <div class="p-8 space-y-6">
                            <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                Kode Permohonan
                            </label>

                            <div class="font-black text-blue-600 text-xl tracking-[0.25em]">
                                {{ strtoupper($permohonan->kode_permohonan ?? '-') }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                Nomor Pendaftaran
                            </label>

                            <div class="font-black text-slate-900 text-lg">
                                {{ $permohonan->nomor_pendaftaran }}
                            </div>
                        </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                    Nama Pemohon
                                </label>
                                <div class="font-black text-slate-900 text-xl">
                                    {{ $permohonan->nama }} ({{ strtoupper($permohonan->kategori_pemohon) }})
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                        NIK / No Identitas
                                    </label>
                                    <div class="font-bold text-slate-700">
                                        {{ $permohonan->nik }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                        Pekerjaan
                                    </label>
                                    <div class="font-bold text-slate-700">
                                        {{ $permohonan->pekerjaan ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                        Nomor HP / WA
                                    </label>
                                    <div class="font-bold text-slate-700">
                                        {{ $permohonan->no_hp }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                        Email
                                    </label>
                                    <div class="font-bold text-slate-700">
                                        {{ $permohonan->email ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                    Alamat
                                </label>
                                <div class="font-semibold text-slate-600 italic leading-relaxed bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                    {{ $permohonan->alamat }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RINCIAN --}}
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8">
                        <h3 class="text-sm font-black text-slate-800 mb-6 flex items-center gap-2 uppercase tracking-widest">
                            <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                            Rincian Permohonan Informasi
                        </h3>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                    Rincian Informasi yang Dibutuhkan
                                </label>
                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm text-slate-700 leading-relaxed">
                                    {{ $permohonan->rincian_informasi ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                    Tujuan Penggunaan Informasi
                                </label>
                                <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-sm text-slate-700 leading-relaxed">
                                    {{ $permohonan->tujuan_penggunaan ?? '-' }}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                        Cara Memperoleh
                                    </label>
                                    <p class="text-sm font-bold text-slate-700 capitalize">
                                        {{ $permohonan->cara_memperoleh ?? '-' }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                        Jenis Salinan
                                    </label>
                                    <p class="text-sm font-bold text-slate-700 capitalize">
                                        {{ $permohonan->jenis_salinan ?? '-' }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                        Cara Pengiriman
                                    </label>
                                    <p class="text-sm font-bold text-slate-700 capitalize">
                                        {{ $permohonan->cara_pengiriman ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            @if($permohonan->no_wa)
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                        Nomor WhatsApp Pengiriman
                                    </label>
                                    <p class="text-sm font-bold text-slate-700">
                                        {{ $permohonan->no_wa }}
                                    </p>
                                </div>
                            @endif

                            @if($permohonan->catatan_admin)
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                        Catatan Admin
                                    </label>
                                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 text-sm text-amber-700 leading-relaxed font-bold">
                                        {{ $permohonan->catatan_admin }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if(isset($keberatan) && $keberatan)
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-amber-100 overflow-hidden"
         x-data="{ openTanggapanKeberatan: false }">

        <div class="bg-amber-500 px-8 py-4 flex items-center justify-between">
            <h3 class="text-sm font-black text-white uppercase tracking-[0.2em]">
                Ajuan Keberatan Informasi
            </h3>

            @php
                $badgeKeberatan = match($keberatan->status) {
                    'selesai' => 'bg-emerald-600 text-white',
                    'diproses' => 'bg-blue-600 text-white',
                    'ditolak' => 'bg-rose-600 text-white',
                    default => 'bg-blue-600 text-white',
                };
            @endphp

            <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase {{ $badgeKeberatan }}">
                {{ $keberatan->status === 'diajukan' ? 'Belum Ditanggapi' : str_replace('_', ' ', $keberatan->status) }}
            </span>
        </div>

        <div class="p-8">
            <h3 class="text-center text-lg font-black text-slate-800 mb-8">
                Pernyataan Keberatan Informasi
            </h3>

            <div class="space-y-8">

                {{-- A. INFORMASI PENGAJU KEBERATAN --}}
                <div>
                    <h4 class="text-sm font-black text-slate-800 uppercase mb-4">
                        A. Informasi Pengaju Keberatan
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-[260px_1fr] gap-y-2 text-sm">
                        <div class="text-slate-600">Kode Monitoring</div>
                        <div class="font-semibold text-slate-900">
                            : {{ strtoupper($permohonan->kode_permohonan ?? '-') }}
                        </div>

                        <div class="text-slate-600">Nomor Permohonan Informasi</div>
                        <div class="font-semibold text-slate-900">
                            : {{ $permohonan->nomor_pendaftaran }}
                        </div>

                        <div class="text-slate-600">Tujuan Penggunaan Informasi</div>
                        <div class="font-semibold text-slate-900">
                            : {{ $permohonan->tujuan_penggunaan ?? '-' }}
                        </div>
                    </div>

                    <div class="mt-6 ml-0 md:ml-10">
                        <h5 class="font-black text-slate-800 mb-2">
                            Identitas Pemohon
                        </h5>

                        <div class="grid grid-cols-1 md:grid-cols-[220px_1fr] gap-y-2 text-sm">
                            <div class="text-slate-600">Nama</div>
                            <div class="font-semibold text-slate-900">: {{ $permohonan->nama }}</div>

                            <div class="text-slate-600">Alamat</div>
                            <div class="font-semibold text-slate-900">: {{ $permohonan->alamat }}</div>

                            <div class="text-slate-600">Pekerjaan</div>
                            <div class="font-semibold text-slate-900">: {{ $permohonan->pekerjaan ?? '-' }}</div>

                            <div class="text-slate-600">Nomor Ponsel</div>
                            <div class="font-semibold text-slate-900">: {{ $permohonan->no_hp }}</div>
                        </div>
                    </div>
                </div>

                {{-- B. ALASAN --}}
                <div>
                    <h4 class="text-sm font-black text-slate-800 uppercase mb-4">
                        B. Alasan Pengajuan Keberatan
                    </h4>

                    @php
                        $masterAlasan = [
                            'A' => 'Permohonan informasi ditolak',
                            'B' => 'Informasi berkala tidak disediakan',
                            'C' => 'Permintaan informasi tidak ditanggapi',
                            'D' => 'Permintaan informasi ditanggapi tidak sebagaimana yang diminta',
                            'E' => 'Permintaan informasi tidak dipenuhi',
                            'F' => 'Biaya yang dikenakan tidak wajar',
                            'G' => 'Informasi disampaikan melebihi jangka waktu',
                        ];

                        $alasanTerpilih = json_decode($keberatan->alasan_keberatan, true);

                        if (!is_array($alasanTerpilih)) {
                            $alasanTerpilih = [$keberatan->alasan_keberatan];
                        }
                    @endphp

                    <div class="ml-0 md:ml-10 space-y-1 text-sm text-slate-700">
                        @foreach($masterAlasan as $kode => $label)
                            <div>
                                <span class="inline-block w-5">{{ $kode }}.</span>
                                <span>{{ $label }}</span>

                                @if(in_array($kode, $alasanTerpilih))
                                    <span class="text-blue-600 font-black ml-1">✓</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- C. KASUS POSISI --}}
                <div>
                    <h4 class="text-sm font-black text-slate-800 uppercase mb-3">
                        C. Kasus Posisi
                    </h4>

                    <div class="ml-0 md:ml-10 bg-slate-50 border border-slate-100 rounded-2xl p-5 text-sm text-slate-700 leading-relaxed">
                        {{ $keberatan->uraian_alasan ?? '-' }}
                    </div>
                </div>

                {{-- D. TANGGAL TANGGAPAN --}}
                <div>
                    <h4 class="text-sm font-black text-slate-800 uppercase mb-3">
                        D. Hari/Tanggal Tanggapan Atas Keberatan Akan Diberikan
                    </h4>

                    <div class="ml-0 md:ml-10 text-sm font-semibold text-slate-800">
                        {{ $keberatan->created_at->copy()->addDays(30)->translatedFormat('l, d F Y') }}
                    </div>
                </div>

                <div class="text-center text-sm text-slate-700 pt-6">
                    <p>Demikian keberatan ini disampaikan, saya menyampaikan terima kasih.</p>
                    <p class="mt-2">{{ $keberatan->created_at->translatedFormat('l, d F Y') }}</p>

                    <div class="mt-8">
                        <p>Pengaju Keberatan</p>
                        <p class="font-black mt-2">{{ $permohonan->nama }}</p>
                    </div>
                </div>

                @if($keberatan->tanggapan_admin)
                                    <div class="mt-8 rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                                        <h4 class="text-sm font-black text-emerald-700 uppercase tracking-widest mb-3">
                                            Tanggapan Atasan PPID
                                        </h4>

                                        <div class="text-sm text-emerald-900 leading-relaxed">
                                            {{ $keberatan->tanggapan_admin }}
                                        </div>

                                        <div class="mt-4 text-xs text-emerald-700 font-bold">
                                            Ditanggapi oleh:
                                            {{ $keberatan->nama_atasan_ppid ?? '-' }}
                                            @if($keberatan->posisi_atasan)
                                                — {{ $keberatan->posisi_atasan }}
                                            @endif
                                            @if($keberatan->ditanggapi_pada)
                                                <br>
                                                Pada {{ $keberatan->ditanggapi_pada->translatedFormat('d F Y H:i') }} WIB
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if(!$keberatan->tanggapan_admin)
                                    <div class="border-t border-slate-100 pt-6">
                                        <button type="button"
                                                @click="openTanggapanKeberatan = !openTanggapanKeberatan"
                                                class="px-7 py-3 border border-violet-500 text-violet-600 font-bold text-sm hover:bg-violet-50 transition">
                                            Berikan Tanggapan
                                        </button>

                                        <div x-show="openTanggapanKeberatan"
                                            x-transition
                                            class="mt-6 border border-slate-200 p-5 bg-white">

                                            <form action="{{ route('desa.ppid.permohonan.keberatan.tanggapan', $permohonan->id) }}"
                                                method="POST">
                                                @csrf

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                                    <input type="text"
                                                        name="nama_atasan_ppid"
                                                        required
                                                        placeholder="Nama Atasan PPID"
                                                        class="w-full border border-slate-300 px-4 py-3 text-sm">

                                                    <input type="text"
                                                        name="posisi_atasan"
                                                        required
                                                        placeholder="Posisi Atasan"
                                                        class="w-full border border-slate-300 px-4 py-3 text-sm">
                                                </div>

                                                <label class="block text-sm font-bold text-slate-700 mb-2">
                                                    Keputusan
                                                </label>

                                                <textarea name="tanggapan_admin"
                                                        required
                                                        rows="5"
                                                        class="w-full border border-slate-300 px-4 py-3 text-sm mb-4"
                                                        placeholder="Tuliskan keputusan / tanggapan atas keberatan..."></textarea>

                                                <button type="submit"
                                                        class="px-8 py-3 bg-emerald-500 text-white text-sm font-black hover:bg-emerald-600 transition">
                                                    ➤ Kirim Tanggapan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endif

                </div>

                {{-- KOLOM KANAN --}}
                <div class="space-y-8">

                    {{-- BERKAS KTP --}}
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">
                            Berkas Identitas
                        </h3>

                        @if($permohonan->file_ktp)
                            <a href="{{ asset('storage/' . $permohonan->file_ktp) }}"
                               target="_blank"
                               class="flex items-center gap-4 p-5 bg-slate-50 rounded-2xl hover:bg-blue-600 hover:text-white transition-all group border border-slate-100 shadow-sm">
                                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black uppercase tracking-tight">KTP Pemohon</p>
                                    <p class="text-[9px] font-bold uppercase opacity-60">Lihat Lampiran →</p>
                                </div>
                            </a>
                        @else
                            <div class="p-6 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200 text-center">
                                <p class="text-[10px] text-slate-400 font-black uppercase italic tracking-widest">
                                    KTP belum diunggah
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- BERKAS AKTA --}}
                    @if(strtolower($permohonan->kategori_pemohon) === 'lembaga')
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">
                                Berkas Akta Lembaga
                            </h3>

                            @if($permohonan->file_akta)
                                <a href="{{ asset('storage/' . $permohonan->file_akta) }}"
                                   target="_blank"
                                   class="flex items-center gap-4 p-5 bg-emerald-50 rounded-2xl hover:bg-emerald-600 hover:text-white transition-all group border border-emerald-100 shadow-sm">
                                    <div>
                                        <p class="text-sm font-black uppercase tracking-tight">Akta Notaris / Lembaga</p>
                                        <p class="text-[9px] font-bold uppercase opacity-60">Lihat Lampiran →</p>
                                    </div>
                                </a>
                            @else
                                <div class="p-6 bg-amber-50 rounded-2xl border-2 border-dashed border-amber-200 text-center">
                                    <p class="text-[10px] text-amber-600 font-black uppercase italic tracking-widest">
                                        Akta belum diunggah
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- PANEL TINDAK LANJUT --}}
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">
                            Panel Tindak Lanjut
                        </h3>

                        <div class="space-y-4">
                            <button type="button"
                                    @click="openUpdateStatus = true"
                                    class="w-full py-4 rounded-2xl bg-indigo-600 text-white font-black text-[11px] uppercase tracking-widest shadow-lg shadow-indigo-100">
                                Update Status Manual
                            </button>

                            @if((isset($pemberitahuan) && $pemberitahuan) || $permohonan->status === 'tidak_lengkap')
                                <button type="button" disabled class="w-full py-4 rounded-2xl bg-slate-200 text-slate-400 font-black text-[11px] uppercase tracking-widest cursor-not-allowed">
                                    Tindak Lanjut Sudah Dibuat
                                </button>

                                <button type="button" disabled class="w-full py-4 rounded-2xl bg-slate-100 border-2 border-slate-200 text-slate-300 font-black text-[11px] uppercase tracking-widest cursor-not-allowed">
                                    Form Terkunci
                                </button>
                            @else
                                <button type="button"
                                        @click="openPemberitahuan = true"
                                        class="w-full py-4 rounded-2xl bg-blue-600 text-white font-black text-[11px] uppercase tracking-widest shadow-lg shadow-blue-100">
                                    Buat Pemberitahuan
                                </button>

                                <button type="button"
                                        @click="openTidakLengkap = true"
                                        class="w-full py-4 rounded-2xl bg-white border-2 border-slate-200 text-slate-600 font-black text-[11px] uppercase tracking-widest hover:bg-slate-50">
                                    Informasi Tidak Lengkap
                                </button>
                            @endif

                            <div class="relative py-4 flex items-center">
                                <div class="flex-grow border-t border-slate-100"></div>
                                <span class="flex-shrink mx-4 text-[9px] font-black text-slate-300 uppercase italic tracking-widest">
                                    Next Step
                                </span>
                                <div class="flex-grow border-t border-slate-100"></div>
                            </div>

                            @if(in_array($permohonan->status, ['diproses', 'selesai']))
                                <button type="button"
                                        @click="openUploadSelesai = true"
                                        class="w-full py-4 rounded-2xl bg-emerald-600 text-white font-black text-[11px] uppercase tracking-widest shadow-lg shadow-emerald-100">
                                    Upload Bukti Penyelesaian
                                </button>
                            @else
                                <div class="bg-slate-50 p-4 rounded-2xl text-center border border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase">
                                        Upload terkunci. Selesaikan tahapan tindak lanjut terlebih dahulu.
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- DOKUMEN KELUAR --}}
                    @if($permohonan->status === 'tidak_lengkap')
                        <div class="p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <p class="text-sm font-black text-slate-900 uppercase tracking-tight">
                                        Pemberitahuan Tidak Lengkap
                                    </p>

                                    <p class="text-[10px] font-black text-emerald-600 uppercase mt-1">
                                        Tersedia
                                    </p>

                                    <a href="{{ route('desa.ppid.permohonan.cetak_tidak_lengkap', $permohonan->id) }}"
                                    target="_blank"
                                    class="mt-4 inline-flex items-center justify-center rounded-2xl bg-orange-600 px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-orange-700">
                                        Lihat Pemberitahuan Tidak Lengkap
                                    </a>
                                </div>
                            </div>
                        </div>

                    @elseif(isset($pemberitahuan) && $pemberitahuan)
                        <div class="p-5 bg-white rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>

                                <div class="flex-1">
                                    <p class="text-sm font-black text-slate-900 uppercase tracking-tight">
                                        {{ $pemberitahuan->status_informasi === 'dapat_diberikan' ? 'Pemberitahuan Tertulis' : 'Pemberitahuan Penolakan' }}
                                    </p>

                                    <p class="text-[10px] font-black text-emerald-600 uppercase mt-1">
                                        Tersedia
                                    </p>

                                    @if(
                                        $pemberitahuan->status_informasi === 'tidak_dapat_diberikan'
                                        && $pemberitahuan->alasan_penolakan === 'informasi_dikecualikan'
                                    )
                                        <a href="{{ route('desa.ppid.permohonan.cetak_sk_penolakan', $permohonan->id) }}"
                                        target="_blank"
                                        class="mt-4 inline-flex items-center justify-center rounded-2xl bg-red-600 px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-red-700">
                                            Lihat SK Penolakan
                                        </a>
                                    @else
                                        <a href="{{ route('desa.ppid.permohonan.cetak_pemberitahuan', $permohonan->id) }}"
                                        target="_blank"
                                        class="mt-4 inline-flex items-center justify-center rounded-2xl bg-red-600 px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-red-700">
                                            Lihat Dokumen
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-5 bg-white rounded-2xl border border-dashed border-slate-200 text-center">
                            <p class="text-[10px] font-black text-slate-400 uppercase">
                                Dokumen belum tersedia
                            </p>
                        </div>
                    @endif

                    {{-- RIWAYAT PERMOHONAN --}}
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">
                            Riwayat Permohonan
                        </h3>

                        <div class="space-y-6">
                            @forelse($logsPermohonan as $index => $log)
                                <div class="flex items-start gap-4">
                                    <div class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-black shrink-0">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </div>

                                    <div>
                                        <p class="text-[11px] font-black text-blue-600 uppercase mb-1">
                                            {{ $log->created_at->translatedFormat('d M Y - H:i') }} WIB
                                        </p>

                                        <h4 class="text-sm font-black text-slate-800">
                                            {{ $log->judul }}
                                        </h4>

                                        <p class="text-xs text-slate-500 mt-1">
                                            {{ $log->deskripsi }}
                                        </p>

                                        @if($log->actor_name)
                                            <p class="text-[10px] text-slate-400 mt-1 italic">
                                                Oleh: {{ $log->actor_name }}
                                                @if($log->actor_role)
                                                    — {{ str_replace('_', ' ', $log->actor_role) }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-xs text-slate-400 italic">
                                    Riwayat permohonan belum tersedia.
                                </div>
                            @endforelse
                        </div>
                    </div>
                    {{-- RIWAYAT AJUAN KEBERATAN --}}
                    @if(isset($keberatan) && $keberatan)
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">
                                Riwayat Ajuan Keberatan
                            </h3>

                            <div class="space-y-6">
                                @forelse($logsKeberatan as $index => $log)
                                    <div class="flex items-start gap-4">
                                        <div class="w-9 h-9 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-black shrink-0">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </div>

                                        <div>
                                            <p class="text-[11px] font-black text-amber-600 uppercase mb-1">
                                                {{ $log->created_at->translatedFormat('d M Y - H:i') }} WIB
                                            </p>

                                            <h4 class="text-sm font-black text-slate-800">
                                                {{ $log->judul }}
                                            </h4>

                                            <p class="text-xs text-slate-500 mt-1">
                                                {{ $log->deskripsi }}
                                            </p>

                                            @if($log->actor_name)
                                                <p class="text-[10px] text-slate-400 mt-1 italic">
                                                    Oleh: {{ $log->actor_name }}
                                                    @if($log->actor_role)
                                                        — {{ str_replace('_', ' ', $log->actor_role) }}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-xs text-slate-400 italic">
                                        Riwayat keberatan belum tersedia.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- MODAL UPDATE STATUS --}}
        <div x-show="openUpdateStatus"
             class="fixed inset-0 z-[999999] overflow-y-auto"
             x-cloak
             style="display:none;">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="openUpdateStatus = false"></div>

                <div class="relative bg-white rounded-[2.5rem] shadow-2xl sm:max-w-lg sm:w-full overflow-hidden">
                    <form action="{{ route('desa.ppid.permohonan.update_status', $permohonan->id) }}" method="POST">
                        @csrf

                        <div class="p-10">
                            <h3 class="text-xl font-black text-slate-800 mb-2">
                                Update Status Manual
                            </h3>

                            <p class="text-xs text-slate-500 mb-8">
                                Ubah status permohonan milik <strong>{{ $permohonan->nama }}</strong>.
                            </p>

                            <div class="text-left space-y-4">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                    Pilih Status
                                </label>

                                <select name="status"
                                        class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none">
                                    <option value="pending" {{ $permohonan->status == 'pending' ? 'selected' : '' }}>
                                        PENDING (Menunggu)
                                    </option>
                                    <option value="diproses" {{ $permohonan->status == 'diproses' ? 'selected' : '' }}>
                                        DIPROSES (Verifikasi)
                                    </option>
                                    <option value="selesai" {{ $permohonan->status == 'selesai' ? 'selected' : '' }}>
                                        SELESAI (Disetujui)
                                    </option>
                                    <option value="ditolak" {{ $permohonan->status == 'ditolak' ? 'selected' : '' }}>
                                        DITOLAK
                                    </option>
                                    <option value="tidak_lengkap" {{ $permohonan->status == 'tidak_lengkap' ? 'selected' : '' }}>
                                        TIDAK LENGKAP
                                    </option>
                                </select>

                                <textarea name="catatan_admin"
                                          rows="3"
                                          class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl text-sm font-bold outline-none"
                                          placeholder="Catatan admin opsional...">{{ $permohonan->catatan_admin }}</textarea>
                            </div>

                            <div class="mt-10 flex gap-4">
                                <button type="button"
                                        @click="openUpdateStatus = false"
                                        class="flex-1 p-4 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase">
                                    Batal
                                </button>

                                <button type="submit"
                                        class="flex-1 p-4 bg-indigo-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-lg shadow-indigo-100">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL PEMBERITAHUAN --}}
<div x-show="openPemberitahuan"
     x-data="{
        statusInfo: '',
        penguasaan: 'desa',
        alasanPenolakan: '',
        biayaSalinan: 0,
        biayaKirim: 0,
        biayaLain: 0,
        get totalBiaya() {
            return Number(this.biayaSalinan || 0) + Number(this.biayaKirim || 0) + Number(this.biayaLain || 0)
        }
     }"
     class="fixed inset-0 z-[999999] flex items-center justify-center p-6 bg-slate-900/90 backdrop-blur-sm"
     x-cloak
     x-transition
     style="display:none;">

    <div @click.stop
         class="bg-white w-full max-w-5xl rounded-[3rem] shadow-2xl overflow-hidden flex flex-col relative border border-white/20"
         style="max-height: 90vh;">

        {{-- HEADER --}}
        <div class="px-10 py-7 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-20">
            <h3 class="text-sm font-black text-slate-900 uppercase tracking-[0.25em]">
                Formulir Pemberitahuan Tertulis
            </h3>

            <button type="button"
                    @click="openPemberitahuan = false; statusInfo = ''"
                    class="text-slate-400 hover:text-red-500 transition-colors text-3xl px-4">
                &times;
            </button>
        </div>

        {{-- BODY --}}
        <div class="p-10 overflow-y-auto bg-white flex-1">
            <form action="{{ route('desa.ppid.permohonan.pemberitahuan', $permohonan->id) }}"
                  method="POST"
                  id="formPemberitahuan">
                @csrf

                <input type="hidden" name="total_biaya" :value="totalBiaya">

                {{-- STEP 1: PILIH STATUS INFORMASI --}}
                <div class="pb-8 border-b border-slate-100">
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">
                        Pilih Pemberitahuan Informasi
                    </label>

                    <select name="status_informasi"
                            x-model="statusInfo"
                            required
                            class="w-full border-[5px] border-blue-600 rounded-[1.4rem] px-6 py-5 text-base font-black focus:ring-0 transition-all outline-none bg-slate-50">
                        <option value="">-- Pilih Status Informasi --</option>
                        <option value="dapat_diberikan">Dapat Diberikan</option>
                        <option value="tidak_dapat_diberikan">Tidak Dapat Diberikan</option>
                    </select>
                </div>

                {{-- STEP 2A: JIKA DAPAT DIBERIKAN --}}
                <div x-show="statusInfo === 'dapat_diberikan'"
                     x-transition
                     class="pt-8 space-y-8">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                        {{-- PENGUASAAN INFORMASI --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">
                                Penguasaan Informasi
                            </label>

                            <div class="grid grid-cols-2 gap-4">
                                <label class="flex items-center gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all"
                                       :class="penguasaan === 'desa' ? 'border-indigo-300 bg-indigo-50' : 'border-slate-100 bg-white'">
                                    <input type="radio"
                                           name="penguasaan_informasi"
                                           value="desa"
                                           x-model="penguasaan"
                                           class="w-5 h-5 text-blue-600">
                                    <span class="text-sm font-black text-slate-800">
                                        Kami / Pemerintah Desa
                                    </span>
                                </label>

                                <label class="flex items-center gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all"
                                       :class="penguasaan === 'badan_publik_lain' ? 'border-amber-300 bg-amber-50' : 'border-slate-100 bg-white'">
                                    <input type="radio"
                                           name="penguasaan_informasi"
                                           value="badan_publik_lain"
                                           x-model="penguasaan"
                                           class="w-5 h-5 text-blue-600">
                                    <span class="text-sm font-black text-slate-800">
                                        Badan Publik Lain
                                    </span>
                                </label>
                            </div>

                            <div x-show="penguasaan === 'badan_publik_lain'"
                                 x-transition
                                 class="mt-4">
                                <input type="text"
                                       name="nama_badan_publik_lain"
                                       placeholder="Nama badan publik lain..."
                                       class="w-full p-4 bg-amber-50 border-2 border-amber-100 rounded-2xl text-sm font-bold outline-none focus:border-amber-400">
                            </div>
                        </div>

                        {{-- BENTUK FISIK --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">
                                Bentuk Fisik
                            </label>

                            <div class="space-y-3">
                                <label class="flex items-center gap-4 p-5 rounded-2xl border border-slate-100 bg-white cursor-pointer">
                                    <input type="radio"
                                           name="bentuk_fisik"
                                           value="softcopy"
                                           checked
                                           class="w-5 h-5 text-blue-600">
                                    <span class="text-sm font-black text-slate-800">
                                        Softcopy
                                    </span>
                                </label>

                                <label class="flex items-center gap-4 p-5 rounded-2xl border border-slate-100 bg-white cursor-pointer">
                                    <input type="radio"
                                           name="bentuk_fisik"
                                           value="hardcopy"
                                           class="w-5 h-5 text-blue-600">
                                    <span class="text-sm font-black text-slate-800">
                                        Hardcopy
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL BIAYA --}}
                    <div class="bg-slate-50 p-8 rounded-[2.5rem] border border-slate-100">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">
                            Detail Biaya Sesuai PERKI
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-[9px] font-black text-slate-500 uppercase mb-3">
                                    1. Penyalinan
                                </label>
                                <input type="number"
                                       name="biaya_salinan"
                                       x-model="biayaSalinan"
                                       min="0"
                                       class="w-full p-5 rounded-2xl border-2 border-white bg-white focus:border-blue-400 outline-none text-lg">
                            </div>

                            <div>
                                <label class="block text-[9px] font-black text-slate-500 uppercase mb-3">
                                    2. Pengiriman
                                </label>
                                <input type="number"
                                       name="biaya_kirim"
                                       x-model="biayaKirim"
                                       min="0"
                                       class="w-full p-5 rounded-2xl border-2 border-white bg-white focus:border-blue-400 outline-none text-lg">
                            </div>

                            <div>
                                <label class="block text-[9px] font-black text-slate-500 uppercase mb-3">
                                    3. Lain-lain
                                </label>
                                <input type="number"
                                       name="biaya_lain"
                                       x-model="biayaLain"
                                       min="0"
                                       class="w-full p-5 rounded-2xl border-2 border-white bg-white focus:border-blue-400 outline-none text-lg">
                            </div>
                        </div>

                        <div class="mt-6 pt-5 border-t border-slate-200 flex items-center justify-between">
                            <span class="text-xs font-black text-slate-500 uppercase">
                                Total Keseluruhan:
                            </span>

                            <span class="text-2xl font-black text-blue-600">
                                Rp <span x-text="totalBiaya.toLocaleString('id-ID')"></span>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                            4. Waktu Penyediaan (Hari Kerja)
                        </label>

                        <input type="number"
                               name="waktu_penyediaan"
                               placeholder="Contoh: 3"
                               class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-sm font-bold outline-none focus:border-blue-400">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                            5. Penjelasan Penghitaman / Pengaburan
                        </label>

                        <textarea name="penjelasan_penghitaman"
                                  rows="4"
                                  class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-sm font-bold outline-none focus:border-blue-400"
                                  placeholder="Jelaskan bagian yang dikaburkan dan alasannya, kosongkan jika tidak ada..."></textarea>
                    </div>
                </div>

                {{-- STEP 2B: JIKA TIDAK DAPAT DIBERIKAN --}}
                <div x-show="statusInfo === 'tidak_dapat_diberikan'"
                     x-transition
                     class="pt-8 space-y-6">

                    <label class="block text-[10px] font-black text-red-500 uppercase tracking-widest">
                        Alasan Penolakan
                    </label>

                    <label class="flex items-center gap-5 p-6 rounded-[2rem] border-2 border-red-50 bg-white cursor-pointer">
                        <input type="radio"
                               name="alasan_penolakan"
                               value="informasi_belum_dikuasai"
                               x-model="alasanPenolakan"
                               class="w-5 h-5 text-red-600">
                        <span class="text-sm md:text-base font-black text-slate-900">
                            Informasi belum dikuasai
                        </span>
                    </label>

                    <label class="flex items-center gap-5 p-6 rounded-[2rem] border-2 border-red-50 bg-white cursor-pointer">
                        <input type="radio"
                               name="alasan_penolakan"
                               value="informasi_belum_didokumentasikan"
                               x-model="alasanPenolakan"
                               class="w-5 h-5 text-red-600">
                        <span class="text-sm md:text-base font-black text-slate-900">
                            Informasi belum didokumentasikan
                        </span>
                    </label>

                    <label class="flex items-center gap-5 p-6 rounded-[2rem] border-2 border-slate-200 bg-white cursor-pointer">
                        <input type="radio"
                            name="alasan_penolakan"
                            value="informasi_dikecualikan"
                            class="w-5 h-5 text-orange-600"
                            x-model="alasanPenolakan">
                        <span>
                            <span class="block text-sm md:text-base font-black text-slate-900">
                                Informasi Dikecualikan (Pasal 17)
                            </span>
                            <span class="block text-[10px] font-black text-slate-900 uppercase mt-1">
                                Format: SK Penolakan
                            </span>
                        </span>
                    </label>

                    <div x-show="alasanPenolakan === 'informasi_dikecualikan'"
                        x-transition
                        class="space-y-6 rounded-[2rem] border border-slate-100 bg-slate-50 p-6">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-black text-slate-900 uppercase tracking-widest mb-3">
                                    Pasal 17 UU KIP (Huruf)
                                </label>

                                <select name="pasal_17_huruf"
                                        class="w-full p-5 bg-white border-2 border-blue-600 rounded-2xl text-sm font-black outline-none focus:ring-0">
                                    <option value="">-- Pilih Huruf --</option>
                                    <option value="a">Huruf a (Proses Penegakan Hukum)</option>
                                    <option value="b">Huruf b (HAKI / Persaingan Usaha)</option>
                                    <option value="c">Huruf c (Pertahanan & Keamanan)</option>
                                    <option value="d">Huruf d (Kekayaan Alam)</option>
                                    <option value="e">Huruf e (Ketahanan Ekonomi)</option>
                                    <option value="f">Huruf f (Hubungan Luar Negeri)</option>
                                    <option value="g">Huruf g (Akta Otentik Pribadi)</option>
                                    <option value="h">Huruf h (Rahasia Pribadi / Riwayat Kesehatan)</option>
                                    <option value="i">Huruf i (Memorandum / Surat Internal)</option>
                                    <option value="j">Huruf j (Dikecualikan UU Lainnya)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-900 uppercase tracking-widest mb-3">
                                    Pasal & UU Lainnya Jika Ada
                                </label>

                                <input type="text"
                                    name="pasal_uu_lainnya"
                                    class="w-full p-5 bg-white border-2 border-slate-300 rounded-2xl text-sm font-bold outline-none focus:border-blue-600"
                                    placeholder="Contoh: Pasal 20 UU No...">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-900 uppercase tracking-widest mb-3">
                                Rincian Informasi yang Ditolak
                            </label>

                            <textarea name="rincian_informasi_ditolak"
                                    rows="4"
                                    class="w-full p-5 bg-white border-2 border-slate-300 rounded-2xl text-sm font-bold outline-none focus:border-blue-600"
                                    placeholder="Sebutkan dokumen apa saja yang tidak boleh diberikan..."></textarea>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-900 uppercase tracking-widest mb-3">
                                Hasil Uji Konsekuensi / Alasan Bahaya
                            </label>

                            <textarea name="hasil_uji_konsekuensi"
                                    rows="4"
                                    class="w-full p-5 bg-white border-2 border-slate-300 rounded-2xl text-sm font-bold outline-none focus:border-blue-600"
                                    placeholder="Menjelaskan bahwa membuka informasi dapat mengakibatkan..."></textarea>
                        </div>
                    </div>

                    <div x-show="alasanPenolakan !== 'informasi_dikecualikan'"
                        x-transition>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                            Catatan Penolakan
                        </label>

                        <textarea name="catatan_penolakan"
                                rows="4"
                                class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-2xl text-sm font-bold outline-none focus:border-red-400"
                                placeholder="Tambahkan catatan penolakan jika diperlukan..."></textarea>
                    </div>
                </div>

            </form>
        </div>

        {{-- FOOTER --}}
        <div class="px-10 py-7 border-t border-slate-100 flex justify-end gap-5 bg-white/95 sticky bottom-0 z-20">
            <button type="button"
                    @click="openPemberitahuan = false; statusInfo = ''"
                    class="px-8 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">
                Batal
            </button>

            <button type="submit"
                    form="formPemberitahuan"
                    :disabled="statusInfo === ''"
                    :class="statusInfo === '' ? 'bg-slate-300 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 shadow-xl shadow-blue-100'"
                    class="px-14 py-4 text-white text-[10px] font-black uppercase rounded-2xl transition-all">
                Simpan & Kirim
            </button>
        </div>
    </div>
</div>

        {{-- MODAL TIDAK LENGKAP --}}
        <div x-show="openTidakLengkap"
             class="fixed inset-0 z-[999999] flex items-center justify-center p-6 bg-slate-900/90 backdrop-blur-sm"
             x-cloak
             x-transition
             style="display:none;">
            <div @click.stop class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden flex flex-col relative border border-white/20">
                <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white sticky top-0 z-10">
                    <h3 class="text-sm font-black text-red-600 uppercase tracking-[0.2em] ml-4">
                        Tindak Lanjut Permohonan Informasi
                    </h3>

                    <button type="button"
                            @click="openTidakLengkap = false"
                            class="text-slate-400 hover:text-red-500 transition-colors text-3xl px-4">
                        &times;
                    </button>
                </div>

                <div class="p-12 overflow-y-auto bg-white">
                    <form action="{{ route('desa.ppid.permohonan.tidak_lengkap', $permohonan->id) }}"
                          method="POST"
                          id="formTidakLengkap">
                        @csrf

                        <div class="space-y-6">
                            <p class="text-sm font-bold text-slate-600 leading-relaxed">
                                Permohonan tidak dapat diproses karena informasi tidak lengkap dengan rincian berikut:
                            </p>

                            <textarea name="rincian_ketidaklengkapan"
                                      rows="6"
                                      required
                                      class="w-full border-2 border-slate-100 rounded-[2rem] px-8 py-6 text-sm font-bold text-slate-700 outline-none focus:border-red-500 bg-slate-50 transition-all shadow-inner"
                                      placeholder="Contoh: Lampiran KTP kurang jelas, rincian informasi terlalu luas, atau tujuan penggunaan belum spesifik..."></textarea>
                        </div>
                    </form>
                </div>

                <div class="p-10 border-t border-slate-100 flex justify-end gap-4 bg-slate-50/30">
                    <button type="button"
                            @click="openTidakLengkap = false"
                            class="px-8 py-4 text-[11px] font-black uppercase text-slate-400 tracking-[0.2em]">
                        Batal
                    </button>

                    <button type="submit"
                            form="formTidakLengkap"
                            class="min-w-[160px] px-10 py-4 bg-red-600 text-white text-[11px] font-black uppercase rounded-2xl shadow-2xl shadow-red-200 hover:bg-red-700 transition-all">
                        Kirim Sekarang
                    </button>
                </div>
            </div>
        </div>

        {{-- MODAL UPLOAD SELESAI --}}
        <div x-show="openUploadSelesai"
             class="fixed inset-0 z-[999999] flex items-center justify-center p-6 bg-slate-900/90 backdrop-blur-sm"
             x-cloak
             x-transition
             style="display:none;">
            <div @click.stop
                 class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden flex flex-col border border-white/20"
                 style="max-height: 90vh;">
                <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em] ml-4">
                        Upload Bukti Penyelesaian
                    </h3>

                    <button type="button"
                            @click="openUploadSelesai = false"
                            class="text-slate-400 hover:text-red-500 transition-colors text-3xl px-4">
                        &times;
                    </button>
                </div>

                <div class="p-12 overflow-y-auto bg-white flex-1">
                    <form action="{{ route('desa.ppid.permohonan.upload_selesai', $permohonan->id) }}"
                          method="POST"
                          enctype="multipart/form-data"
                          id="formUploadSelesai">
                        @csrf

                        <div class="space-y-10">
                            <div class="space-y-4">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                    Pilih Berkas Bukti
                                </label>

                                <input type="file"
                                       name="file_penyelesaian"
                                       required
                                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            </div>

                            <div class="space-y-4">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                                    Keterangan Tambahan
                                </label>

                                <textarea name="keterangan"
                                          rows="3"
                                          class="w-full border-2 border-slate-100 rounded-2xl px-8 py-6 text-sm font-bold text-slate-700 outline-none focus:border-blue-500 bg-slate-50 shadow-inner"
                                          placeholder="Contoh: Berkas telah dikirim melalui WhatsApp/email..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="p-10 border-t border-slate-100 flex justify-end items-center gap-6 bg-slate-50/30">
                    <button type="button"
                            @click="openUploadSelesai = false"
                            class="px-8 py-4 text-[11px] font-black uppercase text-slate-400 tracking-[0.2em] hover:text-slate-600 transition-colors">
                        Batal
                    </button>

                    <button type="submit"
                            form="formUploadSelesai"
                            class="px-12 py-4 bg-slate-900 text-white text-[11px] font-black uppercase rounded-2xl shadow-xl hover:bg-blue-600 transition-all">
                        Simpan Bukti
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>