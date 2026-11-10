<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function mahasiswaProfile(): HasOne
    {
        return $this->hasOne(MahasiswaProfile::class);
    }

    public function permohonanMagang(): HasOne
    {
        return $this->hasOne(PermohonanMagang::class)->latestOfMany();
    }

    public function permohonanMagangs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PermohonanMagang::class);
    }

    public function magang(): HasOne
    {
        return $this->hasOne(Magang::class);
    }

    public function presensis(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Presensi::class);
    }

    public function logbooks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Logbook::class);
    }

    public function perizinans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Perizinan::class);
    }
}