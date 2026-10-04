<?php

namespace Database\Seeders;

use App\Models\KategoriAnggaran;
use Illuminate\Database\Seeder;

class KategoriAnggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // PRESENTASI: Dipisah menjadi seeder mandiri sesuai instruksi materi

        $categories = [
            ['nama_kategori' => 'Belanja Barang Operasional & ATK', 'deskripsi' => 'Pengadaan barang habis pakai', 'pagu_anggaran' => 50000000],
            ['nama_kategori' => 'Pengembangan Perpustakaan & Literasi', 'deskripsi' => 'Pengadaan buku teks dan non-teks', 'pagu_anggaran' => 30000000],
            ['nama_kategori' => 'Peningkatan Kompetensi Guru (SDM)', 'deskripsi' => 'Pelatihan dan workshop guru', 'pagu_anggaran' => 45000000],
            ['nama_kategori' => 'Pemeliharaan Sarana & Prasarana', 'deskripsi' => 'Perbaikan ringan dan rutin bangunan/aset', 'pagu_anggaran' => 80000000],
            ['nama_kategori' => 'Kegiatan Kesiswaan & Lomba', 'deskripsi' => 'O2SN, FLS2N, Porseni', 'pagu_anggaran' => 25000000],
            ['nama_kategori' => 'Kegiatan Luar Sekolah', 'deskripsi' => 'Study Tour', 'pagu_anggaran' => 35000000],
        ];

        foreach ($categories as $cat) {
            KategoriAnggaran::updateOrCreate(
                ['nama_kategori' => $cat['nama_kategori']],
                $cat
            );
        }
    }
}
