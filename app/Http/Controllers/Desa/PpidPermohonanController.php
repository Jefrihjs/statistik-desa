<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\PpidPermohonan;
use App\Models\PpidPemberitahuan;
use App\Models\PpidLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PpidPermohonanController extends Controller
{
    public function index()
    {
        $desaId = auth()->user()->desa_id;

        abort_if(!$desaId, 404, 'Akun Anda belum terhubung dengan desa.');

        $baseQuery = PpidPermohonan::where('desa_id', $desaId);

        $stats = [
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'diproses' => (clone $baseQuery)->where('status', 'diproses')->count(),
            'selesai' => (clone $baseQuery)->where('status', 'selesai')->count(),
            'ditolak' => (clone $baseQuery)->where('status', 'ditolak')->count(),
        ];

        $permohonanBaru = PpidPermohonan::where('desa_id', $desaId)
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        $permohonans = PpidPermohonan::where('desa_id', $desaId)
            ->latest()
            ->paginate(10);

        return view('desa.ppid.permohonan.index', compact(
            'stats',
            'permohonanBaru',
            'permohonans'
        ));
    }

    public function show($id)
    {
        $desaId = auth()->user()->desa_id;

        abort_if(!$desaId, 404, 'Akun Anda belum terhubung dengan desa.');

        $permohonan = PpidPermohonan::where('id', $id)
            ->where('desa_id', $desaId)
            ->firstOrFail();

        $pemberitahuan = \App\Models\PpidPemberitahuan::where('ppid_permohonan_id', $permohonan->id)
            ->where('desa_id', $desaId)
            ->first();

        $keberatan = \App\Models\PpidKeberatan::where('ppid_permohonan_id', $permohonan->id)
            ->where('desa_id', $desaId)
            ->first();

        $logsPermohonan = \App\Models\PpidLog::where('ppid_permohonan_id', $permohonan->id)
            ->where('desa_id', $desaId)
            ->where('tipe', 'permohonan')
            ->orderBy('created_at', 'asc')
            ->get();

        $logsKeberatan = \App\Models\PpidLog::where('ppid_permohonan_id', $permohonan->id)
            ->where('desa_id', $desaId)
            ->where('tipe', 'keberatan')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('desa.ppid.permohonan.show', compact(
            'permohonan',
            'pemberitahuan',
            'keberatan',
            'logsPermohonan',
            'logsKeberatan'
        ));
    }

    public function destroy($id)
    {
        $desaId = auth()->user()->desa_id;

        $permohonan = PpidPermohonan::where('id', $id)
            ->where('desa_id', $desaId)
            ->firstOrFail();

        $permohonan->delete();

        return back()->with('success', 'Permohonan informasi berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $desaId = auth()->user()->desa_id;

        $request->validate([
            'status' => 'required|in:pending,diproses,selesai,ditolak,tidak_lengkap',
            'catatan_admin' => 'nullable|string',
        ]);

        $permohonan = PpidPermohonan::where('id', $id)
            ->where('desa_id', auth()->user()->desa_id)
            ->firstOrFail();

        $permohonan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
            'diproses_pada' => now(),
        ]);

        PpidLog::create([
            'desa_id' => $desaId,
            'ppid_permohonan_id' => $permohonan->id,
            'tipe' => 'permohonan',
            'judul' => 'Status Permohonan Diperbarui',
            'deskripsi' => 'Status permohonan diubah menjadi ' . strtoupper(str_replace('_', ' ', $request->status)) . '.',
            'actor_name' => auth()->user()->name ?? auth()->user()->nama ?? 'Admin Desa',
            'actor_role' => 'admin_desa',
        ]);

        \App\Services\ActivityLogger::log(
            'PPID',
            'Update Status Permohonan',
            'Operator desa memperbarui status permohonan informasi.',
            [
                'permohonan_id' => $id,
                'status' => $request->status,
            ]
        );

        return back()->with('success', 'Status permohonan berhasil diperbarui.');
    }

public function tidakLengkap(Request $request, $id)
{
    $desaId = auth()->user()->desa_id;

    $request->validate([
        'rincian_ketidaklengkapan' => 'required|string',
    ]);

    $permohonan = PpidPermohonan::where('id', $id)
        ->where('desa_id', $desaId)
        ->firstOrFail();

    $permohonan->update([
        'status' => 'tidak_lengkap',
        'catatan_admin' => $request->rincian_ketidaklengkapan,
        'diproses_pada' => now(),
    ]);

    PpidLog::create([
        'desa_id' => $desaId,
        'ppid_permohonan_id' => $permohonan->id,
        'tipe' => 'permohonan',
        'judul' => 'Permohonan Tidak Lengkap',
        'deskripsi' => 'Tim PPID menyatakan permohonan belum lengkap: ' . $request->rincian_ketidaklengkapan,
        'actor_name' => auth()->user()->name ?? auth()->user()->nama ?? 'Admin Desa',
        'actor_role' => 'admin_desa',
    ]);

    \App\Services\ActivityLogger::log(
        'PPID',
        'Permohonan Tidak Lengkap',
        'Operator desa menandai permohonan sebagai tidak lengkap.',
        [
            'permohonan_id' => $permohonan->id,
            'rincian_ketidaklengkapan' => $request->rincian_ketidaklengkapan,
        ]
    );

    return back()->with('success', 'Permohonan ditandai tidak lengkap.');
}

