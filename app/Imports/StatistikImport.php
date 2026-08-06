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
        $indicator = Indicator::find($row['id']);

        if ($indicator) {

            if (($indicator->category->slug == 'demografi' && 
                ($indicator->name == 'Laki-laki' || $indicator->name == 'Perempuan')) ||
                ($indicator->category->slug == 'kelompok-usia')) {
                return null;
            }

            Statistic::updateOrCreate(
                [
                    'desa_id'      => $this->desa_id, 
                    'indicator_id' => $indicator->id, 
                    'year'         => $this->tahun, 
                    'gender'       => 'Laki-laki'
                ],
                ['value' => $row['laki_laki'] ?? 0]
            );

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