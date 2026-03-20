<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'is_active',
        'sort_order',
    ];

    // Tambahkan ini: satu kategori memiliki banyak indikator
    public function indicators()
    {
        return $this->hasMany(\App\Models\Indicator::class, 'category_id');
    }
}