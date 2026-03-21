<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainTracker extends Model
{
    // Supaya bisa isi data lewat seeder
    protected $guarded = [];

    // Relasi ke tabel desa
    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}