<?php

namespace App\Enums;

enum StatusPermohonan: string
{
    case Draft            = 'draft';
    case Submitted        = 'submitted';
    case ReviewedByAdmin  = 'reviewed_by_admin';
    case Approved         = 'approved';
    case Rejected         = 'rejected';
    case Cancelled        = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft           => 'Draft',
            self::Submitted       => 'Menunggu Review Admin',
            self::ReviewedByAdmin => 'Menunggu Approval Super User',
            self::Approved        => 'Disetujui',
            self::Rejected        => 'Ditolak',
            self::Cancelled       => 'Dibatalkan',
        };
    }
}