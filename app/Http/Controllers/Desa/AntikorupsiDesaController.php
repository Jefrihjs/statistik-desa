<?php

namespace App\Http\Controllers\Desa;

use App\Http\Controllers\Controller;
use App\Models\DokumenAntikorupsi;
use Illuminate\Http\Request;

class AntikorupsiDesaController extends Controller
{
    public function index()
    {
        // KUNCI UTAMA: Ambil desa_id dari user yang sedang login, bukan user_id individu
        $desaId = auth()->user()->desa_id;
        abort_if(!$desaId, 404, 'Akun Anda belum terikat dengan entitas Desa manapun.');

        // Cek apakah desa ini sudah memiliki record data indikator di database
        $cekData = DokumenAntikorupsi::where('desa_id', $desaId)->exists();

        // Jika belum ada, kita Auto-Generate data default khusus untuk desa ini
        if (!$cekData) {
            $this->generateDefaultData($desaId);
        }

        // Ambil dokumen yang HANYA dimiliki oleh desa yang bersangkutan
        $dokumen = DokumenAntikorupsi::where('desa_id', $desaId)->get();

        $data = [
            'tatalaksana' => $dokumen->where('kategori', 'tatalaksana')->groupBy('grup_indikator'),
            'pengawasan'  => $dokumen->where('kategori', 'pengawasan')->groupBy('grup_indikator'),
            'pelayanan'   => $dokumen->where('kategori', 'pelayanan')->groupBy('grup_indikator'),
            'partisipasi' => $dokumen->where('kategori', 'partisipasi')->groupBy('grup_indikator'),
            'kearifan'    => $dokumen->where('kategori', 'kearifan')->groupBy('grup_indikator'),
        ];

        $masterGrupList = \App\Models\MasterGrupAntikorupsi::all();

        return view('desa.antikorupsi.index', compact('data', 'masterGrupList'));
    }

    // Fungsi untuk menyimpan perubahan Link Drive secara massal
    public function update(Request $request)
    {
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

        return redirect()->back()->with('success', 'Tautan Google Drive berhasil disimpan!');
    }

