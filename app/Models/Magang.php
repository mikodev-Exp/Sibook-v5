<?php

namespace App\Models;

use App\Enums\StatusMagang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Magang extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'no_surat',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
            'status'          => StatusMagang::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isActive(): bool
    {
        return $this->status === StatusMagang::Active
            && now()->between($this->tanggal_mulai, $this->tanggal_selesai->endOfDay());
    }
}