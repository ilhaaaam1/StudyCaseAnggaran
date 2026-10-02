<?php

namespace Database\Seeders;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanRab;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PengajuanRabSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data dummy sebelumnya agar idempotent (tidak error duplikat)
        PengajuanRab::where('no_rab', 'like', 'RAB-2026-X%')->delete();

        $data = [];
        // Menggunakan looping untuk meng-generate 20 baris data dummy
        for ($i = 1; $i <= 20; $i++) {
            $data[] = [
                'no_rab' => 'RAB-2026-X'.str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                'id_pengguna' => 2, // Asumsi ID 2 adalah Staff (Sari Dewi)
                'id_divisi' => 1,   // Asumsi ID 1 adalah Kurikulum
                'judul_pengajuan' => 'Pengadaan Alat Praktikum Dummy '.$i,
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
                'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'periode_penggunaan' => 'Semester Ganjil 2026',
                'kategori_anggaran' => 'Belanja Barang Operasional & ATK',
                'latar_belakang' => 'Latar belakang pengadaan dummy data ke-'.$i.' untuk keperluan testing paginasi.',
                'estimasi_total' => rand(1000000, 5000000),
                'status' => StatusPengajuan::MENUNGGU_FINANCE->value,
                'tanggal_pengajuan' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert 20 baris data sekaligus ke tabel pengajuan_rab
        PengajuanRab::insert($data);
    }
}