    // --- FUNGSI AUTO-GENERATE DATA DEFAULT PER DESA ---
    private function generateDefaultData($desaId)
    {
        $masterData = [
            // TATA LAKSANA
            ['kategori' => 'tatalaksana', 'grup_indikator' => '1. Perdes/SOP tentang Perencanaan, Pelaksanaan, Penatausahaan, dan Pertanggungjawaban APBDes', 'no_urut' => '1', 'sub' => '', 'nama_dokumen' => 'RPJMDes'],
            ['kategori' => 'tatalaksana', 'grup_indikator' => '1. Perdes/SOP tentang Perencanaan, Pelaksanaan, Penatausahaan, dan Pertanggungjawaban APBDes', 'no_urut' => '2', 'sub' => 'a', 'nama_dokumen' => 'RKPDes Tahun Berjalan'],
            ['kategori' => 'tatalaksana', 'grup_indikator' => '1. Perdes/SOP tentang Perencanaan, Pelaksanaan, Penatausahaan, dan Pertanggungjawaban APBDes', 'no_urut' => '3', 'sub' => 'a', 'nama_dokumen' => 'APBDes Tahun Berjalan'],
            ['kategori' => 'tatalaksana', 'grup_indikator' => '2. SOP Mengenai Mekanisme Pengawasan dan Evaluasi Kinerja', 'no_urut' => '1', 'sub' => '', 'nama_dokumen' => 'SOTK Desa, Tupoksi Masing-Masing Kaur'],
            ['kategori' => 'tatalaksana', 'grup_indikator' => '3. Perdes Tentang Pengendalian Gratifikasi', 'no_urut' => '1', 'sub' => '', 'nama_dokumen' => 'Perdes/Keputusan Kades Tentang Pengendalian Gratifikasi'],
            
            // PENGAWASAN
            ['kategori' => 'pengawasan', 'grup_indikator' => '1. Kegiatan Pengawasan dan Evaluasi Kinerja', 'no_urut' => '1', 'sub' => '', 'nama_dokumen' => 'Undangan Kegiatan Pengawasan dan Evaluasi'],
            ['kategori' => 'pengawasan', 'grup_indikator' => '2. Tindak Lanjut Hasil Pengawasan', 'no_urut' => '1', 'sub' => '', 'nama_dokumen' => 'Laporan Hasil Pemeriksaan (LHP) Inspektorat/BPD'],
            
            // PELAYANAN PUBLIK
            ['kategori' => 'pelayanan', 'grup_indikator' => '1. Standar Pelayanan Minimal Desa', 'no_urut' => '1', 'sub' => '', 'nama_dokumen' => 'Buku Standar Pelayanan Minimal Desa'],
            ['kategori' => 'pelayanan', 'grup_indikator' => '3. Keterbukaan Information Publik', 'no_urut' => '1', 'sub' => '', 'nama_dokumen' => 'SK PPID Desa'],

            // PARTISIPASI
            ['kategori' => 'partisipasi', 'grup_indikator' => '1. Partisipasi Masyarakat dalam Perencanaan', 'no_urut' => '1', 'sub' => '', 'nama_dokumen' => 'Undangan & Daftar Hadir Musrenbangdes'],

            // KEARIFAN LOKAL
            ['kategori' => 'kearifan', 'grup_indikator' => '1. Budaya Lokal/Hukum Adat', 'no_urut' => '1', 'sub' => '', 'nama_dokumen' => 'Dokumen Hukum Adat / Budaya Lokal terkait Nilai Kejujuran'],
        ];

        foreach ($masterData as $item) {
            DokumenAntikorupsi::create([
                'desa_id'        => $desaId, // Disimpan ke desa_id agar terelasi ke tabel desas bapak
                'kategori'       => $item['kategori'],
                'grup_indikator' => $item['grup_indikator'],
                'no_urut'        => $item['no_urut'],
                'sub'            => $item['sub'],
                'nama_dokumen'   => $item['nama_dokumen'],
                'link_drive'     => null
            ]);
        }
    }

    // Fungsi Tambah Indikator Baru Mandiri oleh Desa
    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required',
            'grup_indikator' => 'required',
            'nama_dokumen' => 'required',
        ]);

        DokumenAntikorupsi::create([
            'desa_id'        => auth()->user()->desa_id, // Disimpan berdasarkan desa_id user login
            'kategori'       => $request->kategori,
            'grup_indikator' => $request->grup_indikator,
            'no_urut'        => $request->no_urut,
            'sub'            => $request->sub,
            'nama_dokumen'   => $request->nama_dokumen,
            'link_drive'     => $request->link_drive,
        ]);

        return redirect()->back()->with('success', 'Indikator baru berhasil ditambahkan!');
    }

    // Fungsi Hapus Indikator
    public function destroy($id)
    {
        // Pastikan user desa hanya berhak menghapus data milik desanya sendiri
        $dokumen = DokumenAntikorupsi::where('id', $id)
            ->where('desa_id', auth()->user()->desa_id)
            ->firstOrFail();
            
        $dokumen->delete();

        return redirect()->back()->with('success', 'Indikator berhasil dihapus!');
    }

    // Fungsi Edit Detail Indikator
    public function editData(Request $request, $id)
    {
        $request->validate([
            'kategori' => 'required',
            'grup_indikator' => 'required',
            'nama_dokumen' => 'required',
        ]);

        $dokumen = DokumenAntikorupsi::where('id', $id)
            ->where('desa_id', auth()->user()->desa_id)
            ->firstOrFail();

        $dokumen->update([
            'kategori'       => $request->kategori,
            'grup_indikator' => $request->grup_indikator,
            'no_urut'        => $request->no_urut,
            'sub'            => $request->sub,
            'nama_dokumen'   => $request->nama_dokumen,
            'link_drive'     => $request->link_drive,
        ]);

        return redirect()->back()->with('success', 'Detail indikator berhasil diperbarui!');
    }
}