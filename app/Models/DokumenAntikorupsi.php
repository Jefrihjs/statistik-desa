<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenAntikorupsi extends Model
{
    use HasFactory;

    protected $table = 'dokumen_antikorupsi';
    protected $fillable = [
        'desa_id', 
        'user_id',
        'kategori', 
        'grup_indikator', 
        'urutan_tampil',
        'no_urut', 
        'sub', 
        'sub_judul',
        'nama_dokumen', 
        'link_drive'
    ];
}