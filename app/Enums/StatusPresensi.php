<?php

namespace App\Enums;

enum StatusPresensi: string
{
    case Hadir     = 'hadir';
    case Terlambat = 'terlambat';
    case Izin      = 'izin';
    case Sakit     = 'sakit';
    case Alpha     = 'alpha';

    public function label(): string
    {
        return match ($this) {
            self::Hadir     => 'Hadir',
            self::Terlambat => 'Terlambat',
            self::Izin      => 'Izin',
            self::Sakit     => 'Sakit',
            self::Alpha     => 'Alpha',
        };
    }
}