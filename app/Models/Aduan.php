<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aduan extends Model
{
    protected $fillable = [
        'desa_id',
        'kode_aduan',
        'jenis_identitas',
        'is_identity_hidden',
        'nama_pelapor',
        'no_hp',
        'email',
        'kategori',
        'judul',
        'isi_aduan',
        'status',
        'tanggapan',
        'ditanggapi_pada',
        'ditanggapi_oleh',
    ];

    protected $casts = [
        'ditanggapi_pada' => 'datetime',
         'is_identity_hidden' => 'boolean',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function penanggap()
    {
        return $this->belongsTo(User::class, 'ditanggapi_oleh');
    }
}