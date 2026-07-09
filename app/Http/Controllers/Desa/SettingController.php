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
        
        // INI YANG KURANG SEBELUMNYA 👇
        $desa->featured_category_id = $request->featured_category_id;
        
        $desa->welcome_message = $request->welcome_message;

        if ($request->hasFile('logo')) {
            if ($desa->logo) { 
                \Storage::disk('public')->delete($desa->logo); 
            }
            $desa->logo = $request->file('logo')->store('logos', 'public');
        }

        $desa->save();

        \App\Services\ActivityLogger::log(
            'Branding Desa',
            'Update Branding',
            'Operator desa memperbarui logo atau warna branding desa.',
            [
                'header_color' => $request->header_color ?? null,
                'accent_color' => $request->accent_color ?? null,
                'layout_type' => $request->layout_type ?? null,
                'featured_category_id' => $request->featured_category_id ?? null,
            ]
        );

        return back()->with('success', 'Tampilan branding berhasil diperbarui!');
    }
}