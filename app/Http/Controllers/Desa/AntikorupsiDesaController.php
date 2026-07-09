<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\DokumenAntikorupsi;
use Illuminate\Http\Request;

class AntikorupsiDesaController extends Controller
{
    public function index()
    {
        $desaId = auth()->user()->desa_id;
        abort_if(!$desaId, 404, 'Akun Anda belum terikat dengan entitas Desa manapun.');

        $dokumen = DokumenAntikorupsi::where('desa_id', $desaId)
            ->orderBy('kategori', 'asc')
            ->orderBy('urutan_tampil', 'asc')
            ->orderBy('level', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $masterGrupList = \App\Models\MasterGrupAntikorupsi::orderBy('kategori', 'asc')
            ->orderBy('urutan_grup', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $kategoriKeys = ['tatalaksana', 'pengawasan', 'pelayanan', 'partisipasi', 'kearifan'];

        $data = [];

        foreach ($kategoriKeys as $kategori) {
            $data[$kategori] = collect();

            $grupKategori = $masterGrupList->where('kategori', $kategori);

            foreach ($grupKategori as $grup) {
                $items = $dokumen
                    ->where('kategori', $kategori)
                    ->where('grup_indikator', $grup->nama_grup)
                    ->sortBy([
                        ['urutan_tampil', 'asc'],
                        ['id', 'asc'],
                    ])
                    ->values();

                if ($items->isNotEmpty()) {
                    $data[$kategori]->put($grup->nama_grup, $items);
                }
            }
        }

        return view('desa.antikorupsi.index', compact('data', 'masterGrupList'));
    }

    // Fungsi untuk menyimpan perubahan Link Drive secara massal
    public function update(Request $request)
    {
        $request->merge([
            'links' => collect($request->input('links', []))
                ->map(fn ($link) => $this->normalizeLink($link))
                ->all(),
        ]);

        $request->validate([
            'links' => 'array',
            'links.*' => 'nullable|url' 
        ]);

        if($request->has('links')) {
            foreach ($request->links as $id => $link) {
                DokumenAntikorupsi::where('id', $id)
                    ->where('desa_id', auth()->user()->desa_id) // Proteksi: Pastikan hanya mengupdate dokumen desa sendiri
                    ->update(['link_drive' => $link]);
            }
        }

        \App\Services\ActivityLogger::log(
            'Antikorupsi',
            'Update Link Drive',
            'Operator desa memperbarui tautan Google Drive dokumen Antikorupsi.',
            [
                'total_link' => count($request->links ?? []),
            ]
        );

        return redirect()->back()->with('success', 'Tautan Google Drive berhasil disimpan!');
    }


    // Fungsi Tambah Indikator Baru Mandiri oleh Desa
    public function store(Request $request)
    {
        $request->merge([
            'link_drive' => $this->normalizeLink($request->link_drive),
        ]);

        $request->validate([
            'kategori' => 'required',
            'grup_indikator' => 'required',
            'tipe' => 'nullable|in:subjudul,dokumen',
            'urutan_tampil' => 'nullable|integer',
            'level' => 'nullable|integer|min:0|max:4',
            'sub_judul' => 'nullable|string|max:255',
            'sub_judul_indikator' => 'nullable|string|max:255',
            'no_urut' => 'nullable|string|max:20',
            'sub' => 'nullable|string|max:20',
            'nama_dokumen' => 'nullable|string|max:255',
            'link_drive' => 'nullable|url',
        ]);

        $isSubJudul = $request->input('tipe', 'subjudul') === 'subjudul';
        $subJudul = $request->sub_judul ?: $request->sub_judul_indikator;

        DokumenAntikorupsi::create([
            'desa_id'        => auth()->user()->desa_id, 
            'kategori'       => $request->kategori,
            'grup_indikator' => $request->grup_indikator,
            'urutan_tampil' => $request->urutan_tampil ?? $this->nextUrutan($request->kategori, $request->grup_indikator),
            'parent_id'      => null,
            'level'          => 0,
            'sub_judul'      => $subJudul,
            'no_urut'        => null,
            'sub'            => null,
            'nama_dokumen'   => $isSubJudul ? null : $request->nama_dokumen,
            'link_drive'     => $request->link_drive,
        ]);

        $this->normalizeHierarchy($request->kategori, $request->grup_indikator);

        \App\Services\ActivityLogger::log(
            'Antikorupsi',
            'Tambah Indikator',
            'Operator desa menambahkan indikator dokumen Antikorupsi.',
            [
                'nama_dokumen' => $request->nama_dokumen,
                'kategori' => $request->kategori,
                'grup_indikator' => $request->grup_indikator,
            ]
        );

        return redirect()->back()->with('success', 'Indikator baru berhasil ditambahkan!');
    }

    // Fungsi Hapus Indikator
    public function destroy($id)
    {
        // Pastikan user desa hanya berhak menghapus data milik desanya sendiri
        $dokumen = DokumenAntikorupsi::where('id', $id)
            ->where('desa_id', auth()->user()->desa_id)
            ->firstOrFail();
        
        // Capture data before deletion
        $namaDokumen = $dokumen->nama_dokumen;
        $kategori = $dokumen->kategori;
            
        $dokumen->delete();

        \App\Services\ActivityLogger::log(
            'Antikorupsi',
            'Hapus Indikator',
            'Operator desa menghapus indikator dokumen Antikorupsi.',
            [
                'indikator_id' => $id,
                'nama_dokumen' => $namaDokumen,
                'kategori' => $kategori,
            ]
        );

        return redirect()->back()->with('success', 'Indikator berhasil dihapus!');
    }

    // Fungsi Edit Detail Indikator
    public function editData(Request $request, $id)
    {
        $request->merge([
            'link_drive' => $this->normalizeLink($request->link_drive),
        ]);

        $request->validate([
        'kategori' => 'required',
        'grup_indikator' => 'required',
        'urutan_tampil' => 'nullable|integer',
        'level' => 'nullable|integer|min:0|max:4',
        'sub_judul' => 'nullable|string|max:255',
        'no_urut' => 'nullable|string|max:20',
        'sub' => 'nullable|string|max:20',
        'nama_dokumen' => 'nullable|string|max:255',
        'link_drive' => 'nullable|url',
    ]);

        $dokumen = DokumenAntikorupsi::where('id', $id)
            ->where('desa_id', auth()->user()->desa_id)
            ->firstOrFail();

        $dokumen->update([
            'kategori'       => $request->kategori,
            'grup_indikator' => $request->grup_indikator,
            'urutan_tampil'  => $request->urutan_tampil,
            'level'          => min((int) $request->input('level', $dokumen->level ?? 0), 4),
            'sub_judul'      => $request->sub_judul,
            'nama_dokumen'   => $request->nama_dokumen,
            'link_drive'     => $request->link_drive,
        ]);

        $this->normalizeHierarchy($dokumen->kategori, $dokumen->grup_indikator);

        return redirect()->back()->with('success', 'Detail indikator berhasil diperbarui!');
    }

    // Reorder items
    public function reorder(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|integer',
            'updates.*.urutan' => 'required|integer',
            'updates.*.level' => 'nullable|integer|min:0|max:4',
        ]);

        $touchedGroups = [];

        foreach ($request->updates as $update) {
            $dokumen = DokumenAntikorupsi::where('id', $update['id'])
                ->where('desa_id', auth()->user()->desa_id)
                ->first();

            if (!$dokumen) {
                continue;
            }

            $dokumen->update([
                'urutan_tampil' => $update['urutan'],
                'level' => min((int) ($update['level'] ?? 0), 4),
            ]);

            $touchedGroups[$dokumen->kategori . '|' . $dokumen->grup_indikator] = [
                $dokumen->kategori,
                $dokumen->grup_indikator,
            ];
        }

        foreach ($touchedGroups as [$kategori, $grup]) {
            $this->normalizeHierarchy($kategori, $grup);
        }

        return response()->json(['success' => true]);
    }

