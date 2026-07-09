<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppid_pemberitahuans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('desa_id')
                ->constrained('desas')
                ->cascadeOnDelete();

            $table->foreignId('ppid_permohonan_id')
                ->constrained('ppid_permohonans')
                ->cascadeOnDelete();

            $table->string('status_informasi'); 
            // dapat_diberikan / tidak_dapat_diberikan

            $table->string('penguasaan_informasi')->nullable();
            $table->string('nama_badan_publik_lain')->nullable();

            $table->string('bentuk_fisik')->nullable();

            $table->unsignedBigInteger('biaya_salinan')->default(0);
            $table->unsignedBigInteger('biaya_kirim')->default(0);
            $table->unsignedBigInteger('biaya_lain')->default(0);
            $table->unsignedBigInteger('total_biaya')->default(0);

            $table->integer('waktu_penyediaan')->nullable();
            $table->text('penjelasan_penghitaman')->nullable();

            $table->string('alasan_penolakan')->nullable();
            $table->text('catatan_penolakan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppid_pemberitahuans');
    }
};