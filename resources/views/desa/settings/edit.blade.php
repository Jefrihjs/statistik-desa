<x-app-layout>
    @php
        $headerColor = $desa->header_color ?? '#2563eb';
        $accentColor = $desa->accent_color ?? '#10b981';
    @endphp
    <div class="py-10 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-3xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-800 uppercase italic">Pengaturan Branding Desa</h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-wider">Atur logo dan warna tampilan desa</p>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-emerald-700 font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 p-8">
                <form action="{{ route('desa.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-[11px] font-black uppercase text-slate-500 mb-2">Logo Desa</label>
                        @if($desa->logo)
                            <img src="{{ asset('storage/' . $desa->logo) }}" alt="Logo Desa" class="h-20 w-20 object-contain mb-3 rounded-xl bg-slate-50 p-2 border">
                        @endif
                        <input type="file" name="logo" class="block w-full text-sm font-bold text-slate-600">
                    </div>

                    <div>
                        <label class="block text-[11px] font-black uppercase text-slate-500 mb-2">Warna Header</label>
                        <input type="color" name="header_color" value="{{ old('header_color', $desa->header_color ?? '#2563eb') }}" class="h-12 w-24 rounded-xl border border-slate-200">
                    </div>

                    <div>
                        <label class="block text-[11px] font-black uppercase text-slate-500 mb-2">Warna Aksen</label>
                        <input type="color" name="accent_color" value="{{ old('accent_color', $desa->accent_color ?? '#10b981') }}" class="h-12 w-24 rounded-xl border border-slate-200">
                    </div>

                    <div class="pt-6 border-t border-slate-100 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label class="block text-[11px] font-black uppercase text-slate-500 mb-2">Template Tampilan</label>
                                <select name="layout_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-blue-600">
                                    <option value="default" {{ old('layout_type', $desa->layout_type) == 'default' ? 'selected' : '' }}>Standard (Rapi & Formal)</option>
                                    <option value="infographic" {{ old('layout_type', $desa->layout_type) == 'infographic' ? 'selected' : '' }}>Infografis (Modern & Visual)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black uppercase text-slate-500 mb-2">Fokus Data Unggulan</label>
                                <select name="featured_category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-blue-600">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('featured_category_id', $desa->featured_category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black uppercase text-slate-500 mb-2">Narasi / Pesan Kepala Desa</label>
                            <textarea name="welcome_message" rows="4" 
                                    class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 text-sm font-bold focus:ring-blue-600"
                                    placeholder="Contoh: Peningkatan statistik ekonomi tahun ini adalah hasil kerja keras seluruh warga...">{{ old('welcome_message', $desa->welcome_message) }}</textarea>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest">
                            Simpan Branding
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>