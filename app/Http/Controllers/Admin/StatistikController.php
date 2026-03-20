<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesaItemHide;
use App\Models\Category;
use App\Models\Indicator;
use App\Models\Desa;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StatistikTemplateExport;
use App\Imports\StatistikImport;

class StatistikController extends Controller
{
    // Halaman Utama Admin (Daftar Desa)
    public function index(Request $request)
    {
        // 1. Ambil tahun dari request (Default ke tahun berjalan)
        $tahun = (int) $request->query('tahun', date('Y'));

        $mapping = [
            'KECAMATAN MANGGAR' => ['BARU', 'LALANG', 'LALANG JAYA', 'BUKU LIMAU', 'MEKAR JAYA', 'PADANG', 'KELUBI', 'BENTAIAN JAYA', 'KURNIA JAYA'],
            'KECAMATAN GANTUNG' => ['GANTUNG', 'LENGGANG', 'SELINSING', 'BATU PENYU', 'LIMBONGAN', 'JANGKAR ASAM', 'LILANGAN'],
            'KECAMATAN KELAPA KAMPIT' => ['SENYUBUK', 'MAYANG', 'PEMBAHARUAN', 'MENTAWAK', 'BUDING', 'CENDIL'],
            'KECAMATAN DENDANG' => ['DENDANG', 'JANGKANG', 'NYURUK', 'BALOK'],
            'KECAMATAN DAMAR' => ['MENGKUBANG', 'BURONG MANDI', 'SUKAMANDI', 'MEMPAYA', 'AIR KELIK'],
            'KECAMATAN SIMPANG PESAK' => ['SIMPANG PESAK', 'DUKONG', 'TANJUNG BATU ITAM', 'TANJUNG KELUMPANG'],
            'KECAMATAN SIMPANG RENGGIANG' => ['SIMPANG TIGA', 'RENGGIANG', 'LINTANG', 'AIK MADU'],
        ];

        // 2. HITUNG KE TABEL STATISTICS (BUKAN USERS)
        // Pastikan nama relasi di withCount adalah 'statistics' sesuai di Model Desa
        $desas = Desa::withCount(['statistics as total_input' => function($q) use ($tahun) {
            $q->where('year', $tahun)
            ->where('value', '>', 0);
        }])
        ->orderBy('kecamatan')
        ->orderBy('nama_desa')
        ->get();

        // 3. Ambil daftar tahun unik untuk dropdown
        $listTahun = Statistic::select('year')->distinct()->orderByDesc('year')->pluck('year');
        if (!$listTahun->contains(date('Y'))) { $listTahun->push(date('Y')); }

        return view('admin.index', compact('desas', 'mapping', 'listTahun', 'tahun'));
    }

    // Tampilan Halaman Entri dengan Tab (Versi Filter Per Desa)
    public function entri(Request $request, $desa_id)
    {
        $desa = Desa::findOrFail($desa_id);
        $tahun = (int) $request->query('tahun', date('Y'));

        // 1. Ambil ID yang di-hide (Gunakan First-String Class untuk kecocokan DB)
        $hiddenItems = DesaItemHide::where('desa_id', $desa_id)->get();
        $hiddenCatIds = $hiddenItems->where('hideable_type', 'App\Models\Category')->pluck('hideable_id')->toArray();
        $hiddenIndIds = $hiddenItems->where('hideable_type', 'App\Models\Indicator')->pluck('hideable_id')->toArray();

        // 2. Query Kategori & Indikator (PASTIKAN RELASI DI-FILTER DENGAN BENAR)
        $categories = Category::where('is_active', true)
            ->whereNotIn('id', $hiddenCatIds)
            ->with(['indicators' => function($q) use ($hiddenIndIds, $desa_id, $tahun) {
                // FILTER: Hanya indikator milik kategori ini, aktif secara global, dan tidak di-hide desa
                $q->where('is_active', true)
                ->whereNotIn('id', $hiddenIndIds)
                ->with(['statistics' => function($sq) use ($desa_id, $tahun) {
                    $sq->where('desa_id', $desa_id)
                        ->where('year', $tahun);
                }]);
            }])
            ->get();

        // 3. Logika Titik Hijau
        $categoriesWithData = [];
        foreach ($categories as $category) {
            foreach ($category->indicators as $indicator) {
                // Cek data di koleksi yang sudah di-load (Eager Loading)
                $stat = $indicator->statistics->first();
                if ($stat && $stat->value > 0) {
                    $categoriesWithData[] = $category->id;
                    break; 
                }
            }
        }

        // Ambil daftar tahun untuk dropdown
        $daftarTahun = Statistic::where('desa_id', $desa_id)
            ->select('year')->distinct()->orderByDesc('year')->pluck('year');

        return view('admin.entri', compact('desa', 'categories', 'tahun', 'daftarTahun', 'categoriesWithData'));
    }

