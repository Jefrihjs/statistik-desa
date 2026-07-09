<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkmQuestion extends Model
{
    protected $fillable = [
        'desa_id',
        'unsur',
        'pertanyaan',
        'urutan',
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