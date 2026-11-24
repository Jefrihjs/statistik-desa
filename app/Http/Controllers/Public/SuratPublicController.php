<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\JenisSurat;
use App\Models\PermohonanSurat;
use App\Services\TemplateSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SuratPublicController extends Controller
{
    private function resolveDesa(string $slug): Desa
    {
        return Desa::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();
    }

    public function create(string $slug)
    {
        $desa = $this->resolveDesa($slug);

        if (JenisSurat::where('desa_id', $desa->id)->count() === 0) {
            TemplateSuratService::seedForDesa($desa->id);
        }

        $jenisSurats = JenisSurat::with('syarats')
            ->where('desa_id', $desa->id)
            ->where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        return view('public.surat.create', compact('desa', 'jenisSurats'));
    }

    public function store(Request $request, string $slug)
    {
        $desa = $this->resolveDesa($slug);

        $request->validate([
            'jenis_surat_id' => 'required|exists:jenis_surats,id',
            'nama_pemohon' => 'required|string|max:255',
            'nik' => 'required|digits:16',
            'no_hp' => 'required|string|max:25',
            'email' => 'nullable|email|max:255',
            'alamat' => 'required|string|max:1000',
            'keperluan' => 'required|string|max:1000',
        ]);

        $jenis = JenisSurat::with('syarats')
            ->where('desa_id', $desa->id)
            ->where('is_active', true)
            ->findOrFail($request->jenis_surat_id);

        $fileRules = [];
        foreach ($jenis->syarats->where('tipe_input', 'file') as $syarat) {
            $key = 'dokumen.' . $syarat->id;
            $fileRules[$key] = ($syarat->is_wajib ? 'required|' : 'nullable|') . 'file|mimes:jpg,jpeg,png,pdf|max:2048';
        }
        $request->validate($fileRules);

        $permohonan = DB::transaction(function () use ($request, $desa, $jenis) {
            $kode = 'SRT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));

            $permohonan = PermohonanSurat::create([
                'desa_id' => $desa->id,
                'jenis_surat_id' => $jenis->id,
                'kode_registrasi' => $kode,
                'nama_pemohon' => $request->nama_pemohon,
                'nik' => $request->nik,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'keperluan' => $request->keperluan,
                'status' => 'menunggu',
            ]);

            foreach ($jenis->syarats->where('tipe_input', 'file') as $syarat) {
                if ($request->hasFile('dokumen.' . $syarat->id)) {
                    $file = $request->file('dokumen.' . $syarat->id);
                    $path = $file->store('permohonan-surat/' . $desa->id . '/' . $kode, 'public');

                    $permohonan->dokumens()->create([
                        'jenis_surat_syarat_id' => $syarat->id,
                        'nama_syarat' => $syarat->nama_syarat,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }

            return $permohonan;
        });

        return redirect()->route('public.surat.success', [$desa->slug ?? $desa->id, $permohonan->kode_registrasi]);
    }

    public function success(string $slug, string $kode)
    {
        $desa = $this->resolveDesa($slug);
        $permohonan = PermohonanSurat::with('jenisSurat')
            ->where('desa_id', $desa->id)
            ->where('kode_registrasi', $kode)
            ->firstOrFail();

        return view('public.surat.success', compact('desa', 'permohonan'));
    }

    public function tracking(Request $request, string $slug)
    {
        $desa = $this->resolveDesa($slug);
        $permohonan = null;

        if ($request->filled('kode')) {
            $permohonan = PermohonanSurat::with(['jenisSurat', 'dokumens'])
                ->where('desa_id', $desa->id)
                ->where(function ($q) use ($request) {
                    $q->where('kode_registrasi', trim($request->kode))
                      ->orWhere('nik', trim($request->kode));
                })
                ->latest()
                ->first();
        }

        return view('public.surat.tracking', compact('desa', 'permohonan'));
    }
}
