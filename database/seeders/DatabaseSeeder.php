<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Divisi;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's core master data.
     * Idempotent: Can be safely run multiple times without duplicating or overwriting custom data.
     */
    public function run(): void
    {
        // 1. Seed Master Unit Kerja Sekolah (Divisi)
        $units = [
            'Kurikulum & Pembelajaran',
            'Kesiswaan & Ekstrakurikuler',
            'Sarana & Prasarana (Sarpras)',
            'Tata Usaha & Operasional (TU)',
            'Perpustakaan',
            'UKS (Unit Kesehatan Sekolah)',
        ];

        $unitModels = [];
        foreach ($units as $unitName) {
            $unitModels[$unitName] = Divisi::updateOrCreate(
                ['nama_divisi' => $unitName]
            );
        }

        $unitTU = $unitModels['Tata Usaha & Operasional (TU)'];
        $unitKurikulum = $unitModels['Kurikulum & Pembelajaran'];
        $unitSarpras = $unitModels['Sarana & Prasarana (Sarpras)'];
        $unitKesiswaan = $unitModels['Kesiswaan & Ekstrakurikuler'];

        // 2. Seed Default Essential Accounts (Tabel Pengguna - Authenticatable)
        // Password default: 'password'
        $defaultPassword = Hash::make('password');

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

        // 3. Seed Tabel users untuk kompatibilitas pengujian legacy
        User::updateOrCreate(
            ['email' => 'arif@sirab.local'],
            [
                'name' => 'Drs. Arif Rachman',
                'password' => $defaultPassword,
                'role' => UserRole::ADMIN,
                'division' => 'Keuangan',
                'position' => 'Direktur Keuangan',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sari@sirab.local'],
            [
                'name' => 'Sari Dewi',
                'password' => $defaultPassword,
                'role' => UserRole::USER,
                'division' => 'Teknologi Informasi',
                'position' => 'Staf IT',
            ]
        );

        User::updateOrCreate(
            ['email' => 'finance@sirab.local'],
            [
                'name' => 'Akun Finance',
                'password' => $defaultPassword,
                'role' => UserRole::FINANCE,
                'division' => 'Keuangan',
                'position' => 'Bendahara BOS',
            ]
        );

        User::updateOrCreate(
            ['email' => 'pimpinan@sirab.local'],
            [
                'name' => 'Akun Pimpinan',
                'password' => $defaultPassword,
                'role' => UserRole::PIMPINAN,
                'division' => 'Administrasi',
                'position' => 'Kepala Sekolah',
            ]
        );
    }
}
