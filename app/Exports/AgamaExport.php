<?php

namespace App\Exports;

use App\Models\Statistic;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class AgamaExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize, WithCustomStartCell
{
    protected $tahun;

    public function __construct($tahun)
    {
        $this->tahun = $tahun;
    }

    // Kita mulai tabel dari baris ke-4, karena baris 1-2 untuk Judul
    public function startCell(): string
    {
        return 'A4';
    }

    public function collection()
    {
        return Statistic::with(['desa', 'indicator'])
            ->whereHas('indicator.category', function($q) {
                $q->where('slug', 'agama');
            })
            ->where('year', $this->tahun)
            ->get();
    }

    public function headings(): array
    {
        return ['NAMA DESA', 'INDIKATOR AGAMA', 'JUMLAH (JIWA)', 'TAHUN'];
    }

    public function map($stat): array
    {
        return [
            $stat->desa->nama_desa,
            $stat->indicator->name,
            $stat->value,
            $stat->year,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Judul Laporan di Baris 1
                $event->sheet->mergeCells('A1:D1');
                $event->sheet->setCellValue('A1', 'LAPORAN DATA STATISTIK SEKTORAL (AGAMA)');
                
                // Sub-Judul di Baris 2
                $event->sheet->mergeCells('A2:D2');
                $event->sheet->setCellValue('A2', 'KABUPATEN BELITUNG TIMUR TAHUN ' . $this->tahun);

                // Styling Judul (Baris 1 & 2)
                $event->sheet->getStyle('A1:A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Styling Header Tabel (Baris 4)
                $event->sheet->getStyle('A4:D4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1e293b'], // Warna Slate-900 biar matching sama web
                    ],
                ]);
            },
        ];
    }
}