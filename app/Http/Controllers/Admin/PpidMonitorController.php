<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PpidMonitorController extends Controller
{
    public function index()
    {
        $desas = Desa::orderBy('nama_desa')->get();

        $permohonans = collect();
        $keberatans = collect();

        if (Schema::hasTable('ppid_permohonans')) {
            $permohonans = DB::table('ppid_permohonans')
                ->leftJoin('desas', 'ppid_permohonans.desa_id', '=', 'desas.id')
                ->select(
                    'ppid_permohonans.*',
                    'desas.nama_desa',
                    'desas.kecamatan'
                )
                ->orderByDesc('ppid_permohonans.created_at')
                ->get();
        }

        if (Schema::hasTable('ppid_keberatans')) {
            $keberatans = DB::table('ppid_keberatans')
                ->leftJoin('ppid_permohonans', 'ppid_keberatans.ppid_permohonan_id', '=', 'ppid_permohonans.id')
                ->leftJoin('desas', 'ppid_permohonans.desa_id', '=', 'desas.id')
                ->select(
                    'ppid_keberatans.*',
                    'desas.nama_desa',
                    'desas.kecamatan'
                )
                ->orderByDesc('ppid_keberatans.created_at')
                ->get();
        }

        $rekapDesa = $desas->map(function ($desa) use ($permohonans, $keberatans) {
            $permohonanDesa = $permohonans->where('desa_id', $desa->id);

            return [
                'desa' => $desa,
                'total_permohonan' => $permohonanDesa->count(),
                'pending' => $permohonanDesa->where('status', 'pending')->count(),
                'selesai' => $permohonanDesa->whereIn('status', ['selesai', 'diterima'])->count(),
                'ditolak' => $permohonanDesa->whereIn('status', ['ditolak', 'tidak_lengkap'])->count(),
                'keberatan' => $keberatans->where('nama_desa', $desa->nama_desa)->count(),
            ];
        });

        return view('admin.ppid.monitor', compact(
            'desas',
            'permohonans',
            'keberatans',
            'rekapDesa'
        ));
    }
}