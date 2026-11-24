<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanSurat extends Model
{
    protected $fillable = [
        'desa_id',
        'jenis_surat_id',
        'kode_registrasi',
        'nama_pemohon',
        'nik',
        'no_hp',
        'email',
        'alamat',
        'keperluan',
        'status',
        'catatan_admin',
        'file_surat_jadi',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }

    public function dokumens()
    {
        return $this->hasMany(PermohonanSuratDokumen::class);
    }
}
