<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KelompokKuota extends Model
{
    use HasFactory;

    protected $table = 'kelompok_kuotas';

    protected $fillable = [
        'nama',
        'kuota',
        'aktif',
        'is_catch_all',
        'urutan',
        // Alias fillable
        'name',
        'quota',
        'active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'kuota'        => 'integer',
            'aktif'        => 'integer',
            'is_catch_all' => 'boolean',
            'urutan'       => 'integer',
        ];
    }

    public function jurusans(): HasMany
    {
        return $this->hasMany(Jurusan::class, 'kelompok_kuota_id')->orderBy('nama');
    }

    /**
     * Alias relasi majors untuk kompatibilitas.
     */
    public function majors(): HasMany
    {
        return $this->jurusans();
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

    public function getQuotaAttribute(): int
    {
        return (int) ($this->attributes['kuota'] ?? 0);
    }

    public function setQuotaAttribute($value): void
    {
        $this->attributes['kuota'] = $value;
    }

    public function getActiveAttribute(): int
    {
        return (int) ($this->attributes['aktif'] ?? 0);
    }

    public function setActiveAttribute($value): void
    {
        $this->attributes['aktif'] = $value;
    }

    public function getSortOrderAttribute(): int
    {
        return (int) ($this->attributes['urutan'] ?? 0);
    }

    public function setSortOrderAttribute($value): void
    {
        $this->attributes['urutan'] = $value;
    }

    // ================= Kalkulasi Kuota =================

    /**
     * Sisa kuota yang dihitung otomatis (tidak boleh negatif).
     */
    public function getSisaAttribute(): int
    {
        return max(0, $this->kuota - $this->aktif);
    }

    public function getRemainingAttribute(): int
    {
        return $this->getSisaAttribute();
    }

    /**
     * Persentase penggunaan kuota aktif (0 - 100%).
     */
    public function getPersentaseAttribute(): int
    {
        if ($this->kuota <= 0) {
            return 0;
        }

        return (int) min(100, round(($this->aktif / $this->kuota) * 100));
    }

    public function getPercentageAttribute(): int
    {
        return $this->getPersentaseAttribute();
    }

    /**
     * Persentase sisa kuota terhadap total kuota.
     */
    public function getPersentaseSisaAttribute(): float
    {
        if ($this->kuota <= 0) {
            return 0;
        }

        return round(($this->sisa / $this->kuota) * 100, 1);
    }

    public function getRemainingPercentageAttribute(): float
    {
        return $this->getPersentaseSisaAttribute();
    }

    /**
     * Status apakah kuota sudah penuh.
     */
    public function getIsFullAttribute(): bool
    {
        return $this->aktif >= $this->kuota;
    }

    /**
     * Warna representasi sisa kuota:
     * - Hijau: sisa > 30%
     * - Kuning: sisa 1% - 30%
     * - Merah: sisa 0% (Penuh)
     */
    public function getStatusColorAttribute(): string
    {
        if ($this->sisa <= 0) {
            return 'red';
        }

        if ($this->persentase_sisa <= 30) {
            return 'yellow';
        }

        return 'green';
    }
}