    // Fungsi Simpan Manual (Form)
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

    // Fungsi Download Template Excel
    public function downloadTemplate()
    {
        return Excel::download(new StatistikTemplateExport, 'Template_Statistik_Beltim.xlsx');
    }

    // Fungsi Import Excel
    public function import(Request $request)
    {
        // 1. Validasi File
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'tahun' => 'required'
        ]);

        try {
            // 2. Ambil desa_id (Jika operator desa, ambil dari profilnya)
            $desa_id = auth()->user()->role === 'admin' ? $request->desa_id : auth()->user()->desa_id;

            // 3. Eksekusi Import
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\StatistikImport($desa_id, $request->tahun), 
                $request->file('file')
            );

            $this->syncDemografi($desa_id, $request->tahun);
            // 4. Kirim notifikasi sukses
            return back()->with('success', "Data Statistik Tahun {$request->tahun} Berhasil Diimport!");

        } catch (\Exception $e) {
            // 5. Kirim notifikasi gagal jika ada error
            return back()->with('error', 'Gagal Import: ' . $e->getMessage());
        }
    }

    // Private function untuk menghitung otomatis Laki-laki & Perempuan dari data Usia
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

        // 1. Cek keberadaan data
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

        // 2. JANGAN PAKAI route(), PAKAI URL LANGSUNG
        // Kita tembak langsung ke path /admin/entri/{desa_id}?tahun={tahun}
        return redirect('/admin/entri/' . $desaId . '?tahun=' . $tahun)
            ->with('success', 'Tahun ' . $tahun . ' berhasil dibuka.');
    }

    public function dashboard(Request $request)
    {
        // 1. Ambil daftar tahun unik
        $daftarTahun = Statistic::select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $tahun = (int) $request->query('tahun', $daftarTahun->first() ?? date('Y'));

        // 2. Data Ringkasan Atas
        $totalPenduduk = Statistic::where('year', $tahun)
            ->whereHas('indicator', function($q) {
                $q->whereIn('name', ['Laki-laki', 'Perempuan'])
                ->whereHas('category', function($cat) { $cat->where('slug', 'demografi'); });
            })->sum('value');

        $desaSudahInput = Statistic::where('year', $tahun)->distinct('desa_id')->count('desa_id');
        $totalDesa = Desa::count();
        $persenProgres = $totalDesa > 0 ? ($desaSudahInput / $totalDesa) * 100 : 0;

        // 3. Ambil SEMUA Kategori & Total Statistiknya Sekabupaten (Untuk Grafik)
        $categories = Category::where('is_active', true)
            ->with(['indicators.statistics' => function($q) use ($tahun) {
                $q->where('year', $tahun);
            }])->get();

        return view('admin.dashboard', compact(
            'totalPenduduk',
            'tahun',
            'categories',
            'desaSudahInput',
            'totalDesa',
            'persenProgres',
            'daftarTahun'
        ));
    }

    // Tampilkan halaman pengaturan form per desa
    public function aturForm($desa_id)
    {
        $desa = Desa::findOrFail($desa_id);
        $categories = Category::with('indicators')->get();
        
        // Ambil ID apa saja yang sudah di-hide untuk desa ini
        $hiddenIds = DesaItemHide::where('desa_id', $desa_id)->pluck('hideable_id')->toArray();

        return view('admin.atur-form', compact('desa', 'categories', 'hiddenIds'));
    }

    // Simpan pilihan sembunyi/tampil
    public function simpanAturForm(Request $request, $desa_id)
    {
        // 1. Bersihkan dulu data lama
        DesaItemHide::where('desa_id', $desa_id)->delete();

        // 2. Ambil semua Kategori & Indikator Global
        $allCategories = Category::pluck('id')->toArray();
        $allIndicators = Indicator::pluck('id')->toArray();

        // 3. Cari mana yang TIDAK dicentang (Itu yang masuk daftar HIDE)
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