<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Tampilan Utama Dashboard Desa
     */
    public function index(Request $request)
    {
        $desa = auth()->user()->desa; // Pastikan user punya relasi ke desa
        $tahun = $request->query('tahun', date('Y'));

        // Hitung berapa indikator yang sudah terisi di desa ini untuk tahun ini
        $statusPengisian = \App\Models\Category::where('is_active', 1)
            ->withCount(['indicators as total_indikator'])
            ->withCount(['indicators as terisi' => function($q) use ($desa, $tahun) {
                $q->whereHas('statistics', function($sq) use ($desa, $tahun) {
                    $sq->where('desa_id', $desa->id)->where('year', $tahun)->where('value', '>', 0);
                });
            }])->get();

        return view('desa.dashboard', compact('desa', 'tahun', 'statusPengisian'));
    }

    /**
     * Tampilan Form Edit Branding
     */
    public function edit()
    {
        $desa = auth()->user()->desa;
        abort_if(!$desa, 404);

        // Pastikan nama file blade ini sesuai (desa/settings.blade.php)
        return view('desa.settings', compact('desa')); 
    }

    /**
     * Proses Update Logo dan Warna (Branding)
     */
    public function updateBranding(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'header_color' => 'nullable|string',
            'accent_color' => 'nullable|string'
        ]);

        $desa = auth()->user()->desa;

        // 2. Logika Upload Logo yang Benar (Anti /tmp/)
        if ($request->hasFile('logo')) {
            // Hapus file fisik lama jika ada agar tidak memenuhi storage
            if ($desa->logo && Storage::disk('public')->exists($desa->logo)) {
                Storage::disk('public')->delete($desa->logo);
            }

            // PINDAHKAN file dari /tmp ke storage/app/public/logos
            $path = $request->file('logo')->store('logos', 'public');
            
            // Simpan path hasil store (logos/xxx.png) ke database
            $desa->logo = $path;
        }

        // 3. Update Warna (sesuaikan nama kolom di database Bapak)
        if ($request->filled('header_color')) {
            $desa->header_color = $request->header_color;
        }
        
        if ($request->filled('accent_color')) {
            $desa->accent_color = $request->accent_color;
        }

        $desa->save();

        return back()->with('success', 'Branding Desa ' . $desa->nama_desa . ' berhasil diperbarui!');
    }

    public function statusLaporan(Request $request)
    {
        $desas = Desa::orderBy('kecamatan')->orderBy('nama_desa')->get();
        
        // Ambil semua tahun yang ada di tabel statistics untuk isi dropdown
        $listTahun = \App\Models\Statistic::select('year')
                        ->distinct()
                        ->orderBy('year', 'desc')
                        ->pluck('year');

        // Jika belum ada data sama sekali, tampilkan minimal tahun sekarang
        if($listTahun->isEmpty()) {
            $listTahun = collect([date('Y')]);
        }

        return view('admin.status_laporan', compact('desas', 'listTahun'));
    }
}