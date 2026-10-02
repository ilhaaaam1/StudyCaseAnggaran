<?php

namespace App\Exports;

use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapitulasiExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection(): Enumerable
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'NO',
            'TANGGAL DIAJUKAN',
            'NO RAB',
            'PEMOHON & BIDANG',
            'KATEGORI',
            'TOTAL ANGGARAN',
            'STATUS',
        ];
    }

    public function map(mixed $row): array
    {
        static $index = 0;
        $index++;

        $pemohonBidang = ($row->pengguna->nama_lengkap ?? '-').' / '.($row->divisi->nama_divisi ?? '-');
        $status = $row->status->value ?? $row->status;

        return [
            $index,
            $row->tanggal_pengajuan ? $row->tanggal_pengajuan->format('d/m/Y') : '-',
            $row->no_rab,
            $pemohonBidang,
            $row->kategori_anggaran ?? '-',
            'Rp '.number_format((float) $row->estimasi_total, 0, ',', '.'),
            $status,
        ];
    }

    // PRESENTASI: Menggunakan ShouldAutoSize dan WithStyles agar format Excel rapi dan kolom otomatis menyesuaikan lebar data
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
