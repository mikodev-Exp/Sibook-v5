<?php

namespace App\Models;

use App\Enums\StatusPermohonan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PermohonanMagang extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal_mulai_rencana',
        'tanggal_selesai_rencana',
        'status',
        'catatan_admin',
        'catatan_superuser',
        'reviewed_by',
        'reviewed_at',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai_rencana'   => 'date',
            'tanggal_selesai_rencana' => 'date',
            'status'                  => StatusPermohonan::class,
            'reviewed_at'             => 'datetime',
            'approved_at'             => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function dokumens(): HasMany
    {
        return $this->hasMany(PermohonanDokumen::class, 'permohonan_id');
    }
}