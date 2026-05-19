<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desa extends Model
{
    protected $fillable = [
        'nama_desa', 
        'slug', 
        'logo', 
        'header_color', 
        'accent_color', 
        'kecamatan',
        'layout_type',          
        'welcome_message',     
        'featured_category_id',
        'is_antikorupsi_active',
    ];

    public function statistics()
    {
        return $this->hasMany(Statistic::class, 'desa_id');
    }
}
