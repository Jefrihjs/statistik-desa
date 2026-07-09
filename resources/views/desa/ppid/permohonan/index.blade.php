<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-10 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto">

            {{-- HEADER --}}
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
                            PERMOHONAN INFORMASI
                        </h1>

                        <p style="font-size:12px; margin-top:12px; opacity:.9; max-width:720px;">
                            Daftar permohonan informasi publik yang masuk dari masyarakat melalui form PPID desa.
                        </p>
                    </div>

                    <span style="font-size:24px; background:rgba(255,255,255,0.2); padding:10px; border-radius:1rem;">
                        💬
                    </span>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- CARD STATISTIK --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

                <div class="bg-white p-7 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col items-center text-center">
                    <div class="relative mb-4">
                        <svg class="w-10 h-10 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="absolute -top-2 -right-2 bg-emerald-500 text-white text-[10px] font-black w-6 h-6 flex items-center justify-center rounded-full border-2 border-white">
                            {{ $stats['pending'] ?? 0 }}
                        </span>
                    </div>

                    <h3 class="text-3xl font-black text-emerald-500">
                        {{ $stats['pending'] ?? 0 }}
                    </h3>

                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">
                        Permohonan Masuk
                    </p>
                </div>

                <div class="bg-white p-7 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col items-center text-center">
                    <div class="mb-4 text-slate-800">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5S19.832 5.477 21 6.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>

                    <h3 class="text-3xl font-black text-blue-500">
                        {{ $stats['diproses'] ?? 0 }}
                    </h3>

                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">
                        Sedang Diproses
                    </p>
                </div>

                <div class="bg-white p-7 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col items-center text-center">
                    <div class="mb-4 text-slate-800">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 14l2 2 4-4m5 2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2h10l5-5z"/>
                        </svg>
                    </div>

                    <h3 class="text-3xl font-black text-indigo-500">
                        {{ $stats['selesai'] ?? 0 }}
                    </h3>

                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">
                        Terselesaikan
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM KIRI --}}
                <div class="space-y-4">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">
                        Permohonan Informasi Baru
                    </h3>

                    @forelse($permohonanBaru as $item)
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:border-blue-300 transition-all group relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>

                            <div class="flex justify-between items-start mb-3">
                                <a href="{{ route('desa.ppid.permohonan.show', $item->id) }}"
                                class="font-bold text-blue-600">
                                    {{ $item->nomor_pendaftaran }}
                                </a>

                                <span class="text-[9px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">
                                    {{ $item->created_at->translatedFormat('d M Y') }}
                                </span>
                            </div>

                            <p class="text-xs font-bold text-slate-800 mb-2 line-clamp-2 leading-relaxed">
                                {{ $item->nama }}
                            </p>

                            <p class="text-[10px] text-slate-500 italic line-clamp-2 mb-4">
                                "{{ $item->rincian_informasi }}"
                            </p>

                            <div class="flex justify-between items-center pt-4 border-t border-slate-50">
                                <span class="text-[9px] font-black text-blue-500 uppercase tracking-widest bg-blue-50 px-3 py-1.5 rounded-full">
                                    {{ $item->status }}
                                </span>

                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Baru
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="bg-slate-50/50 p-12 rounded-[2.5rem] border border-dashed border-slate-200 text-center">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>

                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
                                Tidak ada permohonan baru
                            </p>
                        </div>
                    @endforelse
                </div>

                {{-- KOLOM KANAN --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
                        <h3 class="text-lg font-black text-slate-800 mb-6">
                            Semua Permohonan Informasi
                        </h3>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border border-slate-100">
                                <thead class="bg-slate-800 text-white uppercase tracking-wider">
                                    <tr>
                                        <th class="px-4 py-3 border border-slate-700 text-center w-10">#</th>
                                        <th class="px-4 py-3 border border-slate-700">Nomor</th>
                                        <th class="px-4 py-3 border border-slate-700">Pemohon</th>
                                        <th class="px-4 py-3 border border-slate-700">Rincian</th>
                                        <th class="px-4 py-3 border border-slate-700 text-center">Status</th>
                                        <th class="px-4 py-3 border border-slate-700 text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-slate-100">
                                    @forelse($permohonans as $index => $p)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-4 py-3 border border-slate-100 text-center font-bold">
                                                {{ $permohonans->firstItem() + $index }}
                                            </td>

                                            <td class="px-4 py-3 border border-slate-100">
                                                <a href="{{ route('desa.ppid.permohonan.show', $p->id) }}"
                                                class="font-bold text-blue-600">
                                                    {{ $p->nomor_pendaftaran }}
                                                </a>
                                                <div class="text-[10px] text-slate-400 mt-1">
                                                    {{ $p->created_at->translatedFormat('d M Y') }}
                                                </div>
                                            </td>

                                            <td class="px-4 py-3 border border-slate-100">
                                                <div class="font-bold text-slate-700">
                                                    {{ $p->nama }}
                                                </div>
                                                <div class="text-[10px] text-slate-400 uppercase mt-1">
                                                    {{ $p->kategori_pemohon }}
                                                </div>
                                            </td>

                                            <td class="px-4 py-3 border border-slate-100 text-slate-600">
                                                <div class="line-clamp-2">
                                                    {{ $p->rincian_informasi }}
                                                </div>
                                            </td>

                                            <td class="px-4 py-3 border border-slate-100 text-center">
                                                @php
                                                    $statusClass = match($p->status) {
                                                        'selesai' => 'bg-emerald-50 text-emerald-700',
                                                        'diproses' => 'bg-blue-50 text-blue-700',
                                                        'ditolak' => 'bg-rose-50 text-rose-700',
                                                        default => 'bg-slate-100 text-slate-600',
                                                    };
                                                @endphp

                                                <span class="inline-flex rounded-lg px-3 py-1 text-[10px] font-black uppercase {{ $statusClass }}">
                                                    {{ $p->status }}
                                                </span>
                                            </td>

                                            <td class="px-4 py-3 border border-slate-100 text-center">
                                                <form action="{{ route('desa.ppid.permohonan.destroy', $p->id) }}"
                                                      method="POST"
                                                      id="delete-form-{{ $p->id }}"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button"
                                                            onclick="confirmDelete('{{ $p->id }}')"
                                                            class="p-1.5 bg-red-50 text-red-600 rounded shadow-sm hover:bg-red-600 hover:text-white transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-10 text-center text-slate-400 font-bold italic">
                                                Belum ada permohonan informasi.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($permohonans->count())
                            <div class="mt-4 flex flex-col md:flex-row md:justify-between md:items-center gap-3 text-xs text-slate-500 italic">
                                <p>
                                    Showing {{ $permohonans->firstItem() }} to {{ $permohonans->lastItem() }} of {{ $permohonans->total() }} entries
                                </p>

                                <div>
                                    {{ $permohonans->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data permohonan akan dihapus permanen dan tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                customClass: {
                    title: 'font-black tracking-tighter uppercase italic text-slate-800',
                    popup: 'rounded-[2rem] border border-slate-100 p-6',
                    confirmButton: 'rounded-xl font-black text-xs uppercase tracking-wider px-4 py-2.5',
                    cancelButton: 'rounded-xl font-black text-xs uppercase tracking-wider px-4 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>