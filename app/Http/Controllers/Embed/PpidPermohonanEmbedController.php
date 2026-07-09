<?php

namespace App\Http\Controllers\Embed;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\PpidPermohonan;
use App\Models\PpidKeberatan;
use App\Models\PpidLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PpidPermohonanEmbedController extends Controller
{
    public function create($slug)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        return view('embed.ppid.permohonan', compact('desa'));
    }

    public function store(Request $request, $slug)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        $request->validate([
            'kategori_pemohon' => 'required|in:perorangan,lembaga',
            'nik' => 'required|digits:16',
            'nama' => 'required|string|max:255',
            'file_ktp' => 'required|file|mimes:jpg,jpeg,png|max:1024',
            'file_akta' => 'nullable|file|mimes:jpg,jpeg,png|max:1024',

            'alamat' => 'required|string',
            'email' => 'nullable|email|max:255',
            'no_hp' => 'required|string|max:30',
            'pekerjaan' => 'nullable|string|max:255',

            'rincian_informasi' => 'required|string',
            'tujuan_penggunaan' => 'required|string',

            'cara_memperoleh' => 'nullable|string|max:50',
            'jenis_salinan' => 'nullable|string|max:50',
            'cara_pengiriman' => 'nullable|string|max:50',
            'no_wa' => 'nullable|string|max:30',
        ]);

        $ktpPath = $request->file('file_ktp')->store('ppid/ktp', 'public');

        $aktaPath = null;
        if ($request->hasFile('file_akta')) {
            $aktaPath = $request->file('file_akta')->store('ppid/akta', 'public');
        }

        $kodePermohonan = $this->generateKodePermohonan();

        $permohonan = PpidPermohonan::create([
            'desa_id' => $desa->id,
            'kode_permohonan' => $kodePermohonan,
            'kategori_pemohon' => $request->kategori_pemohon,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'file_ktp' => $ktpPath,
            'file_akta' => $aktaPath,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'pekerjaan' => $request->pekerjaan,
            'rincian_informasi' => $request->rincian_informasi,
            'tujuan_penggunaan' => $request->tujuan_penggunaan,
            'cara_memperoleh' => $request->cara_memperoleh,
            'jenis_salinan' => $request->jenis_salinan,
            'cara_pengiriman' => $request->cara_pengiriman,
            'no_wa' => $request->no_wa,
            'status' => 'pending',
        ]);

        PpidLog::create([
            'desa_id' => $desa->id,
            'ppid_permohonan_id' => $permohonan->id,
            'tipe' => 'permohonan',
            'judul' => 'Permohonan Masuk',
            'deskripsi' => $permohonan->nama . ' melakukan pengajuan permohonan informasi publik.',
            'actor_name' => $permohonan->nama,
            'actor_role' => 'pemohon',
        ]);

        return redirect()->route('embed.ppid.permohonan.success', [
            'slug' => $desa->slug,
            'kode' => $kodePermohonan,
        ]);
    }

    private function generateKodePermohonan(): string
    {
        do {
            $kode = strtolower(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 7));
        } while (PpidPermohonan::where('kode_permohonan', $kode)->exists());

        return $kode;
    }

    public function success($slug, $kode)
{
    $desa = Desa::where('slug', $slug)->firstOrFail();

    $permohonan = PpidPermohonan::where('desa_id', $desa->id)
        ->where('kode_permohonan', $kode)
        ->firstOrFail();

    return view('embed.ppid.success', compact('desa', 'permohonan'));
}

public function monitoring($slug)
{
    $desa = Desa::where('slug', $slug)->firstOrFail();

    return view('embed.ppid.monitoring', compact('desa'));
}

public function cekMonitoring(Request $request, $slug)
{
    $desa = Desa::where('slug', $slug)->firstOrFail();

    $request->validate([
        'kode_permohonan' => 'required|string',
        'nik' => 'required|digits:16',
    ]);

    $kode = strtolower($request->kode_permohonan);

    $permohonan = PpidPermohonan::where('desa_id', $desa->id)
        ->where('kode_permohonan', $kode)
        ->where('nik', $request->nik)
        ->first();

    if (!$permohonan) {
        return back()
            ->withInput()
            ->withErrors(['kode_permohonan' => 'Kode permohonan atau NIK tidak ditemukan.']);
    }

    return redirect()->route('embed.ppid.permohonan.monitoring.hasil', [
        'slug' => $desa->slug,
        'kode' => $permohonan->kode_permohonan,
    ]);
}

public function hasilMonitoring(Request $request, $slug, $kode)
{
    $desa = Desa::where('slug', $slug)->firstOrFail();

    $permohonan = PpidPermohonan::where('desa_id', $desa->id)
        ->where('kode_permohonan', strtolower($kode))
        ->firstOrFail();

    $pemberitahuan = \App\Models\PpidPemberitahuan::where('ppid_permohonan_id', $permohonan->id)
        ->where('desa_id', $desa->id)
        ->first();

    $keberatan = PpidKeberatan::where('ppid_permohonan_id', $permohonan->id)
    ->where('desa_id', $desa->id)
    ->first();

    $logsPermohonan = PpidLog::where('ppid_permohonan_id', $permohonan->id)
    ->where('desa_id', $desa->id)
    ->where('tipe', 'permohonan')
    ->orderBy('created_at', 'asc')
    ->get();

    $logsKeberatan = PpidLog::where('ppid_permohonan_id', $permohonan->id)
        ->where('desa_id', $desa->id)
        ->where('tipe', 'keberatan')
        ->orderBy('created_at', 'asc')
        ->get();

    return view('embed.ppid.monitoring-hasil', compact(
        'desa',
        'permohonan',
        'pemberitahuan',
        'keberatan',
        'logsPermohonan',
        'logsKeberatan'
    ));
}

