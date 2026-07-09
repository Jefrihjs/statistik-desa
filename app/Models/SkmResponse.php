<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkmResponse extends Model
{
    protected $fillable = [
        'desa_id',
        'skm_rekomendasi_id',
        'jenis_kelamin',
        'usia',
        'pendidikan',
        'pekerjaan',
        'layanan_yang_dinilai',
        'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8', 'q9',
        'saran',
        'nilai_rata_rata',
    ];

    protected $casts = [
        'q1' => 'integer',
        'q2' => 'integer',
        'q3' => 'integer',
        'q4' => 'integer',
        'q5' => 'integer',
        'q6' => 'integer',
        'q7' => 'integer',
        'q8' => 'integer',
        'q9' => 'integer',
        'nilai_rata_rata' => 'float',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function rekomendasi()
    {
        return $this->belongsTo(SkmRekomendasi::class);
    }

    // Konversi nilai 1-4 ke IKM 25-100
    public function getIkmAttribute()
    {
        if ($this->nilai_rata_rata === null) return null;
        return round((($this->nilai_rata_rata - 1) / 3) * 75 + 25, 2);
    }
}