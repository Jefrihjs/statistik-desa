<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase italic tracking-widest text-[15px]">
            {{ __('Input Dokumen Desa Antikorupsi') }}
        </h2>
    </x-slot>

    <!-- Memanggil fungsi JavaScript di bagian bawah -->
    <div class="py-12" x-data="antikorupsiData()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <button @click="modalAddOpen = true" class="bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-6 rounded-xl shadow-lg font-black text-[10px] uppercase tracking-widest italic transition-all hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Indikator
                </button>

                <a href="{{ route('antikorupsi.index') }}" target="_blank" class="bg-[#1e3a8a] hover:bg-blue-800 text-white py-2 px-6 rounded-xl shadow-lg font-black text-[10px] uppercase tracking-widest italic transition-all hover:-translate-y-1">
                    Lihat Halaman Publik
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-xl shadow-sm font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('desa.antikorupsi.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="bg-white shadow-xl rounded-[2rem] p-8 border border-slate-100">
                    
                    @php 
                        $kategoriList = [
                            'tatalaksana' => '1. Tata Laksana', 
                            'pengawasan' => '2. Pengawasan', 
                            'pelayanan' => '3. Pelayanan Publik', 
                            'partisipasi' => '4. Partisipasi Masyarakat', 
                            'kearifan' => '5. Kearifan Lokal'
                        ]; 
                    @endphp

                    @foreach($kategoriList as $keyKategori => $judulKategori)
                    <h3 class="text-lg font-black uppercase italic tracking-widest border-b pb-2 mt-8 mb-4 text-[#1e3a8a]">{{ $judulKategori }}</h3>
                    
                    @forelse($data[$keyKategori] ?? [] as $grup => $items)
                        <div class="mb-6 bg-white p-6 rounded-[2rem] border border-slate-100 shadow-md overflow-hidden">
                            
                            {{-- HEADER GRUP UTAMA --}}
                            <div class="bg-slate-50 border-b border-slate-100 p-4 -mx-6 -mt-6 mb-4">
                                <h4 class="font-black text-slate-800 text-xs uppercase tracking-wider italic flex items-center gap-2">
                                    <span class="w-2 h-4 bg-[#1e3a8a] rounded-sm"></span>
                                    {{ $grup }}
                                </h4>
                            </div>
                            
                            <div class="flex flex-col gap-3">
                                @foreach($items as $item)
                                    {{-- PROTEKSI OBJEK --}}
                                    @if(is_object($item))
                                        
                                        @if(!empty($item->sub_judul) && empty($item->nama_dokumen))
                                            {{-- BARIS PEMBATAS SUB JUDUL --}}
                                            <div class="flex justify-between items-center bg-slate-100/80 p-3 rounded-xl border border-slate-200 mt-2 mb-1">
                                                <span class="font-black text-[#1e3a8a] text-[11px] uppercase tracking-wider">📂 {{ $item->sub_judul }}</span>
                                                <button type="button" onclick="konfirmasiHapusIndikator('{{ $item->id }}')" class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 transition-colors" title="Hapus Sub Judul">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </div>
                                        @else
                                            {{-- BARIS INPUT DOKUMEN NORMAL --}}
                                            <div class="flex flex-col md:flex-row md:items-center gap-4 bg-white p-3 rounded-xl border border-slate-100 shadow-sm transition-all hover:shadow-md">
                                                <div class="md:w-5/12 flex items-start gap-2">
                                                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded-lg font-black text-[10px] tracking-tight mt-0.5 shrink-0">
                                                        {{ $item->no_urut ?? '' }}{{ !empty($item->sub) ? '.'.$item->sub : '' }}
                                                    </span>
                                                    <label class="text-xs text-slate-700 font-bold uppercase tracking-wide leading-relaxed">
                                                        @if(!empty($item->sub_judul))
                                                            <span class="text-blue-600 font-black block text-[9px] mb-0.5">{{ $item->sub_judul }}</span>
                                                        @endif
                                                        {{ $item->nama_dokumen }}
                                                    </label>
                                                </div>
                                                <div class="md:w-5/12 w-full">
                                                    <input type="url" name="links[{{ $item->id }}]" value="{{ $item->link_drive }}" placeholder="Paste Link Google Drive..." class="w-full border-slate-200 rounded-xl shadow-inner focus:border-[#58896a] focus:ring focus:ring-[#58896a] focus:ring-opacity-20 text-xs bg-slate-50 focus:bg-white transition-all py-2 px-3">
                                                </div>
                                                <div class="md:w-2/12 w-full flex justify-end gap-1 border-t md:border-t-0 pt-2 md:pt-0 border-slate-100">
                                                    <button type="button" @click="openEdit({{ json_encode($item) }})" class="text-blue-500 hover:text-blue-700 p-2 rounded-xl hover:bg-blue-50 transition-colors" title="Edit Indikator">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                    </button>
                                                    <button type="button" onclick="konfirmasiHapusIndikator('{{ $item->id }}')" class="text-red-500 hover:text-red-700 p-2 rounded-xl hover:bg-red-50 transition-colors" title="Hapus Indikator">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                        
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="bg-white border border-slate-100 rounded-[2rem] p-8 text-center shadow-sm mb-6">
                            <p class="text-xs text-slate-400 italic font-bold uppercase tracking-widest">Belum ada indikator untuk kategori ini.</p>
                        </div>
                    @endforelse
                    @endforeach
                    
                    <div class="mt-12 border-t border-slate-200 pt-8">
                        <button type="submit" class="w-full md:w-auto px-10 py-4 bg-[#f59e0b] hover:bg-yellow-500 text-[#1e3a8a] font-black italic uppercase tracking-widest text-[11px] rounded-[1.5rem] shadow-[0_10px_20px_-10px_rgba(245,158,11,0.5)] transition-all hover:-translate-y-1">
                            💾 Simpan Semua Tautan Drive
                        </button>
                    </div>
                </div>
            </form>

            {{-- STRUKTUR BARU (SUDAH DISESUAIKAN DENGAN MULTI-LEVEL GROUP) --}}
            @foreach(['tatalaksana', 'pengawasan', 'pelayanan', 'partisipasi', 'kearifan'] as $kat)
                @foreach($data[$kat] ?? [] as $grup => $items)
                    @foreach($items as $item)
                        @if(is_object($item))
                        <form id="delete-form-{{ $item->id }}" action="{{ route('desa.antikorupsi.destroy', $item->id) }}" method="POST" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                        @endif
                    @endforeach
                @endforeach
            @endforeach

        </div>

        <!-- ================= MODAL TAMBAH ================= -->
        <div x-show="modalAddOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="modalAddOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalAddOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="modalAddOpen" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                    <form action="{{ route('desa.antikorupsi.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-black uppercase italic tracking-widest text-[#1e3a8a] mb-5 border-b pb-3">Tambah Indikator Baru</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Kategori Indikator</label>
                                    <select name="kategori" x-model="addKategori" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-[#58896a]">
                                        <option value="tatalaksana">Tata Laksana</option>
                                        <option value="pengawasan">Pengawasan</option>
                                        <option value="pelayanan">Pelayanan Publik</option>
                                        <option value="partisipasi">Partisipasi Masyarakat</option>
                                        <option value="kearifan">Kearifan Lokal</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Nama Grup Indikator</label>
                                    <select name="grup_indikator" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-[#58896a]">
                                        <option value="">-- Pilih Grup Indikator --</option>
                                        <template x-for="grup in filteredAddGrup" :key="grup.id">
                                            <option :value="grup.nama_grup" x-text="grup.nama_grup"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Sub Judul Indikator (Opsional)</label>
                                    <input type="text" name="sub_judul" placeholder="Contoh: 1. Musyawarah Pemangku Kepentingan..." class="w-full border-slate-200 rounded-xl text-xs">
                                    <small class="text-[10px] text-slate-400 italic mt-1 block">*Isi jika ingin membuat sub-kelompok judul baru di dalam grup ini.</small>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-1/2">
                                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">No Urut</label>
                                        <input type="text" name="no_urut" x-model="editNoUrut" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500">
                                    </div>
                                    <div class="w-1/2">
                                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Sub</label>
                                        <input type="text" name="sub" x-model="editSub" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">
                                        Sub Judul Indikator
                                    </label>
                                    <input type="text"
                                        name="sub_judul"
                                        x-model="editSubJudul"
                                        placeholder="Contoh: Isi Maklumat Pelayanan Memuat Minimal:"
                                        class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500">
                                    <small class="text-[10px] text-slate-400 italic mt-1 block">
                                        Kosongkan jika ini bukan baris sub judul.
                                    </small>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Nama Dokumen</label>
                                    <input type="text" name="nama_dokumen" placeholder="Contoh: RKPDes" class="w-full border-slate-300 rounded-xl text-xs focus:ring-[#58896a]">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent px-4 py-2 bg-[#58896a] text-white hover:bg-emerald-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-xs font-black uppercase italic tracking-widest">
                                Simpan
                            </button>
                            <button type="button" @click="modalAddOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 px-4 py-2 bg-white text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-xs font-black uppercase italic tracking-widest">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ================= MODAL EDIT ================= -->
        <div x-show="modalEditOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="modalEditOpen" x-transition.opacity class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalEditOpen = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div x-show="modalEditOpen" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
                    
                    <form :action="'{{ url('desa/antikorupsi/edit') }}/' + editId" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-black uppercase italic tracking-widest text-[#1e3a8a] mb-5 border-b pb-3">Edit Detail Indikator</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Kategori Indikator</label>
                                    <select name="kategori" x-model="editKategori" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500">
                                        <option value="tatalaksana">Tata Laksana</option>
                                        <option value="pengawasan">Pengawasan</option>
                                        <option value="pelayanan">Pelayanan Publik</option>
                                        <option value="partisipasi">Partisipasi Masyarakat</option>
                                        <option value="kearifan">Kearifan Lokal</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Nama Grup Indikator</label>
                                    <select name="grup_indikator" x-model="editGrup" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500">
                                        <template x-for="grup in filteredEditGrup" :key="grup.id">
                                            <option :value="grup.nama_grup" x-text="grup.nama_grup" :selected="grup.nama_grup === editGrup"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="flex gap-4">
                                    <div class="w-1/2">
                                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">No Urut</label>
                                        <input type="text" name="no_urut" x-model="editNoUrut" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500">
                                    </div>
                                    <div class="w-1/2">
                                        <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Sub</label>
                                        <input type="text" name="sub" x-model="editSub" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Nama Dokumen</label>
                                    <input type="text" name="nama_dokumen" x-model="editNama" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Link Drive</label>
                                    <input type="url" name="link_drive" x-model="editLink" class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100">
                            <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-xs font-black uppercase italic tracking-widest">
                                Update Data
                            </button>
                            <button type="button" @click="modalEditOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 px-4 py-2 bg-white text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-xs font-black uppercase italic tracking-widest">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- SCRIPT ALPINE JS -->
    <script>
        function antikorupsiData() {
            return {
                modalAddOpen: false, 
                modalEditOpen: false,
                
                masterData: @json($masterGrupList),
                addKategori: 'tatalaksana',
                
                get filteredAddGrup() {
                    return this.masterData.filter(g => g.kategori === this.addKategori);
                },

                editId: '',
                editKategori: 'tatalaksana',
                editGrup: '',
                editNoUrut: '',
                editSub: '',
                editSubJudul: '',
                editNama: '',
                editLink: '',

                get filteredEditGrup() {
                    return this.masterData.filter(g => g.kategori === this.editKategori);
                },

                openEdit(item) {
                    this.editId = item.id;
                    this.editKategori = item.kategori;
                    this.editGrup = item.grup_indikator;
                    this.editNoUrut = item.no_urut || '';
                    this.editSub = item.sub || '';
                    this.editSubJudul = item.sub_judul || '';
                    this.editNama = item.nama_dokumen || '';
                    this.editLink = item.link_drive || '';
                    this.modalEditOpen = true;
                }
            }
        }
    </script>
    <script>
    function konfirmasiHapusIndikator(idIndikator) {
        Swal.fire({
            title: 'HAPUS INDIKATOR?',
            text: "Komponen indikator dokumen ini beserta tautan di dalamnya akan dihapus permanen dari data Desa!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Red-500 Tailwind
            cancelButtonColor: '#64748b',  // Slate-500 Tailwind
            confirmButtonText: 'YA, HAPUS INDIKATOR!',
            cancelButtonText: 'BATAL',
            background: '#ffffff',
            customClass: {
                title: 'font-black tracking-tighter uppercase italic text-slate-800',
                popup: 'rounded-[2rem] border border-slate-100 p-6',
                confirmButton: 'rounded-xl font-black text-xs uppercase tracking-wider px-4 py-2.5',
                cancelButton: 'rounded-xl font-black text-xs uppercase tracking-wider px-4 py-2.5'
            }
        }).then((result) => {
            // Jika diklik YA, cari form tersembunyi milik ID tersebut lalu submit
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + idIndikator).submit();
            }
        });
    }
    </script>
</x-app-layout>