public function cetakBukti($slug, $kode)
{
    $desa = Desa::where('slug', $slug)->firstOrFail();

    $permohonan = PpidPermohonan::where('desa_id', $desa->id)
        ->where('kode_permohonan', strtolower($kode))
        ->firstOrFail();

    $pdf = Pdf::loadView('embed.ppid.pdf-bukti-permohonan', [
        'desa' => $desa,
        'permohonan' => $permohonan,
    ])->setPaper('a4', 'portrait');

    $namaFile = 'bukti-permohonan-ppid-' . strtoupper($permohonan->kode_permohonan) . '.pdf';

    return $pdf->stream($namaFile);
}

public function storeKeberatan(Request $request, $slug, $kode)
{
    $desa = Desa::where('slug', $slug)->firstOrFail();

    $permohonan = PpidPermohonan::where('desa_id', $desa->id)
        ->where('kode_permohonan', strtolower($kode))
        ->firstOrFail();

    $request->validate([
        'alasan_keberatan' => 'required|array|min:1',
        'alasan_keberatan.*' => 'in:A,B,C,D,E,F,G',
        'kronologi' => 'required|string|max:5000',
        'persetujuan' => 'accepted',
    ]);

    $sudahAda = PpidKeberatan::where('ppid_permohonan_id', $permohonan->id)
        ->where('desa_id', $desa->id)
        ->first();

    if ($sudahAda) {
        return redirect()
            ->route('embed.ppid.permohonan.monitoring.hasil', [
                'slug' => $desa->slug,
                'kode' => $permohonan->kode_permohonan,
            ])
            ->with('success', 'Keberatan untuk permohonan ini sudah pernah diajukan.');
    }

    $keberatan = PpidKeberatan::create([
        'desa_id' => $desa->id,
        'ppid_permohonan_id' => $permohonan->id,
        'kode_keberatan' => $permohonan->kode_permohonan,
        'alasan_keberatan' => json_encode($request->alasan_keberatan),
        'uraian_alasan' => $request->kronologi,
        'status' => 'diajukan',
    ]);

    PpidLog::create([
        'desa_id' => $desa->id,
        'ppid_permohonan_id' => $permohonan->id,
        'ppid_keberatan_id' => $keberatan->id,
        'tipe' => 'keberatan',
        'judul' => 'Ajuan Keberatan Masuk',
        'deskripsi' => $permohonan->nama . ' melakukan ajuan keberatan atas informasi publik.',
        'actor_name' => $permohonan->nama,
        'actor_role' => 'pemohon',
    ]);

    return redirect()
        ->route('embed.ppid.permohonan.monitoring.hasil', [
            'slug' => $desa->slug,
            'kode' => $permohonan->kode_permohonan,
        ])
        ->with('success', 'Keberatan resmi berhasil diajukan.');
}

public function cetakPemberitahuan($slug, $kode)
{
    $desa = Desa::where('slug', $slug)->firstOrFail();

    $permohonan = PpidPermohonan::where('desa_id', $desa->id)
        ->where('kode_permohonan', strtolower($kode))
        ->firstOrFail();

    $pemberitahuan = \App\Models\PpidPemberitahuan::where('ppid_permohonan_id', $permohonan->id)
        ->where('desa_id', $desa->id)
        ->firstOrFail();

    $pdf = Pdf::loadView('desa.ppid.permohonan.pdf_pemberitahuan', [
        'permohonan' => $permohonan,
        'pemberitahuan' => $pemberitahuan,
        'desa' => $desa,
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('pemberitahuan-ppid-' . strtoupper($permohonan->kode_permohonan) . '.pdf');
}

public function cetakSkPenolakan($slug, $kode)
{
    $desa = Desa::where('slug', $slug)->firstOrFail();

    $permohonan = PpidPermohonan::where('desa_id', $desa->id)
        ->where('kode_permohonan', strtolower($kode))
        ->firstOrFail();

    $pemberitahuan = \App\Models\PpidPemberitahuan::where('ppid_permohonan_id', $permohonan->id)
        ->where('desa_id', $desa->id)
        ->firstOrFail();

    abort_if(
        $pemberitahuan->status_informasi !== 'tidak_dapat_diberikan'
        || $pemberitahuan->alasan_penolakan !== 'informasi_dikecualikan',
        404
    );

    $pdf = Pdf::loadView('desa.ppid.permohonan.pdf_sk_penolakan', [
        'permohonan' => $permohonan,
        'pemberitahuan' => $pemberitahuan,
        'desa' => $desa,
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('sk-penolakan-ppid-' . strtoupper($permohonan->kode_permohonan) . '.pdf');
}

public function cetakTidakLengkap($slug, $kode)
{
    $desa = Desa::where('slug', $slug)->firstOrFail();

    $permohonan = PpidPermohonan::where('desa_id', $desa->id)
        ->where('kode_permohonan', strtolower($kode))
        ->firstOrFail();

    abort_if(
        $permohonan->status !== 'tidak_lengkap',
        404
    );

    $pdf = Pdf::loadView('desa.ppid.permohonan.pdf_tidak_lengkap', [
        'permohonan' => $permohonan,
        'desa' => $desa,
    ])->setPaper('a4', 'portrait');

    return $pdf->stream('pemberitahuan-tidak-lengkap-' . strtoupper($permohonan->kode_permohonan) . '.pdf');
}
}