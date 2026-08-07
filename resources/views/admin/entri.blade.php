<x-app-layout>
    @php
        $currentTab = request('tab', $categories->first()->slug ?? '');

        $headerColor = $desa->header_color ?? '#2563eb';
        $accentColor = $desa->accent_color ?? '#10b981';
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
            @if(session('success'))
                <div class="mb-6 p-6 bg-emerald-50 border-l-4 border-emerald-500 rounded-3xl flex items-center gap-4 text-emerald-950 font-black text-xs sm:text-sm uppercase tracking-wider shadow-sm">
                    <span>🟢</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-6 bg-rose-50 border-l-4 border-rose-500 rounded-3xl flex items-center gap-4 text-rose-950 font-black text-xs sm:text-sm uppercase tracking-wider shadow-sm">
                    <span>🔴</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            
            <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 mb-6"
                style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }});
                        border-radius: 2.5rem;
                        padding: 35px;
                        color: white;
                        margin-bottom: 2rem;
                        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
                        position: relative;
                        overflow: visible;">
                <div style="position: absolute; right: -50px; top: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                <div class="p-8 flex flex-col lg:flex-row justify-between items-center gap-6">
                    
                    <div>
                        <h2 class="text-3xl font-black text-White-900 uppercase italic tracking-tighter leading-none">
                            Desa {{ $desa->nama_desa }}
                        </h2>
                        
                        <div class="flex items-center gap-3 mt-3">
                            <span class="text-[10px] font-black text-White-400 uppercase tracking-widest italic">Tahun Data:</span>
                            
                            <div class="flex items-center gap-2">
                                <form action="{{ url()->current() }}" method="GET" class="m-0">
                                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                                    <select name="tahun" onchange="this.form.submit()" 
                                            class="border-2 rounded-full px-4 py-1.5 font-black text-sm bg-white hover:bg-slate-50 focus:outline-none cursor-pointer transition-all shadow-sm"
                                            style="border-color: {{ $headerColor ?? '#2563eb' }}; color: {{ $headerColor ?? '#2563eb' }};">
                                        @php
                                            // Tetap menggunakan logika ini agar tahun berjalan/terpilih tidak hilang
                                            $koleksiTahun = collect($daftarTahun)->push((int)$tahun)->unique()->sortDesc();
                                        @endphp
                                        @foreach($koleksiTahun as $y)
                                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-3">
                        <div class="flex items-center gap-2 px-4 py-3 bg-slate-50 rounded-2xl border border-slate-200 shadow-inner mr-2">
                            <div class="w-2.5 h-2.5 rounded-full" :class="status === 'Menyimpan...' ? 'bg-yellow-500 animate-bounce' : (status === 'Gagal Simpan' ? 'bg-red-500' : 'bg-green-500')"></div>
                            <span class="text-[9px] font-black uppercase text-slate-600" x-text="status"></span>
                        </div>

                        <a href="{{ route('admin.download-template') }}?tahun={{ $tahun }}" class="bg-emerald-500 hover:bg-emerald-600 text-white px-5 py-3 rounded-2xl text-[10px] font-black uppercase shadow-lg transition-all flex items-center gap-2 transform active:scale-95">
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
                                class="bg-slate-900 hover:bg-slate-800 text-white px-7 py-3 rounded-2xl font-black transition-all uppercase text-[10px] tracking-widest">
                            Simpan Manual
                        </button>
                    </div>
                </div>

                
                    <div class="flex flex-wrap items-center justify-center gap-4 md:gap-6 overflow-visible">
                        
                        @php
                            // Fungsi pintar untuk menentukan ikon SVG berdasarkan nama kategori
                            $getIcon = function($slug) {
                                if (str_contains($slug, 'demografi')) return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>'; // Ikon Orang (User Group)
                                if (str_contains($slug, 'penduduk')) return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>'; // Ikon Grafik Batang (Bar Chart)
                                if (str_contains($slug, 'umur')) return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>'; // Ikon Diagram Lingkaran (Pie Chart)
                                if (str_contains($slug, 'mata-pencaharian')) return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'; // Ikon Uang Dolar (Koin)
                                if (str_contains($slug, 'pendidikan')) return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>'; // Ikon Toga Akademik
                                if (str_contains($slug, 'agama')) return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>'; // Ikon Bintang (Sparkles)
                                if (str_contains($slug, 'tenaga-kerja')) return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>'; // Ikon Tas Kerja (Briefcase)
                                if (str_contains($slug, 'etnis') || str_contains($slug, 'suku')) return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'; // Ikon Bola Dunia (Globe)
                                
                                // Ikon Default (Jika ada kategori baru yang tidak terdaftar di atas)
                                return '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>'; 
                            };
                        @endphp

                        @foreach($categories as $cat)
                            <a href="?tahun={{ $tahun }}&tab={{ $cat->slug }}" 
                                class="group relative flex items-center justify-center w-12 h-12 md:w-14 md:h-14 rounded-full transition-all duration-300 hover:z-[100] {{ $currentTab == $cat->slug ? 'text-white shadow-lg scale-110 z-30' : 'bg-slate-50 text-slate-400 z-10' }}">
                                
                                <svg class="w-5 h-5 md:w-6 md:h-6 relative z-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $getIcon($cat->slug) !!}
                                </svg>

                                @if(in_array($cat->id, $categoriesWithData))
                                    <span class="absolute -top-1 -right-1 flex h-3 w-3 z-20">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500 border-2 border-white"></span>
                                    </span>
                                @endif

                                <div class="absolute bottom-full z-[9999] mb-3 left-1/2 -translate-x-1/2 origin-bottom px-4 py-2 text-white text-[10px] font-black uppercase tracking-widest rounded-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 whitespace-nowrap shadow-2xl transform group-hover:-translate-y-1 pointer-events-none"
                                    style="background-color: {{ $headerColor ?? '#2563eb' }};">
                                    {{ $cat->name }}
                                    
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 w-3 h-3 rotate-45 rounded-sm -mt-1.5"
                                        style="background-color: {{ $headerColor ?? '#2563eb' }};"></div>
                                </div>
                            </a>
                        @endforeach

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
                                                    @if(($currentTab == 'demografi' && ($ind->name == 'Laki-laki' || $ind->name == 'Perempuan')) || $currentTab == 'kelompok-usia')
                                                        <input type="number" readonly
                                                               value="{{ $ind->statistics->where('gender', 'Laki-laki')->where('year', $tahun)->first()->value ?? 0 }}"
                                                               class="w-full p-4 bg-slate-100 border-none rounded-2xl text-center font-black text-xl text-slate-500 cursor-not-allowed shadow-inner"
                                                               title="Dihitung otomatis dari Data Penduduk Per Tahun Usia">
                                                    @else
                                                        <input type="number" name="stats[{{ $ind->id }}][Laki-laki]"
                                                               value="{{ $ind->statistics->where('gender', 'Laki-laki')->where('year', $tahun)->first()->value ?? 0 }}"
                                                               @input.debounce.1000ms="saveData('{{ $ind->id }}', 'Laki-laki', $el.value)"
                                                               class="w-full p-4 bg-blue-50/50 border-none rounded-2xl text-center font-black text-xl text-blue-900 focus:ring-2 focus:ring-blue-500 shadow-inner">
                                                    @endif
                                                </td>
                                                <td class="p-4 text-center">
                                                    @if(($currentTab == 'demografi' && ($ind->name == 'Laki-laki' || $ind->name == 'Perempuan')) || $currentTab == 'kelompok-usia')
                                                        <input type="number" readonly
                                                               value="{{ $ind->statistics->where('gender', 'Perempuan')->where('year', $tahun)->first()->value ?? 0 }}"
                                                               class="w-full p-4 bg-slate-100 border-none rounded-2xl text-center font-black text-xl text-slate-500 cursor-not-allowed shadow-inner"
                                                               title="Dihitung otomatis dari Data Penduduk Per Tahun Usia">
                                                    @else
                                                        <input type="number" name="stats[{{ $ind->id }}][Perempuan]"
                                                               value="{{ $ind->statistics->where('gender', 'Perempuan')->where('year', $tahun)->first()->value ?? 0 }}"
                                                               @input.debounce.1000ms="saveData('{{ $ind->id }}', 'Perempuan', $el.value)"
                                                               class="w-full p-4 bg-pink-50/50 border-none rounded-2xl text-center font-black text-xl text-pink-900 focus:ring-2 focus:ring-pink-500 shadow-inner">
                                                    @endif
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