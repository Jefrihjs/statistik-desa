<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppid_keberatans', function (Blueprint $table) {
            $table->text('alasan_keberatan')->change();
        });
    }

    public function down(): void
    {
        Schema::table('ppid_keberatans', function (Blueprint $table) {
            $table->string('alasan_keberatan', 1)->change();
        });
    }
};