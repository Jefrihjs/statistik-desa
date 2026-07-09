<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            if (!Schema::hasColumn('desas', 'alamat_kantor')) {
                $table->text('alamat_kantor')->nullable()->after('nama_desa');
            }

            if (!Schema::hasColumn('desas', 'email_desa')) {
                $table->string('email_desa')->nullable()->after('alamat_kantor');
            }

            if (!Schema::hasColumn('desas', 'website_desa')) {
                $table->string('website_desa')->nullable()->after('email_desa');
            }

            if (!Schema::hasColumn('desas', 'telepon_desa')) {
                $table->string('telepon_desa')->nullable()->after('website_desa');
            }

            if (!Schema::hasColumn('desas', 'logo_desa')) {
                $table->string('logo_desa')->nullable()->after('telepon_desa');
            }

            if (!Schema::hasColumn('desas', 'nama_ppid')) {
                $table->string('nama_ppid')->nullable()->after('logo_desa');
            }

            if (!Schema::hasColumn('desas', 'jabatan_ppid')) {
                $table->string('jabatan_ppid')->nullable()->after('nama_ppid');
            }

            if (!Schema::hasColumn('desas', 'nip_ppid')) {
                $table->string('nip_ppid')->nullable()->after('jabatan_ppid');
            }
        });
    }

    public function down(): void
    {
        Schema::table('desas', function (Blueprint $table) {
            foreach ([
                'alamat_kantor',
                'email_desa',
                'website_desa',
                'telepon_desa',
                'logo_desa',
                'nama_ppid',
                'jabatan_ppid',
                'nip_ppid',
            ] as $column) {
                if (Schema::hasColumn('desas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};