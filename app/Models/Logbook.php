<?php

namespace App\Models;

use App\Enums\StatusLogbook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'tim',
        'jenis_gsbpm',
        'jam_mulai',
        'jam_selesai',
        'link_bukti',
        'parent_id',
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

    // Ini fungsi yang tadi hilang/salah tempat
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Logbook::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Logbook::class, 'parent_id')->orderBy('tanggal', 'asc');
    }
}