<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesaItemHide extends Model
{
    protected $fillable = ['desa_id', 'hideable_type', 'hideable_id'];

    // Relasi balik (Polymorphic)
    public function hideable()
    {
        return $this->morphTo();
    }
}