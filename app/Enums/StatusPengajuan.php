<?php

declare(strict_types=1);

namespace App\Enums;

enum StatusPengajuan: string
{
    case DRAFT = 'Draft';
    case MENUNGGU_FINANCE = 'Menunggu Verifikasi Finance';
    case REVISI = 'Revisi';
    case MENUNGGU_PIMPINAN = 'Menunggu Persetujuan Pimpinan';
    case DITOLAK = 'Ditolak';
    case PROSES_PENCAIRAN = 'Proses Pencairan';
    case SELESAI = 'Selesai';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::MENUNGGU_FINANCE => 'Menunggu Finance',
            self::REVISI => 'Revisi',
            self::MENUNGGU_PIMPINAN => 'Menunggu Pimpinan',
            self::DITOLAK => 'Ditolak',
            self::PROSES_PENCAIRAN => 'Proses Pencairan',
            self::SELESAI => 'Selesai',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::DRAFT => 'bg-slate-100 text-slate-800 border border-slate-300',
            self::MENUNGGU_FINANCE => 'bg-amber-100 text-amber-800',
            self::MENUNGGU_PIMPINAN => 'bg-blue-100 text-blue-800',
            self::REVISI => 'bg-slate-100 text-slate-800',
            self::DITOLAK => 'bg-rose-100 text-rose-800',
            self::PROSES_PENCAIRAN => 'bg-indigo-100 text-indigo-800',
            self::SELESAI => 'bg-emerald-100 text-emerald-800',
        };
    }
}
