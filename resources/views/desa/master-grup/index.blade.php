<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @php
        $desaAktif = $desa
            ?? auth()->user()->desa
            ?? \App\Models\Desa::find(auth()->user()->desa_id);

        $headerColor = $desaAktif->header_color ?? '#475569';
        $accentColor = $desaAktif->accent_color ?? '#064e3b';

        $kategoriOrder = [
            'tatalaksana' => 1,
            'pengawasan' => 2,
            'pelayanan' => 3,
            'partisipasi' => 4,
            'kearifan' => 5,
        ];

        $masterGrup = $masterGrup->sort(function ($a, $b) use ($kategoriOrder) {
            $weightA = $kategoriOrder[$a->kategori] ?? 99;
            $weightB = $kategoriOrder[$b->kategori] ?? 99;
            
            if ($weightA !== $weightB) {
                return $weightA <=> $weightB;
            }
            
            $hasUrutanA = isset($a->urutan_grup) && is_numeric($a->urutan_grup);
            $hasUrutanB = isset($b->urutan_grup) && is_numeric($b->urutan_grup);
            
            if ($hasUrutanA && $hasUrutanB) {
                return (int)$a->urutan_grup <=> (int)$b->urutan_grup;
            } elseif ($hasUrutanA) {
                return -1;
            } elseif ($hasUrutanB) {
                return 1;
            }
            
            return strcasecmp((string)$a->nama_grup, (string)$b->nama_grup);
        });
    @endphp

    <div x-data="{
            modalAddOpen: false,
            modalEditOpen: false,
            editId: '',
            editKategori: '',
            editNamaGrup: '',
            filterKategori: 'semua',
            searchQuery: '',
            sortBy: 'default',
            groups: @js($masterGrup->values()->map(function($item, $index) {
                return [
                    'id' => $item->id,
                    'kategori' => $item->kategori,
                    'nama_grup' => $item->nama_grup,
                    'original_index' => $index
                ];
            })),
            getBadgeClass(kategori) {
                const classes = {
                    'tatalaksana': 'bg-blue-500/10 text-blue-600 border-blue-500/20',
                    'pengawasan': 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                    'pelayanan': 'bg-sky-500/10 text-sky-600 border-sky-500/20',
                    'partisipasi': 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                    'kearifan': 'bg-indigo-500/10 text-indigo-600 border-indigo-500/20'
                };
                return classes[kategori] || 'bg-slate-500/10 text-slate-500 border-slate-500/20';
            },
            getKategoriLabel(kategori) {
                const labels = {
                    'tatalaksana': 'Tata Laksana',
                    'pengawasan': 'Pengawasan',
                    'pelayanan': 'Pelayanan Publik',
                    'partisipasi': 'Partisipasi Masyarakat',
                    'kearifan': 'Kearifan Lokal'
                };
                return labels[kategori] || kategori.toUpperCase();
            },
            getInitial(kategori) {
                const label = this.getKategoriLabel(kategori);
                return label.charAt(0).toUpperCase();
            },
            get filteredAndSortedGroups() {
                let result = [...this.groups];
                if (this.filterKategori !== 'semua') {
                    result = result.filter(g => g.kategori === this.filterKategori);
                }
                if (this.searchQuery.trim() !== '') {
                    const query = this.searchQuery.toLowerCase();
                    result = result.filter(g => g.nama_grup.toLowerCase().includes(query));
                }
                if (this.sortBy === 'name-asc') {
                    result.sort((a, b) => a.nama_grup.localeCompare(b.nama_grup));
                } else if (this.sortBy === 'name-desc') {
                    result.sort((a, b) => b.nama_grup.localeCompare(a.nama_grup));
                } else if (this.sortBy === 'kategori-asc') {
                    result.sort((a, b) => this.getKategoriLabel(a.kategori).localeCompare(this.getKategoriLabel(b.kategori)));
                } else {
                    result.sort((a, b) => a.original_index - b.original_index);
                }
                return result;
            }
        }"
        class="py-12 min-h-screen bg-slate-50 theme-bg-main">

        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

            <div class="mb-4">
                <a href="{{ route('desa.dashboard') }}"
                class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Hub Utama TARSIUS
                </a>
            </div>
            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] text-white p-8 lg:p-10 mb-8 shadow-sm"
                style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }});">
                
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-emerald-500/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-amber-400/10"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-300 mb-3">
                            TARSIUS • Modul Desa Antikorupsi
                        </p>

                        <h1 class="text-3xl font-black uppercase italic tracking-tight">
                            Master Grup Indikator
                        </h1>

                        <p class="mt-3 text-sm text-slate-300 max-w-3xl leading-relaxed">
                            Kelola kelompok indikator Desa Antikorupsi yang digunakan sebagai struktur pengisian dokumen dan eviden.
                        </p>
                    </div>

                    <button type="button"
                            @click="modalAddOpen = true"
                            class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-emerald-700 shadow-lg shadow-emerald-900/20">
                        + Tambah Master Grup
                    </button>
                </div>
            </div>

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="mb-8 rounded-[2rem] border border-emerald-200 bg-emerald-50 px-6 py-5 text-sm font-bold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- SUMMARY --}}
            @php
                $totalGrup = $masterGrup->count();

                $kategoriCount = collect($masterGrup)
                    ->pluck('kategori')
                    ->filter()
                    ->unique()
                    ->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Total Grup
                    </p>
                    <p class="text-3xl font-black text-slate-900 theme-text-main">
                        {{ $totalGrup }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">
                        Kategori Terpakai
                    </p>
                    <p class="text-3xl font-black text-emerald-600">
                        {{ $kategoriCount }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-500 mb-2">
                        Status
                    </p>
                    <p class="text-3xl font-black text-amber-500">
                        Aktif
                    </p>
                </div>
            </div>

            {{-- LIST --}}
            <div class="rounded-[2.5rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 theme-border">
                    <h2 class="text-lg font-black uppercase italic text-slate-900 theme-text-main">
                        Daftar Master Grup Antikorupsi
                    </h2>

                    <p class="mt-2 text-sm text-slate-500 theme-text-sub">
                        Data ini menjadi dasar pembagian kategori indikator pada modul Desa Antikorupsi.
                    </p>
                </div>

                {{-- FILTER & SORT BAR --}}
                <div class="px-8 py-4 bg-slate-50/50 theme-bg-main border-b border-slate-100 theme-border flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                    {{-- Filter Tabs --}}
                    <div class="flex flex-wrap items-center gap-1.5">
                        <button type="button"
                                @click="filterKategori = 'semua'"
                                :class="filterKategori === 'semua' ? 'bg-slate-900 text-white shadow-sm' : 'bg-white theme-bg-card text-slate-600 hover:bg-slate-100 theme-text-main border border-slate-200 theme-border'"
                                class="px-3.5 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all">
                            Semua
                        </button>
                        <button type="button"
                                @click="filterKategori = 'tatalaksana'"
                                :class="filterKategori === 'tatalaksana' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white theme-bg-card text-blue-600 hover:bg-blue-50 border border-slate-200 theme-border'"
                                class="px-3.5 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all">
                            Tata Laksana
                        </button>
                        <button type="button"
                                @click="filterKategori = 'pengawasan'"
                                :class="filterKategori === 'pengawasan' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white theme-bg-card text-emerald-600 hover:bg-emerald-50 border border-slate-200 theme-border'"
                                class="px-3.5 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all">
                            Pengawasan
                        </button>
                        <button type="button"
                                @click="filterKategori = 'pelayanan'"
                                :class="filterKategori === 'pelayanan' ? 'bg-sky-600 text-white shadow-sm' : 'bg-white theme-bg-card text-sky-600 hover:bg-sky-50 border border-slate-200 theme-border'"
                                class="px-3.5 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all">
                            Pelayanan Publik
                        </button>
                        <button type="button"
                                @click="filterKategori = 'partisipasi'"
                                :class="filterKategori === 'partisipasi' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white theme-bg-card text-amber-600 hover:bg-amber-50 border border-slate-200 theme-border'"
                                class="px-3.5 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all">
                            Partisipasi
                        </button>
                        <button type="button"
                                @click="filterKategori = 'kearifan'"
                                :class="filterKategori === 'kearifan' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white theme-bg-card text-indigo-600 hover:bg-indigo-50 border border-slate-200 theme-border'"
                                class="px-3.5 py-2 rounded-xl text-[9px] font-black uppercase tracking-wider transition-all">
                            Kearifan Lokal
                        </button>
                    </div>

                    {{-- Search & Sort --}}
                    <div class="flex items-center gap-3 w-full xl:w-auto">
                        {{-- Search --}}
                        <div class="relative w-full xl:w-60">
                            <input type="text"
                                   x-model="searchQuery"
                                   placeholder="Cari grup..."
                                   class="w-full rounded-xl border border-slate-200 theme-border bg-white theme-bg-card px-3.5 py-2 text-[10px] font-bold text-slate-700 theme-text-main focus:ring-emerald-600 focus:border-emerald-600 placeholder-slate-400">
                        </div>

                        {{-- Sort --}}
                        <div class="relative shrink-0">
                            <select x-model="sortBy"
                                    class="rounded-xl border border-slate-200 theme-border bg-white theme-bg-card px-3.5 py-2 text-[10px] font-bold text-slate-700 theme-text-main focus:ring-emerald-600 focus:border-emerald-600">
                                <option value="default">Urutan Default</option>
                                <option value="name-asc">Nama (A - Z)</option>
                                <option value="name-desc">Nama (Z - A)</option>
                                <option value="kategori-asc">Kategori</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-slate-100 theme-border">
                    <template x-for="master in filteredAndSortedGroups" :key="master.id">
                        <div x-data="{ expanded: false }"
                             class="py-3 px-6 lg:py-3.5 lg:px-8">
                            
                            <!-- Clickable Header Row -->
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 cursor-pointer select-none"
                                 @click="expanded = !expanded">
                                
                                <div class="flex items-center gap-4 min-w-0 flex-1">
                                    <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-sm shrink-0"
                                         x-text="getInitial(master.kategori)">
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <h3 class="text-sm font-black uppercase italic text-slate-900 theme-text-main truncate flex-1 min-w-0"
                                                x-text="master.nama_grup">
                                            </h3>
                                            <!-- Chevron Indicator -->
                                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200 shrink-0"
                                                 :class="expanded ? 'rotate-180 text-slate-700' : ''"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>

                                        <p class="mt-0.5 text-[9px] font-black uppercase tracking-widest text-slate-400 theme-text-sub truncate">
                                            Komponen indikator Desa Antikorupsi
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center gap-3 shrink-0" @click.stop>
                                    <span class="inline-flex items-center justify-center rounded-xl border px-3.5 py-2 text-[9px] font-black uppercase tracking-widest"
                                          :class="getBadgeClass(master.kategori)"
                                          x-text="getKategoriLabel(master.kategori)">
                                    </span>

                                    <button type="button"
                                            @click="editId = master.id; editKategori = master.kategori; editNamaGrup = master.nama_grup; modalEditOpen = true"
                                            class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-3.5 py-2 text-[9px] font-black uppercase tracking-widest text-white hover:bg-blue-700">
                                        Edit
                                    </button>

                                    <form :action="'/desa/master-grup-antikorupsi/' + master.id"
                                          method="POST"
                                          class="form-hapus-master">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                onclick="pemicuHapusMaster(this)"
                                                class="inline-flex items-center justify-center rounded-xl bg-red-600 px-3.5 py-2 text-[9px] font-black uppercase tracking-widest text-white hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Collapsible Full Name Area -->
                            <div x-show="expanded"
                                 x-transition:enter="transition-all ease-out duration-250"
                                 x-transition:enter-start="opacity-0 max-h-0 transform -translate-y-1"
                                 x-transition:enter-end="opacity-100 max-h-40 transform translate-y-0"
                                 x-transition:leave="transition-all ease-in duration-150"
                                 x-transition:leave-start="opacity-100 max-h-40 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 max-h-0 transform -translate-y-1"
                                 class="overflow-hidden mt-2 text-xs text-slate-600 theme-text-sub border-t border-slate-100 theme-border pt-2 leading-relaxed"
                                 style="display: none;">
                                <span class="font-black uppercase tracking-wider text-[9px] text-slate-400">Nama Lengkap Grup:</span>
                                <p class="mt-1 font-bold text-slate-700 theme-text-main whitespace-normal break-words" x-text="master.nama_grup"></p>
                            </div>
                        </div>
                    </template>

                    <div x-show="groups.length === 0" class="p-12 text-center" style="display: none;">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                            Belum ada master grup.
                        </p>
                    </div>

                    <div x-show="groups.length > 0 && filteredAndSortedGroups.length === 0" class="p-12 text-center" style="display: none;">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                            Tidak ada master grup yang cocok dengan pencarian atau filter.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL ADD --}}
        <div x-show="modalAddOpen"
             x-transition.opacity
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">

            <div class="flex items-center justify-center min-h-screen px-4 py-10">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalAddOpen = false"></div>

                <div class="relative z-10 w-full max-w-lg rounded-[2.5rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-2xl overflow-hidden">
                    <form action="{{ route('desa.master-grup-antikorupsi.store') }}" method="POST">
                        @csrf

                        <div class="p-8 lg:p-10">
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-600 mb-2">
                                Master Grup
                            </p>

                            <h3 class="text-2xl font-black uppercase italic text-slate-900 theme-text-main mb-8">
                                Tambah Master Grup
                            </h3>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                        Kategori
                                    </label>

                                    <select name="kategori"
                                            required
                                            class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-emerald-600">
                                        <option value="tatalaksana">Tata Laksana</option>
                                        <option value="pengawasan">Pengawasan</option>
                                        <option value="pelayanan">Pelayanan Publik</option>
                                        <option value="partisipasi">Partisipasi Masyarakat</option>
                                        <option value="kearifan">Kearifan Lokal</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                        Nama Grup Indikator
                                    </label>

                                    <input type="text"
                                           name="nama_grup"
                                           required
                                           class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-emerald-600"
                                           placeholder="Contoh: Adanya Perdes/Keputusan ....">
                                </div>
                            </div>
                        </div>

                        <div class="px-8 lg:px-10 py-6 bg-slate-50 theme-bg-main border-t border-slate-100 theme-border flex flex-col sm:flex-row sm:justify-end gap-3">
                            <button type="button"
                                    @click="modalAddOpen = false"
                                    class="inline-flex items-center justify-center rounded-2xl bg-white theme-bg-card border border-slate-200 theme-border px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 theme-text-main hover:bg-slate-100">
                                Batal
                            </button>

                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-emerald-700">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT --}}
        <div x-show="modalEditOpen"
             x-transition.opacity
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">

            <div class="flex items-center justify-center min-h-screen px-4 py-10">
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalEditOpen = false"></div>

                <div class="relative z-10 w-full max-w-lg rounded-[2.5rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-2xl overflow-hidden">
                    <form :action="'/desa/master-grup-antikorupsi/' + editId" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="p-8 lg:p-10">
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600 mb-2">
                                Master Grup
                            </p>

                            <h3 class="text-2xl font-black uppercase italic text-slate-900 theme-text-main mb-8">
                                Edit Master Grup
                            </h3>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                        Kategori
                                    </label>

                                    <select name="kategori"
                                            x-model="editKategori"
                                            required
                                            class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                                        <option value="tatalaksana">Tata Laksana</option>
                                        <option value="pengawasan">Pengawasan</option>
                                        <option value="pelayanan">Pelayanan Publik</option>
                                        <option value="partisipasi">Partisipasi Masyarakat</option>
                                        <option value="kearifan">Kearifan Lokal</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                                        Nama Grup Indikator
                                    </label>

                                    <input type="text"
                                           name="nama_grup"
                                           x-model="editNamaGrup"
                                           required
                                           class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                                </div>
                            </div>
                        </div>

                        <div class="px-8 lg:px-10 py-6 bg-slate-50 theme-bg-main border-t border-slate-100 theme-border flex flex-col sm:flex-row sm:justify-end gap-3">
                            <button type="button"
                                    @click="modalEditOpen = false"
                                    class="inline-flex items-center justify-center rounded-2xl bg-white theme-bg-card border border-slate-200 theme-border px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 theme-text-main hover:bg-slate-100">
                                Batal
                            </button>

                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-blue-700">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function pemicuHapusMaster(button) {
            const form = button.closest('.form-hapus-master');

            Swal.fire({
                title: 'Hapus Master Grup?',
                text: 'Data komponen indikator ini akan dihapus permanen dari sistem TARSIUS.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                background: '#ffffff',
                customClass: {
                    title: 'font-black uppercase italic text-slate-800',
                    popup: 'rounded-[2rem] border border-slate-100 p-6',
                    confirmButton: 'rounded-xl font-black text-xs uppercase tracking-wider px-4 py-2.5',
                    cancelButton: 'rounded-xl font-black text-xs uppercase tracking-wider px-4 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>