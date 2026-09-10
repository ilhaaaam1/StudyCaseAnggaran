<?php

declare(strict_types=1);

namespace App\Enums;

enum RabStatus: string
{
    case DRAFT = 'draft';
    case DIAJUKAN = 'diajukan';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';
    case REVISI = 'revisi';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::DIAJUKAN => 'Diajukan',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
            self::REVISI => 'Perlu Revisi',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-slate-100 text-slate-600',
            self::DIAJUKAN => 'bg-amber-50 text-amber-600',
            self::DISETUJUI => 'bg-emerald-50 text-emerald-600',
            self::DITOLAK => 'bg-rose-50 text-rose-600',
            self::REVISI => 'bg-orange-50 text-orange-600',
        };
    }

    public function dotClasses(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-slate-400',
            self::DIAJUKAN => 'bg-amber-400',
            self::DISETUJUI => 'bg-emerald-400',
            self::DITOLAK => 'bg-rose-400',
            self::REVISI => 'bg-orange-400',
        };
    }
}
