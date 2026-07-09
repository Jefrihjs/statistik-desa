<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\PpidPermohonan;
use App\Models\PpidKeberatan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PpidLaporanController extends Controller
{
    public function index(Request $request)
    {
        $desa = auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);

        abort_if(!$desa, 404, 'Akun belum terhubung dengan desa.');

        $tahun = $request->get('tahun', 'semua');
        $tab = $request->get('tab', 'permohonan');

        $tahunList = PpidPermohonan::where('desa_id', $desa->id)
            ->selectRaw('YEAR(created_at) as tahun')
            ->union(
                PpidKeberatan::where('desa_id', $desa->id)
                    ->selectRaw('YEAR(created_at) as tahun')
            )
            ->orderByDesc('tahun')
            ->pluck('tahun')
            ->filter()
            ->unique()
            ->values();

        $permohonans = PpidPermohonan::where('desa_id', $desa->id)
            ->when($tahun !== 'semua', function ($query) use ($tahun) {
                $query->whereYear('created_at', $tahun);
            })
            ->latest()
            ->get();

        $keberatans = PpidKeberatan::with('permohonan')
            ->where('desa_id', $desa->id)
            ->when($tahun !== 'semua', function ($query) use ($tahun) {
                $query->whereYear('created_at', $tahun);
            })
            ->latest()
            ->get();

        return view('desa.ppid.laporan.index', compact(
            'desa',
            'tahun',
            'tab',
            'tahunList',
            'permohonans',
            'keberatans'
        ));
    }

    public function cetak(Request $request)
    {
        $desa = auth()->user()->desa ?? \App\Models\Desa::find(auth()->user()->desa_id);

        abort_if(!$desa, 404, 'Akun belum terhubung dengan desa.');

        $tahun = $request->get('tahun', 'semua');
        $jenis = $request->get('jenis', 'permohonan');

        $permohonans = collect();
        $keberatans = collect();

        if ($jenis === 'permohonan') {
            $permohonans = PpidPermohonan::where('desa_id', $desa->id)
                ->when($tahun !== 'semua', function ($query) use ($tahun) {
                    $query->whereYear('created_at', $tahun);
                })
                ->oldest()
                ->get();
        }

        if ($jenis === 'keberatan') {
            $keberatans = PpidKeberatan::with('permohonan')
                ->where('desa_id', $desa->id)
                ->when($tahun !== 'semua', function ($query) use ($tahun) {
                    $query->whereYear('created_at', $tahun);
                })
                ->oldest()
                ->get();
        }

        $pdf = Pdf::loadView('desa.ppid.laporan.pdf', compact(
            'desa',
            'tahun',
            'jenis',
            'permohonans',
            'keberatans'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-ppid-' . $jenis . '-' . $tahun . '.pdf');
    }
}