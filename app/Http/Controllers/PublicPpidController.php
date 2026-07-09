<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\PpidDip; // Sesuaikan dengan nama Model DIP Abang
use Illuminate\Http\Request;

class PublicPpidController extends Controller
{
    public function embed($slug, $kategori)
    {
        // 1. Cari data desa berdasarkan slug
        $desa = Desa::where('slug', $slug)->firstOrFail();

        // 2. Petakan kode kategori URL ke label judul halaman
        $kategoriList = [
            'berkala'      => 'Informasi Secara Berkala',
            'serta-merta'  => 'Informasi Serta Merta',
            'setiap-saat'  => 'Informasi Setiap Saat',
            'dikecualikan' => 'Informasi Dikecualikan',
        ];

        // Jika parameter kategori di URL ngawur/tidak terdaftar, langsung kunci dengan 404
        if (!array_key_exists($kategori, $kategoriList)) {
            abort(404);
        }

        $kategoriLabel = $kategoriList[$kategori];

        // 3. Ambil data DIP yang hanya cocok dengan desa DAN kategori ini saja
        $items = PpidDip::where('desa_id', $desa->id)
            ->where('kategori', $kategori)
            ->where('is_active', true)
            ->orderBy('urutan', 'asc')
            ->get();

        // 4. Lempar ke file view murni yang berada di resources/views/embed/ppid/dip.blade.php
        return view('embed.ppid.dip', compact('desa', 'items', 'kategoriLabel'));
    }
}