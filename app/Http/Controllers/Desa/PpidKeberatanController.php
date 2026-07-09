<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\PpidKeberatan;

class PpidKeberatanController extends Controller
{
    public function index()
    {
        $desaId = auth()->user()->desa_id;

        abort_if(!$desaId, 404, 'Akun Anda belum terhubung dengan desa.');

        $keberatans = PpidKeberatan::with('permohonan')
            ->where('desa_id', $desaId)
            ->latest()
            ->paginate(10);

        $stats = [
            'diajukan' => PpidKeberatan::where('desa_id', $desaId)->where('status', 'diajukan')->count(),
            'diproses' => PpidKeberatan::where('desa_id', $desaId)->where('status', 'diproses')->count(),
            'selesai'  => PpidKeberatan::where('desa_id', $desaId)->where('status', 'selesai')->count(),
        ];

        return view('desa.ppid.keberatan.index', compact('keberatans', 'stats'));
    }

    public function show($id)
    {
        $desaId = auth()->user()->desa_id;

        abort_if(!$desaId, 404, 'Akun Anda belum terhubung dengan desa.');

        $keberatan = PpidKeberatan::with('permohonan')
            ->where('desa_id', $desaId)
            ->where('id', $id)
            ->firstOrFail();

        return view('desa.ppid.keberatan.show', compact('keberatan'));
    }
}