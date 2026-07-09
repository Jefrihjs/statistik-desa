<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('skm_rekomendasi', function (Blueprint $table) {
            $table->string('kode_survey')->nullable()->after('desa_id');
        });
    }

    public function down()
    {
        Schema::table('skm_rekomendasi', function (Blueprint $table) {
            $table->dropColumn('kode_survey');
        });
    }
};