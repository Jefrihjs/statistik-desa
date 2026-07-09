<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SkmMonitorController extends Controller
{
    public function index()
    {
        $desas = Desa::orderBy('nama_desa')->get();

        $responses = collect();

        if (Schema::hasTable('skm_responses')) {
            $responses = DB::table('skm_responses')
                ->leftJoin('desas', 'skm_responses.desa_id', '=', 'desas.id')
                ->select(
                    'skm_responses.*',
                    'desas.nama_desa',
                    'desas.kecamatan'
                )
                ->orderByDesc('skm_responses.created_at')
                ->get();
        }

        $rekapDesa = $desas->map(function ($desa) use ($responses) {
            $responDesa = $responses->where('desa_id', $desa->id);

            $rataRata = $responDesa->avg('nilai');

            return [
                'desa' => $desa,
                'total_responden' => $responDesa->count(),
                'rata_rata' => $rataRata ? round($rataRata, 2) : null,
            ];
        });

        return view('admin.skm.monitor', compact(
            'desas',
            'responses',
            'rekapDesa'
        ));
    }
}