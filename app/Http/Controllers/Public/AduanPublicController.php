<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Aduan;
use App\Models\Desa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AduanPublicController extends Controller
{
    public function create($slug)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        $userDesa = User::where('desa_id', $desa->id)
            ->where('role', 'desa')
            ->first();

        abort_if(! $userDesa || ! $userDesa->is_aduan_active, 404);

        return view('public.aduan.create', compact('desa'));
    }

    public function store(Request $request, $slug)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        $userDesa = User::where('desa_id', $desa->id)
            ->where('role', 'desa')
            ->first();

        abort_if(! $userDesa || ! $userDesa->is_aduan_active, 404);

        $validated = $request->validate([
            'jenis_identitas' => ['required', 'in:terbuka,rahasia,anonim'],

            'nama_pelapor' => [
                'nullable',
                'string',
                'max:255',
                'required_if:jenis_identitas,terbuka',
            ],

            'no_hp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],

            'kategori' => ['nullable', 'string', 'max:100'],
            'judul' => ['required', 'string', 'max:255'],
            'isi_aduan' => ['required', 'string'],
        ]);

        $jenisIdentitas = $validated['jenis_identitas'];

        $isIdentityHidden = in_array($jenisIdentitas, ['rahasia', 'anonim']);

        if ($jenisIdentitas === 'rahasia') {
            $validated['nama_pelapor'] = 'Dirahasiakan';
        }

        if ($jenisIdentitas === 'anonim') {
            $validated['nama_pelapor'] = 'Anonim';
            $validated['no_hp'] = null;
            $validated['email'] = null;
        }

        $aduan = Aduan::create([
            'desa_id' => $desa->id,
            'kode_aduan' => 'ADN-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),

            'jenis_identitas' => $jenisIdentitas,
            'is_identity_hidden' => $isIdentityHidden,

            'nama_pelapor' => $validated['nama_pelapor'] ?? 'Anonim',
            'no_hp' => $validated['no_hp'] ?? null,
            'email' => $validated['email'] ?? null,

            'kategori' => $validated['kategori'] ?? null,
            'judul' => $validated['judul'],
            'isi_aduan' => $validated['isi_aduan'],

            'status' => 'baru',
        ]);

        return redirect()
            ->route('public.aduan.success', [$desa->slug, $aduan->kode_aduan]);
    }

    public function success($slug, $kode)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        $aduan = Aduan::where('desa_id', $desa->id)
            ->where('kode_aduan', $kode)
            ->firstOrFail();

        return view('public.aduan.success', compact('desa', 'aduan'));
    }

    public function checkStatus($slug)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        $userDesa = User::where('desa_id', $desa->id)
            ->where('role', 'desa')
            ->first();

        abort_if(! $userDesa || ! $userDesa->is_aduan_active, 404);

        return view('public.aduan.check-status', compact('desa'));
    }

    public function showStatus(Request $request, $slug)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        $userDesa = User::where('desa_id', $desa->id)
            ->where('role', 'desa')
            ->first();

        abort_if(! $userDesa || ! $userDesa->is_aduan_active, 404);

        $validated = $request->validate([
            'kode_aduan' => ['required', 'string', 'max:50'],
        ]);

        $aduan = Aduan::where('desa_id', $desa->id)
            ->where('kode_aduan', strtoupper(trim($validated['kode_aduan'])))
            ->first();

        if (! $aduan) {
            return back()
                ->withInput()
                ->with('error', 'Kode aduan tidak ditemukan. Pastikan kode yang dimasukkan sudah benar.');
        }

        return view('public.aduan.status-result', compact('desa', 'aduan'));
    }
}