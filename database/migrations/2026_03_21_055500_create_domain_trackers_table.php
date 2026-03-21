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
        Schema::create('domain_trackers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->onDelete('cascade');
            $table->string('domain_name')->unique(); // contoh: airkelik.desa.id
            $table->date('expiry_date')->nullable();
            $table->integer('days_left')->nullable(); // Sisa hari (biar gampang di-filter)
            $table->enum('status', ['Sehat', 'Kritis', 'Expired', 'Unknown'])->default('Unknown');
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_trackers');
    }
};
