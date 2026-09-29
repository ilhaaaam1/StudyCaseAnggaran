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
        ]);
    }
}
