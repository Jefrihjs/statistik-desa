<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpidPermohonan extends Model
{
    protected $fillable = [
        'desa_id',
        'kode_permohonan',
        'kategori_pemohon',
        'nik',
        'nama',
        'file_ktp',
        'file_akta',
        'alamat',
        'email',
        'no_hp',
        'pekerjaan',
        'rincian_informasi',
        'tujuan_penggunaan',
        'cara_memperoleh',
        'jenis_salinan',
        'cara_pengiriman',
        'no_wa',
        'status',
        'catatan_admin',
        'diproses_pada',
        'file_penyelesaian',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function pemberitahuan()
    {
        return $this->hasOne(PpidPemberitahuan::class, 'ppid_permohonan_id');
    }

    public function keberatan()
    {
        return $this->hasOne(PpidKeberatan::class, 'ppid_permohonan_id');
    }
    
    public function logs()
    {
        return $this->hasMany(PpidLog::class, 'ppid_permohonan_id');
    }
    
    public function getNomorPendaftaranAttribute()
    {
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        $bulan = $this->created_at
            ? (int) $this->created_at->format('n')
            : (int) now()->format('n');

        $tahun = $this->created_at
            ? $this->created_at->format('Y')
            : now()->format('Y');

        $namaDesa = $this->desa->nama_desa
            ?? $this->desa->nama
            ?? 'DESA';

        $kodeDesa = strtoupper($namaDesa);
        $kodeDesa = str_replace(' ', '-', $kodeDesa);
        $kodeDesa = preg_replace('/[^A-Z0-9\-]/', '', $kodeDesa);

        return str_pad($this->id, 3, '0', STR_PAD_LEFT)
            . '/PPID-DESA/'
            . $kodeDesa
            . '/'
            . $bulanRomawi[$bulan]
            . '/'
            . $tahun;
    }
}