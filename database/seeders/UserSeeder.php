<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PRESENTASI: Dipisah menjadi seeder mandiri sesuai instruksi materi
        $defaultPassword = Hash::make('password');

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
