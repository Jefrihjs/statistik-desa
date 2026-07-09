<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aduans', function (Blueprint $table) {
            if (!Schema::hasColumn('aduans', 'jenis_identitas')) {
                $table->string('jenis_identitas')->default('rahasia')->after('desa_id');
            }

            if (!Schema::hasColumn('aduans', 'is_identity_hidden')) {
                $table->boolean('is_identity_hidden')->default(true)->after('jenis_identitas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('aduans', function (Blueprint $table) {
            if (Schema::hasColumn('aduans', 'is_identity_hidden')) {
                $table->dropColumn('is_identity_hidden');
            }

            if (Schema::hasColumn('aduans', 'jenis_identitas')) {
                $table->dropColumn('jenis_identitas');
            }
        });
    }
};