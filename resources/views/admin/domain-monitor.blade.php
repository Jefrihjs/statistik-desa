<x-app-layout>
    <div class="py-12 px-4 md:px-6 bg-[#f8fafc] min-h-screen" x-data="{ selected: null }">
        <div class="max-w-5xl mx-auto">
            
            {{-- HEADER SECTION --}}
            <div class="mb-10 flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-[#1e3a8a] tracking-tighter uppercase italic leading-none">Radar Domain Desa</h2>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em] mt-2 italic">Kabupaten Belitung Timur • SiCANTIK Monitoring</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden md:block">
                        <p class="text-[8px] font-black text-slate-300 uppercase italic">Update Terakhir</p>
                        <p class="text-[11px] font-black text-[#f59e0b] uppercase">{{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                    </div>
                    <div class="bg-blue-50 px-4 py-2 rounded-2xl border border-blue-100">
                        <span class="text-[10px] font-black text-blue-600 uppercase italic">{{ $domains->count() }} DOMAIN</span>
                    </div>
                </div>
            </div>

            {{-- LIST ACCORDION --}}
            <div class="space-y-4">
                @foreach($domains as $d)
                <div class="bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:border-blue-400 hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    
                    {{-- BARIS UTAMA (Tampil 2 Baris) --}}
                    <div class="p-6 cursor-pointer flex flex-wrap md:flex-nowrap items-center justify-between gap-4" 
                         @click="selected !== {{ $d->id }} ? selected = {{ $d->id }} : selected = null">
                        
                        <div class="flex items-center gap-5 flex-1 min-w-[250px]">
                            {{-- Icon Avatar --}}
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black italic text-white shadow-lg text-xl transition-transform group-hover:scale-110 {{ $d->status == 'Sehat' ? 'bg-emerald-500' : ($d->status == 'Expired' ? 'bg-red-600' : 'bg-amber-500 animate-pulse') }}">
                                {{ substr($d->desa->nama_desa, 0, 1) }}
                            </div>
                            
                            <div>
                                {{-- Baris 1: Domain --}}
                                <h3 class="font-black text-slate-800 uppercase italic tracking-tight leading-none text-lg group-hover:text-blue-600 transition-colors">
                                    {{ $d->domain_name }}
                                </h3>
                                {{-- Baris 2: Nama Desa --}}
                                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1.5 italic tracking-widest">
                                    Desa {{ $d->desa->nama_desa }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 md:gap-8">
                            {{-- Badge Peringatan --}}
                            @if($d->status == 'Kritis' || $d->status == 'Expired')
                            <div class="hidden lg:flex items-center gap-2 bg-red-50 px-3 py-1.5 rounded-full border border-red-100 animate-pulse">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                </span>
                                <span class="text-[9px] font-black text-red-600 uppercase italic tracking-tighter">Perhatian Khusus</span>
                            </div>
                            @endif

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
                                <p class="text-[8px] font-black text-slate-400 uppercase italic">Hari lagi</p>
                            </div>

                            {{-- Arrow Icon --}}
                            <div class="text-slate-300 group-hover:text-blue-500 transition-transform duration-300" 
                                 :class="selected === {{ $d->id }} ? 'rotate-180' : ''">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL PANEL (Expands downwards) --}}
                    <div x-show="selected === {{ $d->id }}" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 max-h-0"
                         x-transition:enter-end="opacity-100 max-h-screen"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 max-h-screen"
                         x-transition:leave-end="opacity-0 max-h-0"
                         class="px-8 pb-8 pt-4 border-t border-slate-50 bg-[#fafcfe]" x-cloak>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            {{-- Info Dibuat --}}
                            <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                                <div class="p-3 bg-blue-50 rounded-xl text-blue-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Terdaftar Sejak</p>
                                    <p class="font-bold text-slate-700 italic text-xs uppercase">
                                        {{ $d->created_date ? \Carbon\Carbon::parse($d->created_date)->translatedFormat('d F Y') : 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Info Umur --}}
                            <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                                <div class="p-3 bg-indigo-50 rounded-xl text-indigo-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Umur Domain</p>
                                    <p class="font-bold text-[#1e3a8a] italic text-xs uppercase">
                                        {{ $d->created_date ? \Carbon\Carbon::parse($d->created_date)->diffInYears(now()) . ' Tahun Berjalan' : '-' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Info Kadaluarsa --}}
                            <div class="bg-white p-5 rounded-[1.5rem] border border-slate-100 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
                                <div class="p-3 bg-red-50 rounded-xl text-red-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Tanggal Expired</p>
                                    <p class="font-bold text-red-600 italic text-xs uppercase">
                                        {{ \Carbon\Carbon::parse($d->expiry_date)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- NAME SERVERS / INFRASTRUCTURE --}}
                        <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-inner group/ns hover:border-emerald-300 transition-all mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.3em]">Technical Infrastructure</p>
                                @if(str_contains(strtolower($d->nameservers), 'layanan.go.id'))
                                    <span class="inline-block text-[8px] font-black bg-emerald-600 text-white px-3 py-1 rounded-full italic uppercase shadow-lg shadow-emerald-500/30 tracking-tighter">✓ Terhubung Server Komdigi</span>
                                @endif
                            </div>
                            <div class="text-[#1e3a8a] text-[11px] font-bold italic break-all bg-slate-50 p-5 rounded-2xl shadow-inner group-hover/ns:bg-emerald-50 transition-all border border-transparent group-hover/ns:border-emerald-100">
                                {{ $d->nameservers ?? 'Data belum sinkron / Gunakan Whois manual' }}
                            </div>
                            <div class="mt-3 text-right">
                                <p class="text-[8px] font-black text-slate-300 uppercase italic">Pengecekan Terakhir: {{ $d->last_checked_at ? \Carbon\Carbon::parse($d->last_checked_at)->diffForHumans() : '-' }}</p>
                            </div>
                        </div>

                        {{-- WHATSAPP ACTION --}}
                        <div class="flex justify-center md:justify-end">
                            @php
                                $pesanWa = "Halo Admin Desa " . $d->desa->nama_desa . ",\n\nKami dari Dinas Kominfo/Admin Kabupaten menginformasikan bahwa domain *" . $d->domain_name . "* saat ini berstatus *" . $d->status . "* dengan sisa masa aktif *" . $d->days_left . " hari* lagi (Kadaluarsa: " . \Carbon\Carbon::parse($d->expiry_date)->format('d/m/Y') . ").\n\nMohon segera melakukan koordinasi untuk perpanjangan agar layanan informasi desa tetap berjalan lancar.\n\nTerima kasih.";
                                $waLink = "https://api.whatsapp.com/send?text=" . rawurlencode($pesanWa);
                            @endphp

                            <a href="{{ $waLink }}" target="_blank" class="flex items-center gap-3 bg-[#25D366] hover:bg-[#128C7E] text-white px-8 py-4 rounded-[1.5rem] font-black uppercase italic text-[11px] tracking-widest shadow-xl shadow-green-500/40 transition-all hover:-translate-y-2 active:scale-95 group">
                                <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                Kirim Notifikasi WA ke Desa
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