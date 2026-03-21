<x-app-layout>
    <div class="py-12 px-6 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-[#1e3a8a] tracking-tighter uppercase italic leading-none">Konfigurasi Form</h2>
                    <p class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.2em] mt-2">
                        Desa: <span class="text-[#f59e0b]">{{ $desa->nama_desa }}</span> • Kec. {{ $desa->kecamatan }}
                    </p>
                </div>
                <a href="{{ route('admin.index') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">
                    ← Kembali ke Monitoring
                </a>
            </div>

            <form action="{{ route('admin.atur-form.simpan', $desa->id) }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    @foreach($categories as $category)
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="bg-slate-50 px-8 py-4 flex justify-between items-center border-b border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-[#1e3a8a] rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-900/20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-black text-[#1e3a8a] uppercase italic tracking-wider">{{ $category->name }}</h3>
                                    <span class="text-[9px] text-slate-400 uppercase font-bold tracking-widest">Grup Kategori</span>
                                </div>
                            </div>
                            
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="show_categories[]" value="{{ $category->id }}" 
                                    {{ !in_array($category->id, $hiddenIds) ? 'checked' : '' }}
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#f59e0b]"></div>
                                <span class="ml-3 text-[10px] font-black text-slate-400 uppercase italic peer-checked:text-[#f59e0b]">Tampilkan Tab</span>
                            </label>
                        </div>

                        <div class="px-8 py-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($category->indicators as $indicator)
                                <label class="flex items-center p-4 bg-slate-50 rounded-2xl border border-transparent hover:border-blue-100 hover:bg-blue-50/50 transition-all cursor-pointer group relative overflow-hidden">
                                    <input type="checkbox" name="show_indicators[]" value="{{ $indicator->id }}" 
                                        {{ !in_array($indicator->id, $hiddenIds) ? 'checked' : '' }}
                                        class="w-5 h-5 rounded-lg border-slate-300 text-[#1e3a8a] focus:ring-[#1e3a8a] transition-all">
                                    
                                    <div class="ml-4 flex-1">
                                        <span class="block text-[11px] font-black text-slate-700 uppercase italic leading-tight group-hover:text-[#1e3a8a]">
                                            {{ $indicator->name }}
                                        </span>
                                        
                                        @php
                                            // LOGIKA INTI: Hanya hitung data yang isinya > 0
                                            $statsReal = $indicator->statistics
                                                ->where('desa_id', $desa->id)
                                                ->where('value', '>', 0);

                                            $yearsWithData = $statsReal->pluck('year')->unique()->sort();
                                            $hasRealData = $statsReal->count() > 0;
                                        @endphp

                                        @if($hasRealData)
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                                <span class="text-[8px] font-black text-emerald-600 uppercase tracking-tighter">
                                                    Terisi Tahun: {{ $yearsWithData->first() }} - {{ $yearsWithData->last() }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5 mt-1">
                                                <div class="w-1.5 h-1.5 rounded-full bg-slate-300"></div>
                                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter italic">
                                                    Nol / Belum Ada Data
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="absolute top-2 right-3">
                                        <span class="text-[7px] font-black uppercase px-2 py-0.5 rounded-full {{ $hasRealData ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-500' }}">
                                            {{ $hasRealData ? 'Terisi' : 'Kosong' }}
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="fixed bottom-10 left-1/2 -translate-x-1/2 w-full max-w-4xl px-6 z-50">
                    <button type="submit" class="w-full bg-[#1e3a8a] hover:bg-[#f59e0b] text-white py-4 rounded-2xl shadow-2xl shadow-blue-900/40 transition-all transform hover:scale-[1.02] active:scale-95 flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm font-black uppercase italic tracking-[0.2em]">Simpan Konfigurasi Desa</span>
                    </button>
                </div>
            </form>

            <div class="h-32"></div> 
        </div>
    </div>
</x-app-layout>