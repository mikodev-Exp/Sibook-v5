<?php

namespace App\Models;

use App\Enums\StatusLogbook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbook extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'kegiatan',
        'deskripsi',
        'status',
        'verified_by',
        'verified_at',
        'catatan_verifikator',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'     => 'date',
            'status'      => StatusLogbook::class,
            'verified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}