<?php

namespace App\Models;

use App\Enums\StatusPresensi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'lat_masuk',
        'lng_masuk',
        'jarak_masuk_m',
        'lat_pulang',
        'lng_pulang',
        'jarak_pulang_m',
        'status',
        'keterangan',
        'tipe_presensi',
        'foto',
        'alasan_luar',
        'status_verifikasi'
    ];

    protected $appends = [
        'menit_terlambat',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'         => 'date',
            'status'          => StatusPresensi::class,
            'lat_masuk'       => 'float',
            'lng_masuk'       => 'float',
            'lat_pulang'      => 'float',
            'lng_pulang'      => 'float',
            'jarak_masuk_m'   => 'float',
            'jarak_pulang_m'  => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hitung berapa menit mahasiswa terlambat dengan batas toleransi 07:30:30.
     * Jika tepat waktu (<= 07:30:30) atau belum masuk -> 0 menit.
     * Jika masuk > 07:30:30, dihitung selisih menit keterlambatan dari jam masuk standar (07:30:00).
     * Contoh: presensi masuk pukul 07:30:30 -> 0 menit (tepat waktu).
     *         presensi masuk pukul 07:32:00 -> 2 menit.
     */
    public function getMenitTerlambatAttribute(): int
    {
        if (!$this->jam_masuk) {
            return 0;
        }

        try {
            $masuk = \Carbon\Carbon::parse($this->jam_masuk);
            $batasToleransi = \Carbon\Carbon::parse('07:30:30');
            $titikStandar = \Carbon\Carbon::parse('07:30:00');

            if ($masuk->greaterThan($batasToleransi)) {
                $menit = (int) $masuk->diffInMinutes($titikStandar, true);
                return $menit > 0 ? $menit : 1;
            }
        } catch (\Throwable $e) {
            return 0;
        }

        return 0;
    }

    /**
     * Format teks keterlambatan yang mudah dibaca (misal "2 menit", "1 jam 15 menit").
     */
    public function getFormattedTerlambatAttribute(): ?string
    {
        $menit = $this->menit_terlambat;
        if ($menit <= 0) {
            return null;
        }

        if ($menit < 60) {
            return "{$menit} menit";
        }

        $jam = intdiv($menit, 60);
        $sisaMenit = $menit % 60;

        return $sisaMenit > 0 ? "{$jam} jam {$sisaMenit} menit" : "{$jam} jam";
    }
}