<x-app-layout>
    @php
        $headerColor = $desa->header_color ?? '#2563eb';
        $accentColor = $desa->accent_color ?? '#10b981';
        $waAdminKabupaten = '6281369368422'; // Ganti dengan nomor WhatsApp admin kabupaten yang valid (format internasional tanpa tanda +)
        $domainTag = $tracker ? $tracker->days_left . ' Hari Lagi' : 'Belum Terdata';
        
        if ($tracker) {
            $domainTagColor = $tracker->status === 'Sehat' 
                ? 'bg-emerald-50 text-emerald-700 ring-emerald-700/10' 
                : 'bg-rose-50 text-rose-700 ring-rose-700/10';
            
            $tglExpiredDomain = \Carbon\Carbon::parse($tracker->expiry_date)->translatedFormat('d F Y');
            $pesanDomain = "Halo Admin Kabupaten, saya Operator dari Desa " . ($desa->nama_desa ?? '') . ". Ingin berkoordinasi mengenai perpanjangan masa aktif Domain *" . $tracker->domain_name . "*, yang saat ini menyisakan *" . $tracker->days_left . " Hari Lagi* (Kadaluarsa: " . $tglExpiredDomain . "). Mohon bantuannya untuk proses tindak lanjut. Terima kasih.";
            $domainUrl = 'https://api.whatsapp.com/send?phone=' . $waAdminKabupaten . '&text=' . rawurlencode($pesanDomain);
        } else {
            $domainTagColor = 'bg-slate-50 text-slate-700 ring-slate-700/10';
            $domainUrl = '#';
        }

        $sslTag = $sslInfo['pesan'];
        
        if ($tracker && $sslInfo['status'] === 'active') {
            $sslTagColor = $sslInfo['hari'] <= 15 
                ? 'bg-rose-50 text-rose-700 ring-rose-700/10' 
                : 'bg-emerald-50 text-emerald-700 ring-emerald-700/10';
            
            $pesanSsl = "Halo Admin Kabupaten, saya Operator dari Desa " . ($desa->nama_desa ?? '') . ". Ingin berkoordinasi mengenai pembaruan Sertifikat SSL Keamanan (HTTPS) untuk website *" . $tracker->domain_name . "*, yang saat ini masa aktif sertifikatnya tinggal *" . $sslInfo['pesan'] . "*. Mohon bantuannya untuk pembaharuan sertifikat SSL. Terima kasih.";
            $sslUrl = 'https://api.whatsapp.com/send?phone=' . $waAdminKabupaten . '&text=' . rawurlencode($pesanSsl);
        } else {
            $sslTagColor = 'bg-rose-50 text-rose-700 ring-rose-700/10';
            // Jika live check gagal/tidak terkoneksi di localhost, tombol dialihkan ke link backup DNS Checker
            $sslUrl = $tracker ? 'https://dnschecker.org/ssl-certificate-checker.php?query=' . $tracker->domain_name : '#';
        }

            $user = auth()->user();

            $domainDaysLeft = $domainDaysLeft ?? null;
            $sslDaysLeft = $sslDaysLeft ?? null;

            $layananDesa = [
                [
                    'nama' => 'Statistik Desa',
                    'deskripsi' => 'Pengelolaan data demografi, entri statistik sektoral, unduh format excel, dan monitoring keterisian data.',
                    'tag' => 'Statistik',
                    'icon_color' => 'text-blue-600',
                    'icon_bg' => 'bg-blue-50',
                    'tag_class' => 'bg-blue-50 text-blue-700 ring-blue-700/10',
                    'route' => 'desa.statistik',
                    'enabled' => (bool) ($user->is_statistik_active ?? false),
                    'locked_text' => 'Modul Statistik belum diaktifkan oleh admin kabupaten.',
                    'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 20V10m7 10V4m7 16v-7"/></svg>',
                ],
                [
                    'nama' => 'PPID Desa',
                    'deskripsi' => 'Layanan pengelolaan keterbukaan informasi publik, dokumen berkala, serta permohonan informasi masyarakat.',
                    'tag' => 'Informasi',
                    'icon_color' => 'text-indigo-600',
                    'icon_bg' => 'bg-indigo-50',
                    'tag_class' => 'bg-indigo-50 text-indigo-700 ring-indigo-700/10',
                    'route' => 'desa.ppid.index',
                    'enabled' => (bool) ($user->is_ppid_active ?? false),
                    'locked_text' => 'Modul PPID belum diaktifkan oleh admin kabupaten.',
                    'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/></svg>',
                ],
                [
                    'nama' => 'Antikorupsi Desa',
                    'deskripsi' => 'Transparansi penganggaran, manajemen berkas dana desa, dan pemenuhan indikator desa bebas korupsi.',
                    'tag' => 'Regulasi',
                    'icon_color' => 'text-emerald-600',
                    'icon_bg' => 'bg-emerald-50',
                    'tag_class' => 'bg-emerald-50 text-emerald-700 ring-emerald-700/10',
                    'route' => 'desa.antikorupsi.index',
                    'enabled' => (bool) ($user->is_antikorupsi_active ?? false),
                    'locked_text' => 'Modul Antikorupsi belum diaktifkan oleh admin kabupaten.',
                    'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l7 3v5c0 5-3.5 9-7 10-3.5-1-7-5-7-10V6l7-3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>',
                ],
                [
                    'nama' => 'SKM Desa',
                    'deskripsi' => 'Kelola Survei Kepuasan Masyarakat, pantau nilai layanan, dan rekap hasil evaluasi pelayanan desa.',
                    'tag' => 'Survei',
                    'icon_color' => 'text-orange-600',
                    'icon_bg' => 'bg-orange-50',
                    'tag_class' => 'bg-orange-50 text-orange-700 ring-orange-700/10',
                    'route' => 'desa.skm.index',
                    'enabled' => (bool) ($user->is_skm_active ?? false),
                    'locked_text' => 'Modul SKM belum diaktifkan oleh admin kabupaten.',
                    'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 4h10a2 2 0 012 2v14l-4-2-3 2-3-2-4 2V6a2 2 0 012-2z"/></svg>',
                ],
                [
                    'nama' => 'Perpanjangan Domain Desa',
                    'deskripsi' => 'Monitoring status masa aktif, kelengkapan berkas administrasi, dan pengajuan ekstensi domain desa.id.',
                    'tag' => 'Wajib',
                    'icon_color' => 'text-orange-600',
                    'icon_bg' => 'bg-orange-50',
                    'tag_class' => 'bg-emerald-50 text-emerald-700 ring-emerald-700/10',
                    'route' => 'desa.domain.index',
                    'enabled' => true,
                    'locked_text' => null,
                    'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3a9 9 0 100 18 9 9 0 000-18z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h18M12 3c2.5 2.5 4 5.5 4 9s-1.5 6.5-4 9M12 3c-2.5 2.5-4 5.5-4 9s1.5 6.5 4 9"/></svg>',
                ],
                [
                    'nama' => 'Perpanjangan SSL Desa',
                    'deskripsi' => 'Manajemen enkripsi keamanan website, verifikasi masa berlaku sertifikat SSL sub-domain desa.',
                    'tag' => 'Wajib',
                    'icon_color' => 'text-rose-600',
                    'icon_bg' => 'bg-rose-50',
                    'tag_class' => 'bg-emerald-50 text-emerald-700 ring-emerald-700/10',
                    'route' => 'desa.ssl.index',
                    'enabled' => true,
                    'locked_text' => null,
                    'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11V7a4 4 0 118 0v4M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"/></svg>',
                ],
                [
                'nama' => 'Layanan Aduan Desa',
                'deskripsi' => 'Kelola aduan masyarakat, tindak lanjut laporan, dan dokumentasi penyelesaian aduan desa.',
                'tag' => 'Aduan',
                'icon_color' => 'text-rose-600',
                'icon_bg' => 'bg-rose-50',
                'tag_class' => 'bg-rose-50 text-rose-700 ring-rose-700/10',
                'route' => 'desa.aduan.index',
                'enabled' => (bool) ($user->is_aduan_active ?? false),
                'locked_text' => 'Modul Layanan Aduan belum diaktifkan oleh admin kabupaten.',
                'icon' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h5m-9 6l2.5-4.5A8 8 0 1120 10a8 8 0 01-12.7 6.5L4 20z"/></svg>',
            ],
            ];
        @endphp

        <div class="py-6 sm:py-10 px-3 sm:px-4 bg-slate-50 min-h-screen">
            <div class="max-w-5xl mx-auto">
                <div class="mb-6 sm:mb-8 relative overflow-hidden rounded-2xl sm:rounded-[2.5rem] p-5 sm:p-8 text-white shadow-lg" style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }});">
                    <div style="position: absolute; right: -50px; top: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    
                        <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-6">
                            <div class="flex items-start sm:items-center gap-3 sm:gap-5 flex-1 min-w-0">
                                <div class="w-14 sm:w-[75px] h-14 sm:h-[75px] flex-shrink-0 bg-white rounded-2xl flex items-center justify-center p-2 shadow-lg">
                                    @if($desa->logo)
                                        <img src="{{ Storage::url($desa->logo) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                    @else
                                        <img src="{{ asset('img/logo-beltim.png') }}" class="max-w-[80%] opacity-50">
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-1 opacity-90">
                                        Terintegrasi Administrasi, Regulasi, Statistik, Informasi, dan Urusan Desa
                                    </p>
                                    <h1 class="text-base sm:text-2xl font-black uppercase italic leading-tight break-words">
                                        LAYANAN TARSIUS DESA {{ $desa->nama_desa ?? 'TIDAK TERHUBUNG' }}
                                    </h1>
                                </div>
                            </div>
                            <a href="{{ route('desa.settings.edit') }}" class="flex-shrink-0 px-3 sm:px-5 py-2 sm:py-3 rounded-lg sm:rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-wide whitespace-nowrap border border-white/30 bg-white/20 hover:bg-white/30 backdrop-blur-sm transition" style="text-decoration: none;">
                                ⚙️ Pengaturan
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($layananDesa as $layanan)
                            @php
                                $enabled = $layanan['enabled'] ?? false;
                                $routeExists = isset($layanan['route']) && \Illuminate\Support\Facades\Route::has($layanan['route']);
                                $canOpen = $enabled && $routeExists;
                            @endphp

                            @if($canOpen)
                                <div class="card-glowing transition-transform duration-300 hover:-translate-y-1">
                                    
                                    <a href="{{ route($layanan['route']) }}"
                                    class="relative z-10 block w-full h-full rounded-[2rem] bg-white theme-bg-card p-8 shadow-sm">
                                    
                                        <div class="relative z-20">
                                            <div class="flex items-start justify-between gap-4 mb-8">
                                                <div class="w-14 h-14 rounded-2xl {{ $enabled ? $layanan['icon_bg'] : 'bg-slate-100' }} {{ $enabled ? $layanan['icon_color'] : 'text-slate-400' }} flex items-center justify-center">
                                                    {!! $layanan['icon'] !!}
                                                </div>
                                                <span class="inline-flex items-center rounded-lg px-3 py-1 text-xs font-bold ring-1 ring-inset {{ $enabled ? $layanan['tag_class'] : 'bg-slate-100 text-slate-500 ring-slate-200' }}">
                                                    {{ $enabled ? $layanan['tag'] : 'Belum Aktif' }}
                                                </span>
                                            </div>
                                                <h3 class="text-xl font-black text-slate-900 theme-text-main">
                                                    {{ $layanan['nama'] }}
                                                </h3>
                                                <p class="mt-4 text-sm leading-relaxed text-slate-500 theme-text-sub">
                                                    {{ $layanan['deskripsi'] }}
                                                </p>
                                            <div class="mt-8 border-t border-slate-100 theme-border pt-6">
                                                <span class="text-sm font-black text-blue-600 uppercase tracking-widest">
                                                    Masuk Layanan →
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @else
                                <div class="group relative overflow-hidden rounded-[2rem] bg-white/70 theme-bg-card border border-slate-200 theme-border p-8 shadow-sm opacity-75">
                                    <div class="absolute inset-0 bg-slate-50/70 theme-bg-main/70 backdrop-blur-[1px] z-10"></div>
                                        <div class="absolute top-5 right-5 z-20 inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-4 py-2 text-[9px] font-black uppercase tracking-widest text-white">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11V7a4 4 0 118 0v4M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"/>
                                            </svg>
                                            Terkunci
                                        </div>
                                    <div class="relative z-20">
                                        <div class="flex items-start justify-between gap-4 mb-8">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                                {!! $layanan['icon'] !!}
                                            </div>
                                            <span class="inline-flex items-center rounded-lg px-3 py-1 text-xs font-bold ring-1 ring-inset bg-slate-100 text-slate-500 ring-slate-200">
                                                Belum Aktif
                                            </span>
                                        </div>
                                        <h3 class="text-xl font-black text-slate-900 theme-text-main">
                                            {{ $layanan['nama'] }}
                                        </h3>
                                        <p class="mt-4 text-sm leading-relaxed text-slate-500 theme-text-sub">
                                            {{ $layanan['locked_text'] }}
                                        </p>
                                        <div class="mt-8 border-t border-slate-100 theme-border pt-6">
                                            <span class="text-sm font-black text-slate-400 uppercase tracking-widest">
                                                Menunggu Aktivasi Admin Kabupaten
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <p class="mt-8 sm:mt-12 text-center text-slate-400 text-[8px] sm:text-[10px] font-black uppercase tracking-[0.4em]">
                Diskominfo Belitung Timur &bull; {{ date('Y') }}
            </p>

        </div>
    </div>

    <style>
        @property --angle {
            syntax: "<angle>";
            initial-value: 0deg;
            inherits: false;
        }

        .card-glowing {
            position: relative;
            z-index: 1;
            padding: 3px; 
            border-radius: 2.1rem; 
        }

        .card-glowing::after, 
        .card-glowing::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: conic-gradient(from var(--angle), #ff4545, #00ff99, #006aff, #ff0095, #ff4545);
            z-index: -1;
            border-radius: inherit;
            animation: 3s spin linear infinite;
            opacity: 0; 
            transition: opacity 0.4s ease; 
        }

        .card-glowing:hover::after {
            opacity: 1; 
        }
        
        .card-glowing:hover::before {
            filter: blur(1.5rem);
            opacity: 0.5; 
        }

        @keyframes spin {
            from { --angle: 0deg; }
            to { --angle: 360deg; }
        }
    </style>
</x-app-layout>