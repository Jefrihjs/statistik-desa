<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen_antikorupsi', function (Blueprint $table) {
            if (!Schema::hasColumn('dokumen_antikorupsi', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('urutan_tampil');
            }

            if (!Schema::hasColumn('dokumen_antikorupsi', 'level')) {
                $table->unsignedTinyInteger('level')->default(0)->after('parent_id');
            }
        });

        DB::table('dokumen_antikorupsi')
            ->whereNull('level')
            ->update(['level' => 0]);
    }

    public function down(): void
    {
        Schema::table('dokumen_antikorupsi', function (Blueprint $table) {
            if (Schema::hasColumn('dokumen_antikorupsi', 'level')) {
                $table->dropColumn('level');
            }

            if (Schema::hasColumn('dokumen_antikorupsi', 'parent_id')) {
                $table->dropColumn('parent_id');
            }
        });
    }
};
