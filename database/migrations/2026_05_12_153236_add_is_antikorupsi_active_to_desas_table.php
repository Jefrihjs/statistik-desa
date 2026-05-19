<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            // Tambahkan kolom boolean. Default false (tidak aktif)
            $table->boolean('is_antikorupsi_active')->default(false)->after('nama_desa'); 
        });
    }

    public function down(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->dropColumn('is_antikorupsi_active');
        });
    }
};