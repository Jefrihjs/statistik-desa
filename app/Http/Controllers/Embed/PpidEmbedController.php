<?php

namespace App\Http\Controllers\Embed;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\PpidDip;

class PpidEmbedController extends Controller
{
    public function dip($slug, $kategori)
    {
        $kategoriMap = [
            'berkala' => 'Informasi Berkala',
            'serta-merta' => 'Informasi Serta Merta',
            'setiap-saat' => 'Informasi Setiap Saat',
            'dikecualikan' => 'Informasi Dikecualikan',
        ];

        abort_if(!array_key_exists($kategori, $kategoriMap), 404);

        $kategoriDb = str_replace('-', '_', $kategori);

        $desa = Desa::where('slug', $slug)->firstOrFail();

        $items = PpidDip::where('desa_id', $desa->id)
            ->where('kategori', $kategoriDb)
            ->where('is_active', true)
            ->orderBy('kelompok_informasi', 'asc')
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('embed.ppid.dip', [
            'desa' => $desa,
            'items' => $items,
            'kategoriLabel' => $kategoriMap[$kategori],
        ]);
    }
}