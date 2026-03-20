<x-app-layout>
    @php
        $currentTab = request('tab', $categories->first()->slug ?? '');
    @endphp

    <div class="py-12 px-4 bg-slate-50 min-h-screen" x-data="{ 
        status: 'Siap',
        search: '',
        saveData(indicatorId, gender, value) {
            this.status = 'Menyimpan...';
            axios.post('{{ route('admin.simpan') }}', {
                _token: '{{ csrf_token() }}',
                desa_id: '{{ $desa->id }}',
                tahun: '{{ $tahun }}',
                stats: { [indicatorId]: { [gender]: value } }
            })
            .then(response => {
                this.status = 'Tersimpan';
                setTimeout(() => { this.status = 'Siap'; }, 2000);
            })
            .catch(error => {
                this.status = 'Gagal Simpan';
            });
        }
    }">
        <div class="max-w-7xl mx-auto">
            
            <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 mb-6 overflow-hidden">
                <div class="p-8 flex flex-col lg:flex-row justify-between items-center gap-6">
                    <div>
                        <h2 class="text-3xl font-black text-blue-900 uppercase italic tracking-tighter leading-none">
                            Desa {{ $desa->nama_desa }}
                        </h2>
                        <div class="flex items-center gap-3 mt-3">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest italic">Tahun Data:</span>
                            <form action="{{ url()->current() }}" method="GET" class="m-0">
                                <input type="hidden" name="tab" value="{{ $currentTab }}">
                                <select name="tahun" onchange="this.form.submit()" class="border-2 border-blue-600 rounded-xl px-4 py-1 font-black text-blue-600 text-sm bg-white focus:ring-0">
                                    @foreach($daftarTahun as $y)
                                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <div class="flex items-center gap-2 px-4 py-3 bg-slate-50 rounded-2xl border border-slate-200 shadow-inner mr-2">
                            <div class="w-2.5 h-2.5 rounded-full" :class="status === 'Menyimpan...' ? 'bg-yellow-500 animate-bounce' : (status === 'Gagal Simpan' ? 'bg-red-500' : 'bg-green-500')"></div>
                            <span class="text-[9px] font-black uppercase text-slate-600" x-text="status"></span>
                        </div>

                        <a href="{{ route('admin.download-template') }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-2xl text-[10px] font-black uppercase shadow-lg transition-all flex items-center gap-2 transform active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download
                        </a>

                        <form action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data" class="m-0">
                            @csrf
                            <input type="hidden" name="desa_id" value="{{ $desa->id }}">
                            <input type="hidden" name="tahun" value="{{ $tahun }}">
                            <label class="cursor-pointer bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-2xl text-[10px] font-black uppercase shadow-lg transition-all flex items-center gap-2 transform active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                Upload
                                <input type="file" name="file" class="hidden" onchange="this.form.submit()">
                            </label>
                        </form>

                        <button type="button" onclick="document.getElementById('main-form').submit()" 
                                class="bg-slate-900 hover:bg-black text-white px-7 py-3 rounded-2xl font-black shadow-lg transition-all uppercase text-[10px] tracking-widest border-b-4 border-slate-700 active:border-b-0 transform active:translate-y-1">
                            Simpan Manual
                        </button>
                    </div>
                </div>

                <div class="bg-slate-50 border-t border-slate-100 shadow-inner">
                    <div class="flex overflow-x-auto no-scrollbar items-stretch justify-start md:justify-center">
                        @foreach($categories as $cat)
                            <a href="?tahun={{ $tahun }}&tab={{ $cat->slug }}" 
                               class="flex-shrink-0 w-24 md:w-28 py-4 flex flex-col items-center justify-center gap-1.5 border-b-4 transition-all {{ $currentTab == $cat->slug ? 'border-blue-600 bg-white shadow-sm' : 'border-transparent text-slate-400 hover:bg-white/50' }}">
                                
                                <div class="relative">
                                    <svg class="w-5 h-5 {{ $currentTab == $cat->slug ? 'text-blue-600' : 'text-slate-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>

                                    @if(in_array($cat->id, $categoriesWithData))
                                        <span class="absolute -top-1 -right-1 flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                        </span>
                                    @endif
                                </div>

                                <span class="text-[8px] font-black uppercase tracking-tighter text-center leading-[1.1] px-1 {{ $currentTab == $cat->slug ? 'text-blue-700' : 'text-slate-500' }}">
                                    {!! str_replace(' ', '<br>', $cat->name) !!}
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <form id="main-form" action="{{ route('admin.simpan') }}" method="POST">
                @csrf
                <input type="hidden" name="desa_id" value="{{ $desa->id }}">
                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 min-h-[600px]">
                    @foreach($categories as $cat)
                        @if($currentTab == $cat->slug)
                        <div class="p-10 animate-fade-in">
                            <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
                                <h3 class="text-2xl font-black text-slate-800 border-l-8 border-blue-600 pl-5 uppercase italic tracking-tighter">
                                    Data {{ $cat->name }}
                                </h3>
                                <div class="relative w-full md:w-80">
                                    <input type="text" x-model="search" placeholder="Cari Indikator..." class="w-full text-xs border-slate-200 rounded-2xl pl-10 py-3 bg-slate-50 italic focus:ring-blue-500 focus:bg-white transition-all">
                                    <svg class="absolute left-3 top-3.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>

                            <div class="overflow-hidden rounded-[2rem] border border-slate-200 shadow-sm">
                                <table class="w-full">
                                    <thead>
                                        <tr class="bg-slate-900 text-white text-[10px] uppercase font-black tracking-widest text-center">
                                            <th class="p-6 text-left">Indikator</th>
                                            <th class="p-6 w-48 bg-slate-800">Laki-laki (LK)</th>
                                            <th class="p-6 w-48 bg-slate-800">Perempuan (PR)</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @forelse($cat->indicators as $ind)
                                            <tr x-show="search === '' || '{{ strtolower($ind->name) }}'.includes(search.toLowerCase())" class="hover:bg-blue-50/40 transition-all">
                                                <td class="p-6">
                                                    <div class="text-sm font-black text-slate-700 uppercase italic leading-tight">{{ $ind->name }}</div>
                                                    <div class="text-[9px] text-slate-400 mt-1 uppercase tracking-widest font-bold">Satuan: {{ $ind->unit ?? 'Jiwa' }}</div>
                                                </td>
                                                <td class="p-4 text-center">
                                                    <input type="number" name="stats[{{ $ind->id }}][Laki-laki]"
                                                           value="{{ $ind->statistics->where('gender', 'Laki-laki')->where('year', $tahun)->first()->value ?? 0 }}"
                                                           @input.debounce.1000ms="saveData('{{ $ind->id }}', 'Laki-laki', $el.value)"
                                                           class="w-full p-4 bg-blue-50/50 border-none rounded-2xl text-center font-black text-xl text-blue-900 focus:ring-2 focus:ring-blue-500 shadow-inner">
                                                </td>
                                                <td class="p-4 text-center">
                                                    <input type="number" name="stats[{{ $ind->id }}][Perempuan]"
                                                           value="{{ $ind->statistics->where('gender', 'Perempuan')->where('year', $tahun)->first()->value ?? 0 }}"
                                                           @input.debounce.1000ms="saveData('{{ $ind->id }}', 'Perempuan', $el.value)"
                                                           class="w-full p-4 bg-pink-50/50 border-none rounded-2xl text-center font-black text-xl text-pink-900 focus:ring-2 focus:ring-pink-500 shadow-inner">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="p-20 text-center text-slate-300 italic font-black uppercase tracking-widest text-sm">Belum ada indikator aktif.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </form>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .animate-fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</x-app-layout>