<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'header_color',
        'accent_color',
        'nama_desa',
        'kecamatan',
    ];

    public function statistics()
    {
        return $this->hasMany(Statistic::class, 'desa_id');
    }
}
