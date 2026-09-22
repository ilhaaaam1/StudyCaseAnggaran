<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Perbarui dan sinkronkan 6 Unit Kerja Operasional Sekolah standar pada tabel divisi:
     * 1. Kurikulum & Pembelajaran
     * 2. Kesiswaan & Ekstrakurikuler
     * 3. Sarana & Prasarana (Sarpras)
     * 4. Tata Usaha & Operasional (TU)
     * 5. Perpustakaan
     * 6. UKS (Unit Kesehatan Sekolah)
     */
    public function up(): void
    {
        $schoolUnits = [
            1 => 'Kurikulum & Pembelajaran',
            2 => 'Kesiswaan & Ekstrakurikuler',
            3 => 'Sarana & Prasarana (Sarpras)',
            4 => 'Tata Usaha & Operasional (TU)',
            5 => 'Perpustakaan',
            6 => 'UKS (Unit Kesehatan Sekolah)',
        ];

        foreach ($schoolUnits as $id => $nama) {
            $exists = DB::table('divisi')->where('id_divisi', $id)->first();
            if ($exists) {
                DB::table('divisi')->where('id_divisi', $id)->update([
                    'nama_divisi' => $nama,
                    'updated_at' => now(),
                ]);
            } else {
                DB::table('divisi')->insert([
                    'id_divisi' => $id,
                    'nama_divisi' => $nama,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tetap dipertahankan agar tidak merusak data jika rollback
    }
};
