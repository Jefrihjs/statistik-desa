<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $fillable = [
        'desa_id',
        'nama_layanan',
        'kode_surat',
        'catatan',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function syarats()
    {
        return $this->hasMany(JenisSuratSyarat::class)->orderBy('urutan')->orderBy('id');
    }

    public function permohonans()
    {
        return $this->hasMany(PermohonanSurat::class);
    }
}
