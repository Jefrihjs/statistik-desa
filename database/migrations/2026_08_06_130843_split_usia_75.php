<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Indicator;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Usia Detail
        $catUsiaDetail = Category::where('slug', 'usia-detail')->first();
        if ($catUsiaDetail) {
            // make sure Usia 75 exists
            Indicator::updateOrCreate(
                ['category_id' => $catUsiaDetail->id, 'name' => 'Usia 75'],
                ['unit' => 'Jiwa']
            );
            // make sure Usia 75+ exists
            Indicator::updateOrCreate(
                ['category_id' => $catUsiaDetail->id, 'name' => 'Usia 75+'],
                ['unit' => 'Jiwa']
            );
        }

        // 2. Kelompok Usia
        $catKelompokUsia = Category::where('slug', 'kelompok-usia')->first();
        if ($catKelompokUsia) {
            // make sure 75 exists
            Indicator::updateOrCreate(
                ['category_id' => $catKelompokUsia->id, 'name' => '75'],
                ['unit' => 'Jiwa']
            );
            // make sure 75+ exists
            Indicator::updateOrCreate(
                ['category_id' => $catKelompokUsia->id, 'name' => '75+'],
                ['unit' => 'Jiwa']
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down needed or optionally delete the created indicators
    }
};
