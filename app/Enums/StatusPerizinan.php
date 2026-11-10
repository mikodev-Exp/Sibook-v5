<?php

namespace App\Enums;

enum StatusPerizinan: string
{
    case Submitted = 'submitted';
    case Approved  = 'approved';
    case Rejected  = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Menunggu Persetujuan',
            self::Approved  => 'Disetujui',
            self::Rejected  => 'Ditolak',
        };
    }
}