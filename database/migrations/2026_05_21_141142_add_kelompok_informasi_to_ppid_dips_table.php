<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('ppid_dips', 'kelompok_informasi')) {
            Schema::table('ppid_dips', function (Blueprint $table) {
                $table->string('kelompok_informasi')->nullable()->after('kategori');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ppid_dips', 'kelompok_informasi')) {
            Schema::table('ppid_dips', function (Blueprint $table) {
                $table->dropColumn('kelompok_informasi');
            });
        }
    }
};