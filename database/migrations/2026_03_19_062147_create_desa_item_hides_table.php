<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('desa_item_hides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->onDelete('cascade');
            
            // polymorphic field: ini sakti karena bisa nyimpen ID Kategori atau ID Indikator
            $table->string('hideable_type'); // Isinya nanti: 'App\Models\Category' atau 'App\Models\Indicator'
            $table->unsignedBigInteger('hideable_id'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desa_item_hides');
    }
};
