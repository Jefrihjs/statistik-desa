<?php

namespace Database\Seeders;

use App\Models\Desa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class DesaSeeder extends Seeder
{
    public function run()
    {
        $daftarDesa = [
            'AIR KELIK', 'BURONG MANDI', 'MENGKUBANG', 'SUKAMANDI', 'MEMPAYA',
            'NYURUK', 'DENDANG', 'BALOK', 'JANGKANG', 'GANTUNG',
            'JANGKAR ASAM', 'LENGGANG', 'LILANGAN', 'BATU PENYU', 'LIMBONGAN',
            'SELINGSING', 'MAYANG', 'BUDING', 'MENTAWAK', 'PEMBAHARUAN',
            'CENDIL', 'SENYUBUK', 'BENTAIAN JAYA', 'BUKU LIMAU', 'LALANG JAYA',
            'BARU', 'KELUBI', 'KURNIA JAYA', 'LALANG', 'MEKAR JAYA',
            'PADANG', 'DUKONG', 'TANJUNG BATU ITAM', 'TANJUNG KELUMPANG', 'SIMPANG PESAK',
            'SIMPANG TIGA', 'AIK MADU', 'LINTANG', 'RENGGIANG'
        ];

       foreach ($daftarDesa as $nama) {
            Desa::create([
                'nama_desa' => $nama,
                'slug' => Str::slug($nama),
            ]);
        }
    }
}