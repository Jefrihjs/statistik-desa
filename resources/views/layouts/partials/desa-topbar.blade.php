@php
    $jumlahPermohonanBaru = \App\Models\PpidPermohonan::where('desa_id', auth()->user()->desa_id ?? null)
        ->where('status', 'pending')
        ->count();

    $jumlahKeberatanBaru = class_exists(\App\Models\PpidKeberatan::class)
        ? \App\Models\PpidKeberatan::where('desa_id', auth()->user()->desa_id ?? null)
            ->where('status', 'diajukan')
            ->count()
        : 0;

    $totalAlert = $jumlahPermohonanBaru + $jumlahKeberatanBaru;
@endphp

<header class="sticky top-0 z-30 bg-slate-50/90 backdrop-blur-xl border-b border-slate-200">
    <div class="h-20 px-6 lg:px-10 flex items-center justify-between">

        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-slate-400">
                TARSIUS Admin Desa
            </p>

            <h2 class="text-lg font-black text-slate-900 uppercase italic">
                {{ $title ?? 'Dashboard Desa' }}
            </h2>
        </div>

        <div class="flex items-center gap-3">

            {{-- ALERT CENTER --}}
            <div x-data="{ open: false }" class="relative">
                <button type="button"
                        @click="open = !open"
                        class="relative w-11 h-11 rounded-2xl bg-white border border-slate-200 flex items-center justify-center shadow-sm hover:bg-slate-50">
                    🔔

                    @if($totalAlert > 0)
                        <span class="absolute -top-1 -right-1 min-w-5 h-5 px-1 rounded-full bg-red-600 text-white text-[10px] font-black flex items-center justify-center">
                            {{ $totalAlert }}
                        </span>
                    @endif
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     class="absolute right-0 mt-3 w-80 bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden"
                     style="display:none;">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-800">
                            Alert Center
                        </h3>
                    </div>

                    <div class="p-4 space-y-3">
                        <a href="{{ route('desa.ppid.permohonan.index') }}"
                           class="block rounded-2xl bg-slate-50 p-4 hover:bg-blue-50">
                            <div class="text-xs font-black text-slate-900">
                                Permohonan PPID Baru
                            </div>
                            <div class="text-[11px] text-slate-500 mt-1">
                                {{ $jumlahPermohonanBaru }} permohonan menunggu tindak lanjut.
                            </div>
                        </a>

                        <a href="{{ route('desa.ppid.keberatan.index') }}"
                           class="block rounded-2xl bg-slate-50 p-4 hover:bg-amber-50">
                            <div class="text-xs font-black text-slate-900">
                                Keberatan Informasi Baru
                            </div>
                            <div class="text-[11px] text-slate-500 mt-1">
                                {{ $jumlahKeberatanBaru }} keberatan belum ditanggapi.
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- THEME TOGGLE DUMMY --}}
            <button type="button"
                    class="w-11 h-11 rounded-2xl bg-white border border-slate-200 flex items-center justify-center shadow-sm hover:bg-slate-50">
                🌙
            </button>

            {{-- USER --}}
            <div x-data="{ open: false }" class="relative">
                <button type="button"
                        @click="open = !open"
                        class="flex items-center gap-3 rounded-2xl bg-white border border-slate-200 px-4 py-2 shadow-sm hover:bg-slate-50">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                        👤
                    </div>

                    <div class="hidden md:block text-left">
                        <div class="text-xs font-black text-slate-800">
                            {{ auth()->user()->name ?? 'Admin Desa' }}
                        </div>
                        <div class="text-[10px] text-slate-400 font-bold">
                            Operator Desa
                        </div>
                    </div>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-transition
                     class="absolute right-0 mt-3 w-56 bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden"
                     style="display:none;">
                    <div class="p-3">
                        <a href="{{ route('profile.edit') }}"
                           class="block rounded-2xl px-4 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50">
                            Profil User
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left rounded-2xl px-4 py-3 text-xs font-bold text-red-600 hover:bg-red-50">
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>