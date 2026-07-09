<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ModuleSettingController extends Controller
{
    public function index()
    {
        $users = User::with('desa')
            ->where('role', 'desa')
            ->orderBy('name')
            ->get();

        return view('admin.module-setting.index', compact('users'));
    }

    public function toggle(Request $request, User $user)
    {
        $validated = $request->validate([
            'module' => ['required', 'in:statistik,ppid,antikorupsi,skm,aduan'],
        ]);

        $column = match ($validated['module']) {
            'statistik' => 'is_statistik_active',
            'ppid' => 'is_ppid_active',
            'antikorupsi' => 'is_antikorupsi_active',
            'skm' => 'is_skm_active',
            'aduan' => 'is_aduan_active',
        };

        $newValue = ! (bool) $user->{$column};

        $user->forceFill([
            $column => $newValue,
        ])->save();

        \App\Services\ActivityLogger::log(
            'Pengaturan Modul',
            'Toggle Akses Modul',
            'Admin kabupaten mengubah akses modul desa.',
            [
                'target_user_id' => $user->id,
                'target_user_name' => $user->name,
                'target_desa' => $user->desa->nama_desa ?? null,
                'module' => $validated['module'],
                'status_baru' => $newValue ? 'aktif' : 'nonaktif',
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'active' => $newValue,
                'message' => 'Status akses modul berhasil diperbarui.',
            ]);
        }

        return back()->with('success', 'Status akses modul berhasil diperbarui.');
    }
}