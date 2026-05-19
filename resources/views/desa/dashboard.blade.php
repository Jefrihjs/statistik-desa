<x-app-layout>
    @php
        $headerColor = $desa->header_color ?? '#2563eb';
        $accentColor = $desa->accent_color ?? '#10b981';

        // Silakan sesuaikan nomor WA Admin Kabupaten di bawah ini
        $waAdminKabupaten = '6281234567890'; 

        // 1. PENGKONDISIAN CARD: PERPANJANGAN DOMAIN DESA (Dari DB)
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

        // 2. PENGKONDISIAN CARD: PERPANJANGAN SSL DESA (Dari Live Check Certificate)
        $sslTag = $sslInfo['pesan'];
        
        if ($tracker && $sslInfo['status'] === 'active') {
            // Jika sisa hari SSL di bawah 15 hari, beri warna merah kritis
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

        // Susunan Array Menu Utama TARSIUS Desa
        $layananDesa = [
            [
                'nama' => 'Statistik Desa',
                'deskripsi' => 'Pengelolaan data demografi, entri statistik sektoral, unduh format excel, dan monitoring keterisian data.',
                'icon' => '<svg width="24" height="24" class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
                'tag' => 'Statistik',
                'tag_color' => 'bg-blue-50 text-blue-700 ring-blue-700/10',
                'url' => route('desa.statistik'),
            ],
            [
                'nama' => 'PPID Desa',
                'deskripsi' => 'Layanan pengelolaan keterbukaan informasi publik, dokumen berkala, serta permohonan informasi masyarakat.',
                'icon' => '<svg width="24" height="24" class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>',
                'tag' => 'Informasi',
                'tag_color' => 'bg-indigo-50 text-indigo-700 ring-indigo-700/10',
                'url' => '#',
            ],
            [
                'nama' => 'Antikorupsi Desa',
                'deskripsi' => 'Transparansi penganggaran, manajemen berkas dana desa, dan pemenuhan indikator desa bebas korupsi.',
                'icon' => '<svg width="24" height="24" class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>',
                'tag' => 'Regulasi',
                'tag_color' => 'bg-emerald-50 text-emerald-700 ring-emerald-700/10',
                'url' => route('desa.antikorupsi.index'),
            ],
            [
                'nama' => 'Perpanjangan Domain Desa',
                'deskripsi' => 'Monitoring status masa aktif, kelengkapan berkas administrasi, dan pengajuan ekstensi domain desa.id.',
                'icon' => '<svg width="24" height="24" class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>',
                'tag' => $domainTag,
                'tag_color' => $domainTagColor,
                'url' => $domainUrl,
            ],
            [
                'nama' => 'Perpanjangan SSL Desa',
                'deskripsi' => 'Manajemen enkripsi keamanan website, verifikasi masa berlaku sertifikat SSL sub-domain desa.',
                'icon' => '<svg width="24" height="24" class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>',
                'tag' => $sslTag,
                'tag_color' => $sslTagColor,
                'url' => $sslUrl,
            ],
        ];
    @endphp

    <div class="py-10 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto">
            
            <div style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }}); border-radius: 2.5rem; padding: 35px; color: white; margin-bottom: 2rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); position: relative; overflow: hidden;">
                <div style="position: absolute; right: -50px; top: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 10; flex-wrap: wrap; gap: 20px;">
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <div style="width: 75px; height: 75px; background: white; border-radius: 1.25rem; display: flex; align-items: center; justify-content: center; padding: 8px; box-shadow: 0 10px 15px rgba(0,0,0,0.1); flex-shrink: 0;">
                            @if($desa->logo)
                                <img src="{{ Storage::url($desa->logo) }}" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            @else
                                <img src="{{ asset('img/logo-beltim.png') }}" style="max-width: 80%; opacity: 0.5;">
                            @endif
                        </div>
                        <div>
                            <p style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 2px; opacity: 0.9;">
                                Terintegrasi Administrasi, Regulasi, Statistik, Informasi, dan Urusan Desa
                            </p>
                            <h1 style="font-size: 26px; font-weight: 900; text-transform: uppercase; font-style: italic; line-height: 1;">
                                LAYANAN TARSIUS DESA {{ $desa->nama_desa ?? 'TIDAK TERHUBUNG' }}
                            </h1>
                        </div>
                    </div>
                    <a href="{{ route('desa.settings.edit') }}" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); color: white; padding: 12px 20px; border-radius: 1rem; text-decoration: none; font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em; border: 1px solid rgba(255,255,255,0.3); transition: 0.3s;">
                        ⚙️ Pengaturan Branding
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($layananDesa as $layanan)
                    <div class="relative flex flex-col justify-between overflow-hidden rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md hover:border-slate-300 group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-11 h-11 flex-shrink-0 flex items-center justify-center rounded-xl bg-slate-50 border border-slate-100 group-hover:scale-105 transition-transform duration-200">
                                    {!! $layanan['icon'] !!}
                                </div>
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $layanan['tag_color'] }}">
                                    {{ $layanan['tag'] }}
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-slate-800 group-hover:text-blue-600 transition-colors duration-150">
                                {{ $layanan['nama'] }}
                            </h3>
                            <p class="mt-2 text-xs leading-relaxed text-slate-500">
                                {{ $layanan['deskripsi'] }}
                            </p>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <a href="{{ $layanan['url'] }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-700 gap-1 group/btn">
                                Masuk Layanan 
                                <svg width="14" height="14" class="w-3.5 h-3.5 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <p style="margin-top: 50px; text-align: center; color: #cbd5e1; font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 0.4em;">
                Diskominfo Belitung Timur &bull; {{ date('Y') }}
            </p>

        </div>
    </div>
</x-app-layout>