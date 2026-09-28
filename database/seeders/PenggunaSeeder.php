<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PRESENTASI: Dipisah menjadi seeder mandiri sesuai instruksi materi
        $unitTU = Divisi::where('nama_divisi', 'Tata Usaha & Operasional (TU)')->first();
        $unitKurikulum = Divisi::where('nama_divisi', 'Kurikulum & Pembelajaran')->first();
        $unitSarpras = Divisi::where('nama_divisi', 'Sarana & Prasarana (Sarpras)')->first();
        $unitKesiswaan = Divisi::where('nama_divisi', 'Kesiswaan & Ekstrakurikuler')->first();

        // 2. Seed Default Essential Accounts (Tabel Pengguna - Authenticatable)
        // Password default: 'password'
        $defaultPassword = Hash::make('password');

        if ($unitTU && $unitKurikulum && $unitSarpras && $unitKesiswaan) {
            // Admin / Kepala TU
            Pengguna::updateOrCreate(
                ['email' => 'arif@sirab.local'],
                [
                    'id_divisi' => $unitTU->id_divisi,
                    'nama_lengkap' => 'Drs. Arif Rachman',
                    'jabatan' => 'Kepala Tata Usaha',
                    'password' => $defaultPassword,
                    'role' => 'admin',
                ]
            );

            // Staf Kurikulum
            Pengguna::updateOrCreate(
                ['email' => 'sari@sirab.local'],
                [
                    'id_divisi' => $unitKurikulum->id_divisi,
                    'nama_lengkap' => 'Sari Dewi',
                    'jabatan' => 'Koordinator Kurikulum',
                    'password' => $defaultPassword,
                    'role' => 'user',
                ]
            );

            // Staf Sarpras
            Pengguna::updateOrCreate(
                ['email' => 'budi@sirab.local'],
                [
                    'id_divisi' => $unitSarpras->id_divisi,
                    'nama_lengkap' => 'Budi Santoso',
                    'jabatan' => 'Staf Sarana & Prasarana',
                    'password' => $defaultPassword,
                    'role' => 'user',
                ]
            );

            // Staf Kesiswaan
            Pengguna::updateOrCreate(
                ['email' => 'dina@sirab.local'],
                [
                    'id_divisi' => $unitKesiswaan->id_divisi,
                    'nama_lengkap' => 'Dina Marlina',
                    'jabatan' => 'Pembina Kesiswaan & Ekskul',
                    'password' => $defaultPassword,
                    'role' => 'user',
                ]
            );

            // Finance / Bendahara BOS
            Pengguna::updateOrCreate(
                ['email' => 'finance@sirab.local'],
                [
                    'id_divisi' => $unitTU->id_divisi,
                    'nama_lengkap' => 'Akun Finance',
                    'jabatan' => 'Bendahara BOS',
                    'password' => $defaultPassword,
                    'role' => 'finance',
                ]
            );

            // Pimpinan / Kepala Sekolah
            Pengguna::updateOrCreate(
                ['email' => 'pimpinan@sirab.local'],
                [
                    'id_divisi' => $unitTU->id_divisi,
                    'nama_lengkap' => 'Akun Pimpinan',
                    'jabatan' => 'Kepala Sekolah',
                    'password' => $defaultPassword,
                    'role' => 'pimpinan',
                ]
            );
        }

        Pengguna::factory()->count(20)->create();
    }
}
