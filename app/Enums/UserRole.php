<?php

declare(strict_types=1);

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case FINANCE = 'finance';
    case PIMPINAN = 'pimpinan';
    case ADMIN_IT = 'admin_it';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin / Direktur Keuangan',
            self::USER => 'User / Staf Pengaju',
            self::FINANCE => 'Finance / Bendahara',
            self::PIMPINAN => 'Pimpinan / Kepsek',
            self::ADMIN_IT => 'Admin IT',
        };
    }
}
