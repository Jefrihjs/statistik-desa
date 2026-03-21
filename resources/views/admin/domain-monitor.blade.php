<x-app-layout>
    <div class="py-12 px-6 bg-[#f8fafc] min-h-screen">
        <div class="max-w-5xl mx-auto">
            
            {{-- Header --}}
            <div class="mb-12 flex justify-between items-center bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <div>
                    <h2 class="text-3xl font-black text-[#1e3a8a] tracking-tighter uppercase italic leading-none">Radar Domain Desa</h2>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em] mt-2">Kabupaten Belitung Timur • SiCANTIK</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-slate-300 uppercase italic">Update:</span>
                    <span class="text-[11px] font-black text-[#f59e0b] uppercase ml-1">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
                </div>
            </div>

            {{-- List Expandable Cards (The Accordion) --}}
            <div class="space-y-4" x-data="{ selected: null }">
                @foreach($domains as $d)
                <div class="bg-white border border-slate-100 rounded-2xl shadow-sm hover:border-blue-500 hover:shadow-lg transition-all duration-300 overflow-hidden group">
                    
                    {{-- TAMPILAN AWAL (Hanya 2 Baris saat Tertutup) --}}
                    <div class="p-6 cursor-pointer flex items-center justify-between gap-6" 
                         @click="selected !== {{ $d->id }} ? selected = {{ $d->id }} : selected = null">
                        
                        {{-- Baris 1 & 2: Identitas Desa --}}
                        <div class="flex items-center gap-4 flex-1">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black italic text-white shadow-lg text-lg {{ $d->status == 'Sehat' ? 'bg-emerald-500' : ($d->status == 'Expired' ? 'bg-red-600' : 'bg-amber-500 animate-pulse') }}">
                                {{ substr($d->desa->nama_desa, 0, 1) }}
                            </div>
                            <div>
                                {{-- Baris 1 --}}
                                <h3 class="font-black text-slate-800 uppercase italic tracking-tight leading-none text-base group-hover:text-blue-600">{{ $d->domain_name }}</h3>
                                {{-- Baris 2 --}}
                                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1 italic tracking-wider">Desa {{ $d->desa->nama_desa }}</p>
                            </div>
                        </div>

                        {{-- Status & Sisa Hari --}}
                        <div class="flex items-center gap-5">
                            <div class="text-right">
                                <p class="text-[8px] font-black text-slate-300 uppercase leading-none mb-1">Status</p>
                                <span class="text-[10px] font-black uppercase {{ $d->status == 'Sehat' ? 'text-emerald-500' : ($d->status == 'Expired' ? 'text-red-600' : 'text-amber-500') }} italic">
                                    {{ $d->status }}
                                </span>
                            </div>
                            <div class="bg-slate-50 px-5 py-2.5 rounded-xl text-right min-w-[90px] border border-slate-100 shadow-inner group-hover:bg-blue-50">
                                <p class="text-[18px] font-black tracking-tighter {{ $d->status == 'Kritis' ? 'text-amber-500' : ($d->status == 'Expired' ? 'text-red-600' : 'text-[#1e3a8a]') }} leading-none">
                                    {{ $d->days_left }}
                                </p>
                                <p class="text-[8px] font-black text-slate-400 uppercase italic">Hari</p>
                            </div>
                            
                            {{-- Icon Panah Indikator --}}
                            <div class="text-slate-300 group-hover:text-blue-500 transition-transform" 
                                 :class="selected === {{ $d->id }} ? 'rotate-180' : ''">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL YANG MEMANJANG (Hanya Tampil saat Diklik) --}}
                    <div x-show="selected === {{ $d->id }}" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-screen"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 max-h-screen"
                         x-transition:leave-end="opacity-0 max-h-0"
                         class="px-8 pb-8 pt-2 border-t border-slate-100 bg-slate-50/50" x-cloak>
                        
                        {{-- Data Teknis (Created, Age, Updated) --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Dibuat Pada</p>
                                    <p class="font-bold text-slate-700 italic text-xs">
                                        {{ $d->created_date ? \Carbon\Carbon::parse($d->created_date)->format('d F Y') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Umur Domain</p>
                                    <p class="font-bold text-[#1e3a8a] italic text-xs">
                                        {{ $d->created_date ? \Carbon\Carbon::parse($d->created_date)->diffInYears(now()) . ' Tahun' : '-' }}
                                    </p>
                                </div>
                            </div>
                            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4 hover:border-blue-300 group">
                                <svg class="w-6 h-6 text-slate-300 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase">Perubahan Data</p>
                                    <p class="font-bold text-slate-700 italic text-xs">
                                        {{ $d->last_checked_at ? \Carbon\Carbon::parse($d->last_checked_at)->diffForHumans() : '-' }}
                                        <span class="text-[8px] font-black text-slate-300 ml-1">(WHOIS Update)</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Name Servers (Infrastructure) --}}
                        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-inner group hover:border-emerald-300">
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Infrastructure / Name Servers</p>
                                @if(str_contains($d->nameservers, 'layanan.go.id'))
                                    <span class="inline-block text-[8px] font-black bg-emerald-600 text-white px-2 py-0.5 rounded-lg italic uppercase shadow-lg shadow-emerald-500/30">✓ Gov Server Active</span>
                                @endif
                            </div>
                            <div class="text-[#1e3a8a] text-[11px] font-bold italic break-all bg-slate-50 p-4 rounded-xl shadow-inner group-hover:bg-emerald-50 transition-all">
                                {{ $d->nameservers ?? 'Belum terdeteksi / Check Manual' }}
                            </div>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>