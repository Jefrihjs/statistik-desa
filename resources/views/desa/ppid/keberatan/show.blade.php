<x-app-layout>
    <div class="py-10 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto">

            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-800 uppercase italic tracking-widest">
                        Detail Keberatan Informasi
                    </h1>

                    <p class="text-sm text-slate-500 mt-2">
                        Kode Permohonan:
                        <span class="font-black text-rose-600 tracking-widest">
                            {{ strtoupper($keberatan->permohonan->kode_permohonan ?? $keberatan->kode_keberatan ?? '-') }}
                        </span>
                    </p>
                </div>

                <a href="{{ route('desa.ppid.keberatan.index') }}"
                   class="bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-2xl font-bold hover:bg-slate-50 text-sm shadow-sm">
                    ← Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                        <div class="bg-slate-900 px-8 py-5">
                            <span class="text-slate-400 font-black tracking-[0.2em] text-[10px] uppercase">
                                Data Keberatan
                            </span>
                        </div>

                        <div class="p-8 space-y-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                    Alasan Keberatan
                                </label>
                                <div class="font-black text-slate-800 leading-relaxed">
                                    {{ $keberatan->label_alasan }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                    Kasus Posisi / Kronologi
                                </label>
                                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 text-sm text-slate-700 leading-relaxed">
                                    {{ $keberatan->uraian_alasan ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                    Status
                                </label>
                                <span class="inline-flex rounded-xl bg-amber-50 px-4 py-2 text-xs font-black uppercase text-amber-700">
                                    {{ $keberatan->status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">
                            Permohonan Terkait
                        </h3>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                    Nomor Permohonan
                                </label>
                                <div class="font-black text-blue-600">
                                    {{ $keberatan->permohonan->nomor_pendaftaran ?? '-' }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                    Kode Monitoring
                                </label>
                                <div class="font-black text-slate-800 tracking-widest">
                                    {{ strtoupper($keberatan->permohonan->kode_permohonan ?? '-') }}
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                    Nama Pemohon
                                </label>
                                <div class="font-bold text-slate-700">
                                    {{ $keberatan->permohonan->nama ?? '-' }}
                                </div>
                            </div>

                            <a href="{{ route('desa.ppid.permohonan.show', $keberatan->permohonan->id) }}"
                               class="inline-flex w-full justify-center rounded-2xl bg-slate-900 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-blue-600">
                                Buka Permohonan
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>