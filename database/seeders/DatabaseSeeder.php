<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's core master data.
     * Idempotent: Can be safely run multiple times without duplicating or overwriting custom data.
     */
    public function run(): void
    {
        // Panggil semua seeder
        $this->call([
            DivisiSeeder::class,
            PenggunaSeeder::class,
            UserSeeder::class,
            KategoriAnggaranSeeder::class,
            PengajuanRabSeeder::class,
        // Jalankan semua seeder aplikasi SIRAB
        $this->call([
            DivisiSeeder::class,           // 1. Seed Master Unit Kerja / Divisi
            PenggunaSeeder::class,         // 2. Seed Master User (termasuk User Legacy)
            KategoriAnggaranSeeder::class, // 3. Seed Master Kategori Anggaran
            PengajuanRabSeeder::class,     // 4. Seed 20 baris data Pengajuan RAB Dummy
            AlurPersetujuanSeeder::class,  // 5. Seed histori Alur Persetujuan (Opsional)
            DummyDataSeeder::class,        // 6. Seed data dummy spesifik lainnya
        ]);
    }



}
