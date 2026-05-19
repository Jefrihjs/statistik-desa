<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDesaIdToDokumenAntikorupsiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dokumen_antikorupsi', function (Blueprint $table) {
            // Menambahkan kolom desa_id tepat di bawah kolom ID utama
            $table->unsignedBigInteger('desa_id')->nullable()->after('id');
            
            // Opsional: Jika tabel desa bapak bernama 'desas', aktifkan relasi foreign key ini
            // $table->foreign('desa_id')->references('id')->on('desas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_antikorupsi', function (Blueprint $table) {
            $table->dropColumn('desa_id');
        });
    }
}