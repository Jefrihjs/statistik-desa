<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aduans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();

            $table->string('kode_aduan')->unique();
            $table->string('nama_pelapor');
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();

            $table->string('kategori')->nullable();
            $table->string('judul');
            $table->text('isi_aduan');

            $table->string('status')->default('baru');
            // baru, diproses, selesai, ditolak

            $table->text('tanggapan')->nullable();
            $table->timestamp('ditanggapi_pada')->nullable();
            $table->foreignId('ditanggapi_oleh')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['desa_id', 'status']);
            $table->index('kode_aduan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aduans');
    }
};