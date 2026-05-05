<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class SettingController extends Controller
{
    public function edit()
    {
        $desa = auth()->user()->desa;
        $categories = \App\Models\Category::all(); 
        return view('desa.settings.edit', compact('desa', 'categories'));
    }

    public function update(Request $request)
    {
        $desa = auth()->user()->desa;

        $desa->header_color = $request->header_color;
        $desa->accent_color = $request->accent_color;
        $desa->layout_type = $request->layout_type; 
        $desa->welcome_message = $request->welcome_message;

        if ($request->hasFile('logo')) {
            if ($desa->logo) { 
                \Storage::disk('public')->delete($desa->logo); 
            }
            $desa->logo = $request->file('logo')->store('logos', 'public');
        }

        $desa->save();

        return back()->with('success', 'Tampilan branding berhasil diperbarui!');
    }
}