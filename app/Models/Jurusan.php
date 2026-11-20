<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusans';

    protected $fillable = [
        'nama',
        'kelompok_kuota_id',
        // Alias fillable
        'name',
        'quota_group_id',
    ];

    public function kelompokKuota(): BelongsTo
    {
        return $this->belongsTo(KelompokKuota::class, 'kelompok_kuota_id');
    }

    /**
     * Alias relasi quotaGroup untuk kompatibilitas.
     */
    public function quotaGroup(): BelongsTo
    {
        return $this->kelompokKuota();
    }

    // ================= Accessor & Mutator Kompatibilitas =================

    public function getNameAttribute(): string
    {
        return $this->attributes['nama'] ?? '';
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['nama'] = $value;
    }

    public function getQuotaGroupIdAttribute(): ?int
    {
        return isset($this->attributes['kelompok_kuota_id']) ? (int) $this->attributes['kelompok_kuota_id'] : null;
    }

    public function setQuotaGroupIdAttribute($value): void
    {
        $this->attributes['kelompok_kuota_id'] = $value;
    }
}
