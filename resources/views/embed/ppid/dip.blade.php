<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kategoriLabel }} - {{ $desa->nama_desa ?? 'Desa' }}</title>

    <style>
        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #1f2937;
            background: #ffffff;
        }

        .ppid-wrap {
            max-width: 100%;
        }

        .ppid-title {
            background: #3446a4;
            color: #ffffff;
            padding: 14px 18px;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .ppid-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .group-title {
            background: #3446a4;
            color: #ffffff;
            padding: 12px 14px;
            font-weight: 700;
            font-size: 15px;
            margin-top: 18px;
            margin-bottom: 0;
        }

        .dip-row {
            display: grid;
            grid-template-columns: 60px 1fr 90px;
            gap: 12px;
            align-items: center;
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
            background: #ffffff;
        }

        .dip-row:nth-child(even) {
            background: #f8fafc;
        }

        .dip-number {
            text-align: center;
            font-weight: 700;
        }

        .dip-title {
            font-size: 15px;
            line-height: 1.5;
        }

        .dip-ringkasan {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
            line-height: 1.4;
        }

        .dip-button {
            display: inline-block;
            text-align: center;
            padding: 8px 12px;
            border: 1px solid #06b6d4;
            color: #06b6d4;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 700;
            font-size: 13px;
        }

        .dip-button:hover {
            background: #06b6d4;
            color: #ffffff;
        }

        .empty {
            padding: 20px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            color: #64748b;
            font-size: 14px;
        }

        @media (max-width: 640px) {
            body {
                padding: 12px;
            }

            .dip-row {
                grid-template-columns: 40px 1fr;
            }

            .dip-action {
                grid-column: 2;
            }
        }
    </style>
</head>
<body>
    <div class="ppid-wrap">
        <div class="ppid-title">
            {{ strtoupper($kategoriLabel) }}
        </div>

        <div class="ppid-subtitle">
            PPID Desa {{ $desa->nama_desa ?? '-' }}
        </div>

        @if($items->count())
            @php $lastKelompok = null; @endphp

            @foreach($items as $item)
                @if(!empty($item->kelompok_informasi) && $lastKelompok !== $item->kelompok_informasi)
                    <div class="group-title">
                        {{ $item->kelompok_informasi }}
                    </div>
                    @php $lastKelompok = $item->kelompok_informasi; @endphp
                @endif

                <div class="dip-row">
                    <div class="dip-number">
                        {{ $item->urutan ?? $loop->iteration }}
                    </div>

                    <div>
                        <div class="dip-title">
                            {{ $item->judul_informasi }}
                        </div>

                        @if($item->ringkasan)
                            <div class="dip-ringkasan">
                                {{ $item->ringkasan }}
                            </div>
                        @endif
                    </div>

                    <div class="dip-action">
                        @if($item->link_dokumen)
                            <a href="{{ $item->link_dokumen }}" target="_blank" class="dip-button">
                                Lihat
                            </a>
                        @else
                            <span style="font-size:12px;color:#94a3b8;">Belum ada</span>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty">
                Belum ada data {{ strtolower($kategoriLabel) }} yang ditampilkan.
            </div>
        @endif
    </div>
</body>
</html>