<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesaItemHide;
use App\Models\Category;
use App\Models\Indicator;
use App\Models\Desa;
use App\Models\DomainTracker;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StatistikTemplateExport;
use App\Imports\StatistikImport;

class StatistikController extends Controller
{
    public function index(Request $request)
    {
        $tahun = (int) $request->query('tahun', date('Y'));

        $domains = \App\Models\DomainTracker::with('desa')->get();

        $mapping = [
            'KECAMATAN MANGGAR' => ['BARU', 'LALANG', 'LALANG JAYA', 'BUKU LIMAU', 'MEKAR JAYA', 'PADANG', 'KELUBI', 'BENTAIAN JAYA', 'KURNIA JAYA'],
            'KECAMATAN GANTUNG' => ['GANTUNG', 'LENGGANG', 'SELINSING', 'BATU PENYU', 'LIMBONGAN', 'JANGKAR ASAM', 'LILANGAN'],
            'KECAMATAN KELAPA KAMPIT' => ['SENYUBUK', 'MAYANG', 'PEMBAHARUAN', 'MENTAWAK', 'BUDING', 'CENDIL'],
            'KECAMATAN DENDANG' => ['DENDANG', 'JANGKANG', 'NYURUK', 'BALOK'],
            'KECAMATAN DAMAR' => ['MENGKUBANG', 'BURONG MANDI', 'SUKAMANDI', 'MEMPAYA', 'AIR KELIK'],
            'KECAMATAN SIMPANG PESAK' => ['SIMPANG PESAK', 'DUKONG', 'TANJUNG BATU ITAM', 'TANJUNG KELUMPANG'],
            'KECAMATAN SIMPANG RENGGIANG' => ['SIMPANG TIGA', 'RENGGIANG', 'LINTANG', 'AIK MADU'],
        ];

        $desas = Desa::withCount(['statistics as total_input' => function($q) use ($tahun) {
            $q->where('year', $tahun)
            ->where('value', '>', 0);
        }])
        ->orderBy('kecamatan')
        ->orderBy('nama_desa')
        ->get();

        $listTahun = Statistic::select('year')->distinct()->orderByDesc('year')->pluck('year');
        if (!$listTahun->contains(date('Y'))) { $listTahun->push(date('Y')); }

        return view('admin.index', compact('desas', 'mapping', 'listTahun', 'tahun'));
    }

    public function entri(Request $request, $desa_id)
    {
        $desa = Desa::findOrFail($desa_id);
        $tahun = (int) $request->query('tahun', date('Y'));

        // 1. Ambil ID 
        $hiddenItems = DesaItemHide::where('desa_id', $desa_id)->get();
        $hiddenCatIds = $hiddenItems->where('hideable_type', 'App\Models\Category')->pluck('hideable_id')->toArray();
        $hiddenIndIds = $hiddenItems->where('hideable_type', 'App\Models\Indicator')->pluck('hideable_id')->toArray();

        $categories = Category::where('is_active', true)
            ->whereNotIn('id', $hiddenCatIds)
            ->with(['indicators' => function($q) use ($hiddenIndIds, $desa_id, $tahun) {
                $q->where('is_active', true)
                ->whereNotIn('id', $hiddenIndIds)
                ->with(['statistics' => function($sq) use ($desa_id, $tahun) {
                    $sq->where('desa_id', $desa_id)
                        ->where('year', $tahun);
                }]);
            }])
            ->get();

        $categoriesWithData = [];
        foreach ($categories as $category) {
            foreach ($category->indicators as $indicator) {
                $stat = $indicator->statistics->first();
                if ($stat && $stat->value > 0) {
                    $categoriesWithData[] = $category->id;
                    break; 
                }
            }
        }

        $daftarTahun = Statistic::where('desa_id', $desa_id)
            ->select('year')->distinct()->orderByDesc('year')->pluck('year');

        return view('admin.entri', compact('desa', 'categories', 'tahun', 'daftarTahun', 'categoriesWithData'));
    }

    public function simpan(Request $request)
    {
        foreach ($request->stats as $indicatorId => $genders) {
            foreach ($genders as $gender => $value) {
                Statistic::updateOrCreate(
                    ['desa_id' => $request->desa_id, 'indicator_id' => $indicatorId, 'year' => $request->tahun, 'gender' => $gender],
                    ['value' => $value ?? 0]
                );
            }
        }

        $this->syncDemografi($request->desa_id, $request->tahun);

        return back()->with('success', 'Data berhasil disimpan dan disinkronkan!');
    }

    public function downloadTemplate()
    {
        return Excel::download(new StatistikTemplateExport, 'Template_Statistik_Beltim.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'tahun' => 'required'
        ]);

        try {
            $desa_id = auth()->user()->role === 'admin' ? $request->desa_id : auth()->user()->desa_id;

            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\StatistikImport($desa_id, $request->tahun), 
                $request->file('file')
            );

