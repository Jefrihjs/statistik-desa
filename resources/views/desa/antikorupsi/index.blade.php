<x-app-layout>
    @php
        $desaAktif = $desa ?? auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);
        $headerColor = $desaAktif->header_color ?? '#475569';
        $accentColor = $desaAktif->accent_color ?? '#064e3b';
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <div class="py-12 min-h-screen bg-slate-50">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10">

            
            <div class="mb-4">
                <a href="{{ route('desa.dashboard') }}"
                   class="inline-flex items-center gap-2 text-xs font-bold text-slate-400 hover:text-slate-700">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Hub Utama TARSIUS
                </a>
            </div>

            
            <div class="relative overflow-hidden rounded-[2.5rem] text-white p-8 lg:p-10 mb-8 shadow-sm"
                 style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }});">
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-white/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-white/10"></div>
                <div class="relative z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/80 mb-3">TARSIUS &bull; Modul Tata Kelola Desa</p>
                    <h1 class="text-3xl font-black uppercase tracking-tight">Dokumen Desa Antikorupsi</h1>
                    <p class="mt-3 text-sm text-white/85 max-w-3xl">Kelola dokumen dengan drag-drop untuk mengatur urutan dan penomoran otomatis.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 px-5 py-4 text-emerald-700 font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- LINK & EMBED ANTIKORUPSI --}}
            @php
                $antikorupsiUrl = url('/desa/' . $desaAktif->slug . '/antikorupsi');
            @endphp
            <div class="bg-white rounded-[2rem] border border-slate-200 p-7 shadow-sm mb-8">
                <div class="flex items-start gap-4 mb-5">
                    <div class="shrink-0 w-10 h-10 rounded-2xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                        <svg width="18" height="18" fill="none" stroke="#2563eb" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-slate-900 uppercase italic">Dokumen Desa Antikorupsi Publik</h2>
                        <p class="text-[10px] text-slate-400 font-bold">Embed halaman dokumen antikorupsi ke website desa.</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Link Langsung</label>
                    <div class="flex items-stretch gap-2">
                        <div class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs font-bold text-slate-600 truncate select-all" id="antiKorupsiUrlText">{{ $antikorupsiUrl }}</div>
                        <button type="button" onclick="copyText('antiKorupsiUrlText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span>Salin</span>
                        </button>
                        <a href="{{ $antikorupsiUrl }}" target="_blank" class="shrink-0 rounded-xl bg-blue-600 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-blue-700 transition-colors flex items-center">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Kode Embed (iframe)</label>
                    <div class="flex items-stretch gap-2">
                        <pre class="flex-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-[10px] font-mono text-slate-600 overflow-x-auto whitespace-pre-wrap break-all select-all" id="antiKorupsiEmbedText">&lt;iframe src="{{ $antikorupsiUrl }}" width="100%" height="1200" frameborder="0" style="border:none; border-radius:12px;"&gt;&lt;/iframe&gt;</pre>
                        <button type="button" onclick="copyText('antiKorupsiEmbedText', this)" class="shrink-0 rounded-xl bg-slate-900 px-3.5 text-[9px] font-black uppercase tracking-widest text-white hover:bg-slate-800 transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            <span>Salin</span>
                        </button>
                    </div>
                    <p class="text-[8px] text-slate-300 mt-1.5">Height 1200px agar seluruh konten dokumen antikorupsi tampil utuh.</p>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-6 mb-8">
                <h2 class="text-base font-black uppercase mb-4" style="color: {{ $headerColor }};">+ Tambah Indikator Baru</h2>
                
                <form id="tambahForm" method="POST" action="{{ route('desa.antikorupsi.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="desa_id" value="{{ $desaAktif->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Kategori</label>
                            <select name="kategori" required class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 focus:outline-none" style="border-color: {{ $accentColor }}40;">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="tatalaksana">1. Tata Laksana</option>
                                <option value="pengawasan">2. Pengawasan</option>
                                <option value="pelayanan">3. Pelayanan Publik</option>
                                <option value="partisipasi">4. Partisipasi Masyarakat</option>
                                <option value="kearifan">5. Kearifan Lokal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Grup Indikator</label>
                            <select name="grup_indikator" required class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 focus:outline-none" style="border-color: {{ $accentColor }}40;">
                                <option value="">-- Pilih Grup --</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipe" value="subjudul" checked class="rounded border-slate-300">
                            <span class="text-sm font-bold text-slate-700">Sub Judul Pemisah</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="tipe" value="dokumen" class="rounded border-slate-300">
                            <span class="text-sm font-bold text-slate-700">Indikator/Dokumen</span>
                        </label>
                    </div>

                    <div x-data="{ tipe: 'subjudul' }" @change="tipe = document.querySelector('[name=tipe]:checked').value" class="space-y-4">
                        
                        <div x-show="tipe === 'subjudul'" class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Judul Sub-Kelompok</label>
                            <input type="text" name="sub_judul" placeholder="Contoh: Musyawarah Pemangku Kepentingan" 
                                   class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 focus:outline-none">
                        </div>

                        
                        <div x-show="tipe === 'dokumen'" class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Nama Dokumen/Indikator</label>
                            <input type="text" name="nama_dokumen" placeholder="Contoh: RKPDes, APBDes, dll" 
                                   class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 focus:outline-none">
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Sub Judul Indikator (Opsional)</label>
                            <input type="text" name="sub_judul_indikator" placeholder="Isi jika dokumen ini perlu label sub-kelompok khusus"
                                   class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 focus:outline-none"
                                   @input="document.querySelector('[name=sub_judul]').value = $event.target.value">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-slate-500 mb-2">Link Google Drive (opsional)</label>
                        <input type="url" name="link_drive" placeholder="https://drive.google.com/..." 
                               class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5 focus:outline-none">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-6 py-2.5 rounded-xl text-white font-black uppercase text-xs transition-all"
                                style="background: {{ $headerColor }};">
                            ✚ Tambah Item
                        </button>
                        <button type="reset" class="px-6 py-2.5 rounded-xl border border-slate-200 bg-white font-black uppercase text-xs hover:bg-slate-50">
                            Reset
                        </button>
                    </div>
                </form>
            </div>

            
            <div class="space-y-6">
                @php
                    $kategoriList = [
                        'tatalaksana' => '1. Tata Laksana',
                        'pengawasan' => '2. Pengawasan',
                        'pelayanan' => '3. Pelayanan Publik',
                        'partisipasi' => '4. Partisipasi Masyarakat',
                        'kearifan' => '5. Kearifan Lokal'
                    ];
                @endphp

                @foreach($kategoriList as $keyKategori => $judulKategori)
                    @php
                        $itemsInKategori = $data[$keyKategori] ?? collect();
                        $totalItems = $itemsInKategori->sum(fn($items) => count($items));
                    @endphp

                    <div x-data="{ expanded: localStorage.getItem('cat_expanded_{{ $keyKategori }}') === 'true' }"
                         x-init="$watch('expanded', val => localStorage.setItem('cat_expanded_{{ $keyKategori }}', val))"
                         class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
                        
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between cursor-pointer select-none transition-colors duration-150"
                             style="background: {{ $accentColor }}15;"
                             @click="expanded = !expanded">
                            <h3 class="text-base font-black uppercase flex flex-wrap items-center gap-2" style="color: {{ $headerColor }};">
                                <span>{{ $judulKategori }}</span>
                                @if($totalItems > 0)
                                    <span class="text-xs font-black text-slate-500">({{ $totalItems }} Dokumen)</span>
                                @else
                                    <span class="inline-block rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-[9px] font-black text-slate-400">0 Dokumen</span>
                                @endif
                            </h3>
                            
                            <!-- Toggle plus/minus icon -->
                            <div class="w-8 h-8 rounded-full flex items-center justify-center bg-white border border-slate-200 transition-transform duration-200 shadow-sm"
                                 style="color: {{ $headerColor }};">
                                <span class="text-lg font-black" x-text="expanded ? '-' : '+'"></span>
                            </div>
                        </div>

                        <!-- Collapsible Content -->
                        <div x-show="expanded"
                             x-transition:enter="transition-all ease-out duration-300"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="p-6 space-y-4"
                             style="display: none;">
                            @forelse($itemsInKategori as $grup => $items)
                                <div class="border border-slate-100 rounded-xl p-4">
                                    <h4 class="text-sm font-black uppercase mb-3 p-2 rounded-lg" style="color: {{ $headerColor }}; background: {{ $accentColor }}15;">
                                        📌 {{ $grup }}
                                    </h4>

                                    
                                    <div class="sortable-group space-y-2" data-kategori="{{ $keyKategori }}" data-grup="{{ $grup }}">
                                        @forelse($items as $item)
                                            @php
                                                $level = (int) ($item->level ?? 0);
                                                $isHeading = !empty($item->sub_judul) && empty($item->nama_dokumen);
                                                $displayTitle = $isHeading ? $item->sub_judul : $item->nama_dokumen;
                                                $displayNoUrut = $item->no_urut;
                                                $displaySub = $item->sub;

                                                if (!$displaySub && $level === 1 && !empty($item->no_urut) && str_contains($item->no_urut, '.')) {
                                                    [$displayNoUrut, $legacyChildNumber] = array_pad(explode('.', $item->no_urut, 2), 2, null);

                                                    if (is_numeric($legacyChildNumber)) {
                                                        $displaySub = chr(96 + max(1, min((int) $legacyChildNumber, 26)));
                                                    }
                                                }
                                            @endphp
                                            <div class="sortable-item group flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:border-slate-300 hover:bg-slate-50 cursor-move transition-all"
                                                 data-id="{{ $item->id }}" data-urutan="{{ $item->urutan_tampil ?? 0 }}" data-level="{{ $level }}"
                                                 style="margin-left: {{ $level * 2 }}rem;">
                                                
                                                <div class="drag-handle cursor-grab active:cursor-grabbing text-slate-400 group-hover:text-slate-600 flex-shrink-0">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M8 5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM8 12a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM8 19a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                                    </svg>
                                                </div>

                                                
                                                <div class="flex-1 min-w-0">
                                                    @if(!$isHeading && !empty($item->sub_judul))
                                                        <div class="text-xs font-bold uppercase tracking-wide mb-1" style="color: {{ $headerColor }};">
                                                            {{ $item->sub_judul }}
                                                        </div>
                                                    @endif
                                                    <div class="flex items-center gap-2">
                                                        @if(!empty($displayNoUrut))
                                                        <span class="inline-block px-2 py-0.5 rounded text-xs font-black {{ $isHeading ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                                            {{ $displayNoUrut }}
                                                        </span>
                                                        @endif
                                                        @if(!empty($displaySub))
                                                            <span class="inline-block px-2 py-0.5 rounded text-xs font-black bg-slate-100 text-slate-600">
                                                                {{ $displaySub }}
                                                            </span>
                                                        @endif
                                                        <span class="text-sm font-bold {{ $isHeading ? 'uppercase tracking-wide' : '' }} text-slate-700 truncate">
                                                            {{ $displayTitle }}
                                                        </span>
                                                    </div>
                                                </div>

                                                
                                                <div class="flex w-72 flex-shrink-0 items-center gap-2">
                                                    <input type="url" value="{{ $item->link_drive }}" 
                                                           placeholder="Link Drive..." 
                                                           class="link-drive-input min-w-0 flex-1 border border-slate-200 rounded-lg text-xs px-2.5 py-1.5 bg-slate-50 focus:outline-none focus:bg-white"
                                                           onchange="simpanLink({{ $item->id }}, this.value, this)">
                                                    <a href="{{ $item->link_drive ?: '#' }}"
                                                       target="_blank"
                                                       rel="noopener noreferrer"
                                                       class="link-open-button px-2.5 py-1.5 rounded-lg text-[10px] font-black uppercase {{ $item->link_drive ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'hidden bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                                        Buka
                                                    </a>
                                                    <span class="link-status px-2 py-1 rounded-lg text-[10px] font-black uppercase {{ $item->link_drive ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-400' }}">
                                                        {{ $item->link_drive ? 'Terisi' : 'Kosong' }}
                                                    </span>
                                                </div>

                                                
                                                <div class="flex items-center gap-1 flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <button type="button" onclick="editItem({{ json_encode($item) }})"
                                                            class="p-1.5 rounded-lg hover:bg-blue-50 text-blue-500 hover:text-blue-700 transition-colors" title="Edit">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </button>
                                                    <button type="button" onclick="hapusItem({{ $item->id }})"
                                                            class="p-1.5 rounded-lg hover:bg-red-50 text-red-500 hover:text-red-700 transition-colors" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-6 text-slate-400 text-sm">
                                                Belum ada item di grup ini
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">
                                    Belum ada data untuk kategori ini
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>

            
            <div class="mt-8 text-center">
                <button onclick="simpanSemuaLink()" 
                        class="px-8 py-3 rounded-xl text-white font-black uppercase text-sm shadow-lg transition-all hover:-translate-y-0.5"
                        style="background: {{ $headerColor }};">
                    💾 Simpan Semua Link Drive
                </button>
            </div>

        </div>
    </div>

    
    <div id="editModal" class="fixed inset-0 z-50 items-center justify-center p-4 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg z-10">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-black uppercase" style="color: {{ $headerColor }};">Edit Indikator</h3>
                <button onclick="closeEditModal()" class="w-7 h-7 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="editForm" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="kategori" id="editKategori">
                <input type="hidden" name="grup_indikator" id="editGrup">
                <input type="hidden" name="urutan_tampil" id="editUrutan">
                <input type="hidden" name="level" id="editLevel">
                <input type="hidden" name="no_urut" id="editNoUrut">
                <input type="hidden" name="sub" id="editSub">
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-black uppercase mb-1 text-slate-500">Nama Dokumen</label>
                        <input type="text" name="nama_dokumen" id="editNama" class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase mb-1 text-slate-500">Sub Judul</label>
                        <input type="text" name="sub_judul" id="editSubJudul" class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5">
                    </div>
                    <div>
                        <label class="block text-xs font-black uppercase mb-1 text-slate-500">Link Drive</label>
                        <input type="url" name="link_drive" id="editLink" class="w-full border border-slate-200 rounded-xl text-sm px-3 py-2.5">
                    </div>
                </div>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 font-bold uppercase text-xs hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-white font-bold uppercase text-xs" style="background: {{ $headerColor }};">Update</button>
                </div>
            </form>
        </div>
    </div>

    
    @foreach($data as $items)
        @foreach($items as $itemList)
            @foreach($itemList as $item)
                <form id="delete-form-{{ $item->id }}" action="{{ route('desa.antikorupsi.destroy', $item->id) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endforeach
        @endforeach
    @endforeach

    <script>
        function copyText(elementId, btn) {
            var el = document.getElementById(elementId);
            var text = el.textContent || el.innerText;
            navigator.clipboard.writeText(text).then(function() {
                var span = btn.querySelector('span');
                var original = span.textContent;
                span.textContent = 'Tersalin!';
                btn.classList.remove('bg-slate-900');
                btn.classList.add('bg-emerald-600');
                setTimeout(function() {
                    span.textContent = original;
                    btn.classList.remove('bg-emerald-600');
                    btn.classList.add('bg-slate-900');
                }, 1500);
            });
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        function jsonHeaders() {
            return {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            };
        }

        function normalizeClientLink(link) {
            const trimmed = (link || '').trim();

            if (!trimmed) {
                return '';
            }

            return /^https?:\/\//i.test(trimmed) ? trimmed : `https://${trimmed}`;
        }

        // KATEGORI OPTIONS
        const masterData = @json($masterGrupList);

        // Update grup options based on kategori
        document.querySelector('[name=kategori]')?.addEventListener('change', function() {
            const grupSelect = document.querySelector('[name=grup_indikator]');
            const selected = masterData.filter(g => g.kategori === this.value);
            
            grupSelect.innerHTML = '<option value="">-- Pilih Grup --</option>';
            selected.forEach(g => {
                const opt = document.createElement('option');
                opt.value = g.nama_grup;
                opt.text = g.nama_grup;
                grupSelect.appendChild(opt);
            });
        });

        const interactiveSelector = 'input,button,a,select,textarea,label';
        const maxIndentLevel = 4;
        const indentSize = 32;

        function clampLevel(level) {
            return Math.max(0, Math.min(maxIndentLevel, level));
        }

        function getItemLevel(item) {
            return clampLevel(Number(item?.dataset.level || 0));
        }

        function applyItemLevel(item) {
            const level = getItemLevel(item);
            item.style.marginLeft = `${level * 2}rem`;
        }

        function resolveDropLevel(group, placeholder, clientX) {
            const groupLeft = group.getBoundingClientRect().left;
            const previousItem = placeholder.previousElementSibling?.classList.contains('sortable-item')
                ? placeholder.previousElementSibling
                : null;
            const requestedLevel = Math.round((clientX - groupLeft) / indentSize);
            const maxAllowedLevel = previousItem ? getItemLevel(previousItem) + 1 : 0;

            return Math.max(0, Math.min(maxAllowedLevel, clampLevel(requestedLevel)));
        }

        function applyDraftLevel(group, placeholder, clientX) {
            const level = resolveDropLevel(group, placeholder, clientX);
            placeholder.dataset.level = level;
            placeholder.style.marginLeft = `${level * 2}rem`;
        }

        function setupPointerSortable(group) {
            group.querySelectorAll('.sortable-item').forEach(item => {
                item.style.touchAction = 'none';
                applyItemLevel(item);

                item.addEventListener('pointerdown', event => {
                    if (event.target.closest(interactiveSelector) || event.button > 0) {
                        return;
                    }

                    event.preventDefault();

                    const rect = item.getBoundingClientRect();
                    const placeholder = document.createElement('div');
                    const originalNext = item.nextElementSibling;
                    let moved = false;

                    placeholder.className = 'rounded-lg border-2 border-dashed border-blue-300 bg-blue-50';
                    placeholder.dataset.level = item.dataset.level || '0';
                    placeholder.style.height = `${rect.height}px`;
                    placeholder.style.marginLeft = item.style.marginLeft;

                    item.parentElement.insertBefore(placeholder, item.nextSibling);

                    item.classList.add('shadow-xl', 'bg-white');
                    item.style.position = 'fixed';
                    item.style.zIndex = '9999';
                    item.style.left = `${rect.left}px`;
                    item.style.top = `${rect.top}px`;
                    item.style.width = `${rect.width}px`;
                    item.style.pointerEvents = 'none';

                    const shiftX = event.clientX - rect.left;
                    const shiftY = event.clientY - rect.top;
                    let lastClientX = event.clientX;

                    function moveAt(clientX, clientY) {
                        item.style.left = `${clientX - shiftX}px`;
                        item.style.top = `${clientY - shiftY}px`;
                    }

                    function movePlaceholder(clientX, clientY) {
                        const target = document.elementFromPoint(clientX, clientY)?.closest('.sortable-item');

                        if (!target || target === item || target.parentElement !== group) {
                            return;
                        }

                        const targetBox = target.getBoundingClientRect();
                        const isAfterTargetMiddle = clientY > targetBox.top + targetBox.height / 2;
                        group.insertBefore(placeholder, isAfterTargetMiddle ? target.nextSibling : target);
                        applyDraftLevel(group, placeholder, clientX);
                    }

                    function onPointerMove(moveEvent) {
                        moved = true;
                        lastClientX = moveEvent.clientX;
                        moveAt(moveEvent.clientX, moveEvent.clientY);
                        movePlaceholder(moveEvent.clientX, moveEvent.clientY);
                        applyDraftLevel(group, placeholder, moveEvent.clientX);
                    }

                    function onPointerUp(upEvent) {
                        document.removeEventListener('pointermove', onPointerMove);
                        document.removeEventListener('pointerup', onPointerUp);
                        document.removeEventListener('pointercancel', onPointerUp);

                        item.classList.remove('shadow-xl', 'bg-white');
                        item.style.position = '';
                        item.style.zIndex = '';
                        item.style.left = '';
                        item.style.top = '';
                        item.style.width = '';
                        item.style.pointerEvents = '';
                        item.style.touchAction = 'none';

                        if (moved) {
                            item.dataset.level = resolveDropLevel(group, placeholder, upEvent.clientX ?? lastClientX);
                            applyItemLevel(item);
                            group.insertBefore(item, placeholder);
                            simpanUrutan(group);
                        } else {
                            group.insertBefore(item, originalNext);
                            applyItemLevel(item);
                        }

                        placeholder.remove();
                    }

                    moveAt(event.clientX, event.clientY);
                    document.addEventListener('pointermove', onPointerMove);
                    document.addEventListener('pointerup', onPointerUp);
                    document.addEventListener('pointercancel', onPointerUp);
                });
            });
        }

        function initDragDrop() {
            document.querySelectorAll('.sortable-group').forEach(group => {
                setupPointerSortable(group);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initDragDrop);
        } else {
            initDragDrop();
        }

        async function simpanUrutan(group) {
            const items = Array.from(group.querySelectorAll('[data-id]'));
            const updates = items.map((item, index) => ({
                id: item.dataset.id,
                urutan: index + 1,
                level: getItemLevel(item)
            }));

            try {
                const res = await fetch('{{ route("desa.antikorupsi.reorder") }}', {
                    method: 'POST',
                    headers: jsonHeaders(),
                    body: JSON.stringify({ updates })
                });
                if (res.ok) {
                    console.log('Urutan tersimpan');
                } else {
                    alert('Gagal menyimpan urutan');
                }
            } catch(e) {
                alert('Gagal menyimpan urutan');
            }
        }

        async function simpanLink(id, link, input) {
            const normalizedLink = normalizeClientLink(link);

            if (input) {
                input.value = normalizedLink;
            }

            try {
                const res = await fetch('{{ route("desa.antikorupsi.update.link") }}', {
                    method: 'POST',
                    headers: jsonHeaders(),
                    body: JSON.stringify({ id, link: normalizedLink })
                });

                if (!res.ok) {
                    alert('Gagal menyimpan link. Pastikan format link benar.');
                    return;
                }

                const row = input?.closest('.sortable-item');
                const status = row?.querySelector('.link-status');
                const openButton = row?.querySelector('.link-open-button');

                if (status) {
                    status.textContent = normalizedLink ? 'Terisi' : 'Kosong';
                    status.className = normalizedLink
                        ? 'link-status px-2 py-1 rounded-lg text-[10px] font-black uppercase bg-emerald-100 text-emerald-700'
                        : 'link-status px-2 py-1 rounded-lg text-[10px] font-black uppercase bg-slate-100 text-slate-400';
                }

                if (openButton) {
                    openButton.href = normalizedLink || '#';
                    openButton.classList.toggle('hidden', !normalizedLink);
                }
            } catch(e) {
                alert('Gagal menyimpan link. Coba lagi.');
            }
        }

        async function simpanSemuaLink() {
            const links = {};
            document.querySelectorAll('[data-id]').forEach(item => {
                const input = item.querySelector('input[type="url"]');
                if (input) {
                    input.value = normalizeClientLink(input.value);
                    links[item.dataset.id] = input.value;
                }
            });

            try {
                const res = await fetch('{{ route("desa.antikorupsi.update") }}', {
                    method: 'PUT',
                    headers: jsonHeaders(),
                    body: JSON.stringify({ links })
                });
                if (res.ok) {
                    Swal.fire({ title: 'Sukses!', text: 'Link tersimpan', icon: 'success', timer: 1500 });
                }
            } catch(e) {
                alert('Gagal menyimpan');
            }
        }

        function editItem(item) {
            document.getElementById('editKategori').value = item.kategori || '';
            document.getElementById('editGrup').value = item.grup_indikator || '';
            document.getElementById('editUrutan').value = item.urutan_tampil || '';
            document.getElementById('editLevel').value = item.level || 0;
            document.getElementById('editNoUrut').value = item.no_urut || '';
            document.getElementById('editSub').value = item.sub || '';
            document.getElementById('editNama').value = item.nama_dokumen || '';
            document.getElementById('editSubJudul').value = item.sub_judul || '';
            document.getElementById('editLink').value = item.link_drive || '';
            document.getElementById('editForm').action = '{{ url("desa/antikorupsi/edit") }}/' + item.id;
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        function hapusItem(id) {
            Swal.fire({
                title: 'Hapus Item?',
                text: 'Item ini akan dihapus permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus'
            }).then(r => {
                if (r.isConfirmed) document.getElementById('delete-form-' + id).submit();
            });
        }
    </script>
</x-app-layout>
