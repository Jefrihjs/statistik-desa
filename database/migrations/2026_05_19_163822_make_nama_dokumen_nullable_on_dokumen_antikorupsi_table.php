<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE dokumen_antikorupsi MODIFY nama_dokumen VARCHAR(255) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE dokumen_antikorupsi MODIFY nama_dokumen VARCHAR(255) NOT NULL");
    }
};