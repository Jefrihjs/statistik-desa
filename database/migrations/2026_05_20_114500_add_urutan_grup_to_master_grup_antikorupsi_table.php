<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('master_grup_antikorupsi', 'urutan_grup')) {
            Schema::table('master_grup_antikorupsi', function (Blueprint $table) {
                $table->integer('urutan_grup')->nullable()->after('kategori');
            });
        }

        DB::statement("UPDATE master_grup_antikorupsi SET urutan_grup = id WHERE urutan_grup IS NULL");
    }

    public function down(): void
    {
        if (Schema::hasColumn('master_grup_antikorupsi', 'urutan_grup')) {
            Schema::table('master_grup_antikorupsi', function (Blueprint $table) {
                $table->dropColumn('urutan_grup');
            });
        }
    }
};