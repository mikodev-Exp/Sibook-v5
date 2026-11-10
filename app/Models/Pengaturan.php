<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'string',
        ];
    }

    /**
     * Ambil nilai pengaturan dengan cast sesuai kolom type.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $row = static::where('key', $key)->first();
        if (! $row) {
            return $default;
        }

        return match ($row->type) {
            'int'   => (int) $row->value,
            'float' => (float) $row->value,
            'bool'  => filter_var($row->value, FILTER_VALIDATE_BOOLEAN),
            'json'  => json_decode($row->value, true),
            default => $row->value,
        };
    }

    public static function set(string $key, mixed $value, string $type = 'string', ?string $group = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type'  => $type,
                'group' => $group,
            ]
        );
    }
}