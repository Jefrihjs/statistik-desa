<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('ppid_permohonans', 'file_penyelesaian')) {
            Schema::table('ppid_permohonans', function (Blueprint $table) {
                $table->string('file_penyelesaian')->nullable()->after('catatan_admin');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('ppid_permohonans', 'file_penyelesaian')) {
            Schema::table('ppid_permohonans', function (Blueprint $table) {
                $table->dropColumn('file_penyelesaian');
            });
        }
    }
};