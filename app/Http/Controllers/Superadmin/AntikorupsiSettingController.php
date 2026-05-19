<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Desa; // Sesuaikan dengan model Desa Anda
use Illuminate\Http\Request;

class AntikorupsiSettingController extends Controller
{
    // Halaman daftar desa untuk Superadmin
    public function index()
    {
        // Ambil semua data desa
        $desas = Desa::all(); 
        return view('superadmin.antikorupsi-setting.index', compact('desas'));
    }

    // Fungsi untuk mengubah status Aktif/Tidak
    public function toggleStatus(Request $request, $id)
    {
        $desa = Desa::findOrFail($id);
        
        // Ubah status: Jika true jadi false, jika false jadi true
        $desa->is_antikorupsi_active = !$desa->is_antikorupsi_active;
        $desa->save();

        return redirect()->back()->with('success', 'Status fitur Antikorupsi untuk desa ' . $desa->nama_desa . ' berhasil diperbarui.');
    }
}