<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\DokumenAntikorupsi;
use Illuminate\Http\Request;

class AntikorupsiDesaController extends Controller
{
    public function index()
    {
        $desaId = auth()->user()->desa_id;
        abort_if(!$desaId, 404, 'Akun Anda belum terikat dengan entitas Desa manapun.');

        $dokumen = DokumenAntikorupsi::where('desa_id', $desaId)
            ->orderBy('id', 'asc')
            ->get();

        $data = [
            'tatalaksana' => $dokumen->where('kategori', 'tatalaksana')->groupBy('grup_indikator'),
            'pengawasan'  => $dokumen->where('kategori', 'pengawasan')->groupBy('grup_indikator'),
            'pelayanan'   => $dokumen->where('kategori', 'pelayanan')->groupBy('grup_indikator'),
            'partisipasi' => $dokumen->where('kategori', 'partisipasi')->groupBy('grup_indikator'),
            'kearifan'    => $dokumen->where('kategori', 'kearifan')->groupBy('grup_indikator'),
        ];

        $masterGrupList = \App\Models\MasterGrupAntikorupsi::all();

        return view('desa.antikorupsi.index', compact('data', 'masterGrupList'));
    }

    // Fungsi untuk menyimpan perubahan Link Drive secara massal
    public function update(Request $request)
    {
        $request->validate([
            'links' => 'array',
            'links.*' => 'nullable|url' 
        ]);

        if($request->has('links')) {
            foreach ($request->links as $id => $link) {
                DokumenAntikorupsi::where('id', $id)
                    ->where('desa_id', auth()->user()->desa_id) // Proteksi: Pastikan hanya mengupdate dokumen desa sendiri
                    ->update(['link_drive' => $link]);
            }
        }

        return redirect()->back()->with('success', 'Tautan Google Drive berhasil disimpan!');
    }


    // Fungsi Tambah Indikator Baru Mandiri oleh Desa
    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required',
            'grup_indikator' => 'required',
            'sub_judul' => 'nullable|string|max:255',
            'no_urut' => 'nullable|string|max:20',
            'sub' => 'nullable|string|max:20',
            'nama_dokumen' => 'nullable|string|max:255',
            'link_drive' => 'nullable|url',
        ]);

        DokumenAntikorupsi::create([
            'desa_id'        => auth()->user()->desa_id, 
            'kategori'       => $request->kategori,
            'grup_indikator' => $request->grup_indikator,
            'sub_judul'      => $request->sub_judul,
            'no_urut'        => $request->no_urut,
            'sub'            => $request->sub,
            'nama_dokumen'   => $request->nama_dokumen,
            'link_drive'     => $request->link_drive,
        ]);

        return redirect()->back()->with('success', 'Indikator baru berhasil ditambahkan!');
    }

    // Fungsi Hapus Indikator
    public function destroy($id)
    {
        // Pastikan user desa hanya berhak menghapus data milik desanya sendiri
        $dokumen = DokumenAntikorupsi::where('id', $id)
            ->where('desa_id', auth()->user()->desa_id)
            ->firstOrFail();
            
        $dokumen->delete();

        return redirect()->back()->with('success', 'Indikator berhasil dihapus!');
    }

    // Fungsi Edit Detail Indikator
    public function editData(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required',
            'grup_indikator' => 'required',
            'sub_judul' => 'nullable|string|max:255',
            'no_urut' => 'nullable|string|max:20',
            'sub' => 'nullable|string|max:20',
            'nama_dokumen' => 'nullable|string|max:255',
            'link_drive' => 'nullable|url',
        ]);

        $dokumen = DokumenAntikorupsi::where('id', $id)
            ->where('desa_id', auth()->user()->desa_id)
            ->firstOrFail();

        $dokumen->update([
            'kategori'       => $request->kategori,
            'grup_indikator' => $request->grup_indikator,
            'sub_judul'      => $request->sub_judul,
            'no_urut'        => $request->no_urut,
            'sub'            => $request->sub,
            'nama_dokumen'   => $request->nama_dokumen,
            'link_drive'     => $request->link_drive,
        ]);

        return redirect()->back()->with('success', 'Detail indikator berhasil diperbarui!');
    }
}