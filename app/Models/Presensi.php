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
}