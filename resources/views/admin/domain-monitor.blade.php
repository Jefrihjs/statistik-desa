<x-app-layout>
    <div class="py-12 px-4 md:px-6 bg-[#f8fafc] min-h-screen" 
         x-data="{ 
            selected: null, 
            search: '', 
            kecamatan: 'SEMUA',
            shouldShow(namaDesa, namaKecamatan) {
                const matchSearch = (namaDesa || '').toLowerCase().includes(this.search.toLowerCase());
                const dataKec = (namaKecamatan || '').toUpperCase();
                const matchKecamatan = this.kecamatan === 'SEMUA' || dataKec.includes(this.kecamatan.toUpperCase());
                return matchSearch && matchKecamatan;
            }
         }">
        
        <div class="max-w-5xl mx-auto text-left">
            
            {{-- HEADER SECTION --}}
            <div class="mb-6 flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 gap-4">
                <div class="text-left">
                    <h2 class="text-3xl font-black text-[#1e3a8a] tracking-tighter uppercase italic leading-none text-left">Radar Domain Desa</h2>
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-[0.3em] mt-2 italic text-left text-left">Kabupaten Belitung Timur • Monitoring Infrastruktur</p>
                </div>
                <div class="bg-blue-50 px-4 py-2 rounded-2xl border border-blue-100">
                    <span class="text-[10px] font-black text-blue-600 uppercase italic">{{ $domains->count() }} TOTAL DOMAIN</span>
                </div>
            </div>

            {{-- SEARCH & FILTER --}}
            <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" x-model="search" placeholder="CARI NAMA DESA..." 
                       class="w-full px-6 py-4 bg-white border-none rounded-3xl shadow-sm focus:ring-2 focus:ring-blue-500 font-black italic text-xs uppercase tracking-widest text-slate-700 text-left">

                <select x-model="kecamatan" 
                        class="w-full px-6 py-4 bg-white border-none rounded-3xl shadow-sm focus:ring-2 focus:ring-blue-500 font-black italic text-xs uppercase tracking-widest text-slate-700 cursor-pointer text-left">
                    <option value="SEMUA">-- SEMUA KECAMATAN --</option>
                    <option value="MANGGAR">MANGGAR</option>
                    <option value="GANTUNG">GANTUNG</option>
                    <option value="KELAPA KAMPIT">KELAPA KAMPIT</option>
                    <option value="DAMAR">DAMAR</option>
                    <option value="DENDANG">DENDANG</option>
                    <option value="SIMPANG PESAK">SIMPANG PESAK</option>
                    <option value="SIMPANG RENGGIANG">SIMPANG RENGGIANG</option>
                </select>
            </div>

            {{-- LIST ACCORDION --}}
            <div class="space-y-4">
                @foreach($domains as $d)
                <div x-show="shouldShow('{{ $d->desa->nama_desa }}', '{{ $d->desa->kecamatan }}')"
                     x-transition.fade
                     class="bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:border-blue-400 transition-all duration-300 overflow-hidden group">
                    
                    {{-- BARIS UTAMA --}}
                    <div class="p-6 cursor-pointer flex flex-wrap md:flex-nowrap items-center justify-between gap-4" 
                         @click="selected !== {{ $d->id }} ? selected = {{ $d->id }} : selected = null">
                        
                        <div class="flex items-center gap-5 flex-1 min-w-[250px] text-left">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black italic text-white shadow-lg text-xl {{ $d->status == 'Sehat' ? 'bg-emerald-500' : ($d->status == 'Expired' ? 'bg-red-600' : 'bg-amber-500 animate-pulse') }}">
                                {{ substr($d->desa->nama_desa, 0, 1) }}
                            </div>
                            
                            <div class="text-left">
                                <h3 class="font-black text-slate-800 uppercase italic tracking-tight text-lg group-hover:text-blue-600 transition-colors">
                                    {{ $d->domain_name }}
                                </h3>
                                <p class="text-[9px] font-black text-slate-400 uppercase italic tracking-widest">
                                    Desa {{ $d->desa->nama_desa }} • {{ $d->desa->kecamatan }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 md:gap-8">
                            <div class="text-right">
                                <p class="text-[8px] font-black text-slate-300 uppercase leading-none mb-1 text-right">Sisa Hari</p>
                                <p class="text-2xl font-black tracking-tighter {{ $d->status == 'Kritis' ? 'text-amber-500' : ($d->status == 'Expired' ? 'text-red-600' : 'text-[#1e3a8a]') }} leading-none">
                                    {{ $d->days_left }}
                                </p>
                            </div>
                            <div :class="selected === {{ $d->id }} ? 'rotate-180' : ''" class="text-slate-300 transition-transform duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL PANEL --}}
                    <div x-show="selected === {{ $d->id }}" x-cloak class="px-8 pb-8 pt-2 border-t border-slate-50 bg-[#fafcfe] text-left">
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="space-y-3">
                                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1 text-left">Dibuat (Created)</p>
                                    <p class="font-bold text-slate-700 text-xs italic">{{ $d->created_date ? \Carbon\Carbon::parse($d->created_date)->translatedFormat('d F Y, H:i') : '-' }}</p>
                                </div>
                                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1 text-left">Tanggal Kadaluarsa</p>
                                    <p class="font-bold text-red-600 text-xs italic">{{ \Carbon\Carbon::parse($d->expiry_date)->translatedFormat('d F Y') }}</p>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm text-left">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1">IP Address / Provider</p>
                                    <p class="font-bold text-blue-800 text-xs italic">
                                        {{ $d->ip_address ?? '103.xxx.xxx.xxx' }}
                                    </p>
                                </div>
                                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm text-left">
                                    <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Pemeriksaan Sistem</p>
                                    <p class="font-bold text-slate-500 text-[10px] italic">
                                        {{-- Menggunakan last_checked_at untuk status crawler --}}
                                        {{ $d->last_checked_at ? \Carbon\Carbon::parse($d->last_checked_at)->diffForHumans() : '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                                <p class="text-[8px] font-black text-slate-400 uppercase mb-2 text-left">Nama Server (NS)</p>
                                <div class="space-y-2">
                                    @php $nsservers = explode("\n", $d->nameservers); @endphp
                                    @foreach($nsservers as $ns)
                                        @if(trim($ns))
                                        <div class="flex items-center gap-2 text-[10px] font-bold text-slate-700 italic bg-slate-50 p-2 rounded-lg border border-slate-100">
                                            <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            {{ trim($ns) }}
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- AKSI --}}
                        <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-slate-100">
                             <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full {{ $d->status == 'Sehat' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                <p class="text-[10px] font-black uppercase italic text-slate-500">Status: {{ $d->status }}</p>
                             </div>
                             
                            <a href="https://api.whatsapp.com/send?text={{ rawurlencode("Halo Admin Desa " . $d->desa->nama_desa . ",\n\nDomain *" . $d->domain_name . "* berstatus *" . $d->status . "* (" . $d->days_left . " hari lagi).\n\nMohon segera dicek kembali.\nTerima kasih.") }}" target="_blank" class="bg-[#25D366] text-white px-6 py-3 rounded-2xl font-black uppercase italic text-[10px] shadow-lg hover:-translate-y-1 transition-all flex items-center gap-2">
                                Kirim Pengingat WA
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-10 text-center text-slate-400 text-[9px] font-bold uppercase tracking-[0.4em] italic">
                SiCANTIK v1.0 • Kabupaten Belitung Timur
            </div>
        </div>
    </div>
</x-app-layout>