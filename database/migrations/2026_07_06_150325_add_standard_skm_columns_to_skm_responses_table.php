<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('skm_responses', function (Blueprint $table) {
            $table->integer('q1')->nullable()->after('layanan_yang_dinilai');
            $table->integer('q2')->nullable()->after('q1');
            $table->integer('q3')->nullable()->after('q2');
            $table->integer('q4')->nullable()->after('q3');
            $table->integer('q5')->nullable()->after('q4');
            $table->integer('q6')->nullable()->after('q5');
            $table->integer('q7')->nullable()->after('q6');
            $table->integer('q8')->nullable()->after('q7');
            $table->integer('q9')->nullable()->after('q8');
        });
    }

    public function down()
    {
        Schema::table('skm_responses', function (Blueprint $table) {
            $table->dropColumn(['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9']);
        });
    }
};