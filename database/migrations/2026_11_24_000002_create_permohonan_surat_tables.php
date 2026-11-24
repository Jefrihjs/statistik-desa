<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jenis_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->string('nama_layanan');
            $table->string('kode_surat')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('jenis_surat_syarats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->cascadeOnDelete();
            $table->string('nama_syarat');
            $table->enum('tipe_input', ['file', 'info_fisik'])->default('file');
            $table->boolean('is_wajib')->default(true);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('permohonan_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->foreignId('jenis_surat_id')->constrained('jenis_surats')->cascadeOnDelete();
            $table->string('kode_registrasi')->unique();
            $table->string('nama_pemohon');
            $table->string('nik', 16);
            $table->string('no_hp', 25);
            $table->string('email')->nullable();
            $table->text('alamat');
            $table->text('keperluan');
            $table->enum('status', ['menunggu', 'berkas_kurang', 'diproses', 'selesai', 'ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->string('file_surat_jadi')->nullable();
            $table->timestamps();
        });

        Schema::create('permohonan_surat_dokumens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_surat_id')->constrained('permohonan_surats')->cascadeOnDelete();
            $table->foreignId('jenis_surat_syarat_id')->nullable()->constrained('jenis_surat_syarats')->nullOnDelete();
            $table->string('nama_syarat');
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_surat_dokumens');
        Schema::dropIfExists('permohonan_surats');
        Schema::dropIfExists('jenis_surat_syarats');
        Schema::dropIfExists('jenis_surats');
    }
};
