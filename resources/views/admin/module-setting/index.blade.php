<x-app-layout>
    @php
        $totalUser = $users->count();

        $modulAktif = [
            'statistik' => $users->where('is_statistik_active', true)->count(),
            'ppid' => $users->where('is_ppid_active', true)->count(),
            'antikorupsi' => $users->where('is_antikorupsi_active', true)->count(),
            'skm' => $users->where('is_skm_active', true)->count(),
            'aduan' => $users->where('is_aduan_active', true)->count(),
        ];

        $modules = [
            [
                'key' => 'statistik',
                'label' => 'Statistik',
                'column' => 'is_statistik_active',
                'color' => '#3b82f6',
            ],
            [
                'key' => 'ppid',
                'label' => 'PPID',
                'column' => 'is_ppid_active',
                'color' => '#0ea5e9',
            ],
            [
                'key' => 'antikorupsi',
                'label' => 'Antikorupsi',
                'column' => 'is_antikorupsi_active',
                'color' => '#10b981',
            ],
            [
                'key' => 'skm',
                'label' => 'SKM',
                'column' => 'is_skm_active',
                'color' => '#6366f1',
            ],
            [
                'key' => 'aduan',
                'label' => 'Aduan',
                'column' => 'is_aduan_active',
                'color' => '#f43f5e',
            ],
        ];
    @endphp

    <style>
        /* ============================================ */
        /* STANDARD TOGGLE SWITCH                       */
        /* ============================================ */
        .std-toggle {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }

        .std-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        .std-toggle__track {
            position: absolute;
            inset: 0;
            cursor: pointer;
            background-color: #cbd5e1; /* slate-300 */
            border-radius: 9999px;
            transition: background-color 0.2s ease;
        }

        .std-toggle__thumb {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            background-color: #ffffff;
            border-radius: 9999px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s ease;
        }

        /* ON state */
        .std-toggle.is-on .std-toggle__track {
            background-color: var(--toggle-color, #10b981);
        }

        .std-toggle.is-on .std-toggle__thumb {
            transform: translateX(20px);
        }

        /* Loading state */
        .std-toggle.is-loading .std-toggle__track {
            background-color: #94a3b8; /* slate-400 */
        }

        .std-toggle.is-loading .std-toggle__thumb {
            animation: togglePulse 0.8s ease-in-out infinite;
        }

        @keyframes togglePulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        /* Disabled */
        .std-toggle.is-loading .std-toggle__track {
            cursor: wait;
        }
    </style>

    <div class="py-12 min-h-screen bg-slate-50 theme-bg-main">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-10">

            {{-- HEADER --}}
            <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 text-white p-8 lg:p-10 mb-8 shadow-sm">
                <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-emerald-500/10"></div>
                <div class="absolute right-20 bottom-0 w-32 h-32 rounded-full bg-amber-400/10"></div>
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-emerald-300 mb-3">
                            Kabupaten Belitung Timur • Kontrol Akses Layanan
                        </p>
                        <h1 class="text-3xl font-black uppercase italic tracking-tight">Pengaturan Modul Desa</h1>
                        <p class="mt-3 text-sm text-slate-300 max-w-3xl leading-relaxed">
                            Aktifkan atau nonaktifkan akses layanan Statistik, PPID, Antikorupsi, dan SKM untuk masing-masing desa.
                        </p>
                    </div>
                    <div class="inline-flex items-center justify-center rounded-2xl bg-white/10 border border-white/10 px-6 py-4">
                        <div class="text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-300">Total User Desa</p>
                            <p class="text-2xl font-black text-emerald-300">{{ $totalUser }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-5 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub mb-2">Total Desa</p>
                    <p class="text-2xl font-black text-slate-900 theme-text-main">{{ $totalUser }}</p>
                </div>
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-5 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-blue-500 mb-2">Statistik Aktif</p>
                    <p class="text-2xl font-black text-blue-600" id="count-statistik">{{ $modulAktif['statistik'] }}</p>
                </div>
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-5 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-sky-500 mb-2">PPID Aktif</p>
                    <p class="text-2xl font-black text-sky-600" id="count-ppid">{{ $modulAktif['ppid'] }}</p>
                </div>
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-5 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-2">Antikorupsi Aktif</p>
                    <p class="text-2xl font-black text-emerald-600" id="count-antikorupsi">{{ $modulAktif['antikorupsi'] }}</p>
                </div>
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-5 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-2">SKM Aktif</p>
                    <p class="text-2xl font-black text-indigo-600" id="count-skm">{{ $modulAktif['skm'] }}</p>
                </div>
                <div class="rounded-[2rem] bg-white theme-bg-card border border-slate-200 theme-border p-5 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-rose-500 mb-2">Aduan Aktif</p>
                    <p class="text-2xl font-black text-rose-600" id="count-aduan">{{ $modulAktif['aduan'] }}</p>
                </div>
            </div>

            {{-- LIST USER DESA --}}
            <div class="rounded-[2.5rem] bg-white theme-bg-card border border-slate-200 theme-border shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 theme-border">
                    <h2 class="text-lg font-black uppercase italic text-slate-900 theme-text-main">Daftar Akses Modul per Desa</h2>
                    <p class="mt-2 text-sm text-slate-500 theme-text-sub">Klik tombol ON/OFF untuk membuka atau mengunci modul.</p>
                </div>

                <div class="divide-y divide-slate-100 theme-border">
                    @forelse($users as $user)
                        <div class="p-5 lg:p-6">
                            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">

                                {{-- INFO USER --}}
                                <div class="flex items-center gap-5 min-w-0">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-lg shrink-0">
                                        {{ strtoupper(substr($user->desa->nama_desa ?? $user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-lg font-black uppercase italic text-slate-900 theme-text-main truncate">
                                            {{ $user->desa->nama_desa ?? $user->name }}
                                        </h3>
                                        <p class="mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400 theme-text-sub truncate">
                                            {{ $user->email ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- MODULE TOGGLE AREA --}}
                                <div class="flex flex-wrap items-center gap-3 bg-slate-100/50 dark:bg-slate-900/40 p-3 rounded-2xl border border-slate-200/60 theme-border">
                                    @foreach($modules as $module)
                                        @php
                                            $active = (bool) ($user->{$module['column']} ?? false);
                                        @endphp

                                        <form action="{{ route('admin.module.toggle', $user->id) }}"
                                              method="POST"
                                              class="module-toggle-form"
                                              data-module="{{ $module['key'] }}">
                                            @csrf
                                            <input type="hidden" name="module" value="{{ $module['key'] }}">

                                            <button type="submit"
                                                    class="module-toggle-btn min-w-[140px] inline-flex items-center justify-between gap-3 rounded-xl border px-3 py-2 text-[10px] font-black uppercase tracking-widest transition shadow-sm
                                                    {{ $active
                                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100'
                                                        : 'bg-white theme-bg-card text-slate-500 border-slate-200 hover:bg-slate-50'
                                                    }}"
                                                    data-active="{{ $active ? '1' : '0' }}">

                                                <div class="flex flex-col text-left leading-tight">
                                                    <span>{{ $module['label'] }}</span>
                                                    <span class="module-toggle-badge text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                                                        {{ $active ? 'ON' : 'OFF' }}
                                                    </span>
                                                </div>

                                                <div class="std-toggle {{ $active ? 'is-on' : '' }}"
                                                     style="--toggle-color: {{ $module['color'] }};">
                                                    <span class="std-toggle__track"></span>
                                                    <span class="std-toggle__thumb"></span>
                                                </div>
                                            </button>
                                        </form>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <p class="text-xs font-black uppercase tracking-widest text-slate-400 theme-text-sub">Belum ada data user desa.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.module-toggle-form').forEach(function (form) {
                form.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    const button = form.querySelector('.module-toggle-btn');
                    const badge = form.querySelector('.module-toggle-badge');
                    const toggle = form.querySelector('.std-toggle');
                    const formData = new FormData(form);
                    const moduleKey = form.dataset.module;

                    button.disabled = true;
                    toggle.classList.add('is-loading');
                    button.classList.add('opacity-60');

                    try {
                        const [response] = await Promise.all([
                            fetch(form.action, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                                    'Accept': 'application/json',
                                },
                                body: formData,
                            }),
                            new Promise(resolve => setTimeout(resolve, 500))
                        ]);

                        if (!response.ok) throw new Error('Gagal memperbarui status modul.');

                        const result = await response.json();
                        const active = result.active === true;

                        button.dataset.active = active ? '1' : '0';
                        badge.textContent = active ? 'ON' : 'OFF';

                        if (active) {
                            toggle.classList.add('is-on');
                            toggle.classList.remove('is-loading');
                            button.className = 'module-toggle-btn min-w-[140px] inline-flex items-center justify-between gap-3 rounded-xl border px-3 py-2 text-[10px] font-black uppercase tracking-widest transition shadow-sm bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100';
                        } else {
                            toggle.classList.remove('is-on', 'is-loading');
                            button.className = 'module-toggle-btn min-w-[140px] inline-flex items-center justify-between gap-3 rounded-xl border px-3 py-2 text-[10px] font-black uppercase tracking-widest transition shadow-sm bg-white theme-bg-card text-slate-500 border-slate-200 hover:bg-slate-50';
                        }

                        const counterElement = document.getElementById(`count-${moduleKey}`);
                        if (counterElement) {
                            let currentCount = parseInt(counterElement.textContent);
                            counterElement.textContent = active ? currentCount + 1 : currentCount - 1;
                        }

                    } catch (error) {
                        toggle.classList.remove('is-loading');
                        alert(error.message || 'Terjadi kesalahan saat memperbarui modul.');
                    } finally {
                        button.disabled = false;
                        button.classList.remove('opacity-60');
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>