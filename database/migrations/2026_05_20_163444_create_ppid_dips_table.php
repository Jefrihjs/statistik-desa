<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppid_dips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('desa_id')
                ->constrained('desas')
                ->cascadeOnDelete();

            $table->string('kategori'); 
            // berkala, serta_merta, setiap_saat, dikecualikan

            $table->integer('urutan')->nullable();

            $table->string('judul_informasi');
            $table->text('ringkasan')->nullable();

            $table->string('link_dokumen')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppid_dips');
    }
};