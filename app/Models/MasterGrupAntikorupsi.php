<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterGrupAntikorupsi extends Model
{
    use HasFactory;

    protected $table = 'master_grup_antikorupsi';

    protected $fillable = [
        'kategori',
        'nama_grup'
    ];
}