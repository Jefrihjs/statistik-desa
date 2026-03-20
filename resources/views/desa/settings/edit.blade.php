<x-app-layout>
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