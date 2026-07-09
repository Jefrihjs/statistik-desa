<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PpidPengaturanController extends Controller
{
    public function edit()
    {
        $desaId = auth()->user()->desa_id;

        abort_if(!$desaId, 404, 'Akun Anda belum terhubung dengan desa.');

        $desa = Desa::findOrFail($desaId);

        return view('desa.ppid.pengaturan.edit', compact('desa'));
    }

    public function update(Request $request)
    {
        $desaId = auth()->user()->desa_id;

        abort_if(!$desaId, 404, 'Akun Anda belum terhubung dengan desa.');

        $desa = Desa::findOrFail($desaId);

        $request->validate([
            'alamat_kantor' => 'nullable|string',
            'email_desa' => 'nullable|email|max:255',
            'website_desa' => 'nullable|string|max:255',
            'telepon_desa' => 'nullable|string|max:50',
            'logo_desa' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama_ppid' => 'nullable|string|max:255',
            'jabatan_ppid' => 'nullable|string|max:255',
            'nip_ppid' => 'nullable|string|max:50',
        ]);

        $data = $request->only([
            'alamat_kantor',
            'email_desa',
            'website_desa',
            'telepon_desa',
            'nama_ppid',
            'jabatan_ppid',
            'nip_ppid',
        ]);

        if ($request->hasFile('logo_desa')) {
            if ($desa->logo_desa && Storage::disk('public')->exists($desa->logo_desa)) {
                Storage::disk('public')->delete($desa->logo_desa);
            }

            $data['logo_desa'] = $request->file('logo_desa')->store('ppid/logo-desa', 'public');
        }

        $desa->update($data);

        \App\Services\ActivityLogger::log(
            'PPID',
            'Update Pengaturan PPID',
            'Operator desa memperbarui pengaturan PPID Desa.',
            [
                'nama_ppid' => $request->nama_ppid ?? null,
                'alamat' => $request->alamat ?? null,
            ]
        );
        
        return redirect()
            ->route('desa.ppid.pengaturan.edit')
            ->with('success', 'Pengaturan PPID Desa berhasil disimpan.');
    }
}