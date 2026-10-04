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
        // Jalankan semua seeder aplikasi SIRAB secara berurutan
        $this->call([
            DivisiSeeder::class,           // 1. Seed Master Unit Kerja / Divisi
            PenggunaSeeder::class,         // 2. Seed Master User
            UserSeeder::class,             // 3. Seed User Legacy (jika ada)
            KategoriAnggaranSeeder::class, // 4. Seed Master Kategori Anggaran
            PengajuanRabSeeder::class,     // 5. Seed 3 baris data Pengajuan RAB Dummy
            // AlurPersetujuanSeeder::class,  // 6. Dimatikan agar tidak double dummy data
            // DummyDataSeeder::class,        // 7. Dimatikan agar tidak menghasilkan 4 RAB tambahan
        ]);
    }
}
