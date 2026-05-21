<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\PpidDip;
use Illuminate\Http\Request;

class PpidDipController extends Controller
{
    public function index()
    {
        $desaId = auth()->user()->desa_id;

        abort_if(!$desaId, 404, 'Akun Anda belum terhubung dengan desa.');

        $kategoriList = [
            'berkala' => 'Informasi Berkala',
            'serta_merta' => 'Informasi Serta Merta',
            'setiap_saat' => 'Informasi Setiap Saat',
            'dikecualikan' => 'Informasi Dikecualikan',
        ];

        $data = PpidDip::where('desa_id', $desaId)
            ->orderBy('kategori', 'asc')
            ->orderBy('urutan', 'asc')
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy('kategori');

        return view('desa.ppid.dip.index', compact('data', 'kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:berkala,serta_merta,setiap_saat,dikecualikan',
            'urutan' => 'nullable|integer',
            'judul_informasi' => 'required|string|max:255',
            'kelompok_informasi' => 'nullable|string|max:255',
            'ringkasan' => 'nullable|string',
            'link_dokumen' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        PpidDip::create([
            'desa_id' => auth()->user()->desa_id,
            'kategori' => $request->kategori,
            'kelompok_informasi' => $request->kelompok_informasi,
            'urutan' => $request->urutan,
            'judul_informasi' => $request->judul_informasi,
            'ringkasan' => $request->ringkasan,
            'link_dokumen' => $request->link_dokumen,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Data DIP berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required|in:berkala,serta_merta,setiap_saat,dikecualikan',
            'urutan' => 'nullable|integer',
            'judul_informasi' => 'required|string|max:255',
            'kelompok_informasi' => 'nullable|string|max:255',
            'ringkasan' => 'nullable|string',
            'link_dokumen' => 'nullable|url',
            'is_active' => 'nullable|boolean',
        ]);

        $dip = PpidDip::where('id', $id)
            ->where('desa_id', auth()->user()->desa_id)
            ->firstOrFail();

        $dip->update([
            'kategori' => $request->kategori,
            'kelompok_informasi' => $request->kelompok_informasi,
            'urutan' => $request->urutan,
            'judul_informasi' => $request->judul_informasi,
            'ringkasan' => $request->ringkasan,
            'link_dokumen' => $request->link_dokumen,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Data DIP berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dip = PpidDip::where('id', $id)
            ->where('desa_id', auth()->user()->desa_id)
            ->firstOrFail();

        $dip->delete();

        return back()->with('success', 'Data DIP berhasil dihapus.');
    }
}