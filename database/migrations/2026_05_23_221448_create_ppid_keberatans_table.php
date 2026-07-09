<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppid_keberatans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('desa_id')
                ->constrained('desas')
                ->cascadeOnDelete();

            $table->foreignId('ppid_permohonan_id')
                ->constrained('ppid_permohonans')
                ->cascadeOnDelete();

            $table->string('kode_keberatan', 10)->nullable()->unique();

            $table->string('alasan_keberatan', 1);
            $table->text('uraian_alasan')->nullable();

            $table->string('status')->default('diajukan');
            // diajukan, diproses, selesai, ditolak

            $table->text('tanggapan_admin')->nullable();
            $table->timestamp('ditanggapi_pada')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppid_keberatans');
    }
};