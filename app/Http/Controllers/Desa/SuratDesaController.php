<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\JenisSurat;
use App\Models\PermohonanSurat;
use App\Services\TemplateSuratService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratDesaController extends Controller
{
    private function getDesa(): Desa
    {
        abort_unless((bool) auth()->user()->is_surat_active, 403, 'Modul Permohonan Surat belum diaktifkan oleh admin kabupaten.');
        return auth()->user()->desa ?? Desa::findOrFail(auth()->user()->desa_id);
    }

    public function index(Request $request)
    {
        $desa = $this->getDesa();

        if (JenisSurat::where('desa_id', $desa->id)->count() === 0) {
            TemplateSuratService::seedForDesa($desa->id);
        }

        $query = PermohonanSurat::with(['jenisSurat', 'dokumens'])
            ->where('desa_id', $desa->id)
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_pemohon', 'like', "%{$q}%")
                    ->orWhere('nik', 'like', "%{$q}%")
                    ->orWhere('kode_registrasi', 'like', "%{$q}%");
            });
        }

        $permohonans = $query->paginate(15)->withQueryString();
        $jenisSurats = JenisSurat::with('syarats')
            ->where('desa_id', $desa->id)
            ->orderBy('urutan')
            ->orderBy('id')
            ->get();

        $stats = [
            'menunggu' => PermohonanSurat::where('desa_id', $desa->id)->where('status', 'menunggu')->count(),
            'berkas_kurang' => PermohonanSurat::where('desa_id', $desa->id)->where('status', 'berkas_kurang')->count(),
            'diproses' => PermohonanSurat::where('desa_id', $desa->id)->where('status', 'diproses')->count(),
            'selesai' => PermohonanSurat::where('desa_id', $desa->id)->where('status', 'selesai')->count(),
        ];

        return view('desa.surat.index', compact('desa', 'permohonans', 'jenisSurats', 'stats'));
    }

    public function updateStatus(Request $request, $id)
    {
        $desa = $this->getDesa();
        $permohonan = PermohonanSurat::where('desa_id', $desa->id)->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:menunggu,berkas_kurang,diproses,selesai,ditolak',
            'catatan_admin' => 'nullable|string|max:1000',
            'file_surat_jadi' => 'nullable|file|mimes:pdf|max:4096',
        ]);

        if ($request->hasFile('file_surat_jadi')) {
            $validated['file_surat_jadi'] = $request->file('file_surat_jadi')->store('surat-jadi/' . $desa->id, 'public');
        }

        $permohonan->update($validated);

        return back()->with('success', 'Status permohonan ' . $permohonan->kode_registrasi . ' berhasil diperbarui.');
    }

    public function destroyPermohonan($id)
    {
        $desa = $this->getDesa();
        $permohonan = PermohonanSurat::where('desa_id', $desa->id)->findOrFail($id);
        $permohonan->delete();

        return back()->with('success', 'Data permohonan surat dihapus.');
    }

    public function storeJenis(Request $request)
    {
        $desa = $this->getDesa();

        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kode_surat' => 'nullable|string|max:50',
            'catatan' => 'nullable|string|max:1000',
            'syarat_nama' => 'required|array|min:1',
            'syarat_nama.*' => 'required|string|max:255',
            'syarat_tipe' => 'required|array',
            'syarat_tipe.*' => 'in:file,info_fisik',
            'syarat_wajib' => 'nullable|array',
        ]);

        DB::transaction(function () use ($desa, $validated, $request) {
            $jenis = JenisSurat::create([
                'desa_id' => $desa->id,
                'nama_layanan' => $validated['nama_layanan'],
                'kode_surat' => $validated['kode_surat'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
                'is_active' => true,
                'urutan' => JenisSurat::where('desa_id', $desa->id)->max('urutan') + 1,
            ]);

            foreach ($validated['syarat_nama'] as $idx => $namaSyarat) {
                $jenis->syarats()->create([
                    'nama_syarat' => $namaSyarat,
                    'tipe_input' => $validated['syarat_tipe'][$idx] ?? 'file',
                    'is_wajib' => isset($request->syarat_wajib[$idx]),
                    'urutan' => $idx + 1,
                ]);
            }
        });

        return back()->with('success', 'Jenis layanan surat baru berhasil ditambahkan.');
    }

    public function updateJenis(Request $request, $id)
    {
        $desa = $this->getDesa();
        $jenis = JenisSurat::where('desa_id', $desa->id)->findOrFail($id);

        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kode_surat' => 'nullable|string|max:50',
            'catatan' => 'nullable|string|max:1000',
            'syarat_nama' => 'required|array|min:1',
            'syarat_nama.*' => 'required|string|max:255',
            'syarat_tipe' => 'required|array',
            'syarat_tipe.*' => 'in:file,info_fisik',
            'syarat_wajib' => 'nullable|array',
        ]);

        DB::transaction(function () use ($jenis, $validated, $request) {
            $jenis->update([
                'nama_layanan' => $validated['nama_layanan'],
                'kode_surat' => $validated['kode_surat'] ?? null,
                'catatan' => $validated['catatan'] ?? null,
            ]);

            $jenis->syarats()->delete();

            foreach ($validated['syarat_nama'] as $idx => $namaSyarat) {
                $jenis->syarats()->create([
                    'nama_syarat' => $namaSyarat,
                    'tipe_input' => $validated['syarat_tipe'][$idx] ?? 'file',
                    'is_wajib' => isset($request->syarat_wajib[$idx]),
                    'urutan' => $idx + 1,
                ]);
            }
        });

        return back()->with('success', 'Layanan surat "' . $jenis->nama_layanan . '" berhasil diperbarui.');
    }

    public function toggleJenis($id)
    {
        $desa = $this->getDesa();
        $jenis = JenisSurat::where('desa_id', $desa->id)->findOrFail($id);
        $jenis->update(['is_active' => !$jenis->is_active]);

        return back()->with('success', 'Status layanan surat diperbarui.');
    }

    public function destroyJenis($id)
    {
        $desa = $this->getDesa();
        $jenis = JenisSurat::where('desa_id', $desa->id)->findOrFail($id);
        $jenis->delete();

        return back()->with('success', 'Layanan surat berhasil dihapus.');
    }

    public function importDefault()
    {
        $desa = $this->getDesa();
        $count = TemplateSuratService::seedForDesa($desa->id);

        return back()->with('success', $count . ' template layanan surat default berhasil ditambahkan.');
    }
}
