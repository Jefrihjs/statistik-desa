<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppid_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('desa_id')
                ->constrained('desas')
                ->cascadeOnDelete();

            $table->foreignId('ppid_permohonan_id')
                ->constrained('ppid_permohonans')
                ->cascadeOnDelete();

            $table->foreignId('ppid_keberatan_id')
                ->nullable()
                ->constrained('ppid_keberatans')
                ->nullOnDelete();

            $table->string('tipe')->default('permohonan');
            // permohonan / keberatan

            $table->string('judul');
            $table->text('deskripsi')->nullable();

            $table->string('actor_name')->nullable();
            $table->string('actor_role')->nullable();

            $table->timestamps();

            $table->index(['desa_id', 'ppid_permohonan_id']);
            $table->index(['tipe']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppid_logs');
    }
};