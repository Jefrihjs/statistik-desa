<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermohonanSuratDokumen extends Model
{
    protected $fillable = [
        'permohonan_surat_id',
        'jenis_surat_syarat_id',
        'nama_syarat',
        'file_path',
        'original_name',
    ];

    public function permohonan()
    {
        return $this->belongsTo(PermohonanSurat::class, 'permohonan_surat_id');
    }
}
