<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $groups = DB::table('dokumen_antikorupsi')
            ->select('desa_id', 'kategori', 'grup_indikator')
            ->groupBy('desa_id', 'kategori', 'grup_indikator')
            ->get();

        foreach ($groups as $group) {
            $items = DB::table('dokumen_antikorupsi')
                ->where('desa_id', $group->desa_id)
                ->where('kategori', $group->kategori)
                ->where('grup_indikator', $group->grup_indikator)
                ->orderBy('urutan_tampil')
                ->orderBy('id')
                ->get();

            foreach ($items as $index => $item) {
                DB::table('dokumen_antikorupsi')
                    ->where('id', $item->id)
                    ->update([
                        'urutan_tampil' => $index + 1,
                        'parent_id' => null,
                        'level' => 0,
                        'no_urut' => (string) ($index + 1),
                        'sub' => null,
                    ]);
            }
        }
    }

    public function down(): void
    {
        //
    }
};