public function uploadSelesai(Request $request, $id)
{
    $desaId = auth()->user()->desa_id;
    $request->validate([
        'file_penyelesaian' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'keterangan' => 'nullable|string',
    ]);

    $permohonan = PpidPermohonan::where('id', $id)
        ->where('desa_id', $desaId)
        ->firstOrFail();

    $path = $request->file('file_penyelesaian')->store('ppid/penyelesaian', 'public');

    $permohonan->update([
        'status' => 'selesai',
        'file_penyelesaian' => $path,
        'catatan_admin' => $request->keterangan,
        'diproses_pada' => now(),
    ]);

    PpidLog::create([
        'desa_id' => $desaId,
        'ppid_permohonan_id' => $permohonan->id,
        'tipe' => 'permohonan',
        'judul' => 'Permohonan Selesai',
        'deskripsi' => 'Tim PPID mengunggah bukti penyelesaian permohonan informasi publik.',
        'actor_name' => auth()->user()->name ?? auth()->user()->nama ?? 'Admin Desa',
        'actor_role' => 'admin_desa',
    ]);

    \App\Services\ActivityLogger::log(
        'PPID',
        'Upload Dokumen Jawaban',
        'Operator desa mengunggah dokumen jawaban permohonan informasi.',
        [
            'permohonan_id' => $id,
        ]
    );
    
    return back()->with('success', 'Bukti penyelesaian berhasil diunggah.');
}

public function storePemberitahuan(Request $request, $id)
{
    $desaId = auth()->user()->desa_id;

    $permohonan = PpidPermohonan::where('id', $id)
        ->where('desa_id', $desaId)
        ->firstOrFail();

    $request->validate([
        'status_informasi' => 'required|in:dapat_diberikan,tidak_dapat_diberikan',

        'penguasaan_informasi' => 'nullable|string',
        'nama_badan_publik_lain' => 'nullable|string|max:255',
        'bentuk_fisik' => 'nullable|string',

        'biaya_salinan' => 'nullable|integer|min:0',
        'biaya_kirim' => 'nullable|integer|min:0',
        'biaya_lain' => 'nullable|integer|min:0',
        'total_biaya' => 'nullable|integer|min:0',

        'waktu_penyediaan' => 'nullable|integer|min:0',
        'penjelasan_penghitaman' => 'nullable|string',

        'alasan_penolakan' => 'nullable|string',
        'catatan_penolakan' => 'nullable|string',

        'pasal_17_huruf' => 'nullable|string|max:10',
        'pasal_uu_lainnya' => 'nullable|string|max:255',
        'rincian_informasi_ditolak' => 'nullable|string',
        'hasil_uji_konsekuensi' => 'nullable|string',

    ]);

    $biayaSalinan = (int) $request->biaya_salinan;
    $biayaKirim = (int) $request->biaya_kirim;
    $biayaLain = (int) $request->biaya_lain;
    $totalBiaya = $biayaSalinan + $biayaKirim + $biayaLain;

    $pemberitahuan = PpidPemberitahuan::updateOrCreate(
        [
            'ppid_permohonan_id' => $permohonan->id,
            'desa_id' => $desaId,
        ],
        [
            'status_informasi' => $request->status_informasi,

            'penguasaan_informasi' => $request->penguasaan_informasi,
            'nama_badan_publik_lain' => $request->nama_badan_publik_lain,
            'bentuk_fisik' => $request->bentuk_fisik,

            'biaya_salinan' => $biayaSalinan,
            'biaya_kirim' => $biayaKirim,
            'biaya_lain' => $biayaLain,
            'total_biaya' => $totalBiaya,

            'waktu_penyediaan' => $request->waktu_penyediaan,
            'penjelasan_penghitaman' => $request->penjelasan_penghitaman,

            'alasan_penolakan' => $request->alasan_penolakan,
            'catatan_penolakan' => $request->catatan_penolakan,

            'pasal_17_huruf' => $request->pasal_17_huruf,
            'pasal_uu_lainnya' => $request->pasal_uu_lainnya,
            'rincian_informasi_ditolak' => $request->rincian_informasi_ditolak,
            'hasil_uji_konsekuensi' => $request->hasil_uji_konsekuensi,
        ]
    );

    if ($request->status_informasi === 'dapat_diberikan') {
        $permohonan->update([
            'status' => 'diproses',
            'catatan_admin' => 'Pemberitahuan tertulis: informasi dapat diberikan.',
            'diproses_pada' => now(),
        ]);
    } else {
        $permohonan->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan_penolakan ?: 'Pemberitahuan tertulis: informasi tidak dapat diberikan.',
            'diproses_pada' => now(),
        ]);
    }

    PpidLog::create([
        'desa_id' => $desaId,
        'ppid_permohonan_id' => $permohonan->id,
        'tipe' => 'permohonan',
        'judul' => $request->status_informasi === 'dapat_diberikan'
            ? 'Pemberitahuan Tertulis Dibuat'
            : 'Pemberitahuan Penolakan Dibuat',
        'deskripsi' => $request->status_informasi === 'dapat_diberikan'
            ? 'Tim PPID membuat pemberitahuan bahwa informasi dapat diberikan.'
            : 'Tim PPID membuat pemberitahuan bahwa informasi tidak dapat diberikan.',
        'actor_name' => auth()->user()->name ?? auth()->user()->nama ?? 'Admin Desa',
        'actor_role' => 'admin_desa',
    ]);

    return redirect()
    ->route('desa.ppid.permohonan.show', $permohonan->id)
    ->with('success', 'Pemberitahuan tertulis berhasil dibuat.');
}

