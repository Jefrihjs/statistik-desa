<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Desa; // Pastikan Model Desa di-import
use App\Models\DokumenAntikorupsi;

class AntikorupsiController extends Controller
{
    // Tambahkan $slug sebagai parameter untuk mengidentifikasi desa
    public function index($slug)
    {
        // 1. Cari data desa berdasarkan slug
        // Jika slug tidak ditemukan, otomatis akan mengembalikan halaman 404 Not Found
        $desa = Desa::where('slug', $slug)->firstOrFail();

        // 2. Ambil dokumen HANYA untuk desa tersebut
        $dokumen = DokumenAntikorupsi::where('desa_id', $desa->id)
            ->orderBy('urutan_tampil', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $masterGrupList = \App\Models\MasterGrupAntikorupsi::orderBy('kategori', 'asc')
            ->orderBy('urutan_grup', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $kategoriKeys = ['tatalaksana', 'pengawasan', 'pelayanan', 'partisipasi', 'kearifan'];

        $data = [];

        foreach ($kategoriKeys as $kategori) {
            $data[$kategori] = collect();

            $grupKategori = $masterGrupList->where('kategori', $kategori);

            foreach ($grupKategori as $grup) {
                $items = $dokumen
                    ->where('kategori', $kategori)
                    ->where('grup_indikator', $grup->nama_grup)
                    ->sortBy([
                        ['urutan_tampil', 'asc'],
                        ['id', 'asc'],
                    ])
                    ->values();

                if ($items->isNotEmpty()) {
                    $data[$kategori]->put($grup->nama_grup, $items);
                }
            }
        }

        // 3. Sertakan variabel $desa ke dalam view agar bisa digunakan 
        // untuk menampilkan nama desa, header color, dll di halaman publik
        return view('antikorupsi.index', compact('desa', 'data'));
    }
}