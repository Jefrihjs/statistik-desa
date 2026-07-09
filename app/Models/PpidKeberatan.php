<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpidKeberatan extends Model
{
    protected $fillable = [
        'desa_id',
        'ppid_permohonan_id',
        'kode_keberatan',
        'alasan_keberatan',
        'uraian_alasan',
        'status',
        'nama_atasan_ppid',
        'posisi_atasan',
        'tanggapan_admin',
        'ditanggapi_pada',
    ];

    protected $casts = [
        'ditanggapi_pada' => 'datetime',
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class);
    }

    public function permohonan()
    {
        return $this->belongsTo(PpidPermohonan::class, 'ppid_permohonan_id');
    }

    public function getLabelAlasanAttribute()
    {
        $master = [
            'A' => 'Permohonan informasi ditolak',
            'B' => 'Informasi berkala tidak disediakan',
            'C' => 'Permintaan informasi tidak ditanggapi',
            'D' => 'Permintaan informasi ditanggapi tidak sebagaimana yang diminta',
            'E' => 'Permintaan informasi tidak dipenuhi',
            'F' => 'Biaya yang dikenakan tidak wajar',
            'G' => 'Informasi disampaikan melebihi jangka waktu',
        ];

        $kodeAlasan = json_decode($this->alasan_keberatan, true);

        if (!is_array($kodeAlasan)) {
            $kodeAlasan = [$this->alasan_keberatan];
        }

        return collect($kodeAlasan)
            ->map(fn ($kode) => $master[$kode] ?? null)
            ->filter()
            ->implode(', ');
    }

    public function logs()
    {
        return $this->hasMany(PpidLog::class, 'ppid_keberatan_id');
    }
}