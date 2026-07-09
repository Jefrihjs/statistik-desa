<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('ppid_permohonans', 'kode_permohonan')) {
            Schema::table('ppid_permohonans', function (Blueprint $table) {
                $table->string('kode_permohonan', 10)->nullable()->unique()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ppid_permohonans', 'kode_permohonan')) {
            Schema::table('ppid_permohonans', function (Blueprint $table) {
                $table->dropColumn('kode_permohonan');
            });
        }
    }
};