<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Aduan;
use Illuminate\Http\Request;

class AduanDesaController extends Controller
{
    private function ensureAduanActive(): void
    {
        if (! auth()->user()->is_aduan_active) {
            abort(403, 'Modul Layanan Aduan belum diaktifkan oleh admin kabupaten.');
        }
    }

    public function index(Request $request)
    {
        $this->ensureAduanActive();

        $desaId = auth()->user()->desa_id;

        $aduans = Aduan::where('desa_id', $desaId)
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->keyword, function ($q) use ($request) {
                $keyword = $request->keyword;

                $q->where(function ($query) use ($keyword) {
                    $query->where('kode_aduan', 'like', "%{$keyword}%")
                        ->orWhere('nama_pelapor', 'like', "%{$keyword}%")
                        ->orWhere('judul', 'like', "%{$keyword}%")
                        ->orWhere('isi_aduan', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('desa.aduan.index', compact('aduans'));
    }

    public function updateStatus(Request $request, Aduan $aduan)
    {
        $this->ensureAduanActive();

        abort_if($aduan->desa_id !== auth()->user()->desa_id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:baru,diproses,selesai,ditolak'],
            'tanggapan' => ['nullable', 'string'],
        ]);

        $aduan->update([
            'status' => $validated['status'],
            'tanggapan' => $validated['tanggapan'] ?? $aduan->tanggapan,
            'ditanggapi_pada' => now(),
            'ditanggapi_oleh' => auth()->id(),
        ]);

        \App\Services\ActivityLogger::log(
            'Aduan',
            'Update Status Aduan',
            'Operator desa memperbarui status aduan masyarakat.',
            [
                'aduan_id' => $aduan->id,
                'kode_aduan' => $aduan->kode_aduan,
                'status' => $validated['status'],
            ]
        );

        return back()->with('success', 'Status aduan berhasil diperbarui.');
    }
}