<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpidLog extends Model
{
    protected $fillable = [
        'desa_id',
        'ppid_permohonan_id',
        'ppid_keberatan_id',
        'tipe',
        'judul',
        'deskripsi',
        'actor_name',
        'actor_role',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function permohonan()
    {
        return $this->belongsTo(PpidPermohonan::class, 'ppid_permohonan_id');
    }

    public function keberatan()
    {
        return $this->belongsTo(PpidKeberatan::class, 'ppid_keberatan_id');
    }
}