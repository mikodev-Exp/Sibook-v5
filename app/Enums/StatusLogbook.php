<?php

namespace App\Enums;

enum StatusLogbook: string
{
    case Draft     = 'draft';
    case Submitted = 'submitted';
    case Approved  = 'approved';
    case Rejected  = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Draft     => 'Draft',
            self::Submitted => 'Menunggu Verifikasi',
            self::Approved  => 'Disetujui',
            self::Rejected  => 'Ditolak',
        };
    }
}