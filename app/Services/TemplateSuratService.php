<?php

namespace App\Services;

use App\Models\JenisSurat;

class TemplateSuratService
{
    public static function getDefaultTemplates(): array
    {
        return [
            [
                'nama_layanan' => 'Surat Keterangan Ahli Waris',
                'catatan' => null,
                'syarat' => [
                    ['nama' => 'Surat pengantar RT dan Dusun', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KK dan KTP para ahli waris', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi akta kematian pewaris', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP 2 orang saksi', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => '2 lembar materai Rp10.000 (dibawa saat penandatanganan)', 'tipe' => 'info_fisik', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Akta Kelahiran',
                'catatan' => 'Harap membawa berkas asli saat pengambilan surat di kantor desa.',
                'syarat' => [
                    ['nama' => 'Kartu Keluarga asli (scan/foto)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'KTP asli orang tua bayi (scan/foto)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Surat keterangan lahir asli dari bidan/RS', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Buku nikah orang tua', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'KTP 2 orang saksi', 'tipe' => 'file', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Proposal Bantuan Berobat',
                'catatan' => null,
                'syarat' => [
                    ['nama' => 'Fotokopi KTP pasien', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP pendamping (maksimal 2 orang)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi kartu BPJS pasien', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi Kartu Keluarga pasien', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Surat rujukan dari Rumah Sakit Umum Daerah (RSUD)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Surat Keterangan Tidak Mampu (masuk dalam DTSEN Desil 1–4)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Foto kondisi pasien', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Buku rekening pasien', 'tipe' => 'file', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Tinggal Sementara (Penduduk Non Permanen)',
                'catatan' => 'Surat keterangan ini wajib diperbarui 1 bulan sekali.',
                'syarat' => [
                    ['nama' => 'Pengantar RT setempat', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi Kartu Keluarga', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi buku nikah (jika sudah menikah)', 'tipe' => 'file', 'wajib' => false],
                    ['nama' => 'Pas foto ukuran 3x4', 'tipe' => 'file', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Domisili',
                'catatan' => null,
                'syarat' => [
                    ['nama' => 'Pengantar RT setempat', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi Kartu Keluarga', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP', 'tipe' => 'file', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Hibah',
                'catatan' => null,
                'syarat' => [
                    ['nama' => 'Surat pengantar RT dan Dusun', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi alas hak tanah (REKOM/SKT/Sertifikat)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KK dan KTP pemberi hibah', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KK dan KTP penerima hibah', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP saksi pemberi dan penerima hibah', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => '2 lembar materai Rp10.000 (dibawa saat penandatanganan)', 'tipe' => 'info_fisik', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Jual Beli Tanah',
                'catatan' => 'Wajib membawa dokumen asli alas hak tanah saat verifikasi fisik di kantor desa.',
                'syarat' => [
                    ['nama' => 'Surat pengantar RT dan Dusun', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Scan/fotokopi alas hak tanah (REKOM/SKT/Sertifikat)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Kwitansi jual beli tanah', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Bukti lunas/SPPT PBB tanah', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KK dan KTP penjual', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KK dan KTP pembeli', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP saksi penjual dan pembeli', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP 2 orang ahli waris penjual', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => '2 lembar materai Rp10.000 (dibawa saat penandatanganan)', 'tipe' => 'info_fisik', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Kartu Keluarga Rumah Tangga Baru',
                'catatan' => null,
                'syarat' => [
                    ['nama' => 'Kartu Keluarga asli suami (scan/foto)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Kartu Keluarga istri', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'KTP asli suami dan istri (scan/foto)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Surat keterangan pindah dari daerah asal (jika dari luar desa)', 'tipe' => 'file', 'wajib' => false],
                    ['nama' => 'Ijazah terakhir suami dan istri', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Surat keterangan pekerjaan suami/istri', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi buku nikah', 'tipe' => 'file', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Kehilangan',
                'catatan' => 'Khusus surat pengantar kehilangan KTP atau Buku Tabungan Bank.',
                'syarat' => [
                    ['nama' => 'Fotokopi Kartu Keluarga', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP (atau biodata penduduk)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi buku bank / bukti nomor rekening (jika kehilangan buku bank)', 'tipe' => 'file', 'wajib' => false],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Kematian',
                'catatan' => null,
                'syarat' => [
                    ['nama' => 'Kartu Keluarga asli (scan/foto)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'KTP asli almarhum/almarhumah (scan/foto)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'KTP suami/istri almarhum (jika ada)', 'tipe' => 'file', 'wajib' => false],
                    ['nama' => 'Surat keterangan kematian dari rumah sakit (jika meninggal di RS)', 'tipe' => 'file', 'wajib' => false],
                    ['nama' => 'Akta kelahiran almarhum/almarhumah', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'KTP 2 orang saksi', 'tipe' => 'file', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Pindah Datang (Masuk Desa)',
                'catatan' => 'Khusus penduduk yang pindah masuk ke wilayah desa.',
                'syarat' => [
                    ['nama' => 'Pengantar RT setempat', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'KTP asli (scan/foto)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Kartu Keluarga asli / SKPWNI daerah asal', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi akta kelahiran', 'tipe' => 'file', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Pindah Keluar',
                'catatan' => 'Wajib mencantumkan alamat lengkap tujuan pindah pada kolom Keperluan/Tujuan.',
                'syarat' => [
                    ['nama' => 'Kartu Keluarga asli (scan/foto)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'KTP asli (scan/foto)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi akta kelahiran', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Mencantumkan alamat lengkap tujuan pindah di form permohonan', 'tipe' => 'info_fisik', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Pemanfaatan Tanah (SKPT)',
                'catatan' => 'Tanah harus sudah terpasang tanda batas (patok) dan dalam kondisi Clear and Clean (CNC).',
                'syarat' => [
                    ['nama' => 'Surat pengantar RT dan Dusun', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KK dan KTP pemohon', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Surat pernyataan dan surat permohonan', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Sketsa peta bidang tanah', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Foto patok dan lahan berkoordinat', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Dasar kepemilikan tanah (jual beli/REKOM/bukti lainnya)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP pemilik tanah tetangga yang berbatasan', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => '2 lembar materai Rp10.000 (dibawa saat penandatanganan)', 'tipe' => 'info_fisik', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Pengantar Nikah',
                'catatan' => 'Untuk dokumen KK, KTP, Akta Kelahiran, dan Pas Foto 2x3 wajib melampirkan berkas calon laki-laki dan perempuan masing-masing.',
                'syarat' => [
                    ['nama' => 'Fotokopi Kartu Keluarga (calon laki-laki & perempuan)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP (calon laki-laki & perempuan)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi akta kelahiran (calon laki-laki & perempuan)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Pas foto ukuran 2x3 (calon laki-laki & perempuan)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Akta cerai (bagi yang berstatus cerai hidup)', 'tipe' => 'file', 'wajib' => false],
                    ['nama' => 'Akta kematian pasangan (bagi yang berstatus cerai mati)', 'tipe' => 'file', 'wajib' => false],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Penghasilan',
                'catatan' => null,
                'syarat' => [
                    ['nama' => 'Fotokopi Kartu Keluarga', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Slip gaji dari perusahaan (jika bekerja di perusahaan/PT)', 'tipe' => 'file', 'wajib' => false],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Perjanjian Damai',
                'catatan' => null,
                'syarat' => [
                    ['nama' => 'Surat pengantar RT dan Dusun', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KK dan KTP pihak pertama', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KK dan KTP pihak kedua', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Surat kronologi kejadian', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP saksi pihak pertama dan pihak kedua', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => '2 lembar materai Rp10.000 (dibawa saat penandatanganan)', 'tipe' => 'info_fisik', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Rekomendasi Tanah (SKRT)',
                'catatan' => 'Tanah harus sudah terpasang tanda batas (patok) dan dalam kondisi Clear and Clean (CNC).',
                'syarat' => [
                    ['nama' => 'Surat pengantar RT dan Dusun', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KK dan KTP pemohon', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Sketsa peta bidang tanah', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Foto patok dan lahan berkoordinat', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Dasar kepemilikan tanah (jual beli/bukti lainnya)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP pemilik tanah tetangga yang berbatasan', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => '1 lembar materai Rp10.000 (dibawa saat penandatanganan)', 'tipe' => 'info_fisik', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Catatan Kepolisian (SKCK)',
                'catatan' => 'Surat pengantar dari desa untuk pengurusan SKCK di Polsek/Polres.',
                'syarat' => [
                    ['nama' => 'Fotokopi Kartu Keluarga', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi KTP', 'tipe' => 'file', 'wajib' => true],
                ],
            ],
            [
                'nama_layanan' => 'Surat Keterangan Usaha (SKU)',
                'catatan' => 'Surat Keterangan Usaha ini berlaku selama 3 bulan sejak tanggal diterbitkan.',
                'syarat' => [
                    ['nama' => 'Fotokopi Kartu Tanda Penduduk (KTP)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Fotokopi Kartu Keluarga (KK)', 'tipe' => 'file', 'wajib' => true],
                    ['nama' => 'Foto tempat usaha / produk usaha', 'tipe' => 'file', 'wajib' => true],
                ],
            ],
        ];
    }

    public static function seedForDesa(int $desaId): int
    {
        $inserted = 0;
        foreach (self::getDefaultTemplates() as $idx =>$item) {
            $exists = JenisSurat::where('desa_id',$desaId)
                ->where('nama_layanan', $item['nama_layanan'])
                ->exists();

            if ($exists) {
                continue;
            }

            $jenis = JenisSurat::create([
                'desa_id' => $desaId,
                'nama_layanan' => $item['nama_layanan'],
                'catatan' => $item['catatan'],
                'is_active' => true,
                'urutan' => $idx + 1,
            ]);

            foreach ($item['syarat'] as$sIdx => $syarat) {$jenis->syarats()->create([
                    'nama_syarat' => $syarat['nama'],
                    'tipe_input' => $syarat['tipe'],
                    'is_wajib' => $syarat['wajib'],
                    'urutan' => $sIdx + 1,
                ]);
            }

            $inserted++;
        }

        return $inserted;
    }
}
