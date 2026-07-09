<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 1cm 1.5cm; }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11px;
            line-height: 1.45;
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

        .title-area {
            text-align: center;
            margin: 15px 0;
        }

        .title-surat {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12pt;
            text-decoration: underline;
            margin-bottom: 2px;
        }

        .nomor-pendaftaran {
            font-size: 10pt;
        }

        .dimmed {
            color: #999;
            text-decoration: line-through;
        }

        .table-pemohon {
            width: 100%;
            margin-bottom: 10px;
        }

        .table-pemohon td {
            vertical-align: top;
        }

        .table-isi {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .table-isi th,
        .table-isi td {
            border: 1px solid black;
            padding: 5px;
            vertical-align: top;
        }

        .bg-gray {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            margin-right: 5px;
            text-align: center;
            line-height: 10px;
            font-size: 8px;
        }

        .checkbox-big {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1.5px solid #000;
            text-align: center;
            line-height: 14px;
            font-weight: bold;
            background: #fff;
            margin-right: 5px;
        }

        .footer-area {
            width: 100%;
            border: 1.5px solid #000;
            border-collapse: collapse;
            margin-top: 28px;
            font-size: 10pt;
        }

        .footer-area td {
            vertical-align: middle;
        }
       
    </style>
</head>
<body>
    @php
        $canProvide = $pemberitahuan->status_informasi === 'dapat_diberikan';
        $cannotProvide = $pemberitahuan->status_informasi === 'tidak_dapat_diberikan';

        $alasan = $pemberitahuan->alasan_penolakan ?? '';

        $judul = $canProvide
            ? 'PEMBERITAHUAN TERTULIS'
            : 'PEMBERITAHUAN PENOLAKAN PERMOHONAN';

        $nomor = 'PPID-' . str_pad($permohonan->id, 4, '0', STR_PAD_LEFT);

        $penguasaan = $pemberitahuan->penguasaan_informasi;
        $bentuk = $pemberitahuan->bentuk_fisik;

        $salinan = $pemberitahuan->biaya_salinan ?? 0;
        $kirim = $pemberitahuan->biaya_kirim ?? 0;
        $lain = $pemberitahuan->biaya_lain ?? 0;
        $total = $pemberitahuan->total_biaya ?? 0;
    @endphp

    @include('desa.ppid.partials.kop-pdf', ['desa' => $desa])

    <div class="title-area">
        <div class="title-surat">{{ $judul }}</div>
        <div class="nomor-pendaftaran">
           <p>
                Nomor Pendaftaran:
                <strong>{{ $permohonan->nomor_pendaftaran }}</strong>
            </p>
        </div>
    </div>

    <p>
        Berdasarkan permohonan informasi pada tanggal
        <strong>{{ $permohonan->created_at->locale('id')->translatedFormat('d F Y') }}</strong>,
        kami menyampaikan kepada Saudara/i:
    </p>

    <table class="table-pemohon">
        <tr>
            <td width="22%"><strong>Nama</strong></td>
            <td width="2%">:</td>
            <td>{{ $permohonan->nama }}</td>
        </tr>
        <tr>
            <td><strong>Alamat</strong></td>
            <td>:</td>
            <td>{{ $permohonan->alamat }}</td>
        </tr>
        <tr>
            <td><strong>No. Telp/Email</strong></td>
            <td>:</td>
            <td>{{ $permohonan->no_hp }} / {{ $permohonan->email ?? '-' }}</td>
        </tr>
    </table>

    <p style="margin-bottom: 5px;">Pemberitahuan sebagai berikut:</p>

    <div style="margin-top: 15px; font-weight: bold; text-decoration: underline;"
         class="{{ !$canProvide ? 'dimmed' : '' }}">
        A. Informasi Dapat Diberikan
    </div>

    <table class="table-isi {{ !$canProvide ? 'dimmed' : '' }}">
        <tr class="bg-gray">
            <th width="5%">No.</th>
            <th width="35%">Hal-hal terkait Informasi Publik</th>
            <th>Keterangan</th>
        </tr>

        <tr>
            <td class="text-center">1.</td>
            <td>Penguasaan Informasi Publik</td>
            <td>
                <span class="checkbox">@if($penguasaan === 'desa') v @endif</span>
                Kami / Pemerintah Desa<br>

                <span class="checkbox">@if($penguasaan === 'badan_publik_lain') v @endif</span>
                Badan Publik lain, yaitu
                <strong>{{ $pemberitahuan->nama_badan_publik_lain ?: '................' }}</strong>
            </td>
        </tr>

        <tr>
            <td class="text-center">2.</td>
            <td>Bentuk fisik tersedia</td>
            <td>
                <span class="checkbox">@if($bentuk === 'softcopy') v @endif</span>
                Softcopy (Digital)<br>

                <span class="checkbox">@if($bentuk === 'hardcopy') v @endif</span>
                Hardcopy (Salinan Rekaman/Cetak)
            </td>
        </tr>

        <tr>
            <td class="text-center">3.</td>
            <td>Biaya yang dibutuhkan</td>
            <td>
                <div>
                    <span class="checkbox">@if($salinan > 0) v @endif</span>
                    Penyalinan: Rp {{ number_format($salinan, 0, ',', '.') }}
                </div>

                <div>
                    <span class="checkbox">@if($kirim > 0) v @endif</span>
                    Pengiriman: Rp {{ number_format($kirim, 0, ',', '.') }}
                </div>

                <div>
                    <span class="checkbox">@if($lain > 0) v @endif</span>
                    Lain-lain: Rp {{ number_format($lain, 0, ',', '.') }}
                </div>

                <div>
                    <span class="checkbox">@if($total == 0) v @endif</span>
                    Lain-lain (Gratis)
                </div>

                <div style="margin-top: 8px; font-weight: bold; border-top: 1px solid #000; padding-top: 5px;">
                    Total Biaya: Rp {{ number_format($total, 0, ',', '.') }}
                </div>
            </td>
        </tr>

        <tr>
            <td class="text-center">4.</td>
            <td>Waktu penyediaan</td>
            <td>{{ $pemberitahuan->waktu_penyediaan ?? '.......' }} hari kerja</td>
        </tr>

        <tr>
            <td class="text-center">5.</td>
            <td>Penjelasan penghitaman/pengaburan Informasi yang dimohon</td>
            <td>{{ $pemberitahuan->penjelasan_penghitaman ?: 'Tidak ada.' }}</td>
        </tr>
    </table>

    <div style="margin-top: 15px; font-weight: bold;"
         class="{{ $canProvide ? 'dimmed' : '' }}">
        B. Informasi tidak dapat diberikan karena:
    </div>

    <div style="margin-left: 20px; margin-top: 8px;" class="{{ $canProvide ? 'dimmed' : '' }}">
        <div>
            <span class="checkbox-big">
                @if($cannotProvide && $alasan === 'informasi_belum_dikuasai') v @endif
            </span>
            Informasi yang diminta belum dikuasai
        </div>

        <div style="margin-top: 6px;">
            <span class="checkbox-big">
                @if($cannotProvide && $alasan === 'informasi_belum_didokumentasikan') v @endif
            </span>
            Informasi yang diminta belum didokumentasikan
        </div>

        <div style="margin-top: 6px;">
            <span class="checkbox-big">
                @if($cannotProvide && $alasan === 'informasi_dikecualikan') v @endif
            </span>
            Informasi dikecualikan berdasarkan ketentuan peraturan perundang-undangan.
        </div>
    </div>

    @if($cannotProvide && $pemberitahuan->catatan_penolakan)
        <p style="margin-left: 20px; margin-top: 10px;">
            <strong>Catatan:</strong> {{ $pemberitahuan->catatan_penolakan }}
        </p>
    @endif

    @php
    $qrLink = route('embed.ppid.permohonan.pemberitahuan', [
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