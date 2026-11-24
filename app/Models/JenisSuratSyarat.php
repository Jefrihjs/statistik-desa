<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSuratSyarat extends Model
{
    protected $fillable = [
        'jenis_surat_id',
        'nama_syarat',
        'tipe_input',
        'is_wajib',
        'urutan',
    ];

    protected $casts = [
        'is_wajib' => 'boolean',
    ];

    public function jenisSurat()
    {
        return $this->belongsTo(JenisSurat::class);
    }
}
