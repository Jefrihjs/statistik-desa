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
        'alamat_kantor',
        'email_desa',
        'website_desa',
        'telepon_desa',
        'nama_kepala_desa',
        'nip_kepala',
        'logo_desa',
        'nama_ppid',
        'jabatan_ppid',
        'nip_ppid',
        'public_template_id',
    ];

    public function statistics()
    {
        return $this->hasMany(Statistic::class, 'desa_id');
    }
}
