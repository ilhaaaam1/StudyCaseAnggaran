<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriAnggaranSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Belanja Barang Operasional & ATK', 'deskripsi' => 'Pengadaan barang habis pakai', 'pagu_anggaran' => 50000000],
            ['nama_kategori' => 'Pengembangan Perpustakaan & Literasi', 'deskripsi' => 'Pengadaan buku teks dan non-teks', 'pagu_anggaran' => 30000000],
            ['nama_kategori' => 'Peningkatan Kompetensi Guru (SDM)', 'deskripsi' => 'Pelatihan dan workshop guru', 'pagu_anggaran' => 45000000],
            ['nama_kategori' => 'Pemeliharaan Sarana & Prasarana', 'deskripsi' => 'Perbaikan ringan dan rutin bangunan/aset', 'pagu_anggaran' => 80000000],
            ['nama_kategori' => 'Kegiatan Kesiswaan & Lomba', 'deskripsi' => 'O2SN, FLS2N, Porseni', 'pagu_anggaran' => 25000000],
        ];

        foreach ($categories as $cat) {
            \App\Models\KategoriAnggaran::updateOrCreate(
                ['nama_kategori' => $cat['nama_kategori']],
                $cat
            );
        }
    }
}