            $this->syncDemografi($desa_id, $request->tahun);
            return back()->with('success', "Data Statistik Tahun {$request->tahun} Berhasil Diimport!");

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal Import: ' . $e->getMessage());
        }
    }

    private function syncDemografi($desa_id, $tahun)
    {
        $totalLK = Statistic::where('desa_id', $desa_id)->where('year', $tahun)->where('gender', 'Laki-laki')
            ->whereHas('indicator', function($q) {
                $q->whereHas('category', function($cat) { $cat->where('slug', 'usia-detail'); });
            })->sum('value');

        $totalPR = Statistic::where('desa_id', $desa_id)->where('year', $tahun)->where('gender', 'Perempuan')
            ->whereHas('indicator', function($q) {
                $q->whereHas('category', function($cat) { $cat->where('slug', 'usia-detail'); });
            })->sum('value');

        $indLK = Indicator::where('name', 'Laki-laki')->whereHas('category', function($q){$q->where('slug', 'demografi');})->first();
        $indPR = Indicator::where('name', 'Perempuan')->whereHas('category', function($q){$q->where('slug', 'demografi');})->first();

        if($indLK) {
            Statistic::updateOrCreate(
                ['desa_id' => $desa_id, 'indicator_id' => $indLK->id, 'year' => $tahun, 'gender' => 'Laki-laki'],
                ['value' => $totalLK]
            );
        }
        if($indPR) {
            Statistic::updateOrCreate(
                ['desa_id' => $desa_id, 'indicator_id' => $indPR->id, 'year' => $tahun, 'gender' => 'Perempuan'],
                ['value' => $totalPR]
            );
        }
    }

    public function storeTahun(Request $request)
    {
        $request->validate([
            'year' => 'required|numeric|min:2000|max:2100',
            'desa_id' => 'required'
        ]);

        $tahun = $request->year;
        $desaId = $request->desa_id;

        $exists = \App\Models\Statistic::where('desa_id', $desaId)
            ->where('year', $tahun)
            ->exists();

        if (!$exists) {
            $indicator = \App\Models\Indicator::first();
            if ($indicator) {
                \App\Models\Statistic::create([
                    'desa_id' => $desaId,
                    'year' => $tahun,
                    'indicator_id' => $indicator->id,
                    'gender' => 'Laki-laki',
                    'value' => 0
                ]);
            }
        }

        return redirect('/admin/entri/' . $desaId . '?tahun=' . $tahun)
            ->with('success', 'Tahun ' . $tahun . ' berhasil dibuka.');
    }

    public function dashboard(Request $request)
    {
        $daftarTahun = Statistic::select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $tahun = (int) $request->query('tahun', $daftarTahun->first() ?? date('Y'));

        $desas = \App\Models\Desa::all(); 

        $totalPenduduk = Statistic::where('year', $tahun)
            ->whereHas('indicator', function($q) {
                $q->whereIn('name', ['Laki-laki', 'Perempuan'])
                ->whereHas('category', function($cat) { $cat->where('slug', 'demografi'); });
            })->sum('value');

        $desaSudahInput = Statistic::where('year', $tahun)->distinct('desa_id')->count('desa_id');
        $totalDesa = $desas->count(); // Ambil dari variabel $desas *
        $persenProgres = $totalDesa > 0 ? ($desaSudahInput / $totalDesa) * 100 : 0;

        $categories = Category::where('is_active', true)
            ->with(['indicators.statistics' => function($q) use ($tahun) {
                $q->where('year', $tahun);
            }])->get();

        $allStats = Statistic::where('year', $tahun)
            ->with('indicator.category')
            ->get()
            ->groupBy('desa_id');

        return view('admin.dashboard', compact(
            'totalPenduduk',
            'tahun',
            'categories',
            'desaSudahInput',
            'totalDesa',
            'persenProgres',
            'daftarTahun',
            'allStats',
            'desas' 
        ));
    }

    public function aturForm($desa_id)
    {
        $desa = Desa::findOrFail($desa_id);
        $categories = Category::with('indicators')->get();
        
        $hiddenIds = DesaItemHide::where('desa_id', $desa_id)->pluck('hideable_id')->toArray();

        return view('admin.atur-form', compact('desa', 'categories', 'hiddenIds'));
    }

    public function simpanAturForm(Request $request, $desa_id)
    {
        DesaItemHide::where('desa_id', $desa_id)->delete();

        $allCategories = Category::pluck('id')->toArray();
        $allIndicators = Indicator::pluck('id')->toArray();

        $shownCategories = $request->input('show_categories', []);
        $shownIndicators = $request->input('show_indicators', []);

        $categoriesToHide = array_diff($allCategories, $shownCategories);
        $indicatorsToHide = array_diff($allIndicators, $shownIndicators);

        // 4. Masukkan ke tabel hide
        foreach ($categoriesToHide as $id) {
            DesaItemHide::create([
                'desa_id' => $desa_id,
                'hideable_type' => 'App\Models\Category',
                'hideable_id' => $id
            ]);
        }

        foreach ($indicatorsToHide as $id) {
            DesaItemHide::create([
                'desa_id' => $desa_id,
                'hideable_type' => 'App\Models\Indicator',
                'hideable_id' => $id
            ]);
        }

        return redirect()->route('admin.index')->with('success', 'Form Desa berhasil disesuaikan!');
    }
}