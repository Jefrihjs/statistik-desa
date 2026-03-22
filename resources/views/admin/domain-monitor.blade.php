<x-app-layout>
    <div class="py-12 px-4 md:px-6 bg-[#f8fafc] min-h-screen" 
         x-data="{ 
            selected: null, 
            search: '', 
            kecamatan: 'SEMUA',
            // Fungsi pencocokan yang lebih fleksibel
            shouldShow(namaDesa, namaKecamatan) {
                // 1. Bersihkan input search
                const matchSearch = namaDesa.toLowerCase().includes(this.search.toLowerCase());
                
                // 2. Logika Kecamatan: Kita buat 'MANGGAR' cocok dengan 'KECAMATAN MANGGAR'
                const filterKec = this.kecamatan.toUpperCase();
                const dataKec = namaKecamatan.toUpperCase();
                
                // Jika pilih SEMUA, tampilkan semua. 
                // Jika tidak, cek apakah nama kecamatan di database mengandung kata dari dropdown
                const matchKecamatan = this.kecamatan === 'SEMUA' || dataKec.includes(filterKec);
                
                return matchSearch && matchKecamatan;
            }
        }">
        
        <div class="max-w-5xl mx-auto">
            
            {{-- HEADER SECTION --}}
            <div class="mb-6 flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-[#1e3a8a] tracking-tighter uppercase italic leading-none">Radar Domain Desa</h2>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em] mt-2 italic">Kabupaten Belitung Timur • Monitoring Pusat</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-blue-50 px-4 py-2 rounded-2xl border border-blue-100">
                        <span class="text-[10px] font-black text-blue-600 uppercase italic">{{ $domains->count() }} TOTAL DOMAIN</span>
                    </div>
                </div>
            </div>

            {{-- FILTER & SEARCH SECTION --}}
            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" x-model="search" placeholder="CARI NAMA DESA..." 
                           class="w-full pl-12 pr-4 py-4 bg-white border-none rounded-3xl shadow-sm focus:ring-2 focus:ring-blue-500 font-black italic text-xs uppercase tracking-widest text-slate-700">
                </div>

                <div class="relative">
                    <select x-model="kecamatan" 
                            class="w-full px-6 py-4 bg-white border-none rounded-3xl shadow-sm focus:ring-2 focus:ring-blue-500 font-black italic text-xs uppercase tracking-widest text-slate-700 cursor-pointer appearance-none">
                        <option value="SEMUA">-- SEMUA KECAMATAN --</option>
                        <option value="MANGGAR">KECAMATAN MANGGAR</option>
                        <option value="GANTUNG">KECAMATAN GANTUNG</option>
                        <option value="KELAPA KAMPIT">KECAMATAN KELAPA KAMPIT</option>
                        <option value="DAMAR">KECAMATAN DAMAR</option>
                        <option value="DENDANG">KECAMATAN DENDANG</option>
                        <option value="SIMPANG PESAK">KECAMATAN SIMPANG PESAK</option>
                        <option value="SIMPANG RENGGIANG">KECAMATAN SIMPANG RENGGIANG</option>
                    </select>
                </div>
            </div>

            {{-- LIST ACCORDION --}}
            <div class="space-y-4">
                @foreach($domains as $d)
                {{-- Gunakan x-show untuk filter instan --}}
                <div x-show="shouldShow('{{ $d->desa->nama_desa }}', '{{ $d->desa->kecamatan }}')"
                     x-transition.fade
                     class="bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:border-blue-400 hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    
                    {{-- BARIS UTAMA --}}
                    <div class="p-6 cursor-pointer flex flex-wrap md:flex-nowrap items-center justify-between gap-4" 
                         @click="selected !== {{ $d->id }} ? selected = {{ $d->id }} : selected = null">
                        
                        <div class="flex items-center gap-5 flex-1 min-w-[250px]">
                            {{-- Icon Avatar --}}
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black italic text-white shadow-lg text-xl transition-transform group-hover:scale-110 {{ $d->status == 'Sehat' ? 'bg-emerald-500' : ($d->status == 'Expired' ? 'bg-red-600' : 'bg-amber-500 animate-pulse') }}">
                                {{ substr($d->desa->nama_desa, 0, 1) }}
                            </div>
                            
                            <div>
                                <h3 class="font-black text-slate-800 uppercase italic tracking-tight leading-none text-lg group-hover:text-blue-600 transition-colors">
                                    {{ $d->domain_name }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <p class="text-[9px] font-black text-slate-400 uppercase italic tracking-widest">
                                        {{ $d->desa->nama_desa }}
                                    </p>
                                    <span class="text-[8px] text-slate-300">•</span>
                                    <p class="text-[8px] font-bold text-blue-500 uppercase tracking-tighter">
                                        {{ $d->desa->kecamatan }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 md:gap-8">
                            <div class="text-right">
                                <p class="text-[8px] font-black text-slate-300 uppercase leading-none mb-1">Status</p>
                                <span class="text-[10px] font-black uppercase {{ $d->status == 'Sehat' ? 'text-emerald-500' : ($d->status == 'Expired' ? 'text-red-600' : 'text-amber-500') }} italic">
                                    {{ $d->status }}
                                </span>
                            </div>

                            {{-- Sisa Hari --}}
                            <div class="bg-slate-50 px-6 py-3 rounded-2xl text-right min-w-[100px] border border-slate-100 shadow-inner group-hover:bg-blue-50 transition-colors">
                                <p class="text-2xl font-black tracking-tighter {{ $d->status == 'Kritis' ? 'text-amber-500' : ($d->status == 'Expired' ? 'text-red-600' : 'text-[#1e3a8a]') }} leading-none">
                                    {{ $d->days_left }}
                                </p>
                                <p class="text-[8px] font-black text-slate-400 uppercase italic text-center">Hari lagi</p>
                            </div>

                            {{-- Arrow Icon --}}
                            <div class="text-slate-300 group-hover:text-blue-500 transition-transform duration-300" 
                                 :class="selected === {{ $d->id }} ? 'rotate-180' : ''">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL PANEL --}}
                    <div x-show="selected === {{ $d->id }}" x-cloak
                         class="px-8 pb-8 pt-4 border-t border-slate-50 bg-[#fafcfe]">
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                                <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Berakhir Pada</p>
                                <p class="font-bold text-red-600 text-xs italic uppercase">
                                    {{ \Carbon\Carbon::parse($d->expiry_date)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm col-span-2">
                                <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Name Servers</p>
                                <p class="font-bold text-blue-800 text-[10px] break-all italic">
                                    {{ $d->nameservers ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        {{-- WHATSAPP ACTION --}}
                        <div class="flex justify-end">
                             @php
                                $pesanWa = "Halo Admin Desa " . $d->desa->nama_desa . ", domain *" . $d->domain_name . "* berstatus *" . $d->status . "* (" . $d->days_left . " hari lagi). Mohon segera diperpanjang.";
                                $waLink = "https://api.whatsapp.com/send?text=" . rawurlencode($pesanWa);
                            @endphp
                            <a href="{{ $waLink }}" target="_blank" class="flex items-center gap-2 bg-[#25D366] text-white px-6 py-3 rounded-2xl font-black uppercase italic text-[10px] shadow-lg hover:-translate-y-1 transition-all">
                                KIRIM PENGINGAT WHATSAPP
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-10 text-center text-slate-400 text-[9px] font-bold uppercase tracking-[0.4em] italic">
                Radar SiCANTIK v1.0 • Sistem Informasi Statistik Terintegrasi
            </div>
        </div>
    </div>
</x-app-layout>