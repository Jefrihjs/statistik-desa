<?php

namespace App\Imports;

use App\Models\Indicator;
use App\Models\Statistic;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StatistikImport implements ToModel, WithHeadingRow
{
    private $desa_id;
    private $tahun;

    public function __construct($desa_id, $tahun) {
        $this->desa_id = $desa_id;
        $this->tahun = $tahun;
    }

    public function model(array $row)
    {
        // 1. Gunakan 'id' dari Excel (Kolom ID di template kita)
        // Ini jauh lebih aman daripada mencari berdasarkan nama
        $indicator = Indicator::find($row['id']);

        if ($indicator) {
            // 2. LOGIKA PENCEGAHAN (The Gatekeeper)
            // Jika kategori adalah 'demografi' DAN namanya adalah 'Laki-laki' atau 'Perempuan'
            // Kita kembalikan null (Data diabaikan/skip)
            if ($indicator->category->slug == 'demografi' && 
                ($indicator->name == 'Laki-laki' || $indicator->name == 'Perempuan')) {
                return null;
            }

            // 3. Simpan data Laki-laki
            Statistic::updateOrCreate(
                [
                    'desa_id'      => $this->desa_id, 
                    'indicator_id' => $indicator->id, 
                    'year'         => $this->tahun, 
                    'gender'       => 'Laki-laki'
                ],
                ['value' => $row['laki_laki'] ?? 0]
            );

            // 4. Simpan data Perempuan
            Statistic::updateOrCreate(
                [
                    'desa_id'      => $this->desa_id, 
                    'indicator_id' => $indicator->id, 
                    'year'         => $this->tahun, 
                    'gender'       => 'Perempuan'
                ],
                ['value' => $row['perempuan'] ?? 0]
            );
        }

        return null;
    }
}