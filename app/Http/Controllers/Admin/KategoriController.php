<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    public function index()
    {
        $categories = Category::with('indicators')->withCount('indicators')->get();
        return view('admin.kategori.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon ?? 'collection',
            'is_active' => true,
        ]);

        return back()->with('success', 'Kategori baru berhasil ditambah!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->indicators()->count() > 0) {
            return back()->with('error', 'Kategori masih memiliki indikator. Hapus indikator dulu atau cukup nonaktifkan tab.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Tab {$category->name} berhasil {$status}!");
    }

    public function addIndicator(Request $request, $categoryId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:100',
        ]);

        Indicator::create([
            'category_id' => $categoryId,
            'name' => $request->name,
            'unit' => $request->unit ?? 'Jiwa',
        ]);

        return back()->with('success', 'Indikator berhasil ditambah!');
    }

    public function updateIndicator(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:100',
        ]);

        $indicator = Indicator::findOrFail($id);
        $indicator->update([
            'name' => $request->name,
            'unit' => $request->unit,
        ]);

        return back()->with('success', 'Indikator berhasil diperbarui!');
    }

    public function destroyIndicator($id)
    {
        $indicator = Indicator::findOrFail($id);
        $indicator->delete();

        return back()->with('success', 'Indikator berhasil dihapus!');
    }

    // --- TAMBAHKAN INI ---
    public function toggleIndicatorStatus($id)
    {
        $indicator = Indicator::findOrFail($id);
        $indicator->is_active = !$indicator->is_active;
        $indicator->save();

        $status = $indicator->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Indikator {$indicator->name} berhasil {$status}!");
    }
}