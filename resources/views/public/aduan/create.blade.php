<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Aduan - {{ $desa->nama_desa ?? 'Desa' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 min-h-screen">
    @php
        $headerColor = $desa->header_color ?? '#475569';
        $accentColor = $desa->accent_color ?? '#064e3b';
    @endphp

    <main class="min-h-screen py-10 px-4">
        <div class="max-w-3xl mx-auto">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] text-white p-8 lg:p-10 mb-8 shadow-sm"
                 style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }});">

                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-white/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-white/10"></div>

                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/80 mb-3">
                        TARSIUS • Layanan Aduan Masyarakat
                    </p>

                    <h1 class="text-3xl font-black uppercase italic tracking-tight">
                        Form Aduan Desa
                    </h1>

                    <p class="mt-3 text-sm text-white/85 leading-relaxed">
                        Sampaikan aduan, laporan, atau masukan kepada Pemerintah Desa {{ $desa->nama_desa ?? '-' }}.
                    </p>
                </div>
            </div>

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="mb-6 rounded-[2rem] border border-red-200 bg-red-50 px-6 py-5 text-sm font-bold text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM --}}
            <div class="rounded-[2.5rem] bg-white border border-slate-200 shadow-sm p-6 lg:p-8">
                <form action="{{ route('public.aduan.store', $desa->slug) }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- IDENTITAS --}}
                    <div x-data="{ jenisIdentitas: '{{ old('jenis_identitas', 'rahasia') }}' }" class="space-y-5">

                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                Jenis Identitas Pelapor
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <label class="cursor-pointer rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:bg-white"
                                       :class="jenisIdentitas === 'terbuka' ? 'ring-2 ring-emerald-500 bg-white' : ''">
                                    <input type="radio"
                                           name="jenis_identitas"
                                           value="terbuka"
                                           x-model="jenisIdentitas"
                                           class="hidden">

                                    <div class="text-sm font-black text-slate-900 uppercase">
                                        Terbuka
                                    </div>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Nama, nomor HP, dan email dapat dicatat sebagai identitas pengadu.
                                    </p>
                                </label>

                                <label class="cursor-pointer rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:bg-white"
                                       :class="jenisIdentitas === 'rahasia' ? 'ring-2 ring-emerald-500 bg-white' : ''">
                                    <input type="radio"
                                           name="jenis_identitas"
                                           value="rahasia"
                                           x-model="jenisIdentitas"
                                           class="hidden">

                                    <div class="text-sm font-black text-slate-900 uppercase">
                                        Rahasia
                                    </div>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Nama tidak diminta, tetapi HP atau email boleh diisi untuk tindak lanjut.
                                    </p>
                                </label>

                                <label class="cursor-pointer rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:bg-white"
                                       :class="jenisIdentitas === 'anonim' ? 'ring-2 ring-emerald-500 bg-white' : ''">
                                    <input type="radio"
                                           name="jenis_identitas"
                                           value="anonim"
                                           x-model="jenisIdentitas"
                                           class="hidden">

                                    <div class="text-sm font-black text-slate-900 uppercase">
                                        Anonim
                                    </div>

                                    <p class="mt-1 text-xs text-slate-500">
                                        Aduan dikirim tanpa nama, nomor HP, dan email.
                                    </p>
                                </label>
                            </div>
                        </div>

                        {{-- NAMA: HANYA TERBUKA --}}
                        <div x-show="jenisIdentitas === 'terbuka'" x-transition>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                Nama Pelapor
                            </label>

                            <input type="text"
                                   name="nama_pelapor"
                                   value="{{ old('nama_pelapor') }}"
                                   :required="jenisIdentitas === 'terbuka'"
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold text-slate-700 focus:ring-emerald-600"
                                   placeholder="Masukkan nama lengkap">
                        </div>

                        {{-- HP & EMAIL: TERBUKA + RAHASIA --}}
                        <div x-show="jenisIdentitas !== 'anonim'" x-transition class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                    Nomor HP
                                </label>

                                <input type="text"
                                       name="no_hp"
                                       value="{{ old('no_hp') }}"
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold text-slate-700 focus:ring-emerald-600"
                                       placeholder="08xxxxxxxxxx">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                    Email
                                </label>

                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold text-slate-700 focus:ring-emerald-600"
                                       placeholder="email@example.com">
                            </div>
                        </div>

                        {{-- INFO RAHASIA --}}
                        <div x-show="jenisIdentitas === 'rahasia'"
                             x-transition
                             class="rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4">
                            <p class="text-sm font-bold text-blue-700">
                                Anda memilih aduan rahasia. Nama pelapor tidak akan diminta. Nomor HP atau email boleh diisi agar operator desa dapat menghubungi Anda bila diperlukan.
                            </p>
                        </div>

                        {{-- INFO ANONIM --}}
                        <div x-show="jenisIdentitas === 'anonim'"
                             x-transition
                             class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4">
                            <p class="text-sm font-bold text-amber-700">
                                Anda memilih aduan anonim. Nama, nomor HP, dan email tidak akan diminta.
                                Pastikan isi aduan ditulis jelas agar dapat ditindaklanjuti.
                            </p>
                        </div>
                    </div>

                    {{-- KATEGORI --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Kategori Aduan
                        </label>

                        <select name="kategori"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold text-slate-700 focus:ring-emerald-600">
                            <option value="">Pilih Kategori</option>
                            <option value="Pelayanan Publik" {{ old('kategori') === 'Pelayanan Publik' ? 'selected' : '' }}>Pelayanan Publik</option>
                            <option value="Infrastruktur" {{ old('kategori') === 'Infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                            <option value="Administrasi" {{ old('kategori') === 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                            <option value="Sosial Kemasyarakatan" {{ old('kategori') === 'Sosial Kemasyarakatan' ? 'selected' : '' }}>Sosial Kemasyarakatan</option>
                            <option value="Lainnya" {{ old('kategori') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    {{-- JUDUL --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Judul Aduan
                        </label>

                        <input type="text"
                               name="judul"
                               value="{{ old('judul') }}"
                               required
                               class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold text-slate-700 focus:ring-emerald-600"
                               placeholder="Contoh: Keluhan pelayanan surat keterangan">
                    </div>

                    {{-- ISI --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Isi Aduan
                        </label>

                        <textarea name="isi_aduan"
                                  rows="6"
                                  required
                                  class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold text-slate-700 focus:ring-emerald-600"
                                  placeholder="Tuliskan aduan secara jelas...">{{ old('isi_aduan') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-emerald-700 shadow-lg shadow-emerald-900/20">
                        Kirim Aduan
                    </button>
                </form>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('public.aduan.check-status', $desa->slug) }}"
                   class="text-xs font-black uppercase tracking-widest text-slate-500 hover:text-emerald-600">
                    Cek Status Aduan
                </a>
            </div>

            <p class="mt-6 text-center text-[10px] font-bold uppercase tracking-widest text-slate-400">
                Pemerintah Desa {{ $desa->nama_desa ?? '-' }}
            </p>
        </div>
    </main>
</body>
</html>