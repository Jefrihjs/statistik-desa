<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StatistikCategorySheet implements FromCollection, WithTitle, WithHeadings, WithMapping, ShouldAutoSize
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function collection()
    {
        return $this->category->indicators;
    }

    public function title(): string
    {
        // Membatasi panjang judul sheet maksimal 31 karakter (aturan Excel)
        return substr($this->category->name, 0, 31);
    }

    public function headings(): array
    {
        return [
            'ID',
            'NAMA_INDIKATOR',
            'LAKI_LAKI',
            'PEREMPUAN',
            'SATUAN'
        ];
    }

    /**
     * Memetakan data ke kolom dengan logika OTOMATIS
     */
    public function map($indicator): array
    {
        // Cek apakah ini indikator Laki-laki/Perempuan di kategori Demografi
        $isOtomatis = ($this->category->slug == 'demografi' && 
                      ($indicator->name == 'Laki-laki' || $indicator->name == 'Perempuan'));

        return [
            $indicator->id,
            $indicator->name,
            $isOtomatis ? 'OTOMATIS (DARI DATA USIA)' : '0', // Jika otomatis, beri teks peringatan
            $isOtomatis ? 'OTOMATIS (DARI DATA USIA)' : '0',
            $indicator->unit
        ];
    }
}