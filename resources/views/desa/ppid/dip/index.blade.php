<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <div class="py-10 px-4 bg-slate-50 min-h-screen" x-data="ppidDipData()">
        <div class="max-w-6xl mx-auto">

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8 mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-indigo-600 mb-2">
                            PPID Desa
                        </p>

                        <h1 class="text-2xl md:text-3xl font-black text-[#1e3a8a] uppercase italic tracking-tight">
                            Daftar Informasi Publik
                        </h1>

                        <p class="mt-3 text-sm text-slate-500 max-w-2xl">
                            Kelola informasi berkala, serta merta, setiap saat, dan informasi dikecualikan.
                        </p>
                    </div>

                    <a href="{{ route('desa.ppid.index') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-slate-100 px-4 py-2 text-xs font-black uppercase tracking-widest text-slate-700 hover:bg-slate-200">
                        ← Kembali
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-6 mb-8">
                <h2 class="text-sm font-black uppercase italic tracking-widest text-slate-800 mb-5">
                    Tambah Data DIP
                </h2>

                <form action="{{ route('desa.ppid.dip.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf

                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Kategori Informasi
                        </label>
                        <select name="kategori" required class="w-full border-slate-300 rounded-xl text-sm">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoriList as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Kelompok Informasi
                        </label>
                        <input type="text"
                            name="kelompok_informasi"
                            placeholder="Contoh: Informasi Profil dan Badan Publik"
                            class="w-full border-slate-300 rounded-xl text-sm">
                        <small class="text-[10px] text-slate-400 italic mt-1 block">
                            Opsional. Isi jika informasi ini masuk dalam kelompok tertentu.
                        </small>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Urutan
                        </label>
                        <input type="number" name="urutan" placeholder="Contoh: 10, 20, 30"
                               class="w-full border-slate-300 rounded-xl text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Judul Informasi
                        </label>
                        <input type="text" name="judul_informasi" required
                               placeholder="Contoh: APBDes Tahun 2026"
                               class="w-full border-slate-300 rounded-xl text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Ringkasan
                        </label>
                        <textarea name="ringkasan" rows="3"
                                  placeholder="Ringkasan singkat informasi..."
                                  class="w-full border-slate-300 rounded-xl text-sm"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Link Dokumen
                        </label>
                        <input type="url" name="link_dokumen"
                               placeholder="https://drive.google.com/..."
                               class="w-full border-slate-300 rounded-xl text-sm">
                    </div>

                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit"
                                class="rounded-xl bg-indigo-600 px-6 py-3 text-xs font-black uppercase tracking-widest text-white hover:bg-indigo-700">
                            Simpan Data DIP
                        </button>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                @foreach($kategoriList as $key => $label)
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                            <h3 class="text-sm font-black uppercase italic tracking-widest text-[#1e3a8a]">
                                {{ $label }}
                            </h3>
                        </div>

                        <div class="p-6 space-y-3">
                            @php $lastKelompok = null; @endphp
                            @forelse($data[$key] ?? [] as $item)
                            @if(!empty($item->kelompok_informasi) && $lastKelompok !== $item->kelompok_informasi)
                                <div style="background:#3446a4; color:#ffffff; padding:12px 16px; border-radius:12px; margin-top:18px; margin-bottom:10px;">
                                    <h4 style="margin:0; font-size:14px; font-weight:900; text-transform:uppercase; letter-spacing:.08em; color:#ffffff;">
                                        {{ $item->kelompok_informasi }}
                                    </h4>
                                </div>

                                @php $lastKelompok = $item->kelompok_informasi; @endphp
                            @endif
                                <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="rounded-lg bg-indigo-50 px-2 py-1 text-[10px] font-black text-indigo-700">
                                                    {{ $item->urutan ?? '-' }}
                                                </span>

                                                @if($item->is_active)
                                                    <span class="rounded-lg bg-emerald-50 px-2 py-1 text-[10px] font-black text-emerald-700">
                                                        Tampil
                                                    </span>
                                                @else
                                                    <span class="rounded-lg bg-slate-100 px-2 py-1 text-[10px] font-black text-slate-500">
                                                        Nonaktif
                                                    </span>
                                                @endif
                                            </div>

                                            <h4 class="text-sm font-black text-slate-800">
                                                {{ $item->judul_informasi }}
                                            </h4>

                                            @if($item->ringkasan)
                                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                                                    {{ $item->ringkasan }}
                                                </p>
                                            @endif

                                            @if($item->link_dokumen)
                                                <a href="{{ $item->link_dokumen }}" target="_blank"
                                                   class="mt-3 inline-flex text-xs font-bold text-indigo-600 hover:text-indigo-700">
                                                    Lihat Dokumen →
                                                </a>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <button type="button"
                                                    @click="openEdit({{ json_encode($item) }})"
                                                    class="rounded-xl bg-blue-50 px-3 py-2 text-xs font-black text-blue-600 hover:bg-blue-100">
                                                Edit
                                            </button>

                                            <form id="delete-dip-form-{{ $item->id }}"
                                                action="{{ route('desa.ppid.dip.destroy', $item->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="button"
                                                        onclick="konfirmasiHapusDip('{{ $item->id }}')"
                                                        class="rounded-xl bg-rose-50 px-3 py-2 text-xs font-black text-rose-600 hover:bg-rose-100">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic font-bold">
                                    Belum ada data untuk kategori ini.
                                </p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
        {{-- MODAL EDIT DIP --}}
