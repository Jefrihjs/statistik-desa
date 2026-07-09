<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppid_pemberitahuans', function (Blueprint $table) {
            if (!Schema::hasColumn('ppid_pemberitahuans', 'pasal_17_huruf')) {
                $table->string('pasal_17_huruf')->nullable()->after('catatan_penolakan');
            }

            if (!Schema::hasColumn('ppid_pemberitahuans', 'pasal_uu_lainnya')) {
                $table->string('pasal_uu_lainnya')->nullable()->after('pasal_17_huruf');
            }

            if (!Schema::hasColumn('ppid_pemberitahuans', 'rincian_informasi_ditolak')) {
                $table->text('rincian_informasi_ditolak')->nullable()->after('pasal_uu_lainnya');
            }

            if (!Schema::hasColumn('ppid_pemberitahuans', 'hasil_uji_konsekuensi')) {
                $table->text('hasil_uji_konsekuensi')->nullable()->after('rincian_informasi_ditolak');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ppid_pemberitahuans', function (Blueprint $table) {
            if (Schema::hasColumn('ppid_pemberitahuans', 'pasal_17_huruf')) {
                $table->dropColumn('pasal_17_huruf');
            }

            if (Schema::hasColumn('ppid_pemberitahuans', 'pasal_uu_lainnya')) {
                $table->dropColumn('pasal_uu_lainnya');
            }

            if (Schema::hasColumn('ppid_pemberitahuans', 'rincian_informasi_ditolak')) {
                $table->dropColumn('rincian_informasi_ditolak');
            }

            if (Schema::hasColumn('ppid_pemberitahuans', 'hasil_uji_konsekuensi')) {
                $table->dropColumn('hasil_uji_konsekuensi');
            }
        });
    }
};