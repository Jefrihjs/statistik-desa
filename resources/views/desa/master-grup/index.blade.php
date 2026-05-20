<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase italic tracking-widest text-[15px]">
            {{ __('Master Grup Indikator Antikorupsi') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ modalAddOpen: false, modalEditOpen: false, editId: '', editKategori: '', editNamaGrup: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <button @click="modalAddOpen = true" class="bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-6 rounded-xl shadow-lg font-black text-[10px] uppercase tracking-widest italic transition-all hover:-translate-y-1 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Master Grup
                </button>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-xl shadow-sm font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-xl rounded-[2rem] border border-slate-100 overflow-hidden">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 font-black uppercase text-[10px] tracking-widest text-[#1e3a8a]">Kategori</th>
                            <th class="px-6 py-4 font-black uppercase text-[10px] tracking-widest text-[#1e3a8a]">Nama Grup Indikator</th>
                            <th class="px-6 py-4 font-black uppercase text-[10px] tracking-widest text-[#1e3a8a] text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-bold text-slate-700 divide-y divide-slate-50">
                        @forelse($masterGrup as $master)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 uppercase text-[11px] text-slate-800">{{ $master->kategori }}</td>
                            <td class="px-6 py-4 font-medium text-slate-600">{{ $master->nama_grup }}</td>
                            <td class="px-6 py-4 flex justify-end gap-2">
                                <button @click="editId = '{{ $master->id }}'; editKategori = '{{ $master->kategori }}'; editNamaGrup = '{{ addslashes($master->nama_grup) }}'; modalEditOpen = true" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                
                                {{-- FORM HAPUS MODERN SWEETALERT --}}
                                <form action="{{ route('desa.master-grup-antikorupsi.destroy', $master->id) }}" method="POST" class="form-hapus-master inline-block">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="pemicuHapusMaster(this)" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-slate-400 text-xs font-bold uppercase tracking-widest">Belum ada Master Grup.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="modalAddOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalAddOpen = false"></div>
                <div class="bg-white rounded-2xl overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full z-10 border border-slate-100">
                    <form action="{{ route('desa.master-grup-antikorupsi.store') }}" method="POST">
                        @csrf
                        <div class="p-6">
                            <h3 class="text-lg font-black uppercase italic tracking-widest text-[#1e3a8a] mb-5 border-b pb-3">Tambah Master Grup</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Kategori</label>
                                    <select name="kategori" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-[#58896a]">
                                        <option value="tatalaksana">Tata Laksana</option>
                                        <option value="pengawasan">Pengawasan</option>
                                        <option value="pelayanan">Pelayanan Publik</option>
                                        <option value="partisipasi">Partisipasi Masyarakat</option>
                                        <option value="kearifan">Kearifan Lokal</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Nama Grup Indikator</label>
                                    <input type="text" name="nama_grup" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-[#58896a]" placeholder="1. Perdes tentang...">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3">
                            <button type="submit" class="bg-[#58896a] text-white px-4 py-2 rounded-xl text-xs font-black uppercase italic tracking-widest">Simpan</button>
                            <button type="button" @click="modalAddOpen = false" class="bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-xl text-xs font-black uppercase italic tracking-widest">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="modalEditOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalEditOpen = false"></div>
                <div class="bg-white rounded-2xl overflow-hidden shadow-xl transform transition-all sm:max-w-lg w-full z-10 border border-slate-100">
                    
                    {{-- FIX ROUTE EDIT AGAR TIDAK BENTROK 404 --}}
                    <form :action="'/desa/master-grup-antikorupsi/' + editId" method="POST">
                        @csrf @method('PUT')
                        <div class="p-6">
                            <h3 class="text-lg font-black uppercase italic tracking-widest text-[#1e3a8a] mb-5 border-b pb-3">Edit Master Grup</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-widest mb-1">Kategori</label>
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
                                    <input type="text" name="nama_grup" x-model="editNamaGrup" required class="w-full border-slate-300 rounded-xl text-xs focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase italic tracking-widest">Update</button>
                            <button type="button" @click="modalEditOpen = false" class="bg-white border border-slate-300 text-slate-700 px-4 py-2 rounded-xl text-xs font-black uppercase italic tracking-widest">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT LOGIKA SWEETALERT2 --}}
    <script>
    function pemicuHapusMaster(button) {
        const form = button.closest('.form-hapus-master');

        Swal.fire({
            title: 'HAPUS MASTER GRUP?',
            text: "Data komponen indikator ini akan dihapus permanen dari sistem TARSIUS Kabupaten!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Red-500 Tailwind
            cancelButtonColor: '#64748b',  // Slate-500 Tailwind
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
                form.submit();
            }
        });
    }
    </script>
</x-app-layout>