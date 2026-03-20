<x-app-layout>
    <div x-data="{ 
        search: '', 
        kecamatan: '', 
        tahun: '{{ date('Y') }}' 
    }" class="py-12 px-6 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-10 gap-6">
                <div>
                    <h2 class="text-4xl font-black text-[#1e3a8a] tracking-tighter uppercase italic leading-none">Monitoring Statistik</h2>
                    <p class="text-slate-400 font-bold text-[10px] uppercase tracking-[0.3em] mt-3 flex items-center gap-2">
                        <span class="w-8 h-[2px] bg-[#f59e0b]"></span>
                        Kabupaten Belitung Timur
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                    <div class="relative flex-1 md:flex-none md:w-56 group">
                        <select x-model="kecamatan" 
                                class="appearance-none block w-full pl-5 pr-10 py-3 bg-white border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-[#f59e0b] font-black text-[10px] uppercase tracking-widest italic text-[#1e3a8a] cursor-pointer transition-all">
                            <option value="">Semua Kecamatan</option>
                            @foreach($mapping as $kec => $daftarNamaDesa)
                                <option value="{{ $kec }}">{{ str_replace('KECAMATAN ', '', $kec) }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#f59e0b]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <div class="relative flex-1 md:flex-none md:w-40">
                        <select x-model="tahun" 
                                class="appearance-none block w-full px-5 py-3 bg-white border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-blue-600 font-black text-[10px] uppercase tracking-widest italic text-blue-600 cursor-pointer transition-all">
                            @foreach($listTahun as $th)
                                <option value="{{ $th }}">TAHUN {{ $th }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative w-full md:w-64">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" x-model="search" 
                               placeholder="Cari desa..." 
                               class="block w-full pl-10 pr-4 py-3 bg-white border-none rounded-2xl shadow-sm focus:ring-2 focus:ring-[#1e3a8a] font-bold text-xs transition-all italic">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-slate-100 shadow-blue-900/5">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em]">
                            <th class="px-8 py-6 text-center w-16">No.</th>
                            <th class="px-8 py-6">Nama Wilayah</th>
                            <th class="px-8 py-6 text-center">Status Laporan <span x-text="tahun"></span></th>
                            <th class="px-8 py-6 text-right pr-12">Aksi Manajemen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-bold">
                        @foreach($desas as $index => $desa)
                            <tr class="hover:bg-blue-50/50 transition-all duration-300"
                                x-show="(search === '' || '{{ strtoupper($desa->nama_desa) }}'.includes(search.toUpperCase())) && 
                                        (kecamatan === '' || '{{ strtoupper($desa->kecamatan) }}'.includes(kecamatan.toUpperCase()))"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100">
                                
                                <td class="px-6 py-5 text-center text-xs font-bold text-slate-300">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-blue-900 uppercase italic leading-none tracking-tight">
                                            {{ $desa->nama_desa }}
                                        </span>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase mt-1.5 tracking-tighter">
                                            {{ $desa->kecamatan ?? 'KABUPATEN BELITUNG TIMUR' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-center">
                                    <div class="inline-block">
                                        {{-- Kita cek variabel total_input yang dikirim dari Controller --}}
                                        @if($desa->total_input > 0)
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black bg-emerald-100 text-emerald-700 uppercase italic ring-4 ring-emerald-50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2 animate-pulse"></span>
                                                Terisi ({{ $desa->total_input }} Data)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[9px] font-black bg-orange-50 text-orange-500 uppercase italic ring-4 ring-orange-100/30">
                                                ○ Belum Input
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-5 text-right pr-12">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.atur-form', $desa->id) }}" 
                                        class="px-4 py-2.5 bg-slate-100 hover:bg-[#f59e0b] hover:text-white text-slate-500 text-[9px] font-black rounded-xl transition-all uppercase italic shadow-sm">
                                            Atur
                                        </a>
                                        {{-- Link Entri otomatis mengikuti tahun yang sedang dipilih --}}
                                        <a :href="'{{ url('/admin/entri') }}/' + '{{ $desa->id }}' + '?tahun=' + tahun" 
                                        class="px-6 py-2.5 bg-[#1e3a8a] hover:bg-blue-700 text-white text-[9px] font-black rounded-xl shadow-lg shadow-blue-900/20 transition-all uppercase italic transform hover:scale-105 active:scale-95">
                                            Entri Data
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex justify-between items-center px-8">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">Total {{ count($desas) }} Wilayah Desa</p>
                <div class="flex gap-4">
                     <span class="flex items-center gap-2 text-[9px] font-black text-emerald-600 uppercase italic"><span class="w-2 h-2 bg-emerald-500 rounded-full"></span> Data Aman</span>
                     <span class="flex items-center gap-2 text-[9px] font-black text-orange-500 uppercase italic"><span class="w-2 h-2 bg-orange-400 rounded-full"></span> Perlu Update</span>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
        select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }
    </style>
</x-app-layout>