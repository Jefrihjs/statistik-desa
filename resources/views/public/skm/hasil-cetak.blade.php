<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak SKM Desa</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Times New Roman", serif;
            background: #e5e5e5;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            background: #fff;
            margin: 10mm auto;
            padding: 10mm 12mm;
        }

        .no-print {
            text-align: center;
            margin: 20px;
        }

        .no-print button {
            padding: 10px 30px;
            font-size: 15px;
            cursor: pointer;
        }

        table {
            border-collapse: collapse;
        }

        /* ===== KOP SURAT ===== */
        .kop {
            width: 100%;
        }

        .kop td {
            vertical-align: top;
        }

        .logo {
            width: 90px;
            padding-right: 10px;
        }

        .logo img {
            width: 75px;
        }

        .judul-kop {
            text-align: center;
            width: 100%;
        }

        .judul-kop h4 {
            font-size: 13px;
            text-transform: uppercase;
        }

        .judul-kop h2 {
            font-size: 20px;
            text-transform: uppercase;
        }

        .judul-kop p {
            font-size: 11px;
        }

        .double-line {
            margin-top: 5px;
            border-top: 1px solid #000;
            border-bottom: 3px solid #000;
            height: 4px;
        }

        /* ===== JUDUL ===== */
        .judul {
            text-align: center;
            margin-top: 15px;
        }

        .judul h3 {
            font-size: 18px;
            text-transform: uppercase;
        }

        .judul h4 {
            font-size: 17px;
            text-transform: uppercase;
        }

        .judul p {
            font-size: 12px;
            margin-top: 5px;
        }

        /* ===== DUA KOLOM ===== */
        .container {
            display: flex;
            margin-top: 15px;
            border: 1px solid #000;
        }

        .ikm {
            width: 48%;
            border-right: 1px solid #000;
        }

        .ikm table {
            width: 100%;
            height: 100%;
        }

        .ikm th {
            border-bottom: 1px solid #000;
            padding: 8px;
            font-size: 13px;
            text-transform: uppercase;
        }

        .nilai {
            height: 260px;
            text-align: center;
            vertical-align: middle;
            font-size: 64px;
            font-weight: bold;
        }

        .mutu td {
            border-top: 1px solid #000;
            padding: 8px;
            font-size: 13px;
        }

        .responden {
            width: 52%;
        }

        .responden table {
            width: 100%;
        }

        .responden th {
            border-bottom: 1px solid #000;
            padding: 6px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .responden td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 12px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        /* ===== UCAPAN ===== */
        .ucapan {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            line-height: 1.6;
        }

        /* ===== TANDA TANGAN ===== */
        .ttd {
            width: 100%;
            margin-top: 35px;
        }

        .ttd-kanan {
            width: 45%;
            float: right;
            text-align: center;
        }

        .spasi {
            height: 90px;
        }

        /* ===== PRINT ===== */
        @media print {
            body {
                background: #fff;
            }
            .page {
                margin: 0;
                box-shadow: none;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    {{-- TOMBOL CETAK --}}
    <div class="no-print">
        <button onclick="window.print()">🖨 CETAK</button>
    </div>

    <div class="page">

        {{-- KOP SURAT --}}
        <table class="kop">
            <tr>
                <td class="logo">
                    @php
                        $logoSrc = $desa->logo_desa
                            ? asset('storage/' . $desa->logo_desa)
                            : ($desa->logo
                                ? asset('storage/' . $desa->logo)
                                : asset('images/logo-default.png'));
                    @endphp
                    <img src="{{ $logoSrc }}">
                </td>
                <td class="judul-kop">
                    <h4>Pemerintah Kabupaten Belitung Timur</h4>
                    <h2>Pemerintah Desa {{ strtoupper($desa->nama_desa) }}</h2>
                    <p>
                        Kecamatan {{ strtoupper($desa->kecamatan) }}
                        @if($desa->alamat_kantor)
                            <br>{{ $desa->alamat_kantor }}
                        @endif
                        @if($desa->telepon_desa)
                            <br>Telp. {{ $desa->telepon_desa }}
                        @endif
                        @if($desa->email_desa)
                            <br>Email: {{ $desa->email_desa }}
                        @endif
                    </p>
                </td>
            </tr>
        </table>

        <div class="double-line"></div>

        {{-- JUDUL --}}
        <div class="judul">
            <h3>Survei Kepuasan Masyarakat Terhadap</h3>
            <h4>Pelayanan Publik Di Pemerintah Desa {{ strtoupper($desa->nama_desa) }}</h4>
            <p>
                Periode :
                @if($periodeMulai && $periodeSelesai)
                    {{ $periodeMulai->translatedFormat('d F Y') }} s.d {{ $periodeSelesai->translatedFormat('d F Y') }}
                @else
                    Tahun {{ $rek->tahun }}
                @endif
            </p>
        </div>

        {{-- DUA KOLOM: IKM + RESPONDEN --}}
        <div class="container">

            {{-- KOLOM KIRI: NILAI IKM --}}
            <div class="ikm">
                <table>
                    <tr>
                        <th>Nilai IKM</th>
                    </tr>
                    <tr>
                        <td class="nilai">{{ number_format($ikmTotal, 2) }}</td>
                    </tr>
                    <tr class="mutu">
                        <td>
                            <b>Mutu Pelayanan :</b> {{ $mutuGrade }} - {{ substr($mutuTotal, 4) }}
                        </td>
                    </tr>
                </table>
            </div>

            {{-- KOLOM KANAN: RESPONDEN --}}
            <div class="responden">
                <table>
                    <tr>
                        <th colspan="4">
                            Nama Layanan : Survei Kepuasan Masyarakat Terhadap
                            Pelayanan Publik Di Pemerintah Desa {{ strtoupper($desa->nama_desa) }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="4">Responden</th>
                    </tr>
                    <tr>
                        <td width="45%">Jumlah</td>
                        <td width="5%" class="center">:</td>
                        <td width="20%" class="center">{{ $totalResponden }}</td>
                        <td>Orang</td>
                    </tr>
                    <tr>
                        <td rowspan="2">Jenis Kelamin</td>
                        <td class="center">L</td>
                        <td class="center">{{ $genderL }}</td>
                        <td>Orang</td>
                    </tr>
                    <tr>
                        <td class="center">P</td>
                        <td class="center">{{ $genderP }}</td>
                        <td>Orang</td>
                    </tr>
                    @foreach($pendidikanDist as $pendidikan => $jumlah)
                        <tr>
                            <td>{{ strtoupper($pendidikan) }}</td>
                            <td class="center">:</td>
                            <td class="center">{{ $jumlah }}</td>
                            <td>Orang</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="center" style="padding: 10px;">
                            <b>Periode Survei</b><br>
                            @if($periodeMulai && $periodeSelesai)
                                {{ $periodeMulai->translatedFormat('d F Y') }} s.d {{ $periodeSelesai->translatedFormat('d F Y') }}
                            @else
                                Tahun {{ $rek->tahun }}
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

        </div>

        {{-- UCAPAN TERIMA KASIH --}}
        <div class="ucapan">
            Terima kasih kepada masyarakat <b>Desa {{ $desa->nama_desa }}</b>
            yang telah berpartisipasi aktif dalam memberikan penilaian, masukan serta saran
            terhadap pelayanan publik Pemerintah Desa {{ $desa->nama_desa }}
            sehingga menjadi bahan peningkatan kualitas pelayanan kepada masyarakat.
        </div>

        {{-- TANDA TANGAN --}}
        @php
            $ttdNama = $desa->nama_ppid ?? $desa->nama_kepala_desa ?? null;
            $ttdNip  = $desa->nip_ppid ?? $desa->nip_kepala ?? null;
            $ttdJabatan = $desa->jabatan_ppid ?? 'Kepala Desa';
        @endphp
        <div class="ttd">
            <div class="ttd-kanan">
                <p><b>{{ $ttdJabatan }} {{ $desa->nama_desa }}</b></p>
                <div class="spasi"></div>
                <p>
                    <b>
                        @if(!empty($ttdNama))
                            {{ strtoupper($ttdNama) }}
                        @else
                            ............................................
                        @endif
                    </b>
                </p>
                @if(!empty($ttdNip))
                    <p>{{ $ttdNip }}</p>
                @endif
            </div>
            <div style="clear: both;"></div>
        </div>

    </div>

</body>
</html>