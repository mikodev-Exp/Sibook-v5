<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanMagang extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_magangs';

    protected $fillable = [
        'persyaratan',
        'catatan_persyaratan',
        'status',
        'catatan_status',
        'tanggal_buka',
        'tautan',
        // Alias fillable
        'requirements',
        'requirements_note',
        'status_note',
        'opens_at',
        'links',
    ];

    protected function casts(): array
    {
        return [
            'persyaratan'  => 'array',
            'tautan'       => 'array',
            'tanggal_buka' => 'date',
        ];
    }

    /**
     * Ambil pengaturan saat ini (singleton row).
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'status'              => 'soon',
            'catatan_status'      => null,
            'persyaratan'         => [],
            'catatan_persyaratan' => null,
            'tautan'              => [],
        ]);
    }

    /**
     * Label human-readable untuk status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open'   => 'Dibuka',
            'closed' => 'Ditutup',
            default  => 'Segera Dibuka',
        };
    }

    // ================= Accessor & Mutator Kompatibilitas =================

    public function getRequirementsAttribute(): array
    {
        $val = $this->attributes['persyaratan'] ?? null;
        if (is_array($val)) return $val;
        return is_string($val) ? (json_decode($val, true) ?: []) : [];
    }

    public function setRequirementsAttribute($value): void
    {
        $this->attributes['persyaratan'] = is_array($value) ? json_encode(array_values($value)) : $value;
    }

    public function getRequirementsNoteAttribute(): ?string
    {
        return $this->attributes['catatan_persyaratan'] ?? null;
    }

    public function setRequirementsNoteAttribute($value): void
    {
        $this->attributes['catatan_persyaratan'] = $value;
    }

    public function getStatusNoteAttribute(): ?string
    {
        return $this->attributes['catatan_status'] ?? null;
    }

    public function setStatusNoteAttribute($value): void
    {
        $this->attributes['catatan_status'] = $value;
    }

    public function getOpensAtAttribute(): mixed
    {
        return $this->tanggal_buka;
    }

    public function setOpensAtAttribute($value): void
    {
        $this->attributes['tanggal_buka'] = $value;
    }

    public function getLinksAttribute(): array
    {
        $val = $this->attributes['tautan'] ?? null;
        if (is_array($val)) return $val;
        return is_string($val) ? (json_decode($val, true) ?: []) : [];
    }

    public function setLinksAttribute($value): void
    {
        $this->attributes['tautan'] = is_array($value) ? json_encode(array_values($value)) : $value;
    }
}
