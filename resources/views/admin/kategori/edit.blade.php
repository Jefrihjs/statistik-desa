<x-app-layout>
    <div class="py-12 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto">

            <div class="mb-8">
                <h2 class="text-3xl font-black text-slate-800 tracking-tighter uppercase italic">Edit Kategori Statistik</h2>
                <p class="text-slate-500 font-bold text-sm tracking-widest uppercase">Perbarui nama, urutan, dan status kategori</p>
            </div>

            <div class="bg-white rounded-[3rem] shadow-xl border border-slate-100 p-10">
                <form action="{{ route('admin.kategori.update', $category->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-2">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold focus:ring-blue-600" required>
                        @error('name')
                            <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-2">Slug Sistem</label>
                        <input type="text" value="{{ $category->slug }}" class="w-full bg-slate-100 border-none rounded-2xl px-4 py-3 text-sm font-bold text-slate-400 cursor-not-allowed" readonly>
                        <p class="mt-2 text-[11px] text-slate-400 font-bold">Slug dikunci agar koneksi tab frontend dan blade tetap aman.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-2">Urutan Tampil</label>
                            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $category->sort_order) }}" class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold focus:ring-blue-600" required>
                            @error('sort_order')
                                <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-400 mb-2 ml-2">Status Publikasi</label>
                            <select name="is_active" class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 text-sm font-bold focus:ring-blue-600">
                                <option value="1" {{ old('is_active', $category->is_active) == 1 ? 'selected' : '' }}>Aktif / Tampil</option>
                                <option value="0" {{ old('is_active', $category->is_active) == 0 ? 'selected' : '' }}>Nonaktif / Hidden</option>
                            </select>
                            @error('is_active')
                                <p class="mt-2 text-xs font-bold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex items-center gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-100 transition">
                            Simpan Perubahan
                        </button>

                        <a href="{{ route('admin.kategori.index') }}" class="px-6 py-3 rounded-2xl bg-slate-100 text-slate-500 font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition">
                            Kembali
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>