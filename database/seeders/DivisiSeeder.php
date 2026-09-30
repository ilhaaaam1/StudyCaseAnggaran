<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            'Kurikulum & Pembelajaran',
            'Kesiswaan & Ekstrakurikuler',
            'Sarana & Prasarana (Sarpras)',
            'Tata Usaha & Operasional (TU)',
            'Perpustakaan',
            'UKS (Unit Kesehatan Sekolah)',
            'Laboratorium',
            'raadhittt',
        ];

        foreach ($units as $unitName) {
            Divisi::updateOrCreate(['nama_divisi' => $unitName]);
        }
    }
}
