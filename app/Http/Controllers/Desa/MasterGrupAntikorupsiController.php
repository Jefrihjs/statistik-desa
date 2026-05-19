<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\MasterGrupAntikorupsi;
use Illuminate\Http\Request;

class MasterGrupAntikorupsiController extends Controller
{
    public function index()
    {
        // Mengambil data dan diurutkan berdasarkan kategori lalu nama grup
        $masterGrup = MasterGrupAntikorupsi::orderBy('kategori')->orderBy('nama_grup')->get();
        return view('desa.master-grup.index', compact('masterGrup'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required',
            'nama_grup' => 'required'
        ]);

        MasterGrupAntikorupsi::create($request->all());
        return redirect()->back()->with('success', 'Master Grup berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required',
            'nama_grup' => 'required'
        ]);

        MasterGrupAntikorupsi::findOrFail($id)->update($request->all());
        return redirect()->back()->with('success', 'Master Grup berhasil diperbarui!');
    }

    public function destroy($id)
    {
        MasterGrupAntikorupsi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Master Grup berhasil dihapus!');
    }
}