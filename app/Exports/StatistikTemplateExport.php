<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StatistikTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $categories = Category::with('indicators')->get();

        foreach ($categories as $category) {
            if ($category->slug === 'usia-detail' || $category->slug === 'kelompok-usia') {
                $category->setRelation('indicators', $category->indicators->sortBy(function($ind) {
                    preg_match('/\d+/', $ind->name, $matches);
                    $val = (int)($matches[0] ?? 999);
                    if (str_contains($ind->name, '+')) { $val += 0.5; }
                    return $val;
                })->values());
            }
            $sheets[] = new StatistikCategorySheet($category);
        }

        return $sheets;
    }
}