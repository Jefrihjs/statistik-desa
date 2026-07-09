<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 1cm 1.5cm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
            line-height: 1.45;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }

        .kop-logo-cell {
            width: 95px;
            vertical-align: middle;
            text-align: left;
        }

        .kop-logo {
            width: 62px;
            max-width: 62px;
            height: auto;
            max-height: 55px;
        }

        .kop-text-cell {
            text-align: center;
            vertical-align: middle;
            padding: 0 6px;
            line-height: 1.15;
        }

        .kop-spacer-cell {
            width: 95px;
        }

        .kop-kabupaten {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 3px 0;
            line-height: 1.1;
        }

        .kop-desa {
            font-size: 17pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: .02em;
            line-height: 1.1;
        }

        .kop-ppid {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 7px 0;
            line-height: 1.1;
        }

        .kop-alamat {
            font-size: 8.5pt;
            font-style: italic;
            margin: 0 0 2px 0;
            line-height: 1.1;
        }

        .kop-kontak {
            font-size: 8.5pt;
            font-style: italic;
            margin: 0;
            line-height: 1.1;
        }

        .kop-line {
            border-bottom: 3px double #000;
            margin-top: 5px;
            margin-bottom: 14px;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            font-size: 13px;
            margin: 18px 0 16px;
        }

        .kode-box {
            width: 260px;
            margin: 0 auto 12px;
            border: 1px solid #000;
            text-align: center;
            padding: 10px 0;
        }

        .kode-label {
            font-size: 11px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .kode {
            font-size: 18px;
            font-weight: bold;
            color: #c91d1d;
            letter-spacing: 2px;
        }

        .note-top {
            text-align: center;
            font-size: 10px;
            font-style: italic;
            margin-bottom: 18px;
        }

        table.info {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.info td {
            padding: 7px 4px;
            border-bottom: 1px solid #e5e5e5;
            vertical-align: top;
        }

        table.info td.label {
            width: 30%;
            font-weight: bold;
        }

        table.info td.colon {
            width: 2%;
            text-align: center;
        }

        .catatan {
            border: 1px dashed #bbb;
            margin-top: 26px;
            padding: 14px;
        }

        .catatan strong {
            display: block;
            margin-bottom: 6px;
        }

        .catatan ul {
            margin: 0;
            padding-left: 18px;
        }

        .printed {
            text-align: right;
            font-size: 10px;
            font-style: italic;
            color: #666;
            margin-top: 16px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 9px;
            font-style: italic;
            color: #555;
            padding-top: 6px;
        }
    </style>
</head>
<body>
    @php
        $nomorPendaftaran = str_pad($permohonan->id, 3, '0', STR_PAD_LEFT) . '/PPID/V/' . $permohonan->created_at->format('Y');
        $kode = strtoupper($permohonan->kode_permohonan ?? '-');
    @endphp

    @include('desa.ppid.partials.kop-pdf', ['desa' => $desa])

    <div class="judul">
        Tanda Bukti Permohonan Informasi Publik
    </div>

    <div class="kode-box">
        <div class="kode-label">Kode Permohonan (Tracking)</div>
        <div class="kode">{{ $kode }}</div>
    </div>

    <div class="note-top">
        *Gunakan kode permohonan di atas untuk memantau status secara mandiri melalui halaman monitoring.
    </div>

    <table class="info">
        <tr>
            <td class="label">Nomor Pendaftaran</td>
            <td class="colon">:</td>
            <td>{{ $nomorPendaftaran }}</td>
        </tr>

        <tr>
            <td class="label">Tanggal Pengajuan</td>
            <td class="colon">:</td>
            <td>{{ $permohonan->created_at->translatedFormat('d F Y H:i') }} WIB</td>
        </tr>

        <tr>
            <td class="label">Nama Pemohon</td>
            <td class="colon">:</td>
            <td>{{ $permohonan->nama }}</td>
        </tr>

        <tr>
            <td class="label">Kategori Pemohon</td>
            <td class="colon">:</td>
            <td>{{ strtoupper($permohonan->kategori_pemohon) }}</td>
        </tr>

        <tr>
            <td class="label">NIK / No. Identitas</td>
            <td class="colon">:</td>
            <td>{{ $permohonan->nik }}</td>
        </tr>

        <tr>
            <td class="label">No. HP / WA</td>
            <td class="colon">:</td>
            <td>{{ $permohonan->no_hp }}</td>
        </tr>

        <tr>
            <td class="label">Rincian Informasi</td>
            <td class="colon">:</td>
            <td>{{ $permohonan->rincian_informasi }}</td>
        </tr>

        <tr>
            <td class="label">Tujuan Penggunaan</td>
            <td class="colon">:</td>
            <td>{{ $permohonan->tujuan_penggunaan }}</td>
        </tr>
    </table>

    <div class="catatan">
        <strong>Catatan:</strong>
        <ul>
            <li>Simpan tanda bukti ini untuk melakukan pengecekan status permohonan Anda.</li>
            <li>Proses penanganan dilakukan dalam waktu sesuai ketentuan layanan informasi publik.</li>
            <li>Anda dapat memantau status melalui website desa pada menu PPID / Monitoring Permohonan.</li>
        </ul>
    </div>

    <div class="printed">
        *Dicetak otomatis oleh Sistem PPID Desa pada {{ now()->translatedFormat('l, d F Y H:i') }} WIB
    </div>

    <div class="footer">
        Dokumen ini diterbitkan secara otomatis melalui Sistem PPID Desa.
    </div>
</body>
</html>