<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('skm_rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained()->cascadeOnDelete();
            $table->string('nomor_rekom');
            $table->string('versi')->nullable();
            $table->year('tahun');
            $table->boolean('is_active')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['desa_id', 'tahun']);
        });

        Schema::table('skm_responses', function (Blueprint $table) {
            $table->foreignId('skm_rekomendasi_id')
                  ->nullable()
                  ->after('desa_id')
                  ->constrained('skm_rekomendasi')
                  ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('skm_responses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('skm_rekomendasi_id');
        });

        Schema::dropIfExists('skm_rekomendasi');
    }
};