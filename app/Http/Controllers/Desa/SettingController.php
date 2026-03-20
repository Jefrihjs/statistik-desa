<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $desa = auth()->user()->desa;

        return view('desa.settings.edit', compact('desa'));
    }

    public function update(Request $request)
    {
        $desa = auth()->user()->desa;

        $data = $request->validate([
            'logo' => 'nullable|image',
            'header_color' => 'nullable|string',
            'accent_color' => 'nullable|string',
        ]);

        $desa->update($data);

        return back()->with('success', 'Branding desa diperbarui.');
    }
}