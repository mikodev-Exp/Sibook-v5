<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PermohonanDokumen extends Model
{
    protected $fillable = [
        'permohonan_id',
        'jenis',
        'nama_asli',
        'path',
    ];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(PermohonanMagang::class, 'permohonan_id');
    }
}