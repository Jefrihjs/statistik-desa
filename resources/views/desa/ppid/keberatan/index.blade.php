<x-app-layout>
    <div class="py-10 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto">

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
                            KEBERATAN INFORMASI
                        </h1>

                        <p style="font-size:12px; margin-top:12px; opacity:.9; max-width:720px;">
                            Daftar keberatan informasi yang diajukan masyarakat melalui halaman monitoring permohonan.
                        </p>
                    </div>

                    <span style="font-size:24px; background:rgba(255,255,255,0.2); padding:10px; border-radius:1rem;">
                        ⚠️
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-7 rounded-[2rem] border border-slate-100 shadow-sm text-center">
                    <div class="text-3xl font-black text-rose-500">{{ $stats['diajukan'] ?? 0 }}</div>
                    <div class="mt-2 text-xs font-black text-slate-400 uppercase tracking-widest">Diajukan</div>
                </div>

                <div class="bg-white p-7 rounded-[2rem] border border-slate-100 shadow-sm text-center">
                    <div class="text-3xl font-black text-blue-500">{{ $stats['diproses'] ?? 0 }}</div>
                    <div class="mt-2 text-xs font-black text-slate-400 uppercase tracking-widest">Diproses</div>
                </div>

                <div class="bg-white p-7 rounded-[2rem] border border-slate-100 shadow-sm text-center">
                    <div class="text-3xl font-black text-emerald-500">{{ $stats['selesai'] ?? 0 }}</div>
                    <div class="mt-2 text-xs font-black text-slate-400 uppercase tracking-widest">Selesai</div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-black text-slate-800 mb-6">
                    Semua Keberatan Informasi
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border border-slate-100">
                        <thead class="bg-slate-800 text-white uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 border border-slate-700 text-center w-10">#</th>
                                <th class="px-4 py-3 border border-slate-700">Kode Permohonan</th>
                                <th class="px-4 py-3 border border-slate-700">Pemohon</th>
                                <th class="px-4 py-3 border border-slate-700">Nomor Permohonan</th>
                                <th class="px-4 py-3 border border-slate-700">Alasan</th>
                                <th class="px-4 py-3 border border-slate-700 text-center">Status</th>
                                <th class="px-4 py-3 border border-slate-700 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($keberatans as $index => $item)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-4 py-3 border border-slate-100 text-center font-bold">
                                        {{ $keberatans->firstItem() + $index }}
                                    </td>

                                    <td class="px-4 py-3 border border-slate-100">
                                        <span class="font-black text-rose-600 tracking-widest">
                                            {{ strtoupper($item->permohonan->kode_permohonan ?? $item->kode_keberatan ?? '-') }}
                                        </span>
                                        <div class="text-[10px] text-slate-400 mt-1">
                                            {{ $item->created_at->translatedFormat('d M Y H:i') }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 border border-slate-100">
                                        <div class="font-bold text-slate-700">
                                            {{ $item->permohonan->nama ?? '-' }}
                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            {{ $item->permohonan->no_hp ?? '-' }}
                                        </div>
                                    </td>

                                    <td class="px-4 py-3 border border-slate-100">
                                        <span class="font-bold text-blue-600">
                                            {{ $item->permohonan->nomor_pendaftaran ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 border border-slate-100 text-slate-600">
                                        {{ $item->label_alasan }}
                                    </td>

                                    <td class="px-4 py-3 border border-slate-100 text-center">
                                        @php
                                            $statusClass = match($item->status) {
                                                'selesai' => 'bg-emerald-50 text-emerald-700',
                                                'diproses' => 'bg-blue-50 text-blue-700',
                                                'ditolak' => 'bg-rose-50 text-rose-700',
                                                default => 'bg-amber-50 text-amber-700',
                                            };
                                        @endphp

                                        <span class="inline-flex rounded-lg px-3 py-1 text-[10px] font-black uppercase {{ $statusClass }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 border border-slate-100 text-center">
                                        @if($item->permohonan)
                                            <a href="{{ route('desa.ppid.permohonan.show', $item->permohonan->id) }}"
                                            class="inline-flex rounded-xl bg-slate-900 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-white hover:bg-blue-600">
                                                Buka Permohonan
                                            </a>
                                        @else
                                            <span class="inline-flex rounded-xl bg-slate-100 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                                Data Hilang
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-slate-400 font-bold italic">
                                        Belum ada keberatan informasi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($keberatans->count())
                    <div class="mt-4">
                        {{ $keberatans->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>