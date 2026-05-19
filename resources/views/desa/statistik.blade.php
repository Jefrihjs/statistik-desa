<x-app-layout>
    @php
        $headerColor = $desa->header_color ?? '#2563eb';
        $accentColor = $desa->accent_color ?? '#10b981';
        
        // Perhitungan Statistik Real-time
        $totalTerisi = $statusPengisian->sum('terisi');
        $totalIndikator = $statusPengisian->sum('total_indikator');
        $persen = $totalIndikator > 0 ? round(($totalTerisi / $totalIndikator) * 100) : 0;
    @endphp

    <div class="py-10 px-4 bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto">
            
            <div class="mb-4">
                <a href="{{ route('desa.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Hub Utama TARSIUS
                </a>
            </div>

            <div style="background: linear-gradient(135deg, {{ $headerColor }}, {{ $accentColor }}); border-radius: 2.5rem; padding: 35px; color: white; margin-bottom: 2rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); position: relative; overflow: hidden;">
                <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 10;">
                    <div>
                        <p style="font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 2px; opacity: 0.9;">TARSIUS &bull; Sub-Layanan Statistik</p>
                        <h1 style="font-size: 26px; font-weight: 900; text-transform: uppercase; font-style: italic; line-height: 1;">
                            PANEL STATISTIK SEKTORAL
                        </h1>
                    </div>
                    <span style="font-size: 24px; background: rgba(255,255,255,0.2); padding: 10px; border-radius: 1rem;">📊</span>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 2rem;">
                
                <a href="/admin/entri/{{ auth()->user()->desa_id }}?tahun={{ $tahunAktif }}" style="text-decoration: none;">
                    <div style="background: white; padding: 30px; border-radius: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; transition: all 0.3s; height: 100%; display: flex; align-items: center; gap: 20px;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='{{ $headerColor }}'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#f1f5f9'">
                        <div style="width: 60px; height: 60px; background: {{ $headerColor }}20; color: {{ $headerColor }}; border-radius: 1.25rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg style="width: 30px; height: 30px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <div>
                            <h3 style="font-size: 16px; font-weight: 900; color: #1e293b; margin: 0;">INPUT DATA DI SINI</h3>
                            <p style="font-size: 11px; color: #64748b; margin-top: 4px;">Entri statistik sektoral tahun <b style="color: {{ $headerColor }}">{{ $tahunAktif }}</b></p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.download-template') }}?tahun={{ $tahunAktif }}" style="text-decoration: none;">
                    <div style="background: white; padding: 30px; border-radius: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; transition: all 0.3s; height: 100%; display: flex; align-items: center; gap: 20px;" onmouseover="this.style.transform='translateY(-5px)'; this.style.borderColor='{{ $accentColor }}'" onmouseout="this.style.transform='translateY(0)'; this.style.borderColor='#f1f5f9'">
                        <div style="width: 60px; height: 60px; background: {{ $accentColor }}20; color: {{ $accentColor }}; border-radius: 1.25rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg style="width: 30px; height: 30px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h3 style="font-size: 16px; font-weight: 900; color: #1e293b; margin: 0;">TEMPLATE EXCEL</h3>
                            <p style="font-size: 11px; color: #64748b; margin-top: 4px;">Unduh format impor data {{ $tahunAktif }}</p>
                        </div>
                    </div>
                </a>
            </div>

            <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 2rem;">
                <div style="flex: 1; min-width: 200px; padding: 25px; background: white; border-radius: 1.5rem; border: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div>
                        <p style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">Pilih Tahun Data</p>
                        <form method="GET" action="" style="margin: 0; padding: 0;">
                            <select name="tahunAktif" onchange="this.form.submit()" 
                                    style="font-size: 20px; font-weight: 900; color: #1e293b; background-color: #f8fafc; border: 2px solid #e2e8f0; border-radius: 1rem; padding: 10px 16px; width: 100%; cursor: pointer; outline: none; transition: 0.2s ease-in-out; box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.02);"
                                    onmouseover="this.style.borderColor='#94a3b8'; this.style.backgroundColor='#f1f5f9';" 
                                    onmouseout="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#f8fafc';">
                                @for($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $tahunAktif == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                                @endfor
                            </select>
                        </form>
                    </div>
                    <span style="background: #f1f5f9; padding: 8px; border-radius: 0.75rem; font-size: 20px;">📅</span>
                </div>

                <div style="flex: 1; min-width: 200px; padding: 25px; background: white; border-radius: 1.5rem; border: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <div>
                        <p style="font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em;">Status Sinkronisasi</p>
                        <p style="font-size: 24px; font-weight: 900; color: {{ $headerColor }};">AKTIF</p>
                    </div>
                    <span style="background: {{ $headerColor }}10; padding: 8px; border-radius: 0.75rem; font-size: 20px;">✅</span>
                </div>
            </div>

            <div style="background: white; border-radius: 2.5rem; padding: 35px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 20px;">
                    <div>
                        <p style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 5px;">Progress Data Tahun {{ $tahunAktif }}</p>
                        <h2 style="font-size: 32px; font-weight: 900; color: #1e3a8a; line-height: 1;">{{ $persen }}% <span style="font-size: 12px; color: #94a3b8; font-style: italic; font-weight: 700;">Selesai</span></h2>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 10px; font-weight: 800; color: {{ $headerColor }}; background: {{ $headerColor }}10; padding: 5px 12px; border-radius: 2rem;">
                            {{ $totalTerisi }} / {{ $totalIndikator }} Indikator
                        </span>
                    </div>
                </div>

                <div style="width: 100%; height: 12px; background: #f1f5f9; border-radius: 10px; overflow: hidden; margin-bottom: 30px;">
                    <div style="height: 100%; background: linear-gradient(90deg, {{ $headerColor }}, {{ $accentColor }}); border-radius: 10px; width: {{ $persen }}%; transition: width 0.5s ease-in-out;"></div>
                </div>

                <div style="border: 1px solid #f8fafc; border-radius: 1.5rem; overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
                        <thead>
                            <tr style="background: #f8fafc; text-align: left;">
                                <th style="padding: 12px 20px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em;">Kategori Indikator</th>
                                <th style="padding: 12px 20px; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statusPengisian as $stat)
                            <tr style="border-bottom: 1px solid #f8fafc;">
                                <td style="padding: 15px 20px; font-weight: 800; color: #334155; text-transform: uppercase;">{{ $stat->name }}</td>
                                <td style="padding: 15px 20px; text-align: center;">
                                    @if($stat->terisi == $stat->total_indikator)
                                        <span style="color: #10b981; font-weight: 900; background: #ecfdf5; padding: 4px 10px; border-radius: 2rem; font-size: 9px;">✓ LENGKAP</span>
                                    @elseif($stat->terisi > 0)
                                        <span style="color: #3b82f6; font-weight: 900; background: #eff6ff; padding: 4px 10px; border-radius: 2rem; font-size: 9px;">⚡ {{ $stat->terisi }}/{{ $stat->total_indikator }}</span>
                                    @else
                                        <span style="color: #94a3b8; font-weight: 900; background: #f8fafc; padding: 4px 10px; border-radius: 2rem; font-size: 9px;">○ KOSONG</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <p style="margin-top: 40px; text-align: center; color: #cbd5e1; font-weight: 800; font-size: 10px; text-transform: uppercase; letter-spacing: 0.4em;">
                Diskominfo Belitung Timur &bull; {{ date('Y') }}
            </p>

        </div>
    </div>
</x-app-layout>