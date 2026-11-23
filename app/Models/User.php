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

    /**
     * Total menit keterlambatan mahasiswa dari awal magang sampai akhir magang.
     * Dapat difilter per bulan (format 'Y-m') jika dibutuhkan.
     */
    public function totalMenitTerlambat(?string $bulan = null): int
    {
        $query = $this->presensis();

        if ($bulan) {
            $query->where('tanggal', 'like', "{$bulan}%");
        }

        return (int) $query->get()->sum('menit_terlambat');
    }

    /**
     * Format teks akumulasi keterlambatan (tanggungan waktu yang harus diselesaikan).
     * Contoh: "2 menit", "45 menit", atau "1 jam 15 menit (75 menit)"
     */
    public function formattedTotalTerlambat(?string $bulan = null): string
    {
        $total = $this->totalMenitTerlambat($bulan);
        if ($total <= 0) {
            return '0 menit (Nihil)';
        }

        if ($total < 60) {
            return "{$total} menit";
        }

        $jam = intdiv($total, 60);
        $sisa = $total % 60;

        return $sisa > 0
            ? "{$jam} jam {$sisa} menit ({$total} menit)"
            : "{$jam} jam ({$total} menit)";
    }
}