<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengaturanDesaController extends Controller
{
    public function edit()
    {
        $desa = auth()->user()->desa;
        $categories = \App\Models\Kategori::where('is_active', true)->orderBy('name')->get();

        return view('desa.pengaturan.edit', compact('desa', 'categories'));
    }

    public function update(Request $request)
    {
        $desa = auth()->user()->desa;

        $request->validate([
            'alamat_kantor'    => 'nullable|string|max:500',
            'email_desa'       => 'nullable|email|max:100',
            'website_desa'     => 'nullable|url|max:200',
            'telepon_desa'     => 'nullable|string|max:30',
            'logo_desa'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nama_kepala_desa' => 'nullable|string|max:200',
            'nip_kepala'       => 'nullable|string|max:50',
            'nama_ppid'        => 'nullable|string|max:200',
            'jabatan_ppid'     => 'nullable|string|max:200',
            'nip_ppid'         => 'nullable|string|max:50',
            'layout_type'      => 'nullable|in:default,infographic',
            'featured_category_id' => 'nullable|exists:kategoris,id',
            'welcome_message'  => 'nullable|string|max:2000',
            'public_template_id' => 'nullable|integer|between:1,6',
        ]);

        // Upload logo
        if ($request->hasFile('logo_desa')) {
            if ($desa->logo_desa) {
                \Storage::disk('public')->delete($desa->logo_desa);
            }
            $desa->logo_desa = $request->file('logo_desa')->store('logo-desa', 'public');
        }

        // Text fields
        $fields = [
            'alamat_kantor', 'email_desa', 'website_desa', 'telepon_desa',
            'nama_kepala_desa', 'nip_kepala',
            'nama_ppid', 'jabatan_ppid', 'nip_ppid',
            'layout_type', 'featured_category_id', 'welcome_message',
            'public_template_id',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $desa->$field = $request->$field;
            }
        }

        $desa->save();

        return back()->with('success', 'Pengaturan desa berhasil disimpan.');
    }
}