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

        $daftarTahun = Statistic::where('desa_id', $desa->id)
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $tahun = (int) $request->input('tahun', $daftarTahun->first() ?? date('Y'));

        $hiddenItems = \App\Models\DesaItemHide::where('desa_id', $desa->id)->get();
        $hiddenCatIds = $hiddenItems->where('hideable_type', 'App\Models\Category')->pluck('hideable_id')->toArray();
        $hiddenIndIds = $hiddenItems->where('hideable_type', 'App\Models\Indicator')->pluck('hideable_id')->toArray();

        $categories = Category::where('is_active', 1)
            ->whereNotIn('id', $hiddenCatIds) 
            ->with(['indicators' => function($q) use ($hiddenIndIds, $desa) {
                $q->where('is_active', 1)
                  ->whereNotIn('id', $hiddenIndIds) 
                  ->with(['statistics' => function($sq) use ($desa) {
                      $sq->where('desa_id', $desa->id);
                  }]);
            }])
            ->get();

        foreach ($categories as $category) {
            if ($category->slug === 'usia-detail' || $category->slug === 'kelompok-usia') {
                $category->setRelation('indicators', $category->indicators->sortBy(function($ind) {
                    preg_match('/\d+/', $ind->name, $matches);
                    $val = (int)($matches[0] ?? 999);
                    if (str_contains($ind->name, '+')) { $val += 0.5; }
                    return $val;
                })->values());
            }
        }

        $templateId = $desa->public_template_id ?? 1;
        $viewName = "public.template_" . $templateId;
        
        if (!view()->exists($viewName)) {
            $viewName = 'frontend.desa_profil';
        }

        return view($viewName, compact('desa', 'categories', 'tahun', 'daftarTahun'));
    }
}