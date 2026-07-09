<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\DomainTracker;
use Illuminate\Support\Carbon;

class DomainDesaController extends Controller
{
    public function index()
    {
        $desa = auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);

        abort_if(!$desa, 404, 'Akun belum terhubung dengan desa.');

        $tracker = DomainTracker::where('desa_id', $desa->id)->first();

        $waAdminKabupaten = '6281234567890';

        $waUrl = '#';

        if ($tracker) {
            $tanggalExpired = Carbon::parse($tracker->expiry_date)->translatedFormat('d F Y');

            $pesan = 'Halo Admin Kabupaten, saya Operator dari Desa ' . ($desa->nama_desa ?? '-') .
                '. Ingin berkoordinasi mengenai perpanjangan masa aktif Domain ' .
                $tracker->domain_name .
                ', yang saat ini menyisakan ' . $tracker->days_left .
                ' hari lagi dan akan kedaluwarsa pada ' . $tanggalExpired .
                '. Mohon bantuannya untuk proses tindak lanjut. Terima kasih.';

            $waUrl = 'https://api.whatsapp.com/send?phone=' . $waAdminKabupaten . '&text=' . rawurlencode($pesan);
        }

        return view('desa.domain.index', compact('desa', 'tracker', 'waUrl'));
    }
}