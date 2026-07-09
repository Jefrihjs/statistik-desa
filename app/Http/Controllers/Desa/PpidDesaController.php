<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\PpidPermohonan;
use App\Models\PpidKeberatan;

class PpidDesaController extends Controller
{
    public function index()
    {
        $desaId = auth()->user()->desa_id;

        abort_if(!$desaId, 404, 'Akun Anda belum terhubung dengan desa.');

        $jumlahPermohonanMasuk = PpidPermohonan::where('desa_id', $desaId)
            ->where('status', 'pending')
            ->count();

        $jumlahKeberatanMasuk = PpidKeberatan::where('desa_id', $desaId)
            ->where('status', 'diajukan')
            ->count();

        return view('desa.ppid.index', compact(
            'jumlahPermohonanMasuk',
            'jumlahKeberatanMasuk'
        ));
    }
}