<div x-show="modalEditOpen"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display:none;">

    <div class="flex min-h-screen items-center justify-center px-4 py-10">
        <div x-show="modalEditOpen"
             x-transition.opacity
             class="fixed inset-0 bg-slate-900/50"
             @click="modalEditOpen = false"></div>

        <div x-show="modalEditOpen"
             x-transition
             class="relative z-10 w-full max-w-2xl rounded-[2rem] bg-white shadow-xl border border-slate-200 overflow-hidden">

            <form :action="'{{ url('desa/ppid/dip') }}/' + editId" method="POST">
                @csrf
                @method('PUT')

                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-lg font-black text-[#1e3a8a] uppercase italic tracking-widest">
                        Edit Data DIP
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">
                        Perbarui kategori, urutan, judul informasi, ringkasan, dan tautan dokumen.
                    </p>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Kategori Informasi
                        </label>
                        <select name="kategori"
                                x-model="editKategori"
                                required
                                class="w-full border-slate-300 rounded-xl text-sm">
                            @foreach($kategoriList as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Kelompok Informasi
                        </label>
                        <input type="text"
                            name="kelompok_informasi"
                            x-model="editKelompok"
                            placeholder="Contoh: Informasi Profil dan Badan Publik"
                            class="w-full border-slate-300 rounded-xl text-sm">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Urutan
                        </label>
                        <input type="number"
                               name="urutan"
                               x-model="editUrutan"
                               class="w-full border-slate-300 rounded-xl text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Judul Informasi
                        </label>
                        <input type="text"
                               name="judul_informasi"
                               x-model="editJudul"
                               required
                               class="w-full border-slate-300 rounded-xl text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Ringkasan
                        </label>
                        <textarea name="ringkasan"
                                  x-model="editRingkasan"
                                  rows="3"
                                  class="w-full border-slate-300 rounded-xl text-sm"></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                            Link Dokumen
                        </label>
                        <input type="url"
                               name="link_dokumen"
                               x-model="editLink"
                               class="w-full border-slate-300 rounded-xl text-sm">
                    </div>

                    <div class="md:col-span-2">
                        <label class="inline-flex items-center gap-2 text-xs font-bold text-slate-700">
                            <input type="checkbox"
                                   name="is_active"
                                   value="1"
                                   x-model="editIsActive"
                                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            Tampilkan di halaman publik/embed
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-2">
                    <button type="button"
                            @click="modalEditOpen = false"
                            class="rounded-xl border border-slate-300 bg-white px-5 py-2 text-xs font-black uppercase tracking-widest text-slate-600 hover:bg-slate-100">
                        Batal
                    </button>

                    <button type="submit"
                            class="rounded-xl bg-blue-600 px-5 py-2 text-xs font-black uppercase tracking-widest text-white hover:bg-blue-700">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
    </div>
    <script>
    function ppidDipData() {
        return {
            modalEditOpen: false,

            editId: '',
            editKategori: '',
            editKelompok: '',
            editUrutan: '',
            editJudul: '',
            editRingkasan: '',
            editLink: '',
            editIsActive: true,

            openEdit(item) {
                this.editId = item.id;
                this.editKategori = item.kategori || '';
                this.editKelompok = item.kelompok_informasi || '';
                this.editUrutan = item.urutan || '';
                this.editJudul = item.judul_informasi || '';
                this.editRingkasan = item.ringkasan || '';
                this.editLink = item.link_dokumen || '';
                this.editIsActive = item.is_active ? true : false;

                this.modalEditOpen = true;
            }
        }
    }
</script>
<script>
    function konfirmasiHapusDip(id) {
        Swal.fire({
            title: 'HAPUS DATA DIP?',
            text: 'Data informasi publik ini akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'YA, HAPUS!',
            cancelButtonText: 'BATAL',
            background: '#ffffff',
            customClass: {
                title: 'font-black tracking-tighter uppercase italic text-slate-800',
                popup: 'rounded-[2rem] border border-slate-100 p-6',
                confirmButton: 'rounded-xl font-black text-xs uppercase tracking-wider px-4 py-2.5',
                cancelButton: 'rounded-xl font-black text-xs uppercase tracking-wider px-4 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-dip-form-' + id).submit();
            }
        });
    }
</script>
</x-app-layout>