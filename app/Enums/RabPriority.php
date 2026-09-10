<?php

declare(strict_types=1);

namespace App\Enums;

enum RabPriority: string
{
    case RENDAH = 'rendah';
    case SEDANG = 'sedang';
    case TINGGI = 'tinggi';

    public function label(): string
    {
        return match ($this) {
            self::RENDAH => 'Rendah',
            self::SEDANG => 'Sedang',
            self::TINGGI => 'Tinggi',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::RENDAH => 'bg-indigo-50 text-indigo-600 border border-indigo-100',
            self::SEDANG => 'bg-amber-50 text-amber-600 border border-amber-100',
            self::TINGGI => 'bg-rose-50 text-rose-600 border border-rose-100',
        };
    }
}
