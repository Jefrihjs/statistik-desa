<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_grup_antikorupsi', function (Blueprint $table) {
            $table->id();
            $table->string('kategori'); // Menyimpan value: tatalaksana, pengawasan, dll
            $table->string('nama_grup'); // Menyimpan teks: "1. Perdes/SOP tentang Perencanaan..."
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_grup_antikorupsi');
    }
};