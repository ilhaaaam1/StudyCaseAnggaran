<?php

namespace Database\Seeders;

use App\Enums\StatusPengajuan;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use App\Models\Divisi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PengajuanRabSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama agar tidak duplikat saat dijalankan ulang
        PengajuanRab::where('no_rab', 'like', 'RAB-2026-X%')->delete();

        $staff = Pengguna::where('email', 'staff@sirab')->first();
        $divisi = Divisi::where('nama_divisi', 'like', '%Kurikulum%')->first();

        $idPengguna = $staff ? $staff->id_pengguna : 2;
        $idDivisi = $divisi ? $divisi->id_divisi : 1;

        $data = [
            // ====================== BARIS 1 ======================
            [
                'no_rab' => 'RAB-2026-X001',
                'id_pengguna' => $idPengguna,
                'id_divisi' => $idDivisi,
                'judul_pengajuan' => 'Pengadaan Alat Praktikum Dummy 1',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari - Juni)',
                'tanggal_mulai' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'periode_penggunaan' => 'Semester Ganjil 2026',
                'kategori_anggaran' => 'Belanja Barang Operasional & ATK',
                'latar_belakang' => 'Latar belakang pengadaan dummy data ke-1.',
                'estimasi_total' => 1500000,
                'status' => StatusPengajuan::MENUNGGU_FINANCE->value,
                'tanggal_pengajuan' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // ====================== BARIS 2 ======================
            [
                'no_rab' => 'RAB-2026-X002',
                'id_pengguna' => $idPengguna,
                'id_divisi' => $idDivisi,
                'judul_pengajuan' => 'Pengadaan Alat Praktikum Dummy 2',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari - Juni)',
                'tanggal_mulai' => Carbon::now()->addDays(6)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(12)->format('Y-m-d'),
                'periode_penggunaan' => 'Semester Ganjil 2026',
                'kategori_anggaran' => 'Belanja Barang Operasional & ATK',
                'latar_belakang' => 'Latar belakang pengadaan dummy data ke-2.',
                'estimasi_total' => 2500000,
                'status' => StatusPengajuan::MENUNGGU_FINANCE->value,
                'tanggal_pengajuan' => Carbon::now()->subDays(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // ====================== BARIS 3 ======================
            [
                'no_rab' => 'RAB-2026-X003',
                'id_pengguna' => $idPengguna,
                'id_divisi' => $idDivisi,
                'judul_pengajuan' => 'Pengadaan Alat Praktikum Dummy 3',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari - Juni)',
                'tanggal_mulai' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'tanggal_selesai' => Carbon::now()->addDays(14)->format('Y-m-d'),
                'periode_penggunaan' => 'Semester Ganjil 2026',
                'kategori_anggaran' => 'Belanja Barang Operasional & ATK',
                'latar_belakang' => 'Latar belakang pengadaan dummy data ke-3.',
                'estimasi_total' => 3500000,
                'status' => StatusPengajuan::MENUNGGU_FINANCE->value,
                'tanggal_pengajuan' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        PengajuanRab::insert($data);
    }
}