<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\DomainTracker;
use App\Models\Desa;
use App\Services\SslCertificateService;

class SslDesaController extends Controller
{
    public function index(SslCertificateService $sslChecker)
    {
        $desa = auth()->user()->desa ?? Desa::find(auth()->user()->desa_id);

        abort_if(!$desa, 404, 'Akun belum terhubung dengan desa.');

        $tracker = DomainTracker::where('desa_id', $desa->id)->first();

        $domain = $tracker->domain_name ?? null;

        // Cek SSL asli dari sertifikat HTTPS
        $sslInfo = $sslChecker->check($domain);

        $waAdminKabupaten = '6281234567890';

        $waUrl = '#';

        if ($tracker && $domain) {
            $pesan = 'Halo Admin Kabupaten, saya Operator dari Desa ' . ($desa->nama_desa ?? '-') .
                '. Ingin berkoordinasi mengenai pembaruan Sertifikat SSL/HTTPS untuk website ' .
                $domain .
                '. Mohon bantuannya untuk pengecekan dan tindak lanjut. Terima kasih.';

            $waUrl = 'https://api.whatsapp.com/send?phone=' . $waAdminKabupaten . '&text=' . rawurlencode($pesan);
        }

        return view('desa.ssl.index', compact(
            'desa',
            'tracker',
            'domain',
            'sslInfo',
            'waUrl'
        ));
    }
}