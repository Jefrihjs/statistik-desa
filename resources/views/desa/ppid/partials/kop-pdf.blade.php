@php
    $logoBase64 = null;

    if (!empty($desa->logo_desa) && file_exists(storage_path('app/public/' . $desa->logo_desa))) {
        $logoPath = storage_path('app/public/' . $desa->logo_desa);
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($logoPath));
    } elseif (file_exists(public_path('images/logo-ppid-beltim.png'))) {
        $logoPath = public_path('images/logo-ppid-beltim.png');
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($logoPath));
    }

    $namaDesa = strtoupper($desa->nama_desa ?? $desa->nama ?? '-');
@endphp

<table class="kop-table">
    <tr>
        <td class="kop-logo-cell">
            @if($logoBase64)
                <img src="{{ $logoBase64 }}" class="kop-logo">
            @endif
        </td>

        <td class="kop-text-cell">

            <div class="kop-desa">
                PEMERINTAH DESA {{ $namaDesa }}
            </div>

            <div class="kop-ppid">
                PEJABAT PENGELOLA INFORMASI DAN DOKUMENTASI
            </div>

            <div class="kop-alamat">
                {{ $desa->alamat_kantor ?? 'Alamat kantor desa belum diisi' }}
            </div>

            <div class="kop-kontak">
                @if($desa->email_desa)
                    Email: {{ $desa->email_desa }}
                @endif

                @if($desa->website_desa)
                    @if($desa->email_desa) | @endif
                    Website: {{ $desa->website_desa }}
                @endif

                @if($desa->telepon_desa)
                    @if($desa->email_desa || $desa->website_desa) | @endif
                    Telp: {{ $desa->telepon_desa }}
                @endif
            </div>
        </td>

        <td class="kop-spacer-cell"></td>
    </tr>
</table>

<div class="kop-line"></div>