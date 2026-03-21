<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Desa;
use App\Models\DomainTracker;

class DomainTrackerSeeder extends Seeder
{
    public function run()
    {
        $domains = [
            'airkelik.desa.id', 'burongmandi.desa.id', 'mengkubang.desa.id',
            'sukamandi-beltim.desa.id', 'mempaya.desa.id', 'nyuruk.desa.id',
            'dendang.desa.id', 'balok.desa.id', 'jangkang-beltim.desa.id',
            'gantung.desa.id', 'jangkarasam.desa.id', 'lenggang.desa.id',
            'lilangan.desa.id', 'batupenyu.desa.id', 'limbongan.desa.id',
            'selinsing.desa.id', 'mayang.desa.id', 'buding.desa.id',
            'mentawak.desa.id', 'pembaharuan.desa.id', 'cendil.desa.id',
            'senyubuk.desa.id', 'bentaianjaya.desa.id', 'bukulimau.desa.id',
            'lalangjaya.desa.id', 'baru-beltim.desa.id', 'kelubi.desa.id',
            'kurniajaya.desa.id', 'lalang.desa.id', 'mekarjaya-beltim.desa.id',
            'padang-beltim.desa.id', 'dukong-beltim.desa.id', 'tanjungbatuitam.desa.id',
            'tanjungkelumpang.desa.id', 'simpangpesak.desa.id', 'simpangtiga.desa.id',
            'aikmadu.desa.id', 'lintang.desa.id', 'renggiang.desa.id'
        ];

        foreach ($domains as $domain) {
            $clean = explode('.', $domain)[0];
            $search = str_replace('-beltim', '', $clean);
            
            // Normalisasi: Huruf Besar & Tanpa Spasi
            $searchUpper = strtoupper($search);
            $searchUpper = str_replace(' ', '', $searchUpper);

            // 1. Coba Cari Presisi (Tanpa Spasi)
            $desa = Desa::whereRaw("REPLACE(nama_desa, ' ', '') = ?", [$searchUpper])->first();

            // 2. Kalau Gagal, Coba Cari Pakai LIKE (Untuk kasus Selinsing vs Selingsing)
            if (!$desa) {
                // Kita ambil 4 huruf depan saja untuk cari kemiripan (Misal: SELI...)
                $shortSearch = substr($searchUpper, 0, 4);
                $desa = Desa::where('nama_desa', 'LIKE', $shortSearch . '%')->first();
            }

            if ($desa) {
                DomainTracker::updateOrCreate(
                    ['domain_name' => $domain],
                    ['desa_id' => $desa->id, 'status' => 'Unknown']
                );
                $this->command->info("✅ PAS: {$domain} -> {$desa->nama_desa}");
            } else {
                $this->command->error("❌ GAGAL: {$domain} tetap tidak ketemu.");
            }
        }
    }
}