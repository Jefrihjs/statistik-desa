<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 1.2cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9pt;
            color: #111827;
        }

        h2, h3 {
            text-align: center;
            margin: 0;
            text-transform: uppercase;
        }

        h2 {
            font-size: 13pt;
            margin-bottom: 4px;
        }

        h3 {
            font-size: 10pt;
            margin-bottom: 18px;
            color: #374151;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #1f2937;
            color: #ffffff;
            padding: 8px;
            border: 1px solid #111827;
            text-align: left;
        }

        td {
            padding: 7px;
            border: 1px solid #d1d5db;
            vertical-align: top;
        }

        tr:nth-child(even) td {
            background: #f3f4f6;
        }

        .small {
            font-size: 8pt;
        }
    </style>
</head>
<body>
    <h2>
        {{ $jenis === 'keberatan' ? 'Register Keberatan Informasi Publik' : 'Register Permohonan Informasi Publik' }}
    </h2>

    <h3>
        PPID Desa {{ $desa->nama_desa ?? '-' }}
        <br>
        {{ $tahun === 'semua' ? 'Semua Tahun' : 'Tahun ' . $tahun }}
    </h3>

    @if($jenis === 'permohonan')
        <table>
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="10%">Tanggal</th>
                    <th width="15%">Nomor</th>
                    <th width="17%">Nama</th>
                    <th>Alamat</th>
                    <th width="13%">Kontak</th>
                    <th width="12%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permohonans as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->created_at->translatedFormat('d M Y') }}</td>
                        <td>{{ $item->nomor_pendaftaran }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ $item->no_hp ?? '-' }}</td>
                        <td>{{ strtoupper(str_replace('_', ' ', $item->status)) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">Data tidak tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    @if($jenis === 'keberatan')
        <table>
            <thead>
                <tr>
                    <th width="4%">#</th>
                    <th width="10%">Tanggal</th>
                    <th width="14%">Kode</th>
                    <th width="17%">Pemohon</th>
                    <th width="17%">Nomor Permohonan</th>
                    <th>Alasan</th>
                    <th width="12%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($keberatans as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->created_at->translatedFormat('d M Y') }}</td>
                        <td>{{ $item->kode_keberatan }}</td>
                        <td>{{ $item->permohonan->nama ?? '-' }}</td>
                        <td>{{ $item->permohonan->nomor_pendaftaran ?? '-' }}</td>
                        <td>{{ $item->label_alasan }}</td>
                        <td>{{ strtoupper(str_replace('_', ' ', $item->status)) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">Data tidak tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <p class="small" style="margin-top: 20px; text-align: right;">
        Dicetak pada {{ now()->translatedFormat('d F Y H:i') }} WIB
    </p>
</body>
</html>