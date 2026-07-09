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
            line-height: 1.35;
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

        .judul-sk {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin: 18px 0 22px;
            line-height: 1.45;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .table-data td {
            vertical-align: top;
            padding: 3px 0;
        }

        .kotak-label {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            margin: 12px auto;
            width: 285px;
            text-transform: uppercase;
        }

        .dasar {
            margin-left: 25px;
            margin-bottom: 15px;
        }

        .dasar div {
            margin-bottom: 6px;
        }

        .text-justify {
            text-align: justify;
        }

        .catatan {
            font-size: 9.5pt;
            font-style: italic;
            text-align: justify;
            margin-top: 16px;
        }

        .pengesahan {
            width: 100%;
            border: 1.5px solid #000;
            border-collapse: collapse;
            margin-top: 28px;
            font-family: Arial, sans-serif;
        }

        .pengesahan td {
            vertical-align: top;
            padding: 12px;
        }

        .qr {
            width: 95px;
            text-align: center;
            border-right: 1.5px solid #000;
        }

        .qr-box {
            width: 72px;
            height: 72px;
            border: 1px solid #000;
            margin: 0 auto 5px;
            font-size: 7pt;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-sign {
            text-align: center;
            line-height: 1.4;
            font-size: 10pt;
        }

        

        .logo {
            position: absolute;
            left: 0;
            top: 5px;
            width: 70px;
            max-width: 70px;
            height: 70px;
            max-height: 70px;
            object-fit: contain;
        }

        .kop-text {
            padding-left: 90px;
            text-align: center;
        }
    </style>
</head>
<body>
    @php
        $logoPath = public_path('images/logo-ppid-beltim.png');
        $logoBase64 = null;

        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $hurufPasal = $pemberitahuan->pasal_17_huruf ?? null;

        $labelHuruf = [
            'a' => 'Huruf a (Proses Penegakan Hukum)',
            'b' => 'Huruf b (HAKI / Persaingan Usaha)',
            'c' => 'Huruf c (Pertahanan & Keamanan)',
            'd' => 'Huruf d (Kekayaan Alam)',
            'e' => 'Huruf e (Ketahanan Ekonomi)',
            'f' => 'Huruf f (Hubungan Luar Negeri)',
            'g' => 'Huruf g (Akta Otentik Pribadi)',
            'h' => 'Huruf h (Rahasia Pribadi / Riwayat Kesehatan)',
            'i' => 'Huruf i (Memorandum / Surat Internal)',
            'j' => 'Huruf j (Dikecualikan UU Lainnya)',
        ];

        $labelPasal17 = $hurufPasal
            ? ($labelHuruf[$hurufPasal] ?? 'Huruf ' . strtoupper($hurufPasal))
            : null;
    @endphp

    @include('desa.ppid.partials.kop-pdf', ['desa' => $desa])

    <div class="judul-sk">
        Surat Keputusan PPID tentang Penolakan Permohonan Informasi<br>
        Nomor Pendaftaran: {{ $permohonan->nomor_pendaftaran }}
    </div>

    <table class="table-data">
        <tr>
            <td width="32%">Nama</td>
            <td width="2%">:</td>
            <td>{{ $permohonan->nama }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $permohonan->alamat }}</td>
        </tr>
        <tr>
            <td>Nomor Telp / Email</td>
            <td>:</td>
            <td>{{ $permohonan->no_hp }} / {{ $permohonan->email ?? '-' }}</td>
        </tr>
        <tr>
            <td>Rincian Informasi yang Dibutuhkan</td>
            <td>:</td>
            <td>{{ $permohonan->rincian_informasi ?? '-' }}</td>
        </tr>
    </table>

    <p>PPID memutuskan bahwa informasi yang dimohon adalah:</p>

    <div class="kotak-label">
        Informasi yang Dikecualikan
    </div>

    <p>Pengecualian informasi didasarkan pada alasan:</p>

    <div class="dasar">
        @if($labelPasal17)
            <div>
                <span style="font-family: DejaVu Sans, sans-serif;">✔</span>
                Pasal 17 <strong>{{ $labelPasal17 }}</strong> Undang-Undang Keterbukaan Informasi Publik
            </div>
        @endif

        @if($pemberitahuan->pasal_uu_lainnya)
            <div>
                <span style="font-family: DejaVu Sans, sans-serif;">✔</span>
                {{ $pemberitahuan->pasal_uu_lainnya }}
            </div>
        @endif

        @if(!$labelPasal17 && !$pemberitahuan->pasal_uu_lainnya)
            <div style="color:#777;font-style:italic;">
                Dasar hukum pengecualian belum ditentukan.
            </div>
        @endif
    </div>

    @if($pemberitahuan->rincian_informasi_ditolak)
        <p class="text-justify">
            Adapun rincian informasi yang ditolak untuk diberikan adalah:
            <br>
            <em>"{{ $pemberitahuan->rincian_informasi_ditolak }}"</em>
        </p>
    @endif

    <p class="text-justify">
        Bahwa berdasarkan pasal-pasal di atas, membuka informasi tersebut dapat menimbulkan konsekuensi sebagai berikut:
        <br>
        <em>"{{ $pemberitahuan->hasil_uji_konsekuensi ?? 'Belum diisi.' }}"</em>
    </p>

    <p>Dengan demikian menyatakan bahwa:</p>

    <div class="kotak-label">
        Permohonan Informasi Ditolak
    </div>

    @if($pemberitahuan->catatan_penolakan)
        <p class="text-justify">
            Catatan:
            <br>
            <em>"{{ $pemberitahuan->catatan_penolakan }}"</em>
        </p>
    @endif

    <p class="catatan">
        Jika Pemohon Informasi keberatan atas penolakan ini, maka Pemohon Informasi dapat mengajukan keberatan kepada Atasan PPID selambat-lambatnya 30 (tiga puluh) hari kerja sejak menerima Surat Keputusan ini.
    </p>

    <table class="pengesahan">
        <tr>
            <td class="qr">
                @php
                    $qrLink = route('embed.ppid.permohonan.sk_penolakan', [
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

                <img src="data:image/svg+xml;base64,{{ $qrCode }}"
                    style="width:75px;height:75px;">

                <div style="font-size:7pt;font-weight:bold;margin-top:5px;">
                    SCAN VERIFIKASI
                </div>
            </td>

            <td class="footer-sign">
                <p style="margin:0 0 14px;">
                    Dokumen ini sah, diterbitkan secara elektronik melalui Sistem PPID Desa sehingga tidak memerlukan cap dan tanda tangan basah.
                </p>

                <p style="margin:0;">
                    Belitung Timur, {{ now()->translatedFormat('d F Y') }}
                </p>

                <p style="margin:5px 0 0;font-weight:bold;text-transform:uppercase;">
                    PPID Desa {{ $desa->nama_desa ?? $desa->nama ?? '-' }}
                </p>

                <br><br>

                <p style="margin:0;text-decoration:underline;font-weight:bold;text-transform:uppercase;">
                    Pejabat Pengelola Informasi dan Dokumentasi
                </p>
            </td>
        </tr>
    </table>
</body>
</html>