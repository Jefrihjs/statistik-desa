<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('slug');
            $table->string('header_color', 20)->nullable()->after('logo');
            $table->string('accent_color', 20)->nullable()->after('header_color');
        });
    }

    public function down(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->dropColumn(['logo', 'header_color', 'accent_color']);
        });
    }
};