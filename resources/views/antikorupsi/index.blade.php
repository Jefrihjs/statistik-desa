<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indikator Desa Antikorupsi</title>
    <!-- Memanggil Tailwind CSS bawaan Laravel -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-transparent font-sans antialiased py-6">
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-slate-100 overflow-hidden">
            <!-- NAVIGATION TABS -->
            <div class="flex flex-wrap justify-center gap-2 border-b border-slate-200 pb-6 mb-6">
                <button class="tab-btn active px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest bg-[#1e3a8a] text-white transition-all shadow-md" data-target="tatalaksana">Tata Laksana</button>
                <button class="tab-btn px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 bg-slate-50 hover:bg-slate-100 transition-all border border-slate-200" data-target="pengawasan">Pengawasan</button>
                <button class="tab-btn px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 bg-slate-50 hover:bg-slate-100 transition-all border border-slate-200" data-target="pelayanan">Pelayanan Publik</button>
                <button class="tab-btn px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 bg-slate-50 hover:bg-slate-100 transition-all border border-slate-200" data-target="partisipasi">Partisipasi</button>
                <button class="tab-btn px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-500 bg-slate-50 hover:bg-slate-100 transition-all border border-slate-200" data-target="kearifan">Kearifan Lokal</button>
            </div>

            <!-- CONTENT TABS -->
            <div>
                
                <!-- 1. TATA LAKSANA -->
                <div id="tatalaksana" class="tab-content block">
                    @php $i = 1; @endphp
                    @forelse($data['tatalaksana'] ?? [] as $grup => $items)
                    <div class="border border-slate-200 rounded-xl mb-3 overflow-hidden shadow-sm">
                        <button class="accordion-btn w-full flex justify-between items-center bg-slate-50 hover:bg-slate-100 py-3 px-5 transition-colors" data-target="tata-{{ $i }}">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#1e3a8a] text-left leading-tight pr-4">{{ $grup }}</span>
                            <svg class="w-4 h-4 text-slate-400 transform transition-transform duration-300 icon-arrow flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="tata-{{ $i }}" class="accordion-body hidden bg-white border-t border-slate-200">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-slate-600">
                                    <tbody>
                                        @foreach($items as $item)
                                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50">
                                            <td class="px-4 py-3 w-10 text-center font-bold text-slate-400 text-xs">{{ $item->no_urut }}</td>
                                            <td class="px-2 py-3 w-8 text-center font-medium text-xs">{{ $item->sub }}</td>
                                            <td class="px-3 py-3 font-bold uppercase text-[10px] text-slate-700 leading-tight">{{ $item->nama_dokumen }}</td>
                                            <td class="px-4 py-3 text-center w-28">
                                                @if($item->link_drive)
                                                    <a href="{{ $item->link_drive }}" target="_blank" class="inline-block bg-[#58896a] hover:bg-emerald-700 text-white text-[9px] font-black uppercase tracking-widest py-1.5 px-3 rounded-lg shadow-sm transition-all hover:-translate-y-0.5">Lihat</a>
                                                @else
                                                    <span class="inline-block bg-slate-100 text-slate-400 text-[9px] font-bold uppercase tracking-widest py-1.5 px-3 rounded-lg">Kosong</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @php $i++; @endphp
                    @empty
                        <div class="text-center py-8 bg-slate-50 rounded-xl text-slate-400 text-[10px] font-bold uppercase tracking-widest">Belum ada dokumen Tata Laksana.</div>
                    @endforelse
                </div>

                <!-- 2. PENGAWASAN -->
                <div id="pengawasan" class="tab-content hidden">
                    @php $i = 1; @endphp
                    @forelse($data['pengawasan'] ?? [] as $grup => $items)
                    <div class="border border-slate-200 rounded-xl mb-3 overflow-hidden shadow-sm">
                        <button class="accordion-btn w-full flex justify-between items-center bg-slate-50 hover:bg-slate-100 py-3 px-5 transition-colors" data-target="peng-{{ $i }}">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#1e3a8a] text-left leading-tight pr-4">{{ $grup }}</span>
                            <svg class="w-4 h-4 text-slate-400 transform transition-transform duration-300 icon-arrow flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="peng-{{ $i }}" class="accordion-body hidden bg-white border-t border-slate-200">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-slate-600">
                                    <tbody>
                                        @foreach($items as $item)
                                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50">
                                            <td class="px-4 py-3 w-10 text-center font-bold text-slate-400 text-xs">{{ $item->no_urut }}</td>
                                            <td class="px-2 py-3 w-8 text-center font-medium text-xs">{{ $item->sub }}</td>
                                            <td class="px-3 py-3 font-bold uppercase text-[10px] text-slate-700 leading-tight">{{ $item->nama_dokumen }}</td>
                                            <td class="px-4 py-3 text-center w-28">
                                                @if($item->link_drive)
                                                    <a href="{{ $item->link_drive }}" target="_blank" class="inline-block bg-[#58896a] hover:bg-emerald-700 text-white text-[9px] font-black uppercase tracking-widest py-1.5 px-3 rounded-lg shadow-sm transition-all hover:-translate-y-0.5">Lihat</a>
                                                @else
                                                    <span class="inline-block bg-slate-100 text-slate-400 text-[9px] font-bold uppercase tracking-widest py-1.5 px-3 rounded-lg">Kosong</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @php $i++; @endphp
                    @empty
                        <div class="text-center py-8 bg-slate-50 rounded-xl text-slate-400 text-[10px] font-bold uppercase tracking-widest">Belum ada dokumen Pengawasan.</div>
                    @endforelse
                </div>

                <!-- 3. PELAYANAN PUBLIK -->
                <div id="pelayanan" class="tab-content hidden">
                    @php $i = 1; @endphp
                    @forelse($data['pelayanan'] ?? [] as $grup => $items)
                    <div class="border border-slate-200 rounded-xl mb-3 overflow-hidden shadow-sm">
                        <button class="accordion-btn w-full flex justify-between items-center bg-slate-50 hover:bg-slate-100 py-3 px-5 transition-colors" data-target="layan-{{ $i }}">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#1e3a8a] text-left leading-tight pr-4">{{ $grup }}</span>
                            <svg class="w-4 h-4 text-slate-400 transform transition-transform duration-300 icon-arrow flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="layan-{{ $i }}" class="accordion-body hidden bg-white border-t border-slate-200">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-slate-600">
                                    <tbody>
                                        @foreach($items as $item)
                                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50">
                                            <td class="px-4 py-3 w-10 text-center font-bold text-slate-400 text-xs">{{ $item->no_urut }}</td>
                                            <td class="px-2 py-3 w-8 text-center font-medium text-xs">{{ $item->sub }}</td>
                                            <td class="px-3 py-3 font-bold uppercase text-[10px] text-slate-700 leading-tight">{{ $item->nama_dokumen }}</td>
                                            <td class="px-4 py-3 text-center w-28">
                                                @if($item->link_drive)
                                                    <a href="{{ $item->link_drive }}" target="_blank" class="inline-block bg-[#58896a] hover:bg-emerald-700 text-white text-[9px] font-black uppercase tracking-widest py-1.5 px-3 rounded-lg shadow-sm transition-all hover:-translate-y-0.5">Lihat</a>
                                                @else
                                                    <span class="inline-block bg-slate-100 text-slate-400 text-[9px] font-bold uppercase tracking-widest py-1.5 px-3 rounded-lg">Kosong</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @php $i++; @endphp
                    @empty
                        <div class="text-center py-8 bg-slate-50 rounded-xl text-slate-400 text-[10px] font-bold uppercase tracking-widest">Belum ada dokumen Pelayanan Publik.</div>
                    @endforelse
                </div>

                <!-- 4. PARTISIPASI -->
                <div id="partisipasi" class="tab-content hidden">
                    @php $i = 1; @endphp
                    @forelse($data['partisipasi'] ?? [] as $grup => $items)
                    <div class="border border-slate-200 rounded-xl mb-3 overflow-hidden shadow-sm">
                        <button class="accordion-btn w-full flex justify-between items-center bg-slate-50 hover:bg-slate-100 py-3 px-5 transition-colors" data-target="part-{{ $i }}">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#1e3a8a] text-left leading-tight pr-4">{{ $grup }}</span>
                            <svg class="w-4 h-4 text-slate-400 transform transition-transform duration-300 icon-arrow flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="part-{{ $i }}" class="accordion-body hidden bg-white border-t border-slate-200">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-slate-600">
                                    <tbody>
                                        @foreach($items as $item)
                                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50">
                                            <td class="px-4 py-3 w-10 text-center font-bold text-slate-400 text-xs">{{ $item->no_urut }}</td>
                                            <td class="px-2 py-3 w-8 text-center font-medium text-xs">{{ $item->sub }}</td>
                                            <td class="px-3 py-3 font-bold uppercase text-[10px] text-slate-700 leading-tight">{{ $item->nama_dokumen }}</td>
                                            <td class="px-4 py-3 text-center w-28">
                                                @if($item->link_drive)
                                                    <a href="{{ $item->link_drive }}" target="_blank" class="inline-block bg-[#58896a] hover:bg-emerald-700 text-white text-[9px] font-black uppercase tracking-widest py-1.5 px-3 rounded-lg shadow-sm transition-all hover:-translate-y-0.5">Lihat</a>
                                                @else
                                                    <span class="inline-block bg-slate-100 text-slate-400 text-[9px] font-bold uppercase tracking-widest py-1.5 px-3 rounded-lg">Kosong</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @php $i++; @endphp
                    @empty
                        <div class="text-center py-8 bg-slate-50 rounded-xl text-slate-400 text-[10px] font-bold uppercase tracking-widest">Belum ada dokumen Partisipasi.</div>
                    @endforelse
                </div>

                <!-- 5. KEARIFAN LOKAL -->
                <div id="kearifan" class="tab-content hidden">
                    @php $i = 1; @endphp
                    @forelse($data['kearifan'] ?? [] as $grup => $items)
                    <div class="border border-slate-200 rounded-xl mb-3 overflow-hidden shadow-sm">
                        <button class="accordion-btn w-full flex justify-between items-center bg-slate-50 hover:bg-slate-100 py-3 px-5 transition-colors" data-target="kearif-{{ $i }}">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-[#1e3a8a] text-left leading-tight pr-4">{{ $grup }}</span>
                            <svg class="w-4 h-4 text-slate-400 transform transition-transform duration-300 icon-arrow flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="kearif-{{ $i }}" class="accordion-body hidden bg-white border-t border-slate-200">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-slate-600">
                                    <tbody>
                                        @foreach($items as $item)
                                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50">
                                            <td class="px-4 py-3 w-10 text-center font-bold text-slate-400 text-xs">{{ $item->no_urut }}</td>
                                            <td class="px-2 py-3 w-8 text-center font-medium text-xs">{{ $item->sub }}</td>
                                            <td class="px-3 py-3 font-bold uppercase text-[10px] text-slate-700 leading-tight">{{ $item->nama_dokumen }}</td>
                                            <td class="px-4 py-3 text-center w-28">
                                                @if($item->link_drive)
                                                    <a href="{{ $item->link_drive }}" target="_blank" class="inline-block bg-[#58896a] hover:bg-emerald-700 text-white text-[9px] font-black uppercase tracking-widest py-1.5 px-3 rounded-lg shadow-sm transition-all hover:-translate-y-0.5">Lihat</a>
                                                @else
                                                    <span class="inline-block bg-slate-100 text-slate-400 text-[9px] font-bold uppercase tracking-widest py-1.5 px-3 rounded-lg">Kosong</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @php $i++; @endphp
                    @empty
                        <div class="text-center py-8 bg-slate-50 rounded-xl text-slate-400 text-[10px] font-bold uppercase tracking-widest">Belum ada dokumen Kearifan Lokal.</div>
                    @endforelse
                </div>

            </div>
        </div>


    <!-- SCRIPT LOGIKA ACCORDION & TABS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // Logika Tab Navigation
            const tabBtns = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Reset semua button
                    tabBtns.forEach(b => {
                        b.classList.remove('bg-[#1e3a8a]', 'text-white', 'active', 'shadow-md');
                        b.classList.add('bg-slate-50', 'text-slate-500', 'border', 'border-slate-200');
                    });
                    
                    // Sembunyikan konten
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                        content.classList.remove('block');
                    });

                    // Set button aktif
                    btn.classList.add('bg-[#1e3a8a]', 'text-white', 'active', 'shadow-md');
                    btn.classList.remove('bg-slate-50', 'text-slate-500', 'border', 'border-slate-200');

                    // Tampilkan konten target
                    const targetId = btn.getAttribute('data-target');
                    document.getElementById(targetId).classList.remove('hidden');
                    document.getElementById(targetId).classList.add('block');
                });
            });

            // Logika Accordion (Drop-down)
            const accordionBtns = document.querySelectorAll('.accordion-btn');

            accordionBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetId = btn.getAttribute('data-target');
                    const body = document.getElementById(targetId);
                    const icon = btn.querySelector('.icon-arrow');

                    if (body.classList.contains('hidden')) {
                        body.classList.remove('hidden');
                        icon.classList.add('rotate-180');
                    } else {
                        body.classList.add('hidden');
                        icon.classList.remove('rotate-180');
                    }
                });
            });

        });
    </script>
</body>
</html>