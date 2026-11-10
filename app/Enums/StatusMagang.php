<?php

namespace App\Enums;

enum StatusMagang: string
{
    case Active     = 'active';
    case Completed  = 'completed';
    case Terminated = 'terminated';

    public function label(): string
    {
        return match ($this) {
            self::Active     => 'Aktif',
            self::Completed  => 'Selesai',
            self::Terminated => 'Dihentikan',
        };
    }
}