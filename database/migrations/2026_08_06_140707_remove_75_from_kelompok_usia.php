<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Indicator;
use App\Models\Statistic;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $cat = Category::where('slug', 'kelompok-usia')->first();
        if ($cat) {
            $indicator = Indicator::where('category_id', $cat->id)->where('name', '75')->first();
            if ($indicator) {
                // Delete associated statistics
                Statistic::where('indicator_id', $indicator->id)->delete();
                // Delete indicator
                $indicator->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed
    }
};
