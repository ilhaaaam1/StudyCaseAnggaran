<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // PRESENTASI: Update Data Master Divisi ke Konteks Sekolah
        // Data divisi korporat diubah menjadi bidang/bagian sekolah
        // untuk menyesuaikan dengan domain aplikasi.
        $schoolDivisions = [
            1 => 'Kurikulum & Pembelajaran',
            2 => 'Kesiswaan & Ekstrakurikuler',
            3 => 'Sarana & Prasarana',
            4 => 'Tata Usaha',
            5 => 'Perpustakaan',
            6 => 'Humas & Kemitraan',
            7 => 'Laboratorium', // Tambahan agar ID 7 tetap punya nama yang wajar
        ];

        foreach ($schoolDivisions as $id => $name) {
            DB::table('divisi')->where('id_divisi', $id)->update(['nama_divisi' => $name]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert ke divisi korporat
        $corporateDivisions = [
            1 => 'Teknologi Informasi',
            2 => 'Keuangan',
            3 => 'Marketing',
            4 => 'HRD',
            5 => 'Umum & Fasilitas',
            6 => 'Logistik',
            7 => 'Administrasi',
        ];

        foreach ($corporateDivisions as $id => $name) {
            DB::table('divisi')->where('id_divisi', $id)->update(['nama_divisi' => $name]);
        }
    }
};
