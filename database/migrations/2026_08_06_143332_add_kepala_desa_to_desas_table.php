<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->string('nama_kepala_desa')->nullable()->after('telepon_desa');
            $table->string('nip_kepala')->nullable()->after('nama_kepala_desa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->dropColumn(['nama_kepala_desa', 'nip_kepala']);
        });
    }
};
