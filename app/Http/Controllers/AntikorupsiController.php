<?php

namespace App\Http\Controllers;

use App\Models\DokumenAntikorupsi;
use Illuminate\Http\Request;

class AntikorupsiController extends Controller
{
    public function index()
    {
        $dokumen = DokumenAntikorupsi::orderBy('urutan_tampil', 'asc')
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

        return view('antikorupsi.index', compact('data'));
    }
}