<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'desa_id',
        'role',
        'is_active',

        // Akses modul desa
        'is_statistik_active',
        'is_ppid_active',
        'is_antikorupsi_active',
        'is_skm_active',
        'is_aduan_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',

            // Cast boolean akses modul
            'is_active' => 'boolean',
            'is_statistik_active' => 'boolean',
            'is_ppid_active' => 'boolean',
            'is_antikorupsi_active' => 'boolean',
            'is_skm_active' => 'boolean',
            'is_aduan_active' => 'boolean',
        ];
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Desa::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDesa(): bool
    {
        return $this->role === 'desa';
    }
}