public function cetakPemberitahuan($id)
{
    $desaId = auth()->user()->desa_id;

    $permohonan = PpidPermohonan::where('id', $id)
        ->where('desa_id', $desaId)
        ->firstOrFail();

    $pemberitahuan = PpidPemberitahuan::where('ppid_permohonan_id', $permohonan->id)
        ->where('desa_id', $desaId)
        ->firstOrFail();

    $pdf = Pdf::loadView('desa.ppid.permohonan.pdf_pemberitahuan', [
        'permohonan' => $permohonan,
        'pemberitahuan' => $pemberitahuan,
        'desa' => $permohonan->desa,
    ])->setPaper('a4', 'portrait');

    $namaFile = 'pemberitahuan-ppid-' . str_pad($permohonan->id, 4, '0', STR_PAD_LEFT) . '.pdf';

    return $pdf->stream($namaFile);
}

public function tanggapiKeberatan(Request $request, $id)
{
    $desaId = auth()->user()->desa_id;

    $permohonan = PpidPermohonan::where('id', $id)
        ->where('desa_id', $desaId)
        ->firstOrFail();

    $keberatan = \App\Models\PpidKeberatan::where('ppid_permohonan_id', $permohonan->id)
        ->where('desa_id', $desaId)
        ->firstOrFail();

    $request->validate([
        'nama_atasan_ppid' => 'required|string|max:255',
        'posisi_atasan' => 'required|string|max:255',
        'tanggapan_admin' => 'required|string',
    ]);

    $keberatan->update([
        'status' => 'selesai',
        'nama_atasan_ppid' => $request->nama_atasan_ppid,
        'posisi_atasan' => $request->posisi_atasan,
        'tanggapan_admin' => $request->tanggapan_admin,
        'ditanggapi_pada' => now(),
    ]);

    PpidLog::create([
        'desa_id' => $desaId,
        'ppid_permohonan_id' => $permohonan->id,
        'ppid_keberatan_id' => $keberatan->id,
        'tipe' => 'keberatan',
        'judul' => 'Tanggapan Keberatan Diberikan',
        'deskripsi' => 'Atasan PPID memberikan tanggapan atas ajuan keberatan pemohon.',
        'actor_name' => $request->nama_atasan_ppid,
        'actor_role' => $request->posisi_atasan,
    ]);

    return redirect()
        ->route('desa.ppid.permohonan.show', $permohonan->id)
        ->with('success', 'Tanggapan keberatan berhasil dikirim.');
}

public function cetakSkPenolakan($id)
{
    $desaId = auth()->user()->desa_id;

    $permohonan = PpidPermohonan::where('id', $id)
        ->where('desa_id', $desaId)
        ->firstOrFail();

    $pemberitahuan = PpidPemberitahuan::where('ppid_permohonan_id', $permohonan->id)
        ->where('desa_id', $desaId)
        ->firstOrFail();

    abort_if(
        $pemberitahuan->status_informasi !== 'tidak_dapat_diberikan'
        || $pemberitahuan->alasan_penolakan !== 'informasi_dikecualikan',
        404,
        'Dokumen SK penolakan hanya untuk informasi dikecualikan.'
    );

    $pdf = Pdf::loadView('desa.ppid.permohonan.pdf_sk_penolakan', [
        'permohonan' => $permohonan,
        'pemberitahuan' => $pemberitahuan,
        'desa' => $permohonan->desa,
    ])->setPaper('a4', 'portrait');

    $namaFile = 'sk-penolakan-ppid-' . strtoupper($permohonan->kode_permohonan) . '.pdf';

    return $pdf->stream($namaFile);
}

public function cetakTidakLengkap($id)
{
    $desaId = auth()->user()->desa_id;

    $permohonan = PpidPermohonan::where('id', $id)
        ->where('desa_id', $desaId)
        ->firstOrFail();

    abort_if(
        $permohonan->status !== 'tidak_lengkap',
        404,
        'Dokumen ini hanya untuk permohonan tidak lengkap.'
    );

    $pdf = Pdf::loadView('desa.ppid.permohonan.pdf_tidak_lengkap', [
        'permohonan' => $permohonan,
        'desa' => $permohonan->desa,
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('pemberitahuan-tidak-lengkap-' . strtoupper($permohonan->kode_permohonan) . '.pdf');
}
}