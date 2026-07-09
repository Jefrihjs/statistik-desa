<x-app-layout>
    @php
        $desaAktif = $desa ?? auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);

        $headerColor = $desaAktif->header_color ?? '#2563eb';
        $accentColor = $desaAktif->accent_color ?? '#0f766e';
    @endphp

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

            <div class="mb-4">
                <a href="{{ route('desa.ppid.index') }}"
                   class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    ← Kembali ke PPID Desa
                </a>
            </div>

            <div style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }}); border-radius: 2.5rem; padding: 35px; color: white; margin-bottom: 2rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative; overflow: hidden;">
                <div style="display:flex; justify-content:space-between; align-items:center; position:relative; z-index:10;">
                    <div>
                        <p style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.2em; margin-bottom:2px; opacity:0.9;">
                            TARSIUS &bull; Laporan Keterbukaan Informasi Publik
                        </p>

                        <h1 style="font-size:26px; font-weight:900; text-transform:uppercase; font-style:italic; line-height:1;">
                            LAPORAN PPID DESA
                        </h1>

                        <p style="font-size:12px; margin-top:12px; opacity:.9; max-width:720px;">
                            Cetak register permohonan informasi dan register keberatan berdasarkan tahun laporan.
                        </p>
                    </div>

                    <span style="font-size:24px; background:rgba(255,255,255,0.2); padding:10px; border-radius:1rem;">
                        📑
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm p-8 mb-8">
                <form method="GET" action="{{ route('desa.ppid.laporan.index') }}"
                      class="grid grid-cols-1 md:grid-cols-[1fr_1fr_auto] gap-5 items-end">

                    <input type="hidden" name="tab" value="{{ $tab }}">

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Tahun Laporan
                        </label>

                        <select name="tahun"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold">
                            <option value="semua" {{ $tahun === 'semua' ? 'selected' : '' }}>
                                Semua Tahun
                            </option>

                            @foreach($tahunList as $thn)
                                <option value="{{ $thn }}" {{ (string) $tahun === (string) $thn ? 'selected' : '' }}>
                                    {{ $thn }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            Jenis Laporan
                        </label>

                        <select name="jenis_cetak"
                                id="jenis_cetak"
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 px-5 py-4 text-sm font-bold">
                            <option value="permohonan" {{ $tab === 'permohonan' ? 'selected' : '' }}>
                                Register Permohonan
                            </option>
                            <option value="keberatan" {{ $tab === 'keberatan' ? 'selected' : '' }}>
                                Register Keberatan
                            </option>
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                                class="rounded-2xl bg-slate-900 px-6 py-4 text-xs font-black uppercase tracking-widest text-white">
                            Tampilkan
                        </button>

                        <a id="btnCetak"
                           href="{{ route('desa.ppid.laporan.cetak', ['tahun' => $tahun, 'jenis' => $tab]) }}"
                           target="_blank"
                           class="rounded-2xl bg-red-600 px-6 py-4 text-xs font-black uppercase tracking-widest text-white">
                            Cetak PDF
                        </a>
                    </div>
                </form>
            </div>

            <div class="flex gap-3 mb-0">
                <a href="{{ route('desa.ppid.laporan.index', ['tahun' => $tahun, 'tab' => 'permohonan']) }}"
                   class="px-8 py-4 rounded-t-2xl text-xs font-black uppercase tracking-widest {{ $tab === 'permohonan' ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 border border-blue-100' }}">
                    Register Permohonan
                </a>

                <a href="{{ route('desa.ppid.laporan.index', ['tahun' => $tahun, 'tab' => 'keberatan']) }}"
                   class="px-8 py-4 rounded-t-2xl text-xs font-black uppercase tracking-widest {{ $tab === 'keberatan' ? 'bg-blue-600 text-white' : 'bg-white text-blue-600 border border-blue-100' }}">
                    Register Keberatan
                </a>
            </div>

            <div class="bg-white rounded-b-[2.5rem] rounded-tr-[2.5rem] border border-slate-200 shadow-sm p-8 overflow-x-auto">

                @if($tab === 'permohonan')
                    <div class="text-center mb-8">
                        <h2 class="text-xl font-black uppercase tracking-widest text-slate-800">
                            Register Permohonan Informasi Publik
                        </h2>
                        <p class="text-xs font-bold text-slate-400 mt-2">
                            {{ $tahun === 'semua' ? 'Semua Tahun' : 'Tahun ' . $tahun }}
                        </p>
                    </div>

                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-900 text-white">
                                <th class="px-4 py-4 text-left">#</th>
                                <th class="px-4 py-4 text-left">Tanggal</th>
                                <th class="px-4 py-4 text-left">Nomor</th>
                                <th class="px-4 py-4 text-left">Nama</th>
                                <th class="px-4 py-4 text-left">Alamat</th>
                                <th class="px-4 py-4 text-left">Kontak</th>
                                <th class="px-4 py-4 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($permohonans as $item)
                                <tr class="border-b border-slate-100 odd:bg-slate-50">
                                    <td class="px-4 py-4 font-bold">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4">{{ $item->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-4 font-black text-blue-600">{{ $item->nomor_pendaftaran }}</td>
                                    <td class="px-4 py-4 font-bold">{{ $item->nama }}</td>
                                    <td class="px-4 py-4">{{ $item->alamat }}</td>
                                    <td class="px-4 py-4">{{ $item->no_hp ?? '-' }}</td>
                                    <td class="px-4 py-4">
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-black uppercase">
                                            {{ str_replace('_', ' ', $item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-xs font-black uppercase tracking-widest text-slate-400">
                                        Data permohonan tidak tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif

                @if($tab === 'keberatan')
                    <div class="text-center mb-8">
                        <h2 class="text-xl font-black uppercase tracking-widest text-slate-800">
                            Register Keberatan Informasi Publik
                        </h2>
                        <p class="text-xs font-bold text-slate-400 mt-2">
                            {{ $tahun === 'semua' ? 'Semua Tahun' : 'Tahun ' . $tahun }}
                        </p>
                    </div>

                    <table class="w-full border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-900 text-white">
                                <th class="px-4 py-4 text-left">#</th>
                                <th class="px-4 py-4 text-left">Tanggal</th>
                                <th class="px-4 py-4 text-left">Kode</th>
                                <th class="px-4 py-4 text-left">Pemohon</th>
                                <th class="px-4 py-4 text-left">Nomor Permohonan</th>
                                <th class="px-4 py-4 text-left">Alasan</th>
                                <th class="px-4 py-4 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($keberatans as $item)
                                <tr class="border-b border-slate-100 odd:bg-slate-50">
                                    <td class="px-4 py-4 font-bold">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4">{{ $item->created_at->translatedFormat('d M Y') }}</td>
                                    <td class="px-4 py-4 font-black text-blue-600">{{ $item->kode_keberatan }}</td>
                                    <td class="px-4 py-4 font-bold">{{ $item->permohonan->nama ?? '-' }}</td>
                                    <td class="px-4 py-4">{{ $item->permohonan->nomor_pendaftaran ?? '-' }}</td>
                                    <td class="px-4 py-4">{{ $item->label_alasan }}</td>
                                    <td class="px-4 py-4">
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[10px] font-black uppercase">
                                            {{ str_replace('_', ' ', $item->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-xs font-black uppercase tracking-widest text-slate-400">
                                        Data keberatan tidak tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>

    <script>
        const tahunSelect = document.querySelector('select[name="tahun"]');
        const jenisSelect = document.getElementById('jenis_cetak');
        const btnCetak = document.getElementById('btnCetak');

        function updateCetakUrl() {
            const tahun = tahunSelect.value;
            const jenis = jenisSelect.value;

            btnCetak.href = "{{ route('desa.ppid.laporan.cetak') }}" + "?tahun=" + tahun + "&jenis=" + jenis;
        }

        tahunSelect.addEventListener('change', updateCetakUrl);
        jenisSelect.addEventListener('change', updateCetakUrl);
    </script>
</x-app-layout>