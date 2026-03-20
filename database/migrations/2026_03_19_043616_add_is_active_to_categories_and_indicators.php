<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kita skip categories karena sudah ada dari migrasi tanggal 15
        
        // Kita tambahkan HANYA ke indicators
        Schema::table('indicators', function (Blueprint $table) {
            if (!Schema::hasColumn('indicators', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('category_id');
            }
        });
    }

    public function down()
    {
        Schema::table('indicators', function (Blueprint $table) {
            if (Schema::hasColumn('indicators', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};