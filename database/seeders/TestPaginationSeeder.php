<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestPaginationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Perulangan untuk membuat 20 data dummy
        for ($i = 1; $i <= 20; $i++) {
            Pengguna::create([
                'email' => "userpaginasi{$i}@sirab.local",
                'nama_lengkap' => "Pengguna Test {$i}",
                'jabatan' => 'Staf Pengujian',
                'password' => Hash::make('password'),
                'role' => 'user',
                'id_divisi' => 1, // Sesuaikan dengan id divisi yang ada
            ]);
        }
    }
}
