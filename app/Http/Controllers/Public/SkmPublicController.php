<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use App\Models\SkmRekomendasi;
use App\Models\SkmResponse;
use Illuminate\Http\Request;

class SkmPublicController extends Controller
{
    public function create($slug)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        $rekom = SkmRekomendasi::where('desa_id', $desa->id)
            ->where('is_active', true)
            ->first();

        if (!$rekom) {
            return view('public.skm.closed', compact('desa'));
        }

        $questions = [
            ['id' => 1, 'text' => 'Bagaimana pendapat Saudara tentang kesesuaian persyaratan pelayanan dengan jenis pelayanannya', 'options' => ['Tidak sesuai', 'Kurang sesuai', 'Sesuai', 'Sangat sesuai']],
            ['id' => 2, 'text' => 'Bagaimana pemahaman Saudara tentang kemudahan prosedur pelayanan di unit ini', 'options' => ['Tidak mudah', 'Kurang mudah', 'Mudah', 'Sangat mudah']],
            ['id' => 3, 'text' => 'Bagaimana pendapat Saudara tentang kecepatan waktu memberikan pelayanan', 'options' => ['Tidak cepat', 'Kurang cepat', 'Cepat', 'Sangat cepat']],
            ['id' => 4, 'text' => 'Bagaimana pendapat Saudara tentang kewajaran biaya/tarif dalam pelayanan', 'options' => ['Sangat mahal', 'Cukup mahal', 'Murah', 'Gratis']],
            ['id' => 5, 'text' => 'Bagaimana pendapat Saudara tentang kesesuaian produk pelayanan antara yang tercantum dalam standar pelayanan dengan hasil yang diberikan', 'options' => ['Tidak sesuai', 'Kurang sesuai', 'Sesuai', 'Sangat sesuai']],
            ['id' => 6, 'text' => 'Bagaimana pendapat Saudara tentang kompetensi/kemampuan petugas dalam pelayanan', 'options' => ['Tidak kompeten', 'Kurang kompeten', 'Kompeten', 'Sangat kompeten']],
            ['id' => 7, 'text' => 'Bagaimana pendapat Saudara perilaku petugas dalam pelayanan terkait kesopanan dan keramahan', 'options' => ['Tidak sopan dan ramah', 'Kurang sopan dan ramah', 'Sopan dan ramah', 'Sangat sopan dan ramah']],
            ['id' => 8, 'text' => 'Bagaimana pendapat Saudara tentang kualitas sarana dan prasarana', 'options' => ['Buruk', 'Cukup', 'Baik', 'Sangat baik']],
            ['id' => 9, 'text' => 'Bagaimana pendapat Saudara tentang penanganan pengaduan pengguna layanan', 'options' => ['Tidak ada', 'Ada tetapi tidak berfungsi', 'Berfungsi kurang maksimal', 'Dikelola dengan baik']],
        ];

