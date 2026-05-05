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
        $desa = auth()->user()->desa; 
        $tahun = $request->query('tahun', date('Y'));

        $statusPengisian = \App\Models\Category::where('is_active', 1)
            ->withCount(['indicators as total_indikator'])
            ->withCount(['indicators as terisi' => function($q) use ($desa, $tahun) {
                $q->whereHas('statistics', function($sq) use ($desa, $tahun) {
                    $sq->where('desa_id', $desa->id)->where('year', $tahun)->where('value', '>', 0);
                });
            }])->get();

        return view('desa.dashboard', compact('desa', 'tahun', 'statusPengisian'));
    }

    public function edit()
    {
        $desa = auth()->user()->desa;
        abort_if(!$desa, 404);

        return view('desa.settings', compact('desa')); 
    }

    public function updateBranding(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'header_color' => 'nullable|string',
            'accent_color' => 'nullable|string'
        ]);

        $desa = auth()->user()->desa;

        if ($request->hasFile('logo')) {
            if ($desa->logo && Storage::disk('public')->exists($desa->logo)) {
                Storage::disk('public')->delete($desa->logo);
            }

            $path = $request->file('logo')->store('logos', 'public');
            
            $desa->logo = $path;
        }

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
        
        $listTahun = \App\Models\Statistic::select('year')
                        ->distinct()
                        ->orderBy('year', 'desc')
                        ->pluck('year');

        if($listTahun->isEmpty()) {
            $listTahun = collect([date('Y')]);
        }

        return view('admin.status_laporan', compact('desas', 'listTahun'));
    }
}