<?php

namespace App\Http\Controllers;

use App\Models\DokumenAntikorupsi;
use Illuminate\Http\Request;

class AntikorupsiController extends Controller
{
    public function index()
    {
        // Ambil semua dokumen
        // Jika sistemnya per desa: $dokumen = DokumenAntikorupsi::where('desa_id', $id)->get();
        $dokumen = DokumenAntikorupsi::all();

        // Kelompokkan data berdasarkan kategori agar mudah dilooping di Blade
        $data = [
            'tatalaksana' => $dokumen->where('kategori', 'tatalaksana')->groupBy('grup_indikator'),
            'pengawasan' => $dokumen->where('kategori', 'pengawasan')->groupBy('grup_indikator'),
            'pelayanan' => $dokumen->where('kategori', 'pelayanan')->groupBy('grup_indikator'),
            'partisipasi' => $dokumen->where('kategori', 'partisipasi')->groupBy('grup_indikator'),
            'kearifan' => $dokumen->where('kategori', 'kearifan')->groupBy('grup_indikator'),
        ];

        return view('antikorupsi.index', compact('data'));
    }
}