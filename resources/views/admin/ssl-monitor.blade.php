<x-app-layout>
    <div class="py-12 px-4 bg-slate-50 min-h-screen text-left">
        <div class="max-w-7xl mx-auto">
            
            {{-- HEADER --}}
            <div class="mb-8">
                <h2 class="text-3xl font-black text-slate-800 tracking-tighter uppercase italic">Monitoring Enkripsi SSL Desa</h2>
                <p class="text-slate-500 font-bold text-sm tracking-widest uppercase">Pusat Keamanan Siber & Protokol HTTPS Wilayah Belitung Timur</p>
            </div>

            {{-- TABLE DETAIL STATUS SSL --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 text-[10px] font-black uppercase tracking-widest italic">
                                <th class="p-4">Nama Desa</th>
                                <th class="p-4">URL Website</th>
                                <th class="p-4">Status SSL</th>
                                <th class="p-4">Sisa Masa Aktif</th>
                                <th class="p-4">Tanggal Kedaluwarsa</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-bold text-slate-700 divide-y divide-slate-50">
                            @foreach($domains as $domain)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="p-4 uppercase font-black text-slate-800">
                                    {{ $domain->desa ? $domain->desa->nama_desa : $domain->nama_desa }}
                                </td>
                                <td class="p-4 text-blue-600 lowercase font-mono">
                                    <a href="https://{{ $domain->domain_name }}" target="_blank" class="hover:underline">
                                        https://{{ $domain->domain_name }}
                                    </a>
                                </td>
                                <td class="p-4">
                                    @if($domain->days_left > 30)
                                        <span class="bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-xl font-black uppercase text-[9px] tracking-wider">🔒 SECURED (AMAN)</span>
                                    @else
                                        <span class="bg-rose-50 text-rose-600 px-3 py-1.5 rounded-xl font-black uppercase text-[9px] tracking-wider animate-pulse">🚨 TINGKAT KRITIS</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    @if($domain->days_left > 30)
                                        <span class="text-emerald-600 font-black">{{ $domain->days_left }} Hari Lagi</span>
                                    @else
                                        <span class="text-rose-600 font-black">{{ $domain->days_left }} Hari (Butuh Re-issue!)</span>
                                    @endif
                                </td>
                                <td class="p-4 text-slate-500 font-mono">
                                    {{ $domain->expiry_date ? \Carbon\Carbon::parse($domain->expiry_date)->format('d-m-Y') : '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>