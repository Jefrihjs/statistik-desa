<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skm_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->string('unsur')->nullable();
            $table->text('pertanyaan');
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['desa_id', 'is_active']);
        });

        Schema::create('skm_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();

            $table->string('nama')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('usia')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('layanan_yang_dinilai')->nullable();

            $table->text('saran')->nullable();
            $table->decimal('nilai_rata_rata', 5, 2)->default(0);
            $table->timestamps();

            $table->index(['desa_id', 'created_at']);
        });

        Schema::create('skm_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skm_response_id')->constrained('skm_responses')->cascadeOnDelete();
            $table->foreignId('skm_question_id')->constrained('skm_questions')->cascadeOnDelete();
            $table->unsignedTinyInteger('nilai');
            $table->timestamps();

            $table->unique(['skm_response_id', 'skm_question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skm_answers');
        Schema::dropIfExists('skm_responses');
        Schema::dropIfExists('skm_questions');
    }
};