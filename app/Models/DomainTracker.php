<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainTracker extends Model
{
    protected $guarded = [];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }
}