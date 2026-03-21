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
        Schema::table('domain_trackers', function (Blueprint $table) {
            $table->timestamp('created_date')->nullable();
            $table->string('nameservers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domain_trackers', function (Blueprint $table) {
            //
        });
    }
};
