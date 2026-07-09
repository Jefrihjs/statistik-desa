<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkmRekomendasi extends Model
{
    protected $table = 'skm_rekomendasi';

        protected $fillable = [
        'desa_id',
        'kode_survey',
        'nomor_rekom',
        'tahun',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'catatan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tahun' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function responses()
    {
        return $this->hasMany(SkmResponse::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}