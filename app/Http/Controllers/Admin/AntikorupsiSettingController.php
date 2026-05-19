<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AntikorupsiSettingController extends Controller
{
    public function index()
    {
        // UBAH DI SINI: Ganti 'user' menjadi 'desa'
        $users = User::where('role', 'desa')->get(); 
        
        return view('admin.antikorupsi-setting.index', compact('users'));
    }

    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $user->is_antikorupsi_active = !$user->is_antikorupsi_active;
        $user->save();

        return redirect()->back()->with('success', 'Status modul Antikorupsi untuk user ' . $user->name . ' berhasil diperbarui.');
    }
}