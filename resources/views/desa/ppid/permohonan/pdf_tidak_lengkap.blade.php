<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 1.5cm 2cm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11pt;
            line-height: 1.55;
            color: #000;
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
            font-size: 13pt;
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
            margin-bottom: 18px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            font-size: 14pt;
            margin-bottom: 6px;
        }

        .nomor {
            text-align: center;
            margin-bottom: 28px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0 22px;
        }

        .info-table td {
            vertical-align: top;
            padding: 3px 0;
        }

        .box {
            border: 1px solid #999;
            background: #f8fafc;
            padding: 14px;
            margin: 12px 0 18px;
            line-height: 1.6;
        }

        .warning-title {
            font-weight: bold;
            color: #92400e;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .catatan {
            font-size: 10pt;
            font-style: italic;
            margin-top: 14px;
        }

        .footer-area {
            width: 100%;
            border: 1.5px solid #000;
            border-collapse: collapse;
            margin-top: 35px;
            font-size: 10pt;
        }

        .footer-area td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
    @include('desa.ppid.partials.kop-pdf', ['desa' => $desa])

    <div class="title">
        Pemberitahuan Permohonan Tidak Lengkap
    </div>

    <div class="nomor">
        Nomor Pendaftaran:
        <strong>{{ $permohonan->nomor_pendaftaran }}</strong>
    </div>

    <p>
        Berdasarkan permohonan informasi publik yang Saudara/i ajukan pada tanggal
        <strong>{{ $permohonan->created_at->translatedFormat('d F Y') }}</strong>,
        dengan ini kami menyampaikan bahwa permohonan informasi berikut:
    </p>

    <table class="info-table">
        <tr>
            <td width="32%">Nama Pemohon</td>
            <td width="2%">:</td>
            <td>{{ $permohonan->nama }}</td>
        </tr>
        <tr>
            <td>NIK / No. Identitas</td>
            <td>:</td>
            <td>{{ $permohonan->nik }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $permohonan->alamat }}</td>
        </tr>
        <tr>
            <td>Nomor HP / Email</td>
            <td>:</td>
            <td>{{ $permohonan->no_hp }} / {{ $permohonan->email ?? '-' }}</td>
        </tr>
        <tr>
            <td>Rincian Informasi</td>
            <td>:</td>
            <td>{{ $permohonan->rincian_informasi }}</td>
        </tr>
        <tr>
            <td>Tujuan Penggunaan</td>
            <td>:</td>
            <td>{{ $permohonan->tujuan_penggunaan }}</td>
        </tr>
    </table>

    <div class="warning-title">
        Berkas / Informasi Permohonan Tidak Lengkap
    </div>

    <div class="box">
        {{ $permohonan->catatan_admin ?? 'Permohonan belum dapat diproses karena data atau berkas yang disampaikan belum lengkap.' }}
    </div>

    <p>
        Sehubungan dengan hal tersebut, permohonan informasi ini
        <strong>tidak dapat diproses lebih lanjut</strong> karena data atau berkas yang disampaikan belum lengkap.
    </p>

    <p>
        Pemohon dapat mengajukan permohonan informasi ulang melalui layanan PPID Desa dengan melengkapi data,
        dokumen, atau keterangan yang diperlukan sesuai catatan kekurangan di atas.
    </p>

    <p class="catatan">
        Catatan: Pengajuan ulang dapat dilakukan menggunakan formulir permohonan informasi yang tersedia pada
        halaman PPID Desa. Pastikan seluruh data identitas, rincian informasi yang dimohon, tujuan penggunaan,
        serta lampiran pendukung telah dilengkapi agar permohonan dapat diproses.
    </p>

    @php
        $qrLink = route('embed.ppid.permohonan.tidak_lengkap', [
            $desa->slug,
            $permohonan->kode_permohonan
        ]);

        $qrCode = base64_encode(
            \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                ->size(90)
                ->margin(1)
                ->errorCorrection('H')
                ->generate($qrLink)
        );
    @endphp

    <table class="footer-area">
        <tr>
            <td style="width: 110px; padding: 12px; text-align: center; border-right: 1px solid #000;">
                <img src="data:image/svg+xml;base64,{{ $qrCode }}"
                     style="width: 72px; height: 72px;">

                <div style="font-size: 7pt; font-weight: bold; margin-top: 5px;">
                    SCAN VERIFIKASI
                </div>
            </td>

            <td style="padding: 15px; text-align: center;">
                <p style="margin: 0 0 12px 0;">
                    Dokumen ini diterbitkan secara elektronik melalui sistem PPID Desa
                    sehingga tidak memerlukan cap dan tanda tangan basah.
                </p>

                <p style="margin: 0;">
                    Belitung Timur, {{ now()->translatedFormat('d F Y') }}
                </p>

                <p style="font-weight: bold; margin: 6px 0 0 0; text-transform: uppercase;">
                    {{ $desa->jabatan_ppid ?? 'TIM PPID' }}
                </p>

                <p style="font-weight: bold; margin: 0; text-transform: uppercase;">
                    Pemerintah Desa {{ $desa->nama_desa ?? '-' }}
                </p>

                @if($desa->nama_ppid)
                    <br><br>

                    <p style="font-weight: bold; margin: 0; text-decoration: underline; text-transform: uppercase;">
                        {{ $desa->nama_ppid }}
                    </p>

                    @if($desa->nip_ppid)
                        <p style="font-weight: bold; margin: 0;">
                            NIP. {{ $desa->nip_ppid }}
                        </p>
                    @endif
                @endif
            </td>
        </tr>
    </table>
</body>
</html>