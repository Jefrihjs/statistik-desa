<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'unit'];

    // Satu indikator dimiliki oleh satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Satu indikator memiliki banyak data statistik (per tahun/desa)
    public function statistics()
    {
        return $this->hasMany(Statistic::class);
    }
}