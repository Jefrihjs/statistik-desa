<x-app-layout>
    <div class="py-10 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto">

            @php
                $desaAktif = $desa ?? auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);
                $headerColor = $desaAktif->header_color ?? '#2563eb';
                $accentColor = $desaAktif->accent_color ?? '#0f766e';
            @endphp

            <div class="mb-4">
                <a href="{{ route('desa.dashboard') }}"
                   class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Hub Utama TARSIUS
                </a>
            </div>

            {{-- HEADER --}}
            <div style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }}); border-radius: 2.5rem; padding: 35px; color: white; margin-bottom: 2rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative; overflow: hidden;">
                <div style="position: absolute; right: -50px; top: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:10; gap:20px;">
                    <div>
                        <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.2em; margin-bottom:2px; opacity:0.9;">
                            TARSIUS &bull; Konfigurasi
                        </p>
                        <h1 style="font-size:26px; font-weight:900; text-transform:uppercase; font-style:italic; line-height:1;">
                            PENGATURAN DESA
                        </h1>
                        <p style="font-size:12px; margin-top:12px; opacity:.9; max-width:720px;">
                            Identitas desa, pejabat penandatangan, dan tampilan halaman publik — diatur sekali, dipakai di semua dokumen.
                        </p>
                    </div>
                    <span style="font-size:24px; background:rgba(255,255,255,0.2); padding:10px; border-radius:1rem;">⚙️</span>
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

            <form action="{{ route('desa.pengaturan.update') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                {{-- ============================================================ --}}
                {{-- SECTION 1: IDENTITAS DESA --}}
                {{-- ============================================================ --}}
                <div class="rounded-[2rem] border border-slate-100 bg-white shadow-sm overflow-hidden mb-8">

                    <div class="bg-slate-900 px-8 py-5 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-xs font-black text-white">1</span>
                        <div>
                            <span class="text-slate-400 font-black tracking-[0.2em] text-[10px] uppercase">Section 01</span>
                            <p class="text-white font-black text-sm uppercase">Identitas Desa</p>
                        </div>
                        <span class="ml-auto text-[9px] font-bold text-slate-500 bg-slate-800 px-3 py-1 rounded-full">Kop Surat · Header Publik</span>
                    </div>

                    <div class="p-8 space-y-8">

                        {{-- Nama Desa (readonly) --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Nama Desa</label>
                            <input type="text"
                                   value="{{ $desaAktif->nama_desa ?? $desaAktif->nama ?? '-' }}"
                                   disabled
                                   class="w-full rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4 text-sm font-black text-slate-500">
                        </div>

                        {{-- Logo Desa --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Logo Desa</label>
                            <div class="grid grid-cols-1 md:grid-cols-[160px_1fr] gap-6 items-center">
                                <div class="w-36 h-36 rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 flex items-center justify-center overflow-hidden">
                                    @if($desaAktif->logo_desa)
                                        <img src="{{ asset('storage/' . $desaAktif->logo_desa) }}" class="w-full h-full object-contain p-4" alt="Logo">
                                    @elseif($desaAktif->logo)
                                        <img src="{{ asset('storage/' . $desaAktif->logo) }}" class="w-full h-full object-contain p-4" alt="Logo">
                                    @else
                                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest text-center px-4">Belum Ada Logo</span>
                                    @endif
                                </div>
                                <div>
                                    <input type="file" name="logo_desa" accept="image/png,image/jpeg,image/jpg"
                                           class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-5 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-3 text-xs text-slate-400 italic">Format JPG/PNG, maks 2MB. Dipakai di kop surat SKM, PPID, dan header halaman publik.</p>
                                </div>
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Alamat Kantor</label>
                            <textarea name="alamat_kantor" rows="2"
                                      class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                      placeholder="Jl. Raya Desa ..., Kecamatan ..., Kabupaten Belitung Timur">{{ old('alamat_kantor', $desaAktif->alamat_kantor) }}</textarea>
                        </div>

                        {{-- Kontak: Email, Website, Telepon --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Email Desa</label>
                                <input type="email" name="email_desa"
                                       value="{{ old('email_desa', $desaAktif->email_desa) }}"
                                       class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                       placeholder="desa@email.go.id">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Website Desa</label>
                                <input type="text" name="website_desa"
                                       value="{{ old('website_desa', $desaAktif->website_desa) }}"
                                       class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                       placeholder="https://desa.go.id">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Telepon Desa</label>
                                <input type="text" name="telepon_desa"
                                       value="{{ old('telepon_desa', $desaAktif->telepon_desa) }}"
                                       class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                       placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- SECTION 2: PEJABAT & PENANDATANGAN --}}
                {{-- ============================================================ --}}
                <div class="rounded-[2rem] border border-slate-100 bg-white shadow-sm overflow-hidden mb-8">

                    <div class="bg-slate-900 px-8 py-5 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 flex items-center justify-center text-xs font-black text-white">2</span>
                        <div>
                            <span class="text-slate-400 font-black tracking-[0.2em] text-[10px] uppercase">Section 02</span>
                            <p class="text-white font-black text-sm uppercase">Pejabat & Penandatangan</p>
                        </div>
                        <span class="ml-auto text-[9px] font-bold text-slate-500 bg-slate-800 px-3 py-1 rounded-full">Tanda Tangan Dokumen</span>
                    </div>

                    <div class="p-8 space-y-8">

                        {{-- Info box --}}
                        <div class="rounded-2xl bg-blue-50 border border-blue-100 px-5 py-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <p class="text-xs font-black text-blue-700">Data Kepala Desa adalah default untuk semua dokumen</p>
                                <p class="text-[11px] text-blue-500 mt-0.5">Jika PPID dijabat oleh orang lain, isi bagian PPID di bawah. Jika sama dengan Kepala Desa, biarkan kosong.</p>
                            </div>
                        </div>

                        {{-- Kepala Desa --}}
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-900 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-slate-900"></span>
                                Kepala Desa (Default Penandatangan)
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Nama Kepala Desa</label>
                                    <input type="text" name="nama_kepala_desa"
                                           value="{{ old('nama_kepala_desa', $desaAktif->nama_kepala_desa) }}"
                                           class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                           placeholder="Nama lengkap">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">NIP</label>
                                    <input type="text" name="nip_kepala"
                                           value="{{ old('nip_kepala', $desaAktif->nip_kepala) }}"
                                           class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                           placeholder="Kosongkan jika tidak ada">
                                </div>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="border-t border-dashed border-slate-200"></div>

                        {{-- PPID --}}
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-700 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                Pejabat PPID (Opsional — isi hanya jika berbeda dengan Kepala Desa)
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Nama PPID</label>
                                    <input type="text" name="nama_ppid"
                                           value="{{ old('nama_ppid', $desaAktif->nama_ppid) }}"
                                           class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                           placeholder="Biarkan kosong jika sama">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Jabatan PPID</label>
                                    <input type="text" name="jabatan_ppid"
                                           value="{{ old('jabatan_ppid', $desaAktif->jabatan_ppid) }}"
                                           class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                           placeholder="Kepala Desa">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">NIP PPID</label>
                                    <input type="text" name="nip_ppid"
                                           value="{{ old('nip_ppid', $desaAktif->nip_ppid) }}"
                                           class="w-full rounded-2xl border border-slate-200 px-5 py-4 text-sm font-bold text-slate-700 outline-none focus:border-blue-500"
                                           placeholder="Biarkan kosong jika sama">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- SECTION 3: TAMPILAN PUBLIK --}}
                {{-- ============================================================ --}}
                <div class="rounded-[2rem] border border-slate-100 bg-white shadow-sm overflow-hidden mb-8">

                    <div class="bg-slate-900 px-8 py-5 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-xl bg-amber-500 flex items-center justify-center text-xs font-black text-white">3</span>
                        <div>
                            <span class="text-slate-400 font-black tracking-[0.2em] text-[10px] uppercase">Section 03</span>
                            <p class="text-white font-black text-sm uppercase">Tampilan Publik</p>
                        </div>
                        <span class="ml-auto text-[9px] font-bold text-slate-500 bg-slate-800 px-3 py-1 rounded-full">Halaman Depan Desa</span>
                    </div>

                    <div class="p-8 space-y-8">

                        {{-- Layout & Kategori --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3">Template Tampilan</label>
                                <select name="layout_type"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold focus:ring-blue-600 outline-none focus:border-blue-500">
                                    <option value="default" {{ old('layout_type', $desaAktif->layout_type ?? 'default') == 'default' ? 'selected' : '' }}>
                                        Standard (Rapi & Formal)
                                    </option>
                                    <option value="infographic" {{ old('layout_type', $desaAktif->layout_type ?? '') == 'infographic' ? 'selected' : '' }}>
                                        Infografis (Modern & Visual)
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3">Fokus Data Unggulan</label>
                                <select name="featured_category_id"
                                        class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold focus:ring-blue-600 outline-none focus:border-blue-500">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('featured_category_id', $desaAktif->featured_category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Pesan Kepala Desa --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 tracking-widest mb-3">Pesan Kepala Desa</label>
                            <textarea name="welcome_message" rows="5"
                                      class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold focus:ring-blue-600 outline-none focus:border-blue-500"
                                      placeholder="Contoh: Peningkatan statistik ekonomi tahun ini adalah hasil kerja keras seluruh warga...">{{ old('welcome_message', $desaAktif->welcome_message ?? '') }}</textarea>
                            <p class="text-[11px] text-slate-400 mt-2 italic">
                                Narasi ini tampil di halaman publik desa. Nama penandatangan otomatis dari Section 02.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- TOMBOL AKSI --}}
                {{-- ============================================================ --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('desa.dashboard') }}"
                       class="rounded-2xl bg-white border border-slate-200 px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-500 hover:bg-slate-100">
                        Batal
                    </a>
                    <button type="submit"
                            class="rounded-2xl bg-blue-600 px-8 py-3 text-xs font-black uppercase tracking-widest text-white shadow-lg shadow-blue-100 hover:bg-blue-700">
                        Simpan Semua Pengaturan
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>