    // Update single link
    public function updateLink(Request $request)
    {
        $request->merge([
            'link' => $this->normalizeLink($request->link),
        ]);

        $request->validate([
            'id' => 'required|exists:dokumen_antikorupsi,id',
            'link' => 'nullable|url',
        ]);

        DokumenAntikorupsi::where('id', $request->id)
            ->where('desa_id', auth()->user()->desa_id)
            ->update(['link_drive' => $request->link]);

        return response()->json(['success' => true]);
    }

    private function normalizeLink(?string $link): ?string
    {
        $link = trim((string) $link);

        if ($link === '') {
            return null;
        }

        return preg_match('/^https?:\/\//i', $link) ? $link : 'https://' . $link;
    }

    private function nextUrutan(string $kategori, string $grup): int
    {
        return ((int) DokumenAntikorupsi::where('desa_id', auth()->user()->desa_id)
            ->where('kategori', $kategori)
            ->where('grup_indikator', $grup)
            ->max('urutan_tampil')) + 1;
    }

    private function normalizeHierarchy(string $kategori, string $grup): void
    {
        $items = DokumenAntikorupsi::where('desa_id', auth()->user()->desa_id)
            ->where('kategori', $kategori)
            ->where('grup_indikator', $grup)
            ->orderBy('urutan_tampil', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Pass 1: Compute resolved levels
        $resolvedLevels = [];
        $previousLevel = 0;
        foreach ($items as $index => $item) {
            $requestedLevel = min((int) ($item->level ?? 0), 4);
            $level = $index === 0 ? 0 : min($requestedLevel, $previousLevel + 1);
            $resolvedLevels[$index] = $level;
            $previousLevel = $level;
        }

        $topLevelCounter = 0;
        $childCounters = [];
        $lastItemByLevel = [];
        $previousLevel = 0;

        foreach ($items as $index => $item) {
            $level = $resolvedLevels[$index];

            $parentId = $level > 0 ? ($lastItemByLevel[$level - 1]->id ?? null) : null;
            $noUrut = null;
            $sub = null;

            if ($level === 0) {
                $topLevelCounter++;
                $noUrut = (string) $topLevelCounter;
                $childCounters = [];

                // Check if this level 0 item has children (if next item is level 1)
                $hasChildren = isset($resolvedLevels[$index + 1]) && $resolvedLevels[$index + 1] === 1;
                if ($hasChildren) {
                    $sub = 'a';
                }
            } else {
                $topAncestor = $lastItemByLevel[0] ?? null;
                $topLevelNumber = $topAncestor?->no_urut ?: (string) max($topLevelCounter, 1);

                if ($level === 1) {
                    // For level 1, parent uses 'a' if it has children, so child starts at 'b' (value 2)
                    $childCounters[$parentId] = ($childCounters[$parentId] ?? 1) + 1;
                    $noUrut = null; // "tanpa angka 2"
                    $sub = $this->numberToAlphabet($childCounters[$parentId]);
                } else {
                    $childCounters[$parentId] = ($childCounters[$parentId] ?? 0) + 1;
                    $parentNumber = $lastItemByLevel[$level - 1]->no_urut ?? $topLevelNumber;
                    $noUrut = $parentNumber . '.' . $childCounters[$parentId];
                }
            }

            foreach (array_keys($lastItemByLevel) as $trackedLevel) {
                if ($trackedLevel > $level) {
                    unset($lastItemByLevel[$trackedLevel]);
                }
            }

            $item->update([
                'urutan_tampil' => $index + 1,
                'parent_id' => $parentId,
                'level' => $level,
                'no_urut' => $noUrut,
                'sub' => $sub,
            ]);

            $lastItemByLevel[$level] = $item->fresh();
            $previousLevel = $level;
        }
    }

    private function numberToAlphabet(int $number): string
    {
        $result = '';

        while ($number > 0) {
            $number--;
            $result = chr(97 + ($number % 26)) . $result;
            $number = intdiv($number, 26);
        }

        return $result;
    }
}
