<?php

namespace Database\Seeders;

use App\Models\Divisi;
use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil ID Divisi yang relevan
        $unitTU = Divisi::where('nama_divisi', 'like', '%Tata Usaha%')->first();
        $unitPerpustakaan = Divisi::where('nama_divisi', 'like', '%Perpustakaan%')->first();

        if (! $unitTU || ! $unitPerpustakaan) {
            return;
        }

        $defaultPassword = Hash::make('11223344');

        // 1. Pimpinan
        Pengguna::updateOrCreate(
            ['email' => 'pimpinan@sirab'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'pak arif rahman',
                'jabatan' => 'KEPALA SEKOLAH',
                'password' => Hash::make('12345678'),
                'role' => 'pimpinan',
            ]
        );

        // 2. Staff
        Pengguna::updateOrCreate(
            ['email' => 'staff@sirab'],
            [
                'id_divisi' => $unitPerpustakaan->id_divisi,
                'nama_lengkap' => 'staff',
                'nip' => '3431231231241',
                'jabatan' => 'Kepala Perpustakaan',
                'password' => Hash::make('12345678'),
                'role' => 'staff',
            ]
        );

        // 3. Admin IT
        Pengguna::updateOrCreate(
            ['email' => 'adminit@sirab'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Admin IT',
                'jabatan' => 'Kepala IT',
                'password' => Hash::make('12345678'),
                'role' => 'admin_it',
            ]
        );

        // 4. Finance
        Pengguna::updateOrCreate(
            ['email' => 'finance@sirab'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'Finance',
                'jabatan' => 'Bendahara Sekolah',
                'password' => Hash::make('12345678'),
                'role' => 'finance',
            ]
        );
        Pengguna::updateOrCreate(
            ['email' => 'radhit@sirab'],
            [
                'id_divisi' => $unitTU->id_divisi,
                'nama_lengkap' => 'radhit',
                'jabatan' => 'Bendahara 2',
                'password' => Hash::make('12345678'),
                'role' => 'finance',
            ]
        );
    }
}
