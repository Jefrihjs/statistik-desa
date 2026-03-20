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
            $sheets[] = new StatistikCategorySheet($category);
        }

        return $sheets;
    }
}