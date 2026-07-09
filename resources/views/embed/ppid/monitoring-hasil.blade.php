<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Permohonan</title>
    <style>
        body {
            margin: 0;
            background: #f8fafc;
            font-family: Arial, sans-serif;
            color: #0f172a;
        }

        .wrap {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .top {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 30px;
        }

        h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 900;
        }

        h1 span {
            color: #2563eb;
        }

        .badge {
            background: #2563eb;
            color: white;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        .card {
            background: white;
            border-radius: 26px;
            padding: 34px;
            border: 1px solid #e2e8f0;
            margin-bottom: 28px;
        }

        .profile {
            display: flex;
            gap: 20px;
            align-items: center;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 24px;
        }

        .name {
            font-size: 20px;
            font-weight: 900;
        }

        .date {
            color: #94a3b8;
            font-size: 12px;
            font-style: italic;
            margin-top: 4px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-top: 24px;
        }

        .info-label {
            font-size: 9px;
            color: #94a3b8;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .12em;
        }

        .info-value {
            margin-top: 6px;
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .status {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 6px;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .status-pending { background: #fff7ed; color: #ea580c; }
        .status-diproses { background: #eff6ff; color: #2563eb; }
        .status-selesai { background: #ecfdf5; color: #059669; }
        .status-ditolak { background: #fff1f2; color: #e11d48; }
        .status-tidak_lengkap { background: #fffbeb; color: #d97706; }

        .tab {
            display: inline-block;
            background: #0f172a;
            color: white;
            padding: 11px 25px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .timeline {
            background: white;
            border-radius: 26px;
            border: 1px solid #e2e8f0;
            padding: 34px;
        }

        .step {
            display: flex;
            gap: 18px;
            margin-bottom: 28px;
            position: relative;
        }

        .num {
            width: 24px;
            height: 24px;
            background: #2563eb;
            color: white;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .num.green { background: #22c55e; }
        .num.red { background: #ef4444; }
        .num.orange { background: #f59e0b; }

        .step-title {
            font-weight: 900;
            margin-bottom: 6px;
        }

        .step-date {
            color: #2563eb;
            font-size: 10px;
            font-weight: 900;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        .step-desc {
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
        }

        .alert {
            margin-top: 20px;
            padding: 24px;
            border-radius: 22px;
            border: 1px solid #fecaca;
            background: #fff1f2;
        }

        .alert h3 {
            margin: 0 0 10px;
            color: #b91c1c;
            font-size: 18px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .alert p {
            color: #991b1b;
            font-style: italic;
            margin: 0 0 18px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            background: #d97706;
            color: white;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .tab-outline {
            background: #fff1f2 !important;
            color: #e11d48 !important;
            border: 1px solid #fecdd3;
        }

        @media(max-width: 700px) {
            .top, .profile {
                flex-direction: column;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        .permohonan-card {
    overflow: hidden;
    padding-bottom: 0;
}

.avatar {
    width: 74px;
    height: 74px;
    background: #f1f5f9;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    position: relative;
    flex-shrink: 0;
}

.avatar::after {
    content: "✓";
    position: absolute;
    right: -10px;
    bottom: -10px;
    width: 34px;
    height: 34px;
    border-radius: 999px;
    background: #10b981;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: 900;
    box-shadow: 0 8px 18px rgba(16, 185, 129, .25);
}

.card-footer-actions {
    margin: 30px -34px 0;
    padding: 22px 34px;
    background: #f8fafc;
    border-top: 1px solid #eef2f7;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    border-radius: 0 0 26px 26px;
}

.left-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.detail-btn {
    min-width: 210px;
    height: 42px;
    padding: 0 24px;
    border-radius: 10px;
    border: 1px solid #dbe3ee;
    background: #ffffff;
    color: #0f172a;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: .16em;
    text-transform: uppercase;
    cursor: pointer;
    box-shadow: 0 6px 14px rgba(15, 23, 42, .04);
}

.print-btn {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    border: 1px solid #dbe3ee;
    background: #ffffff;
    color: #334155;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 6px 14px rgba(15, 23, 42, .04);
}

.detail-btn:hover,
.print-btn:hover {
    background: #f8fafc;
}

.database-id {
    font-size: 10px;
    color: #94a3b8;
    font-weight: 900;
    font-style: italic;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.tabs {
    text-align: center;
    margin: 34px 0 22px;
}

@media(max-width: 700px) {
    .card-footer-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .left-actions {
        width: 100%;
    }

    .detail-btn {
        flex: 1;
        min-width: 0;
    }

    .database-id {
        text-align: right;
    }
}

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .72);
            backdrop-filter: blur(6px);
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .modal-box {
            width: 100%;
            max-width: 880px;
            max-height: 90vh;
            overflow: hidden;
            background: white;
            border-radius: 34px;
            box-shadow: 0 24px 80px rgba(15, 23, 42, .25);
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            padding: 28px 32px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 14px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .14em;
        }

        .modal-close {
            width: 46px;
            height: 46px;
            border-radius: 999px;
            border: 0;
            background: #f8fafc;
            color: #94a3b8;
            font-size: 28px;
            cursor: pointer;
        }

        .modal-body {
            padding: 32px 38px;
            overflow-y: auto;
        }

        .modal-disabled {
            background: #e2e8f0 !important;
            color: #94a3b8 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
            pointer-events: none;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 28px 40px;
        }

        .detail-grid .full {
            grid-column: 1 / -1;
        }

        .detail-label {
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .detail-value {
            font-size: 15px;
            font-weight: 800;
            color: #334155;
            line-height: 1.5;
        }

        .text-blue {
            color: #2563eb;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .lampiran-section {
            margin-top: 34px;
            padding-top: 28px;
            border-top: 1px solid #f1f5f9;
        }

        .lampiran-card {
            display: inline-flex;
            margin-top: 12px;
            padding: 14px 18px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #2563eb;
            font-size: 12px;
            font-weight: 900;
            text-decoration: none;
            text-transform: uppercase;
        }

        .lampiran-empty {
            margin-top: 12px;
            padding: 14px 18px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            color: #94a3b8;
            font-size: 12px;
            font-weight: 900;
        }

        .modal-footer {
            padding: 24px 38px;
            border-top: 1px solid #f1f5f9;
            background: #f8fafc;
            display: flex;
            justify-content: flex-end;
            gap: 14px;
        }

        .modal-btn {
            min-width: 150px;
            padding: 15px 22px;
            border-radius: 16px;
            border: 0;
            text-decoration: none;
            text-align: center;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
            cursor: pointer;
        }

        .modal-green {
            background: #10b981;
            color: white;
        }

        .modal-dark {
            background: #0f172a;
            color: white;
        }

        @media(max-width: 700px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .modal-footer {
                flex-direction: column;
            }

            .modal-btn {
                width: 100%;
            }
        }

        .modal-keberatan {
            max-width: 720px;
        }

        .keberatan-summary {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .keberatan-summary .full {
            grid-column: 1 / -1;
        }

        .summary-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 18px 20px;
        }

        .summary-label {
            font-size: 10px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .14em;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 15px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.5;
        }

        .section-divider {
            height: 1px;
            background: #eef2f7;
            margin: 26px 0;
        }

        .text-helper {
            margin: 0 0 22px;
            text-align: center;
            font-size: 11px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .14em;
        }

        .keberatan-list {
            display: grid;
            gap: 10px;
            margin-bottom: 26px;
        }

        .keberatan-option {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 18px;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            cursor: pointer;
            transition: .2s;
        }

        .keberatan-option:hover {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .keberatan-option input {
            margin-top: 3px;
        }

        .keberatan-option span {
            font-size: 13px;
            font-weight: 800;
            color: #475569;
            line-height: 1.5;
        }

        .keberatan-option strong {
            color: #2563eb;
            margin-right: 4px;
        }

        .field-block {
            margin-top: 20px;
        }

        .field-block label {
            display: block;
            margin-bottom: 10px;
            font-size: 10px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .14em;
        }

        .field-block .hint {
            color: #64748b;
            font-style: italic;
            letter-spacing: normal;
            text-transform: none;
            font-size: 11px;
        }

        .textarea-keberatan {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 16px;
            font-size: 14px;
            outline: none;
            resize: vertical;
            background: #ffffff;
        }

        .textarea-keberatan:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .08);
        }

        .agreement-box {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 18px 20px;
            border-radius: 20px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            margin-top: 20px;
        }

        .agreement-box input {
            margin-top: 4px;
            width: 18px;
            height: 18px;
            accent-color: #ea580c;
            flex-shrink: 0;
        }

        .agreement-box span {
            font-size: 13px;
            font-weight: 800;
            color: #9a3412;
            line-height: 1.6;
        }
        .no-border {
            border-top: 0;
            padding: 24px 0 0;
        }

        .modal-light {
            background: #f1f5f9;
            color: #64748b;
        }

        @media(max-width: 700px) {
            .keberatan-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @php
        $statusClass = 'status-' . $permohonan->status;
    @endphp

    <div class="wrap">
        <div class="top">
            <div>
                <h1>Status <span>Permohonan</span></h1>
                <p style="margin-top:6px;color:#475569;">Lacak progres permintaan informasi Anda secara transparan.</p>
            </div>

            <div class="badge">
                Kode Permohonan<br>
                {{ strtoupper($permohonan->kode_permohonan) }}
            </div>
        </div>

        <div class="card permohonan-card">
            <div class="profile">
                <div class="avatar">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none">
                        <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5Z" fill="#94a3b8"/>
                        <path d="M4 22c0-4.418 3.582-8 8-8s8 3.582 8 8" fill="#94a3b8"/>
                    </svg>
                </div>

                <div>
                    <div class="name">{{ $permohonan->nama }}</div>
                    <div class="date">
                        Diajukan pada {{ $permohonan->created_at->translatedFormat('l, d F Y') }}
                    </div>
                </div>
            </div>

            <div class="info-grid">
                <div>
                    <div class="info-label">Kategori</div>
                    <div class="info-value">{{ strtoupper($permohonan->kategori_pemohon) }}</div>
                </div>

                <div>
                    <div class="info-label">No. Ponsel</div>
                    <div class="info-value">{{ $permohonan->no_hp }}</div>
                </div>

                <div>
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="status {{ $statusClass }}">
                            {{ strtoupper(str_replace('_', ' ', $permohonan->status)) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-footer-actions">
                <div class="left-actions">
                    <button type="button"
                            onclick="openDetailModal()"
                            class="detail-btn">
                        Detail Permohonan
                    </button>

                    <a href="{{ route('embed.ppid.permohonan.bukti', [$desa->slug, $permohonan->kode_permohonan]) }}"
                    target="_blank"
                    class="print-btn"
                    title="Cetak Bukti Permohonan">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M7 8V3h10v5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 17H5a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 14h10v7H7v-7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <div class="database-id">
                    ID DATABASE #{{ $permohonan->id }}
                </div>
            </div>
        </div>

       <div class="tabs">
            <span class="tab">Riwayat Progres</span>

            <button type="button"
                   @if(isset($keberatan) && $keberatan)
                        <span class="tab tab-outline" style="margin-left:8px;">
                            Keberatan Diajukan
                        </span>
                    @else
                        <button type="button"
                                onclick="openKeberatanModal()"
                                class="tab tab-outline"
                                style="margin-left:8px;border:none;cursor:pointer;">
                            Ajukan Keberatan
                        </button>
                    @endif
            </button>
        </div>
        

        <div class="timeline">
            <div class="step">
                <div class="num">01</div>
                <div>
                    <div class="step-date">{{ $permohonan->created_at->translatedFormat('d M Y - H:i') }} WIB</div>
                    <div class="step-title">Permohonan Terkirim</div>
                    <div class="step-desc">
                        Permohonan informasi Anda telah masuk ke sistem PPID Desa {{ $desa->nama_desa }}.
                    </div>
                </div>
            </div>

            @if(in_array($permohonan->status, ['diproses', 'selesai', 'ditolak', 'tidak_lengkap']))
                <div class="step">
                    <div class="num green">02</div>
                    <div>
                        <div class="step-date">{{ $permohonan->updated_at->translatedFormat('d M Y - H:i') }} WIB</div>
                        <div class="step-title">Permohonan Telah Diverifikasi</div>
                        <div class="step-desc">
                            Tim PPID telah memeriksa berkas identitas dan rincian permohonan Anda.
                        </div>
                    </div>
                </div>
            @endif

            @if($permohonan->status === 'diproses')
                <div class="step">
                    <div class="num green">03</div>
                    <div>
                        <div class="step-title">Permohonan Sedang Diproses</div>
                        <div class="step-desc">
                            Informasi sedang disiapkan oleh PPID Desa.
                        </div>
                    </div>
                </div>
            @endif

            @if($permohonan->status === 'selesai')
                <div class="step">
                    <div class="num green">03</div>
                    <div>
                        <div class="step-title">Permohonan Selesai</div>
                        <div class="step-desc">
                            Permohonan informasi telah selesai diproses.
                        </div>

                        @if($permohonan->file_penyelesaian)
                            <a href="{{ asset('storage/' . $permohonan->file_penyelesaian) }}" target="_blank" class="btn">
                                Unduh Bukti Penyelesaian
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            @if(in_array($permohonan->status, ['ditolak', 'tidak_lengkap']))
                <div class="alert">
                    <h3>
                        {{ $permohonan->status === 'ditolak' ? 'Permohonan Tidak Dapat Dipenuhi' : 'Permohonan Tidak Lengkap' }}
                    </h3>

                    <p>
                        "{{ $permohonan->catatan_admin ?? 'Permohonan belum dapat diproses lebih lanjut.' }}"
                    </p>

                    @if($permohonan->status === 'tidak_lengkap')
                        <a href="{{ route('embed.ppid.permohonan.tidak_lengkap', [$desa->slug, $permohonan->kode_permohonan]) }}"
                        target="_blank"
                        class="btn">
                            Unduh Pemberitahuan Tidak Lengkap (PDF)
                        </a>

                    @elseif($pemberitahuan)
                        @if(
                            $pemberitahuan->status_informasi === 'tidak_dapat_diberikan'
                            && $pemberitahuan->alasan_penolakan === 'informasi_dikecualikan'
                        )
                            <a href="{{ route('embed.ppid.permohonan.sk_penolakan', [$desa->slug, $permohonan->kode_permohonan]) }}"
                            target="_blank"
                            class="btn">
                                Unduh SK Penolakan (PDF)
                            </a>
                        @else
                            <a href="{{ route('embed.ppid.permohonan.pemberitahuan', [$desa->slug, $permohonan->kode_permohonan]) }}"
                            target="_blank"
                            class="btn">
                                Unduh Surat Pemberitahuan (PDF)
                            </a>
                        @endif
                    @endif
                </div>
            @endif

            @if(isset($keberatan) && $keberatan)
                <div class="step">
                    <div class="num orange">04</div>
                    <div>
                        <div class="step-date">
                            {{ $keberatan->created_at->translatedFormat('d M Y - H:i') }} WIB
                        </div>

                        <div class="step-title">
                            Keberatan Diajukan
                        </div>

                        <div class="step-desc">
                            Keberatan telah diajukan dengan alasan:
                            <strong>{{ $keberatan->label_alasan }}</strong>

                            @if($keberatan->uraian_alasan)
                                <br>
                                "{{ $keberatan->uraian_alasan }}"
                            @endif
                        </div>
                    </div>
                </div>

                @if($keberatan->tanggapan_admin)
                    <div class="step">
                        <div class="num green">05</div>
                        <div>
                            <div class="step-date">
                                {{ $keberatan->ditanggapi_pada ? $keberatan->ditanggapi_pada->translatedFormat('d M Y - H:i') : $keberatan->updated_at->translatedFormat('d M Y - H:i') }} WIB
                            </div>

                            <div class="step-title">
                                Tanggapan Keberatan Diberikan
                            </div>

                            <div class="step-desc">
                                Atasan PPID telah memberikan tanggapan atas ajuan keberatan Anda.

                                <br><br>
                                <strong>Tanggapan:</strong><br>
                                "{{ $keberatan->tanggapan_admin }}"

                                <br><br>
                                <strong>Ditanggapi oleh:</strong>
                                {{ $keberatan->nama_atasan_ppid ?? 'Atasan PPID' }}

                                @if($keberatan->posisi_atasan)
                                    — {{ $keberatan->posisi_atasan }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <div id="detailModal" class="modal-overlay" style="display:none;">
    <div class="modal-box">
        <div class="modal-header">
            <h2>
                Detail Permohonan #{{ $permohonan->nomor_pendaftaran }}
            </h2>

            <button type="button" onclick="closeDetailModal()" class="modal-close">
                ×
            </button>
        </div>

        <div class="modal-body">
            <div class="detail-grid">
                <div>
                    <div class="detail-label">Nomor Pendaftaran</div>
                    <div class="detail-value">
                        {{ $permohonan->nomor_pendaftaran }}
                    </div>
                </div>

                <div>
                    <div class="detail-label">Kategori Pemohon</div>
                    <div class="detail-value">
                        {{ ucfirst($permohonan->kategori_pemohon) }}
                    </div>
                </div>

                <div>
                    <div class="detail-label">Kode Permohonan</div>
                    <div class="detail-value text-blue">
                        {{ strtoupper($permohonan->kode_permohonan) }}
                    </div>
                </div>

                <div>
                    <div class="detail-label">NIK</div>
                    <div class="detail-value">
                        {{ $permohonan->nik }}
                    </div>
                </div>

                <div>
                    <div class="detail-label">Nama Lengkap</div>
                    <div class="detail-value">
                        {{ $permohonan->nama }}
                    </div>
                </div>

                <div>
                    <div class="detail-label">Email</div>
                    <div class="detail-value">
                        {{ $permohonan->email ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="detail-label">Telepon</div>
                    <div class="detail-value">
                        {{ $permohonan->no_hp }}
                    </div>
                </div>

                <div>
                    <div class="detail-label">Cara Memperoleh</div>
                    <div class="detail-value">
                        {{ $permohonan->cara_memperoleh ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="detail-label">Jenis Salinan</div>
                    <div class="detail-value">
                        {{ $permohonan->jenis_salinan ?? '-' }}
                    </div>
                </div>

                <div class="full">
                    <div class="detail-label">Alamat Domisili</div>
                    <div class="detail-value">
                        {{ $permohonan->alamat }}
                    </div>
                </div>

                <div class="full">
                    <div class="detail-label">Rincian Informasi</div>
                    <div class="detail-value">
                        {{ $permohonan->rincian_informasi }}
                    </div>
                </div>

                <div class="full">
                    <div class="detail-label">Tujuan Penggunaan</div>
                    <div class="detail-value">
                        {{ $permohonan->tujuan_penggunaan }}
                    </div>
                </div>
            </div>

            <div class="lampiran-section">
                <div class="detail-label">Lampiran Identitas (KTP)</div>

                @if($permohonan->file_ktp)
                    <a href="{{ asset('storage/' . $permohonan->file_ktp) }}"
                       target="_blank"
                       class="lampiran-card">
                        Lihat Lampiran KTP →
                    </a>
                @else
                    <div class="lampiran-empty">
                        Lampiran KTP tidak tersedia
                    </div>
                @endif
            </div>
        </div>

        <div class="modal-footer">
            <a href="{{ route('embed.ppid.permohonan.bukti', [$desa->slug, $permohonan->kode_permohonan]) }}"
               target="_blank"
               class="modal-btn modal-green">
                Cetak Data
            </a>

            <button type="button"
                    onclick="closeDetailModal()"
                    class="modal-btn modal-dark">
                Tutup
            </button>
        </div>
    </div>
</div>
<div id="keberatanModal" class="modal-overlay" style="display:none;">
    <div class="modal-box modal-keberatan">
        <div class="modal-header">
            <h2>Formulir Keberatan Resmi</h2>

            <button type="button" onclick="closeKeberatanModal()" class="modal-close">
                ×
            </button>
        </div>

        <form action="{{ route('embed.ppid.permohonan.keberatan.store', [$desa->slug, $permohonan->kode_permohonan]) }}"
              method="POST"
              class="modal-body">
            @csrf

            <div class="keberatan-summary">
                <div class="summary-item">
                    <div class="summary-label">Nomor Permohonan</div>
                    <div class="summary-value">
                        {{ $permohonan->nomor_pendaftaran }}
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-label">Tujuan Penggunaan Informasi</div>
                    <div class="summary-value">
                        {{ $permohonan->tujuan_penggunaan ?? '-' }}
                    </div>
                </div>

                <div class="summary-item full">
                    <div class="summary-label">
                        Hari/Tanggal Tanggapan Atas Keberatan Akan Diberikan
                    </div>
                    <div class="summary-value">
                        {{ now()->addDays(30)->translatedFormat('l, d F Y') }}
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

            <div class="field-block">
                <label>
                    Alasan Pengajuan Keberatan
                    <span class="hint">(boleh lebih dari 1)</span>
                </label>

                <div class="keberatan-list">
                    @foreach([
                        'A' => 'Permohonan informasi ditolak',
                        'B' => 'Informasi berkala tidak disediakan',
                        'C' => 'Permintaan informasi tidak ditanggapi',
                        'D' => 'Permintaan informasi ditanggapi tidak sebagaimana yang diminta',
                        'E' => 'Permintaan informasi tidak dipenuhi',
                        'F' => 'Biaya yang dikenakan tidak wajar',
                        'G' => 'Informasi disampaikan melebihi jangka waktu',
                    ] as $key => $val)
                        <label class="keberatan-option">
                            <input type="checkbox"
                                   name="alasan_keberatan[]"
                                   value="{{ $key }}">

                            <span>
                                {{ $val }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="field-block">
                <label>Kasus Posisi / Kronologi</label>

                <textarea name="kronologi"
                          rows="5"
                          required
                          placeholder="Jelaskan kronologi keberatan Anda..."
                          class="textarea-keberatan"></textarea>
            </div>

            <label class="agreement-box">
                <input type="checkbox"
                    id="persetujuanKeberatan"
                    name="persetujuan"
                    value="1"
                    required>

                <span>
                    Dengan mencentang ini, saya menyadari dan dengan sepenuhnya menyampaikan keberatan ini
                    untuk dapat disampaikan kepada tim PPID Desa {{ $desa->nama_desa ?? '-' }}.
                </span>
            </label>

            <div class="modal-footer no-border">
                <button type="button"
                        onclick="closeKeberatanModal()"
                        class="modal-btn modal-light">
                    Batal
                </button>

                <button type="submit"
                        id="btnKirimKeberatan"
                        disabled
                        class="modal-btn modal-disabled">
                    Kirim Keberatan Resmi
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    function openDetailModal() {
        document.getElementById('detailModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    function openKeberatanModal() {
        document.getElementById('keberatanModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeKeberatanModal() {
        document.getElementById('keberatanModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('persetujuanKeberatan');
        const button = document.getElementById('btnKirimKeberatan');

        if (!checkbox || !button) return;

        function toggleKeberatanButton() {
            if (checkbox.checked) {
                button.disabled = false;
                button.classList.remove('modal-disabled');
                button.classList.add('modal-dark');
            } else {
                button.disabled = true;
                button.classList.remove('modal-dark');
                button.classList.add('modal-disabled');
            }
        }

        checkbox.addEventListener('change', toggleKeberatanButton);
        toggleKeberatanButton();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDetailModal();
            closeKeberatanModal();
        }
    });
</script>
</body>
</html>