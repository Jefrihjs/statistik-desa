<x-app-layout>
    <div class="py-12 px-4">
        <div class="max-w-4xl mx-auto">
            <a href="{{ route('admin.kategori.index') }}" class="text-blue-600 font-bold flex items-center gap-2 mb-4 hover:underline">
                ← Kembali ke Daftar Kategori
            </a>
            
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
                <div class="p-8 bg-gray-800 text-white">
                    <h2 class="text-2xl font-black uppercase tracking-wider">Kategori: {{ $kategori->name }}</h2>
                    <p class="text-gray-400 text-xs mt-1 font-bold italic">*Menambah indikator di sini akan otomatis menambah baris di Form Desa & Template Excel</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('admin.kategori.add-indicator', $kategori->id) }}" method="POST" class="flex gap-2 mb-8 bg-blue-50 p-4 rounded-2xl border border-blue-100">
                        @csrf
                        <div class="flex-1">
                            <input type="text" name="name" required placeholder="Nama Indikator Baru..." class="w-full border-gray-200 rounded-xl focus:ring-blue-600">
                        </div>
                        <div class="w-32">
                            <input type="text" name="unit" value="Jiwa" class="w-full border-gray-200 rounded-xl text-center">
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-6 rounded-xl font-black hover:bg-blue-700 transition">TAMBAH</button>
                    </form>

                    <h4 class="text-xs font-black uppercase text-gray-400 mb-4 tracking-widest">Daftar Indikator Saat Ini</h4>
                    <div class="space-y-2">
                        @foreach($kategori->indicators as $ind)
                        <div class="flex justify-between items-center p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-blue-200 transition">
                            <span class="font-bold text-gray-700 uppercase text-sm">{{ $ind->name }}</span>
                            <div class="flex items-center gap-4">
                                <span class="text-[10px] bg-white border px-2 py-1 rounded-md font-bold text-gray-500">{{ $ind->unit }}</span>
                                <button class="text-red-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>