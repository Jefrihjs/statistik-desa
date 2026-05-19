<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('dokumen_antikorupsi', 'sub_judul')) {
            Schema::table('dokumen_antikorupsi', function (Blueprint $table) {
                $table->string('sub_judul')->nullable()->after('sub');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('dokumen_antikorupsi', 'sub_judul')) {
            Schema::table('dokumen_antikorupsi', function (Blueprint $table) {
                $table->dropColumn('sub_judul');
            });
        }
    }
};