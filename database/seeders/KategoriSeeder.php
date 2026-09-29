<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 1; $i <= 20; $i++) {
            Divisi::updateOrCreate(
                ['nama_divisi' => "Kategori Seed {$i}: ".$faker->jobTitle]
            );
        }
    }
}
