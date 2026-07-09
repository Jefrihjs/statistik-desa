<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_statistik_active')) {
                $table->boolean('is_statistik_active')->default(false);
            }

            if (!Schema::hasColumn('users', 'is_ppid_active')) {
                $table->boolean('is_ppid_active')->default(false)->after('is_statistik_active');
            }

            if (!Schema::hasColumn('users', 'is_antikorupsi_active')) {
                $table->boolean('is_antikorupsi_active')->default(false)->after('is_ppid_active');
            }

            if (!Schema::hasColumn('users', 'is_skm_active')) {
                $table->boolean('is_skm_active')->default(false)->after('is_antikorupsi_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'is_statistik_active',
                'is_ppid_active',
                'is_antikorupsi_active',
                'is_skm_active',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};