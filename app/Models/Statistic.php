<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statistic extends Model
{
    use HasFactory;

    // Pastikan semua kolom ini ada agar updateOrCreate berhasil
    protected $fillable = ['desa_id', 'indicator_id', 'gender', 'year', 'value'];

    /**
     * Relasi ke Indikator
     */
    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    /**
     * Relasi ke Desa (PENTING: Tambahkan ini agar error hilang)
     */
    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }
}