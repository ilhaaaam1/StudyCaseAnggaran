<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Divisi;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        $unitTU = Divisi::where('nama_divisi', 'Tata Usaha & Operasional (TU)')->first();
        $unitKurikulum = Divisi::where('nama_divisi', 'Kurikulum & Pembelajaran')->first();
        $unitSarpras = Divisi::where('nama_divisi', 'Sarana & Prasarana (Sarpras)')->first();
        $unitKesiswaan = Divisi::where('nama_divisi', 'Kesiswaan & Ekstrakurikuler')->first();

        if (!$unitTU) return; // Ensure divisions exist

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

        // Pimpinan / Wakil Kepala sekolah
        Pengguna::updateOrCreate(
            ['email' => 'wakakepsek@sirab.local'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'radhit',
                'jabatan' => 'Koordinator Kurikulum',
                'password' => $defaultPassword,
                'role' => 'user',
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
        )
    }
}
