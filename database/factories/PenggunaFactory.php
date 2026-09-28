<?php

namespace Database\Factories;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pengguna>
 */
class PenggunaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   public function definition(): array
{
    return [
        'nama_lengkap' => fake()->name(), // Ubah menjadi nama_lengkap
        'email' => fake()->unique()->safeEmail(),
        'password' => bcrypt('password123'),
        'id_divisi' => 1, // Contoh: memberikan id_divisi statis 1 untuk user dummy
        'jabatan' => 'Staf Pengujian', // Contoh teks dummy untuk jabatan
        'role' => 'user', // Contoh role default
    ];
}
}

    
