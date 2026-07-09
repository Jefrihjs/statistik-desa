<x-app-layout>
    <div class="py-10 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto">

            @php
                $desaAktif = $desa ?? auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);

                $headerColor = $desaAktif->header_color ?? '#2563eb';
                $accentColor = $desaAktif->accent_color ?? '#0f766e';
            @endphp

            <div class="mb-4">
                <a href="{{ route('desa.ppid.index') }}"
                class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke PPID Desa
                </a>
            </div>

            <div style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }}); border-radius: 2.5rem; padding: 35px; color: white; margin-bottom: 2rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative; overflow: hidden;">
                <div style="position: absolute; right: -50px; top: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>

                <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:10; gap:20px;">
                    <div>
                        <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.2em; margin-bottom:2px; opacity:0.9;">
                            TARSIUS &bull; Layanan Informasi Publik
                        </p>

                        <h1 style="font-size:26px; font-weight:900; text-transform:uppercase; font-style:italic; line-height:1;">
                            PENGATURAN PPID DESA
                        </h1>

                        <p style="font-size:12px; margin-top:12px; opacity:.9; max-width:720px;">
                            Atur data kop surat, logo, alamat, kontak, dan pejabat PPID untuk dokumen PDF desa.
                        </p>
                    </div>

                    <span style="font-size:24px; background:rgba(255,255,255,0.2); padding:10px; border-radius:1rem;">
                        ⚙️
                    </span>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('desa.ppid.pengaturan.update') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                @csrf

                <div class="bg-slate-900 px-8 py-5">
                    <span class="text-slate-400 font-black tracking-[0.2em] text-[10px] uppercase">
                        Data Kop Surat PPID Desa
                    </span>
                </div>

                <div class="p-8 space-y-8">

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                            Nama Desa
                        </label>

                        <input type="text"
                               value="{{ $desa->nama_desa ?? $desa->nama ?? '-' }}"
                               disabled
                               class="w-full rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4 text-sm font-black text-slate-500">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                            Alamat Kantor
                        </label>

                        <textarea name="alamat_kantor"
                                  rows="3"
                                  class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                  placeholder="Contoh: Jl. Raya Desa ..., Kecamatan ..., Kabupaten Belitung Timur">{{ old('alamat_kantor', $desa->alamat_kantor) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                Email Desa
                            </label>

                            <input type="email"
                                   name="email_desa"
                                   value="{{ old('email_desa', $desa->email_desa) }}"
                                   class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                   placeholder="desa@email.go.id">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                Website Desa
                            </label>

                            <input type="text"
                                   name="website_desa"
                                   value="{{ old('website_desa', $desa->website_desa) }}"
                                   class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                   placeholder="https://desa.go.id">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                Telepon Desa
                            </label>

                            <input type="text"
                                   name="telepon_desa"
                                   value="{{ old('telepon_desa', $desa->telepon_desa) }}"
                                   class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                   placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                Nama PPID / Kades
                            </label>

                            <input type="text"
                                   name="nama_ppid"
                                   value="{{ old('nama_ppid', $desa->nama_ppid) }}"
                                   class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                   placeholder="Nama pejabat">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                Jabatan PPID
                            </label>

                            <input type="text"
                                   name="jabatan_ppid"
                                   value="{{ old('jabatan_ppid', $desa->jabatan_ppid) }}"
                                   class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                   placeholder="Kepala Desa / Atasan PPID">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                                NIP
                            </label>

                            <input type="text"
                                   name="nip_ppid"
                                   value="{{ old('nip_ppid', $desa->nip_ppid) }}"
                                   class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                   placeholder="Kosongkan jika tidak ada">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                            Logo Desa
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] gap-6 items-center">
                            <div class="w-36 h-36 rounded-3xl border border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden">
                                @if($desa->logo_desa)
                                    <img src="{{ asset('storage/' . $desa->logo_desa) }}"
                                         class="w-full h-full object-contain p-4"
                                         alt="Logo Desa">
                                @else
                                    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest text-center px-4">
                                        Belum Ada Logo
                                    </span>
                                @endif
                            </div>

                            <div>
                                <input type="file"
                                       name="logo_desa"
                                       accept="image/png,image/jpeg,image/jpg"
                                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

                                <p class="mt-3 text-xs text-slate-400 italic">
                                    Format JPG/PNG, maksimal 2MB. Logo ini akan dipakai pada kop surat PDF PPID Desa.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('desa.ppid.index') }}"
                       class="rounded-2xl bg-white border border-slate-200 px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:bg-slate-100">
                        Batal
                    </a>

                    <button type="submit"
                            class="rounded-2xl bg-blue-600 px-8 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-blue-100 hover:bg-blue-700">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>