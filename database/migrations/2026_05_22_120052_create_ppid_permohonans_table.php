<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppid_permohonans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('desa_id')
                ->constrained('desas')
                ->cascadeOnDelete();

            $table->string('kategori_pemohon'); // perorangan / lembaga
            $table->string('nik', 16);
            $table->string('nama');
            $table->string('file_ktp')->nullable();
            $table->string('file_akta')->nullable();

            $table->text('alamat');
            $table->string('email')->nullable();
            $table->string('no_hp');
            $table->string('pekerjaan')->nullable();

            $table->text('rincian_informasi');
            $table->text('tujuan_penggunaan');

            $table->string('cara_memperoleh')->nullable();
            $table->string('jenis_salinan')->nullable();
            $table->string('cara_pengiriman')->nullable();
            $table->string('no_wa')->nullable();

            $table->string('status')->default('masuk');
            // masuk, diproses, selesai, ditolak

            $table->text('catatan_admin')->nullable();
            $table->timestamp('diproses_pada')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppid_permohonans');
    }
};