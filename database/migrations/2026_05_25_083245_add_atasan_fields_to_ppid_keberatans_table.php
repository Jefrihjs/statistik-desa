<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppid_keberatans', function (Blueprint $table) {
            if (!Schema::hasColumn('ppid_keberatans', 'nama_atasan_ppid')) {
                $table->string('nama_atasan_ppid')->nullable()->after('status');
            }

            if (!Schema::hasColumn('ppid_keberatans', 'posisi_atasan')) {
                $table->string('posisi_atasan')->nullable()->after('nama_atasan_ppid');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ppid_keberatans', function (Blueprint $table) {
            if (Schema::hasColumn('ppid_keberatans', 'nama_atasan_ppid')) {
                $table->dropColumn('nama_atasan_ppid');
            }

            if (Schema::hasColumn('ppid_keberatans', 'posisi_atasan')) {
                $table->dropColumn('posisi_atasan');
            }
        });
    }
};