        return view('public.skm.form', compact('desa', 'rekom', 'questions'));
    }

    public function store(Request $request, $slug)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        $rekom = SkmRekomendasi::where('desa_id', $desa->id)
            ->where('is_active', true)
            ->firstOrFail();

        $request->validate([
            'jenis_kelamin' => 'required|in:L,P',
            'usia' => 'required|string|max:20',
            'pendidikan' => 'required|string|max:50',
            'pekerjaan' => 'required|string|max:100',
            'pekerjaan_lainnya' => 'nullable|string|max:100',
            'layanan_yang_dinilai' => 'required|string|max:255',
            'q1' => 'required|in:1,2,3,4',
            'q2' => 'required|in:1,2,3,4',
            'q3' => 'required|in:1,2,3,4',
            'q4' => 'required|in:1,2,3,4',
            'q5' => 'required|in:1,2,3,4',
            'q6' => 'required|in:1,2,3,4',
            'q7' => 'required|in:1,2,3,4',
            'q8' => 'required|in:1,2,3,4',
            'q9' => 'required|in:1,2,3,4',
            'saran' => 'nullable|string',
        ]);

        $totalNilai = $request->q1 + $request->q2 + $request->q3 + $request->q4 +
                       $request->q5 + $request->q6 + $request->q7 + $request->q8 + $request->q9;

        $nilaiRataRata = round($totalNilai / 9, 2);
        $pekerjaanFinal = $request->pekerjaan === 'Lainnya' ? $request->pekerjaan_lainnya : $request->pekerjaan;

        SkmResponse::create([
            'desa_id' => $desa->id,
            'skm_rekomendasi_id' => $rekom->id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'usia' => $request->usia,
            'pendidikan' => $request->pendidikan,
            'pekerjaan' => $pekerjaanFinal,
            'layanan_yang_dinilai' => $request->layanan_yang_dinilai,
            'q1' => $request->q1,
            'q2' => $request->q2,
            'q3' => $request->q3,
            'q4' => $request->q4,
            'q5' => $request->q5,
            'q6' => $request->q6,
            'q7' => $request->q7,
            'q8' => $request->q8,
            'q9' => $request->q9,
            'saran' => $request->saran,
            'nilai_rata_rata' => $nilaiRataRata,
        ]);

        return redirect()->route('public.skm.success', $desa->slug);
    }

    public function success($slug)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();
        return view('public.skm.success', compact('desa'));
    }

    public function hasil($slug)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        $unsurNames = [
            'Persyaratan', 'Prosedur', 'Waktu Pelayanan', 'Biaya/Tarif',
            'Produk Pelayanan', 'Kompetensi Petugas', 'Perilaku Petugas',
            'Sarana & Prasarana', 'Penanganan Pengaduan'
        ];

        $rekomendasi = SkmRekomendasi::where('desa_id', $desa->id)
            ->withCount('responses')
            ->orderBy('tahun', 'desc')
            ->get();

        $stats = [];

        foreach ($rekomendasi as $rek) {
            if ($rek->responses_count === 0) continue;

            $responses = $rek->responses;
            $totalResponden = $responses->count();

            $unsurStats = [];
            $totalAll = 0;

            for ($i = 1; $i <= 9; $i++) {
                $avg = round($responses->avg('q' . $i), 2);
                $ikmUnsur = round((($avg - 1) / 3) * 75 + 25, 2);
                $grade = $this->getGrade($ikmUnsur);

                $unsurStats[] = [
                    'name' => $unsurNames[$i - 1],
                    'avg' => $avg,
                    'ikm' => $ikmUnsur,
                    'grade' => $grade,
                    'gradeLabel' => $this->getGradeLabel($ikmUnsur),
                ];

                $totalAll += $avg;
            }

            $avgTotal = round($totalAll / 9, 2);
            $ikmTotal = round((($avgTotal - 1) / 3) * 75 + 25, 2);
            $mutuTotal = $this->getMutu($ikmTotal);

            $periodeMulai = $rek->tanggal_mulai;
            $periodeSelesai = $rek->tanggal_selesai;

            $stats[] = [
                'rekom' => $rek,
                'totalResponden' => $totalResponden,
                'unsurStats' => $unsurStats,
                'avgTotal' => $avgTotal,
                'ikmTotal' => $ikmTotal,
                'mutuTotal' => $mutuTotal,
                'periodeMulai' => $periodeMulai,
                'periodeSelesai' => $periodeSelesai,
            ];
        }

        $latest = $stats[0] ?? null;
        $previous = $stats[1] ?? null;
        $perbedaan = null;

        if ($latest && $previous) {
            $perbedaan = round($latest['ikmTotal'] - $previous['ikmTotal'], 2);
        }

        return view('public.skm.hasil', compact(
            'desa', 'stats', 'latest', 'previous', 'perbedaan', 'unsurNames'
        ));
    }

        public function cetakHasil($slug, $id)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        $unsurNames = [
            'Persyaratan', 'Prosedur', 'Waktu Pelayanan', 'Biaya/Tarif',
            'Produk Pelayanan', 'Kompetensi Petugas', 'Perilaku Petugas',
            'Sarana & Prasarana', 'Penanganan Pengaduan'
        ];

        $rek = SkmRekomendasi::where('id', $id)
            ->where('desa_id', $desa->id)
            ->firstOrFail();

        $responses = $rek->responses;

        if ($responses->isEmpty()) {
            abort(404, 'Belum ada data survei untuk periode ini.');
        }

        $totalResponden = $responses->count();
        $unsurStats = [];
        $totalAll = 0;

        for ($i = 1; $i <= 9; $i++) {
            $avg = round($responses->avg('q' . $i), 2);
            $ikmUnsur = round((($avg - 1) / 3) * 75 + 25, 2);
            $grade = $this->getGrade($ikmUnsur);

            $unsurStats[] = [
                'name' => $unsurNames[$i - 1],
                'avg' => $avg,
                'ikm' => $ikmUnsur,
                'grade' => $grade,
                'gradeLabel' => $this->getGradeLabel($ikmUnsur),
            ];

            $totalAll += $avg;
        }

        $avgTotal = round($totalAll / 9, 2);
        $ikmTotal = round((($avgTotal - 1) / 3) * 75 + 25, 2);
        $mutuTotal = $this->getMutu($ikmTotal);
        $mutuGrade = $this->getGrade($ikmTotal);

        $periodeMulai = $rek->tanggal_mulai;
        $periodeSelesai = $rek->tanggal_selesai;

        // Distribusi
        $genderL = $responses->where('jenis_kelamin', 'L')->count();
        $genderP = $responses->where('jenis_kelamin', 'P')->count();

        $pendidikanList = ['SD', 'SMP', 'SMA', 'D III', 'S1', 'S2', 'S3'];
        $pendidikanDist = [];
        foreach ($pendidikanList as $p) {
            $pendidikanDist[$p] = $responses->where('pendidikan', $p)->count();
        }

        // Distribusi jawaban per unsur
        $distribusi = [];
        for ($i = 1; $i <= 9; $i++) {
            $distribusi[$i] = [
                1 => $responses->where('q' . $i, 1)->count(),
                2 => $responses->where('q' . $i, 2)->count(),
                3 => $responses->where('q' . $i, 3)->count(),
                4 => $responses->where('q' . $i, 4)->count(),
            ];
        }

        return view('public.skm.hasil-cetak', compact(
            'desa', 'rek', 'totalResponden', 'unsurStats', 'avgTotal',
            'ikmTotal', 'mutuTotal', 'mutuGrade',
            'periodeMulai', 'periodeSelesai', 'distribusi',
            'genderL', 'genderP', 'pendidikanDist'
        ));
    }

    private function getGrade($ikm)
    {
        if ($ikm >= 88.31) return 'A';
        if ($ikm >= 76.61) return 'B';
        if ($ikm >= 62.51) return 'C';
        return 'D';
    }

    private function getGradeLabel($ikm)
    {
        if ($ikm >= 88.31) return 'Sangat Baik';
        if ($ikm >= 76.61) return 'Baik';
        if ($ikm >= 62.51) return 'Kurang Baik';
        return 'Tidak Baik';
    }

    private function getMutu($ikm)
    {
        if ($ikm >= 88.31) return 'A - Sangat Baik';
        if ($ikm >= 76.61) return 'B - Baik';
        if ($ikm >= 62.51) return 'C - Kurang Baik';
        return 'D - Tidak Baik';
    }

            public function detailResponden($slug, $id)
    {
        $desa = Desa::where('slug', $slug)->firstOrFail();

        $rek = SkmRekomendasi::where('id', $id)
            ->where('desa_id', $desa->id)
            ->firstOrFail();

        $responses = $rek->responses()->get();

        if ($responses->isEmpty()) {
            abort(404, 'Belum ada data responden untuk periode ini.');
        }

        $totalResponden = $responses->count();

        // IKM Total
        $avgTotal = round($responses->avg('nilai_rata_rata'), 2);
        $ikmTotal = round((($avgTotal - 1) / 3) * 75 + 25, 2);
        $mutuTotal = $this->getMutu($ikmTotal);

        // Periode
        $periodeMulai = $rek->tanggal_mulai;
        $periodeSelesai = $rek->tanggal_selesai;

        // --- DISTRIBUSI JENIS KELAMIN ---
        $genderDist = [
            ['label' => 'Laki-laki', 'count' => $responses->where('jenis_kelamin', 'L')->count()],
            ['label' => 'Perempuan', 'count' => $responses->where('jenis_kelamin', 'P')->count()],
        ];

        // --- DISTRIBUSI USIA ---
        $ageGroups = [
            ['label' => '≤ 20 tahun', 'min' => 0, 'max' => 20],
            ['label' => '> 20 – 30 tahun', 'min' => 21, 'max' => 30],
            ['label' => '> 30 – 40 tahun', 'min' => 31, 'max' => 40],
            ['label' => '> 40 tahun', 'min' => 41, 'max' => 999],
        ];
        $ageDist = [];
        foreach ($ageGroups as $g) {
            $count = $responses->filter(function ($r) use ($g) {
                $age = (int) $r->usia;
                return $age >= $g['min'] && $age <= $g['max'];
            })->count();
            $ageDist[] = ['label' => $g['label'], 'count' => $count];
        }

        // --- DISTRIBUSI PENDIDIKAN ---
        $pendidikanOptions = ['SD', 'SMP', 'SMA', 'D III', 'S1', 'S2', 'S3'];
        $pendidikanDist = [];
        foreach ($pendidikanOptions as $p) {
            $count = $responses->where('pendidikan', $p)->count();
            if ($count > 0) {
                $pendidikanDist[] = ['label' => $p, 'count' => $count];
            }
        }

        // --- DISTRIBUSI PEKERJAAN ---
        $pekerjaanDist = $responses->groupBy('pekerjaan')->map(function ($group) {
            return $group->count();
        })->sortDesc()->map(function ($count, $label) {
            return ['label' => $label, 'count' => $count];
        })->values()->toArray();

        // --- DISTRIBUSI LAYANAN ---
        $layananDist = $responses->groupBy('layanan_yang_dinilai')->map(function ($group) {
            return $group->count();
        })->sortDesc()->map(function ($count, $label) {
            return ['label' => str_replace('Jasa Layanan ', '', $label), 'count' => $count];
        })->values()->toArray();

        // --- NILAI PER UNSUR (untuk tabel bawah) ---
        $unsurNames = [
            'Persyaratan', 'Prosedur', 'Waktu Pelayanan', 'Biaya/Tarif',
            'Produk Pelayanan', 'Kompetensi Petugas', 'Perilaku Petugas',
            'Sarana & Prasarana', 'Penanganan Pengaduan'
        ];

        $unsurStats = [];
        for ($i = 1; $i <= 9; $i++) {
            $avg = round($responses->avg('q' . $i), 2);
            $ikmUnsur = round((($avg - 1) / 3) * 75 + 25, 2);
            $unsurStats[] = [
                'name' => $unsurNames[$i - 1],
                'avg' => $avg,
                'ikm' => $ikmUnsur,
                'grade' => $this->getGrade($ikmUnsur),
                'gradeLabel' => $this->getGradeLabel($ikmUnsur),
            ];
        }

        return view('public.skm.hasil-responden', compact(
            'desa', 'rek', 'responses', 'totalResponden',
            'ikmTotal', 'mutuTotal', 'avgTotal',
            'periodeMulai', 'periodeSelesai',
            'genderDist', 'ageDist', 'pendidikanDist', 'pekerjaanDist', 'layananDist',
            'unsurStats'
        ));
    }
}