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
        Schema::table('desas', function (Blueprint $table) {
            $table->string('layout_type')->default('default'); // Untuk pilih template
            $table->text('welcome_message')->nullable();      // Narasi Kades
            $table->unsignedBigInteger('featured_category_id')->nullable(); // Kategori unggulan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            //
        });
    }
};
