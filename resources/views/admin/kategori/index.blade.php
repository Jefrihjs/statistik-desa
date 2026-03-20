<x-app-layout>
    <div class="py-12 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto">

            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tighter uppercase italic">Manajemen Tab Statistik</h2>
                    <p class="text-slate-500 font-bold text-sm tracking-widest uppercase">Atur Publikasi Data Sektoral Desa</p>
                </div>
                <button onclick="document.getElementById('modalAdd').showModal()" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition">
                    + Kategori Baru
                </button>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-emerald-700 font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 px-5 py-4 text-red-700 font-bold text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-[3rem] shadow-xl border border-slate-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em]">
                        <tr>
                            <th class="p-6">Urutan</th>
                            <th class="p-6">Nama Tab (Kategori) & Indikator</th>
                            <th class="p-6">Slug Sistem</th>
                            <th class="p-6 text-center">Jumlah Indikator</th>
                            <th class="p-6 text-center">Status Publikasi</th>
                            <th class="p-6 text-right">Aksi Kontrol</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-bold">
                        @forelse($categories as $cat)
                        <tr class="group hover:bg-blue-50/30 transition duration-150 align-top">
                            <td class="p-6 text-slate-400 text-sm italic">
                                #{{ $cat->sort_order ?? '-' }}
                            </td>

                            <td class="p-6">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-slate-100 rounded-xl group-hover:bg-blue-100 transition">
                                        <svg class="w-5 h-5 text-slate-500 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                        </svg>
                                    </div>

                                    <div class="flex-1">
                                        <div class="text-slate-800 uppercase tracking-tight">{{ $cat->name }}</div>

                                        @if($cat->indicators->count())
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach($cat->indicators as $indicator)
                                                    <div class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-[11px] font-bold text-slate-700 flex items-center gap-2">
                                                        <span>{{ $indicator->name }}</span>

                                                        @if(!empty($indicator->unit))
                                                            <span class="text-slate-400 text-[10px]">({{ $indicator->unit }})</span>
                                                        @endif

                                                        <button
                                                            type="button"
                                                            onclick="openEditIndicatorModal('{{ $indicator->id }}', @js($indicator->name), @js($indicator->unit))"
                                                            class="text-blue-600 hover:text-blue-800 text-[10px] uppercase font-black"
                                                        >
                                                            Edit
                                                        </button>

                                                        <form action="{{ route('admin.indikator.destroy', $indicator->id) }}" method="POST" onsubmit="return confirm('Hapus indikator ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 text-[10px] uppercase font-black">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="mt-2 text-xs text-slate-400 italic">Belum ada indikator.</div>
                                        @endif

                                        <form action="{{ route('admin.kategori.add-indicator', $cat->id) }}" method="POST" class="mt-4 flex flex-wrap gap-2">
                                            @csrf
                                            <input
                                                type="text"
                                                name="name"
                                                required
                                                placeholder="Tambah indikator baru"
                                                class="flex-1 min-w-[220px] bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold"
                                            >
                                            <input
                                                type="text"
                                                name="unit"
                                                placeholder="Unit"
                                                class="w-24 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold"
                                            >
                                            <button
                                                type="submit"
                                                class="px-3 py-2 rounded-xl bg-blue-600 text-white text-[10px] font-black uppercase"
                                            >
                                                Tambah
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>

                            <td class="p-6">
                                <code class="bg-slate-50 text-slate-400 px-2 py-1 rounded text-[10px] font-mono">{{ $cat->slug }}</code>
                            </td>

                            <td class="p-6 text-center">
                                <span class="inline-flex items-center justify-center min-w-10 px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-[10px] font-black uppercase">
                                    {{ $cat->indicators_count }}
                                </span>
                            </td>

                            <td class="p-6 text-center">
                                @if($cat->is_active)
                                    <span class="inline-flex items-center gap-1.5 text-green-600 text-[10px] font-black uppercase italic bg-green-50 px-3 py-1 rounded-full ring-4 ring-green-50/50">
                                        <span class="w-1.5 h-1.5 bg-green-600 rounded-full animate-pulse"></span>
                                        Tampil (ON)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-slate-400 text-[10px] font-black uppercase italic bg-slate-50 px-3 py-1 rounded-full">
                                        Hidden (OFF)
                                    </span>
                                @endif
                            </td>

                            <td class="p-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('admin.kategori.toggle', $cat->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="px-4 py-2 rounded-xl text-[10px] font-black uppercase transition-all duration-300
                                            {{ $cat->is_active ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-blue-600 text-white hover:bg-blue-700 shadow-lg shadow-blue-200' }}">
                                            {{ $cat->is_active ? 'Matikan Tab' : 'Aktifkan Tab' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.kategori.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3m-7 0h8"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-slate-400 font-bold uppercase text-sm">
                                Belum ada kategori
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8 bg-blue-50 p-6 rounded-[2rem] border border-blue-100 flex items-start gap-4">
                <div class="text-blue-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <p class="text-xs text-blue-800 leading-relaxed font-bold italic uppercase tracking-wider">
                    Tips: Jika kategori masih memiliki indikator, sebaiknya cukup dinonaktifkan saja agar histori data tetap aman.
                </p>
            </div>
        </div>
    </div>

    {{-- Modal tambah kategori --}}
    <dialog id="modalAdd" class="p-0 rounded-[3rem] shadow-2xl border-none backdrop:bg-slate-900/50">
        <div class="w-[420px] bg-white p-10">
            <h3 class="text-xl font-black text-slate-800 uppercase italic mb-6">Tambah Kategori</h3>
            <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Nama Kategori</label>
                    <input type="text" name="name" required placeholder="Contoh: Data Pertanian" class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold focus:ring-blue-600">
                    @error('name')
                        <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-2 pt-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-2xl font-black text-xs uppercase italic tracking-widest">Simpan</button>
                    <button type="button" onclick="document.getElementById('modalAdd').close()" class="px-6 bg-slate-100 text-slate-400 rounded-2xl font-black text-xs uppercase italic">Batal</button>
                </div>
            </form>
            <form action="{{ route('admin.indicator.toggle', $indicator->id) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" 
                    class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-tighter transition-all {{ $indicator->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400' }}">
                    {{ $indicator->is_active ? 'Tampil' : 'Sembunyi' }}
                </button>
            </form>
        </div>
    </dialog>

    {{-- Modal edit indikator --}}
    <dialog id="modalEditIndicator" class="p-0 rounded-[3rem] shadow-2xl border-none backdrop:bg-slate-900/50">
        <div class="w-[420px] bg-white p-10">
            <h3 class="text-xl font-black text-slate-800 uppercase italic mb-6">Edit Indikator</h3>

            <form id="formEditIndicator" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Nama Indikator</label>
                    <input type="text" id="edit_indicator_name" name="name" required class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold focus:ring-blue-600">
                </div>

                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-2">Unit</label>
                    <input type="text" id="edit_indicator_unit" name="unit" class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold focus:ring-blue-600">
                </div>

                <div class="flex gap-2 pt-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-2xl font-black text-xs uppercase italic tracking-widest">
                        Simpan
                    </button>
                    <button type="button" onclick="document.getElementById('modalEditIndicator').close()" class="px-6 bg-slate-100 text-slate-400 rounded-2xl font-black text-xs uppercase italic">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        function openEditIndicatorModal(id, name, unit) {
            const form = document.getElementById('formEditIndicator');
            const nameInput = document.getElementById('edit_indicator_name');
            const unitInput = document.getElementById('edit_indicator_unit');
            const modal = document.getElementById('modalEditIndicator');

            form.action = `/admin/indikator/${id}`;
            nameInput.value = name || '';
            unitInput.value = unit || '';
            modal.showModal();
        }
    </script>
</x-app-layout>