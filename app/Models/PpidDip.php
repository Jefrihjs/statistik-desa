<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpidDip extends Model
{
    protected $fillable = [
        'desa_id',
        'kategori',
        'kelompok_informasi',
        'urutan',
        'judul_informasi',
        'ringkasan',
        'link_dokumen',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}