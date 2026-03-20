<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Indicator;

class StatistikSeeder extends Seeder
{
    public function run()
    {
        // 1. DEMOGRAFI
        $catDemografi = Category::updateOrCreate(['slug' => 'demografi'], ['name' => 'Demografi Umum', 'icon' => 'users']);
        foreach (['Kepala Keluarga', 'Laki-laki', 'Perempuan'] as $ind) {
            Indicator::updateOrCreate(['category_id' => $catDemografi->id, 'name' => $ind], ['unit' => ($ind == 'Kepala Keluarga' ? 'KK' : 'Jiwa')]);
        }

        // 2. PER TAHUN USIA
        $catUsiaDetail = Category::updateOrCreate(['slug' => 'usia-detail'], ['name' => 'Data Penduduk Per Tahun Usia', 'icon' => 'calendar']);
        for ($i = 0; $i <= 75; $i++) {
            $label = ($i == 75) ? 'Usia 75+' : 'Usia ' . $i;
            Indicator::updateOrCreate(['category_id' => $catUsiaDetail->id, 'name' => $label], ['unit' => 'Jiwa']);
        }

        // 3. KELOMPOK UMUR
        $catKelompokUsia = Category::updateOrCreate(['slug' => 'kelompok-usia'], ['name' => 'Kelompok Umur', 'icon' => 'chart-bar']);
        $kelompokUsia = ['0-4', '5-9', '10-14', '15-19', '20-24', '25-29', '30-34', '35-39', '40-44', '45-49', '50-54', '55-59', '60-64', '65-69', '70-74', '75+'];
        foreach ($kelompokUsia as $usia) {
            Indicator::updateOrCreate(['category_id' => $catKelompokUsia->id, 'name' => $usia], ['unit' => 'Jiwa']);
        }

        // 4. MATA PENCAHARIAN (Lengkap sesuai form desa)
        $catPekerjaan = Category::updateOrCreate(
            ['slug' => 'mata-pencaharian'], 
            ['name' => 'Mata Pencaharian', 'icon' => 'briefcase']
        );

        $pekerjaan = [
            'Petani', 'Buruh tani', 'Buruh migran perempuan', 'Buruh migran laki-laki', 
            'Pegawai Negeri Sipil', 'Pengrajin industri rumah tangga', 'Pedagang keliling', 
            'Peternak', 'Nelayan', 'Montir', 'Dokter swasta', 'Bidan swasta', 
            'Perawat swasta', 'Pembantu rumah tangga', 'TNI', 'POLRI', 'Pensiunan PNS', 
            'Pengusaha kecil dan menengah', 'Pengacara', 'Notaris', 'Dukun Kampung Terlatih', 
            'Jasa pengobatan alternative', 'Dosen swasta', 'Pengusaha besar', 'Arsitektur', 
            'Seniman/Artis', 'Karyawan perusahaan swasta', 'Karyawan perusahaan pemerintah', 
            'Buruh Pertambangan', 'Sopir', 'Honorer', 'Buruh Harian Lepas', 'Tukang Kayu', 
            'Tukang Jahit', 'Tukang cukur', 'Tukang Besi', 'Tukang Gali Sumur', 'Tukang Pijat', 
            'Pelajar', 'Mengurus Rumah Tangga'
        ];

        foreach ($pekerjaan as $p) {
            Indicator::updateOrCreate(
                ['category_id' => $catPekerjaan->id, 'name' => $p],
                ['unit' => 'Jiwa']
            );
        }

        // 5. PENDIDIKAN & STATUS SEKOLAH
        $catPendidikan = Category::updateOrCreate(['slug' => 'pendidikan'], ['name' => 'Pendidikan & Status Sekolah', 'icon' => 'academic-cap']);
        $pendidikan = [
            'Usia 3 – 6 tahun yang belum masuk TK', 'Usia 3 – 6 tahun yang sedang TK/Play Group',
            'Usia 7 – 18 tahun yang tidak pernah sekolah', 'Usia 7 – 18 tahun yang sedang sekolah',
            'Usia 18 – 56 tahun tidak pernah sekolah', 'Usia 18 – 56 thn pernah SD tetapi tidak tamat',
            'Tamat SD / sederajat', 'Jumlah usia 12 – 56 tahun tidak tamat SLTP',
            'Jumlah usia 18 – 56 tahun tidak tamat SLTA', 'Tamat SMP / sederajat', 'Tamat SMA / sederajat',
            'Tamat D-1 / sederajat', 'Tamat D-2 / sederajat', 'Tamat D-3 / sederajat',
            'Tamat S-1 / sederajat', 'Tamat S-2 / sederajat', 'Tamat S-3 / sederajat',
            'Tamat SLB A', 'Tamat SLB B', 'Tamat SLB C'
        ];
        foreach ($pendidikan as $pen) {
            Indicator::updateOrCreate(['category_id' => $catPendidikan->id, 'name' => $pen], ['unit' => 'Jiwa']);
        }

        // 6. AGAMA
        $catAgama = Category::updateOrCreate(['slug' => 'agama'], ['name' => 'Agama & Kepercayaan', 'icon' => 'heart']);
        $agama = ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Budha', 'Khonghucu', 'Kepercayaan Kepada Tuhan YME', 'Aliran Kepercayaan Lainnya'];
        foreach ($agama as $ag) {
            Indicator::updateOrCreate(['category_id' => $catAgama->id, 'name' => $ag], ['unit' => 'Jiwa']);
        }

        // 7. TENAGA KERJA
        $catTenagaKerja = Category::updateOrCreate(['slug' => 'tenaga-kerja'], ['name' => 'Tenaga Kerja', 'icon' => 'identification']);
        $tenagaKerja = ['Penduduk usia 0 – 6 tahun', 'Penduduk masih sekolah 7 – 18 tahun', 'Penduduk usia 18 – 56 tahun yang bekerja', 'Penduduk usia 18 – 56 tahun yang belum atau tidak bekerja', 'Penduduk usia 56 tahun ke atas'];
        foreach ($tenagaKerja as $tk) {
            Indicator::updateOrCreate(['category_id' => $catTenagaKerja->id, 'name' => $tk], ['unit' => 'Jiwa']);
        }

        // 8. ETNIS
        $catEtnis = Category::updateOrCreate(['slug' => 'etnis'], ['name' => 'Etnis / Suku Bangsa', 'icon' => 'globe-alt']);
        $etnis = ['Aceh', 'Batak', 'Nias', 'Mentawai', 'Melayu', 'Minang', 'Kubu', 'Anak Dalam', 'Badui', 'Betawi', 'Sunda', 'Jawa', 'Madura', 'Bali', 'Banjar', 'Dayak', 'Bugis', 'Makasar', 'Mandar', 'Sasak', 'Ambon', 'Minahasa', 'Flores', 'Papua', 'Timor', 'Sabu', 'Rote', 'Sumba', 'Ternate', 'Tolaki', 'Buton', 'Muna', 'Mikongga', 'Wanci', 'Alor', 'Benoa', 'Tunjung', 'Mbojo', 'Samawa', 'Asia', 'Afrika', 'Australia', 'China', 'Amerika', 'Eropa'];
        foreach ($etnis as $et) {
            Indicator::updateOrCreate(['category_id' => $catEtnis->id, 'name' => $et], ['unit' => 'Jiwa']);
        }

    } // Akhir fungsi run
} // Akhir class