<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Statistic extends Model
{
    use HasFactory;

    protected $fillable = ['desa_id', 'indicator_id', 'gender', 'year', 'value'];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }
}