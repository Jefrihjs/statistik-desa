<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_antikorupsi', function (Blueprint $table) {
            $table->id();
            // Jika TARSIUS multi-desa, buka komen di bawah ini:
            // $table->foreignId('desa_id')->constrained('desa')->cascadeOnDelete(); 
            
            $table->string('kategori'); // tatalaksana, pengawasan, pelayanan, partisipasi, kearifan
            $table->string('grup_indikator'); // Untuk judul Accordion
            $table->string('no_urut')->nullable(); // Angka 1, 2, 3...
            $table->string('sub')->nullable(); // a, b, c...
            $table->string('nama_dokumen');
            $table->string('link_drive')->nullable(); // Diisi oleh admin desa
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_antikorupsi');
    }
};