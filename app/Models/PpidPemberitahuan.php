<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpidPemberitahuan extends Model
{
    protected $fillable = [
        'desa_id',
        'ppid_permohonan_id',
        'status_informasi',
        'penguasaan_informasi',
        'nama_badan_publik_lain',
        'bentuk_fisik',
        'biaya_salinan',
        'biaya_kirim',
        'biaya_lain',
        'total_biaya',
        'waktu_penyediaan',
        'penjelasan_penghitaman',
        'alasan_penolakan',
        'catatan_penolakan',
        'pasal_17_huruf',
        'pasal_uu_lainnya',
        'rincian_informasi_ditolak',
        'hasil_uji_konsekuensi',
    ];

    public function permohonan()
    {
        return $this->belongsTo(PpidPermohonan::class, 'ppid_permohonan_id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}