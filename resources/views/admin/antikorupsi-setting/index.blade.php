<x-app-layout>
    <div class="py-12 min-h-screen bg-slate-50 theme-bg-main">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white p-8 lg:p-10 mb-8 shadow-sm">
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-emerald-500/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-amber-400/10"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-300 mb-3">
                            Kabupaten Belitung Timur • Kontrol Akses Modul
                        </p>

                        <h1 class="text-3xl font-black uppercase italic tracking-tight">
                            Pengaturan Akses Antikorupsi
                        </h1>

                        <p class="mt-3 text-sm text-slate-300 max-w-2xl leading-relaxed">
                            Aktifkan atau nonaktifkan akses modul Desa Antikorupsi untuk masing-masing akun desa.
                        </p>
                    </div>

                    <div class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/10 px-6 py-4">
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-300">
                                Total Desa/User
                            </p>
                            <p class="text-2xl font-black text-emerald-300">
                                {{ $users->count() }}
                            </p>
                        </div>
                    </div>
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
                $aktif = $users->where('is_antikorupsi_active', true)->count();
                $nonaktif = $users->where('is_antikorupsi_active', false)->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">
                        Total User Desa
                    </p>
                    <p class="text-3xl font-black text-slate-900 theme-text-main">
                        {{ $users->count() }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">
                        Modul Aktif
                    </p>
                    <p class="text-3xl font-black text-emerald-600">
                        {{ $aktif }}
                    </p>
                </div>

                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-6 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-red-500 mb-2">
                        Modul Nonaktif
                    </p>
                    <p class="text-3xl font-black text-red-600">
                        {{ $nonaktif }}
                    </p>
                </div>
            </div>

            {{-- LIST --}}
            <div class="rounded-[2.5rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 theme-border">
                    <h2 class="text-lg font-black uppercase italic text-slate-900 theme-text-main">
                        Daftar Akses Modul Desa Antikorupsi
                    </h2>

                    <p class="mt-2 text-sm text-slate-500 theme-text-sub">
                        Desa hanya dapat membuka menu dan card Antikorupsi setelah akses diaktifkan oleh admin kabupaten.
                    </p>
                </div>

                <div class="divide-y divide-slate-100 theme-border">
                    @forelse($users as $user)
                        <div class="p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center font-black text-lg shrink-0
                                    {{ $user->is_antikorupsi_active ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>

                                <div class="min-w-0">
                                    <h3 class="text-base font-black uppercase italic text-slate-900 theme-text-main truncate">
                                        {{ $user->name }}
                                    </h3>

                                    <p class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                                        {{ $user->email ?? 'Akun Desa' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                                @if($user->is_antikorupsi_active)
                                    <span class="inline-flex items-center justify-center rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-emerald-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-[10px] font-black uppercase tracking-widest text-red-700">
                                        Nonaktif
                                    </span>
                                @endif

                                <form action="{{ route('admin.antikorupsi.toggle', $user->id) }}" method="POST">
                                    @csrf

                                    <button type="submit"
                                            class="inline-flex items-center justify-center rounded-2xl px-6 py-3 text-[10px] font-black uppercase tracking-widest text-white transition
                                            {{ $user->is_antikorupsi_active ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                                        {{ $user->is_antikorupsi_active ? 'Matikan Akses' : 'Aktifkan Akses' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">
                                Belum ada data user/desa.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>