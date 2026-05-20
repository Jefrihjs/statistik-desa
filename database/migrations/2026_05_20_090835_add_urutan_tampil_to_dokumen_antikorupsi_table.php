<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('dokumen_antikorupsi', 'urutan_tampil')) {
            Schema::table('dokumen_antikorupsi', function (Blueprint $table) {
                $table->integer('urutan_tampil')->nullable()->after('grup_indikator');
            });
        }

        DB::statement("UPDATE dokumen_antikorupsi SET urutan_tampil = id WHERE urutan_tampil IS NULL");
    }

    public function down(): void
    {
        if (Schema::hasColumn('dokumen_antikorupsi', 'urutan_tampil')) {
            Schema::table('dokumen_antikorupsi', function (Blueprint $table) {
                $table->dropColumn('urutan_tampil');
            });
        }
    }
};