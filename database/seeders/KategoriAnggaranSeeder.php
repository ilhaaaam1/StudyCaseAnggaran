<?php

namespace Database\Seeders;

use App\Models\KategoriAnggaran;
use Illuminate\Database\Seeder;

class KategoriAnggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * PRESENTASI: Seeder mandiri untuk Master Kategori & Pagu Anggaran
     */
    public function run(): void
    {
        $categories = [
            ['nama_kategori' => 'Belanja Barang Operasional & ATK', 'deskripsi' => 'Pengadaan barang habis pakai operasional sekolah', 'pagu_anggaran' => 50000000],
            ['nama_kategori' => 'Pengembangan Perpustakaan & Literasi', 'deskripsi' => 'Pengadaan buku teks dan non-teks serta fasilitas perpustakaan', 'pagu_anggaran' => 30000000],
            ['nama_kategori' => 'Peningkatan Kompetensi Guru (SDM)', 'deskripsi' => 'Pelatihan, seminar, dan workshop peningkatan kapasitas guru', 'pagu_anggaran' => 45000000],
            ['nama_kategori' => 'Pemeliharaan Sarana & Prasarana', 'deskripsi' => 'Perbaikan ringan dan rutin bangunan, sanitasi, dan aset sekolah', 'pagu_anggaran' => 80000000],
            ['nama_kategori' => 'Kegiatan Kesiswaan & Lomba', 'deskripsi' => 'Penyelenggaraan dan partisipasi lomba O2SN, FLS2N, kepramukaan, dan keagamaan', 'pagu_anggaran' => 25000000],
        ];

        foreach ($categories as $cat) {
            KategoriAnggaran::updateOrCreate(
                ['nama_kategori' => $cat['nama_kategori']],
                $cat
            );
        }
    }
}
