<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Desa;
use App\Models\Category;
use App\Models\Statistic;

class DesaController extends Controller
{
    public function profilDesa(Request $request, $slug)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        // 1. Ambil daftar tahun yang tersedia
        $daftarTahun = Statistic::where('desa_id', $desa->id)
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $tahun = (int) $request->input('tahun', $daftarTahun->first() ?? date('Y'));

        // --- TAMBAHKAN LOGIKA FILTER HIDE DI SINI ---
        // 2. Ambil ID Kategori & Indikator yang disembunyikan KHUSUS desa ini
        $hiddenItems = \App\Models\DesaItemHide::where('desa_id', $desa->id)->get();
        $hiddenCatIds = $hiddenItems->where('hideable_type', 'App\Models\Category')->pluck('hideable_id')->toArray();
        $hiddenIndIds = $hiddenItems->where('hideable_type', 'App\Models\Indicator')->pluck('hideable_id')->toArray();

        // 3. Filter Query agar yang di-hide TIDAK MUNCUL
        $categories = Category::where('is_active', 1)
            ->whereNotIn('id', $hiddenCatIds) // Saring Kategori Tersembunyi
            ->with(['indicators' => function($q) use ($hiddenIndIds, $desa) {
                $q->where('is_active', 1)
                  ->whereNotIn('id', $hiddenIndIds) // Saring Indikator Tersembunyi
                  ->with(['statistics' => function($sq) use ($desa) {
                      $sq->where('desa_id', $desa->id);
                  }]);
            }])
            ->get();

        return view('frontend.desa_profil', compact('desa', 'categories', 'tahun', 'daftarTahun'));
    }
}