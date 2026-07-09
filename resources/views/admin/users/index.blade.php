<x-app-layout>
    @php
        $totalUser = $users->count();
        $totalOperatorDesa = $users->filter(fn ($item) => $item->desa_id !== null)->count();
        $totalAdminKabupaten = $users->filter(fn ($item) => $item->desa_id === null)->count();
    @endphp

    <div class="py-12 min-h-screen bg-slate-50 theme-bg-main">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white p-8 lg:p-10 mb-8 shadow-sm">
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-blue-500/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-amber-400/10"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-300 mb-3">
                            Kabupaten Belitung Timur • Manajemen Akses
                        </p>

                        <h1 class="text-3xl font-black uppercase italic tracking-tight">
                            Manajemen Akun Desa
                        </h1>

                        <p class="mt-3 text-sm text-slate-300 max-w-2xl leading-relaxed">
                            Kelola akun operator desa, wilayah tugas, dan akses administrasi layanan TARSIUS.
                        </p>
                    </div>

                    <button onclick="document.getElementById('modalAddUser').showModal()"
                            class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-blue-700 shadow-lg shadow-blue-900/20">
                        + Tambah Operator
                    </button>
                </div>
            </div>

            {{-- SUCCESS --}}
            @if (session('success'))
                <div class="mb-8 rounded-[2rem] border border-emerald-200 bg-emerald-50 px-6 py-5 text-sm font-bold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- SUMMARY --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Total Akun
                    </p>
                    <p class="text-3xl font-black text-slate-900 theme-text-main">
                        {{ $totalUser }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-2">
                        Operator Desa
                    </p>
                    <p class="text-3xl font-black text-blue-600">
                        {{ $totalOperatorDesa }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-500 mb-2">
                        Admin Kabupaten
                    </p>
                    <p class="text-3xl font-black text-amber-500">
                        {{ $totalAdminKabupaten }}
                    </p>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 mb-8 shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Cari Nama / Email / Desa
                        </label>

                        <input type="text"
                               id="filterKeywordUser"
                               placeholder="Ketik nama operator, email, atau desa..."
                               class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                            Jenis Akun
                        </label>

                        <select id="filterRoleUser"
                                class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                            <option value="semua">Semua Akun</option>
                            <option value="desa">Operator Desa</option>
                            <option value="admin">Admin Kabupaten</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- LIST USER --}}
            <div class="space-y-5" id="userList">
                @forelse($users as $user)
                    @php
                        $isAdminKabupaten = $user->desa_id === null;
                        $roleFilter = $isAdminKabupaten ? 'admin' : 'desa';

                        $initial = strtoupper(substr($user->name ?? 'U', 0, 1));

                        $searchText = strtolower(
                            ($user->name ?? '') . ' ' .
                            ($user->email ?? '') . ' ' .
                            ($user->desa->nama_desa ?? 'admin kabupaten')
                        );
                    @endphp

                    <div class="user-row rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm overflow-hidden"
                         data-role="{{ $roleFilter }}"
                         data-search="{{ $searchText }}">

                        <div class="p-6 lg:p-7 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                            <div class="flex items-center gap-5 min-w-0">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-lg text-white shrink-0
                                    {{ $isAdminKabupaten ? 'bg-amber-500' : 'bg-blue-600' }}">
                                    {{ $initial }}
                                </div>

                                <div class="min-w-0">
                                    <h2 class="text-lg lg:text-xl font-black uppercase italic text-slate-900 theme-text-main truncate">
                                        {{ $user->name }}
                                    </h2>

                                    <p class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub truncate">
                                        {{ $user->email }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 lg:justify-end">
                                <span class="inline-flex items-center justify-center rounded-2xl border px-5 py-3 text-[10px] font-black uppercase tracking-widest
                                    {{ $isAdminKabupaten ? 'bg-amber-500/10 text-amber-600 border-amber-500/20' : 'bg-blue-500/10 text-blue-600 border-blue-500/20' }}">
                                    {{ $user->desa->nama_desa ?? 'Admin Kabupaten' }}
                                </span>

                                <span class="inline-flex items-center justify-center rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-emerald-600">
                                    Aktif
                                </span>

                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-blue-700">
                                    Edit
                                </a>

                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun operator ini? Data yang dihapus tidak bisa dikembalikan.')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="inline-flex items-center justify-center rounded-2xl bg-red-600 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-white hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[2rem] bg-white theme-bg-card border border-dashed border-slate-200 theme-border p-12 text-center">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                            Belum ada data user/desa.
                        </p>
                    </div>
                @endforelse
            </div>

            <div id="emptyUserFilter"
                 class="hidden mt-8 rounded-[2rem] bg-white theme-bg-card border border-dashed border-slate-200 theme-border p-12 text-center">
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                    Data user tidak ditemukan berdasarkan filter.
                </p>
            </div>

        </div>
    </div>

    {{-- MODAL ADD USER --}}
    <dialog id="modalAddUser"
            class="p-0 rounded-[2.5rem] shadow-2xl border-none backdrop:bg-slate-900/60 backdrop:backdrop-blur-sm">

        <div class="w-[calc(100vw-2rem)] max-w-lg bg-white theme-bg-card p-8 lg:p-10 relative">

            <button type="button"
                    onclick="document.getElementById('modalAddUser').close()"
                    class="absolute top-6 right-6 w-10 h-10 rounded-2xl bg-slate-100 theme-bg-main text-slate-400 hover:text-red-500 flex items-center justify-center transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="3"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-blue-600 mb-2">
                Manajemen Akses
            </p>

            <h3 class="text-2xl font-black text-slate-900 theme-text-main uppercase italic mb-8">
                Buat Akun Desa
            </h3>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Nama Operator
                    </label>
                    <input type="text"
                           name="name"
                           required
                           class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Email
                    </label>
                    <input type="email"
                           name="email"
                           required
                           class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Tugas di Desa
                    </label>
                    <select name="desa_id"
                            required
                            class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600">
                        <option value="">-- PILIH DESA --</option>
                        @foreach($desas as $desa)
                            <option value="{{ $desa->id }}">{{ $desa->nama_desa }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Password
                    </label>
                    <input type="password"
                           name="password"
                           required
                           class="w-full rounded-2xl border-slate-200 theme-border bg-slate-50 theme-bg-main px-5 py-4 text-sm font-bold text-slate-700 theme-text-main focus:ring-blue-600"
                           placeholder="Minimal 8 karakter">
                </div>

                <button type="submit"
                        class="w-full inline-flex items-center justify-center rounded-2xl bg-blue-600 px-6 py-4 text-xs font-black uppercase tracking-widest text-white hover:bg-blue-700 shadow-lg shadow-blue-900/20">
                    Simpan Akun
                </button>
            </form>
        </div>
    </dialog>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const keywordInput = document.getElementById('filterKeywordUser');
                const roleInput = document.getElementById('filterRoleUser');
                const rows = document.querySelectorAll('.user-row');
                const emptyMessage = document.getElementById('emptyUserFilter');

                function applyUserFilter() {
                    const keyword = (keywordInput.value || '').toLowerCase().trim();
                    const role = roleInput.value;
                    let visibleCount = 0;

                    rows.forEach(row => {
                        const rowSearch = row.dataset.search || '';
                        const rowRole = row.dataset.role || '';

                        const matchKeyword = keyword === '' || rowSearch.includes(keyword);
                        const matchRole = role === 'semua' || rowRole === role;

                        if (matchKeyword && matchRole) {
                            row.classList.remove('hidden');
                            visibleCount++;
                        } else {
                            row.classList.add('hidden');
                        }
                    });

                    if (emptyMessage) {
                        emptyMessage.classList.toggle('hidden', visibleCount > 0);
                    }
                }

                keywordInput.addEventListener('input', applyUserFilter);
                roleInput.addEventListener('change', applyUserFilter);
            });
        </script>
    @endpush
</x-app-layout>