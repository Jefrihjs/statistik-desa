<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\SkmRekomendasi;
use App\Models\SkmResponse;
use Illuminate\Http\Request;

class SkmDesaController extends Controller
{
    public function index(Request $request)
    {
        $desaId = auth()->user()->desa_id;

        // ---- REKOMENDASI BPS ----
        $rekomendasi = SkmRekomendasi::where('desa_id', $desaId)
            ->orderBy('tahun', 'desc')
            ->get();

        // ---- FILTER ----
        $layananFilter = $request->get('layanan');
        $rekomFilter = $request->get('rekom');

        $layananOptions = [
            'Jasa Layanan Seksi Pemerintahan',
            'Jasa Layanan Seksi Pelayanan',
            'Jasa Layanan Seksi Kesejahteraan Sosial',
        ];

        // ---- STATISTIK GLOBAL ----
        $statsQuery = SkmResponse::where('desa_id', $desaId);

        if ($rekomFilter) {
            $statsQuery->where('skm_rekomendasi_id', $rekomFilter);
        }

        $totalResponden = $statsQuery->count();

        $avgRaw = $totalResponden > 0 ? $statsQuery->avg('nilai_rata_rata') : null;
        $nilaiRataRata = $avgRaw !== null ? round($avgRaw, 2) : null;

        // Konversi ke IKM (skala 25-100)
        $ikm = null;
        if ($nilaiRataRata !== null) {
            $ikm = round((($nilaiRataRata - 1) / 3) * 75 + 25, 2);
        }

        // Mutu layanan
        $mutu = '-';
        if ($ikm !== null) {
            if ($ikm >= 88.31)      $mutu = 'A (Sangat Baik)';
            elseif ($ikm >= 76.61)  $mutu = 'B (Baik)';
            elseif ($ikm >= 62.51)  $mutu = 'C (Kurang Baik)';
            else                    $mutu = 'D (Tidak Baik)';
        }

        // ---- RESPONSES (DENGAN FILTER) ----
        $responsesQuery = SkmResponse::where('desa_id', $desaId);

        if ($layananFilter) {
            $responsesQuery->where('layanan_yang_dinilai', $layananFilter);
        }

        if ($rekomFilter) {
            $responsesQuery->where('skm_rekomendasi_id', $rekomFilter);
        }

        $responses = $responsesQuery->latest()->paginate(20)->withQueryString();

        return view('desa.skm.index', compact(
            'rekomendasi',
            'layananOptions',
            'layananFilter',
            'rekomFilter',
            'totalResponden',
            'nilaiRataRata',
            'ikm',
            'mutu',
            'responses',
        ));
    }

    // ---- REKOMENDASI BPS ----

        public function storeRekomendasi(Request $request)
    {
        $desaId = auth()->user()->desa_id;

        $request->validate([
            'kode_survey' => 'required|string|max:100',
            'nomor_rekom' => 'required|string|max:100',
            'tahun' => 'required|integer|min:2000|max:2100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        // Cegah duplikat tahun
        $exists = SkmRekomendasi::where('desa_id', $desaId)
            ->where('tahun', $request->tahun)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Rekomendasi untuk tahun ' . $request->tahun . ' sudah ada.');
        }

        // Nonaktifkan rekomendasi lama
        SkmRekomendasi::where('desa_id', $desaId)->update(['is_active' => false]);

        SkmRekomendasi::create([
            'desa_id' => $desaId,
            'kode_survey' => $request->kode_survey,
            'nomor_rekom' => $request->nomor_rekom,
            'tahun' => $request->tahun,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'is_active' => true,
        ]);

        return back()->with('success', 'Rekomendasi BPS berhasil ditambahkan dan diaktifkan.');
    }

    public function toggleRekomendasi($id)
    {
        $rekom = SkmRekomendasi::where('id', $id)
            ->where('desa_id', auth()->user()->desa_id)
            ->firstOrFail();

        // Nonaktifkan semua dulu
        SkmRekomendasi::where('desa_id', auth()->user()->desa_id)
            ->update(['is_active' => false]);

        // Aktifkan yang dipilih
        $rekom->update(['is_active' => true]);

        return back()->with('success', 'Rekomendasi BPS berhasil diaktifkan.');
    }

    public function destroyRekomendasi($id)
    {
        $rekom = SkmRekomendasi::where('id', $id)
            ->where('desa_id', auth()->user()->desa_id)
            ->firstOrFail();

        $rekom->delete();

        return back()->with('success', 'Rekomendasi BPS berhasil dihapus.');
    }
}