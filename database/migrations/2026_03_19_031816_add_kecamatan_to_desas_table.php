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
            $table->string('kecamatan')->nullable()->after('nama_desa');
        });
    }

    public function down()
    {
        Schema::table('desas', function (Blueprint $table) {
            $table->dropColumn('kecamatan');
        });
    }
};
