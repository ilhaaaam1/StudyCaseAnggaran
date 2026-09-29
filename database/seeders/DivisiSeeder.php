<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PRESENTASI: Dipisah menjadi seeder mandiri sesuai instruksi materi
        $units = [
            'Kurikulum & Pembelajaran',
            'Kesiswaan & Ekstrakurikuler',
            'Sarana & Prasarana (Sarpras)',
            'Tata Usaha & Operasional (TU)',
            'Perpustakaan',
            'UKS (Unit Kesehatan Sekolah)',
        ];

        foreach ($units as $unitName) {
            Divisi::updateOrCreate(
                ['nama_divisi' => $unitName]
            );
        }
    }
}
