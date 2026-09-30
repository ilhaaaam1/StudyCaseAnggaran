<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PengajuanRabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan pemeriksaan foreign key sementara untuk mengosongkan tabel
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('alur_persetujuan')->truncate();
        DB::table('rincian_item')->truncate();
        DB::table('pengajuan_rab')->truncate();
        DB::table('pengguna')->truncate();
        DB::table('divisi')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. SEED TABEL DIVISI
        DB::table('divisi')->insert([
            [
                'id_divisi' => 1,
                'nama_divisi' => 'IT & Kurikulum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_divisi' => 2,
                'nama_divisi' => 'Kesiswaan & Humas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_divisi' => 3,
                'nama_divisi' => 'Sarana & Prasarana',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. SEED TABEL PENGGUNA (Dengan Role: Staff, Finance, Pimpinan, Admin IT)
        DB::table('pengguna')->insert([
            [
                'id_pengguna' => 1,
                'id_divisi' => 1,
                'nama_lengkap' => 'Ahmad Staff',
                'nip' => '199001012022011001',
                'jabatan' => 'Staff IT',
                'email' => 'staff@test.com',
                'no_hp' => '081234567890',
                'foto_profil' => null,
                'password' => Hash::make('password'),
                'role' => 'staff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 2,
                'id_divisi' => 2,
                'nama_lengkap' => 'Budi Staff',
                'nip' => '199202022022011002',
                'jabatan' => 'Staff Kesiswaan',
                'email' => 'budi@test.com',
                'no_hp' => '081234567891',
                'foto_profil' => null,
                'password' => Hash::make('password'),
                'role' => 'staff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 3,
                'id_divisi' => 1,
                'nama_lengkap' => 'Siti Keuangan',
                'nip' => '198503032020012001',
                'jabatan' => 'Finance Verificator',
                'email' => 'finance@test.com',
                'no_hp' => '081234567892',
                'foto_profil' => null,
                'password' => Hash::make('password'),
                'role' => 'finance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 4,
                'id_divisi' => 1,
                'nama_lengkap' => 'Dr. Hendra Pimpinan',
                'nip' => '197504042015011001',
                'jabatan' => 'Kepala Lembaga / Pimpinan',
                'email' => 'pimpinan@test.com',
                'no_hp' => '081234567893',
                'foto_profil' => null,
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 5,
                'id_divisi' => 1,
                'nama_lengkap' => 'Admin Sistem',
                'nip' => '198805052018011001',
                'jabatan' => 'Administrator IT',
                'email' => 'admin@test.com',
                'no_hp' => '081234567894',
                'foto_profil' => null,
                'password' => Hash::make('password'),
                'role' => 'admin_it',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. SEED TABEL PENGAJUAN RAB (Baris 'prioritas' sudah dihapus)
        DB::table('pengajuan_rab')->insert([
            [
                'id_pengajuan' => 1,
                'id_pengguna' => 1,
                'id_divisi' => 1,
                'no_rab' => 'RAB/2026/03/001',
                'judul_pengajuan' => 'Pengadaan Komputer Lab Komputer & Server',
                'periode_penggunaan' => 'Semester Genap 2025/2026',
                'latar_belakang' => 'Kebutuhan peremajaan unit PC di lab untuk kegiatan ujian dan praktikum.',
                'estimasi_total' => 25000000.00,
                'status' => 'Menunggu Verifikasi Finance',
                'tanggal_pengajuan' => '2026-03-01 09:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengajuan' => 2,
                'id_pengguna' => 2,
                'id_divisi' => 2,
                'no_rab' => 'RAB/2026/03/002',
                'judul_pengajuan' => 'Workshop Pembelajaran Interaktif Guru',
                'periode_penggunaan' => 'Bulan April 2026',
                'latar_belakang' => 'Peningkatan kapasitas guru dalam pemanfaatan media digital.',
                'estimasi_total' => 5000000.00,
                'status' => 'Selesai',
                'tanggal_pengajuan' => '2026-03-05 10:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengajuan' => 3,
                'id_pengguna' => 1,
                'id_divisi' => 1,
                'no_rab' => 'RAB/2026/03/003',
                'judul_pengajuan' => 'Pembelian ATK Operasional Kantor',
                'periode_penggunaan' => 'Triwulan II 2026',
                'latar_belakang' => 'Stok ATK operasional dan konsumsi rapat koordinasi.',
                'estimasi_total' => 1500000.00,
                'status' => 'Ditolak',
                'tanggal_pengajuan' => '2026-03-10 14:15:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 4. SEED TABEL RINCIAN ITEM
        DB::table('rincian_item')->insert([
            // Item untuk Pengajuan #1
            [
                'id_pengajuan' => 1,
                'uraian_barang' => 'PC Desktop Core i5',
                'satuan' => 'Unit',
                'volume' => 2,
                'harga_satuan' => 10000000.00,
                'total_harga' => 20000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengajuan' => 1,
                'uraian_barang' => 'Switch Hub 24 Port Gigabit',
                'satuan' => 'Unit',
                'volume' => 1,
                'harga_satuan' => 5000000.00,
                'total_harga' => 5000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Item untuk Pengajuan #2
            [
                'id_pengajuan' => 2,
                'uraian_barang' => 'Honorarium Pemateri Workshop',
                'satuan' => 'Orang',
                'volume' => 2,
                'harga_satuan' => 2500000.00,
                'total_harga' => 5000000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Item untuk Pengajuan #3
            [
                'id_pengajuan' => 3,
                'uraian_barang' => 'Kertas A4 80gr',
                'satuan' => 'Rim',
                'volume' => 10,
                'harga_satuan' => 60000.00,
                'total_harga' => 600000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengajuan' => 3,
                'uraian_barang' => 'Tinta Printer Epson Original',
                'satuan' => 'Botol',
                'volume' => 6,
                'harga_satuan' => 150000.00,
                'total_harga' => 900000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. SEED TABEL ALUR PERSETUJUAN (Log Reviewer)
        DB::table('alur_persetujuan')->insert([
            // Review untuk Pengajuan #2 (ACC oleh Finance & Pimpinan)
            [
                'id_pengajuan' => 2,
                'id_reviewer' => 3, // ID Siti Keuangan
                'level_persetujuan' => 1,
                'status_persetujuan' => 'ACC',
                'catatan' => 'Dokumen pendukung dan kalkulasi anggaran valid.',
                'tanggal_proses' => '2026-03-06 09:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengajuan' => 2,
                'id_reviewer' => 4, // ID Dr. Hendra Pimpinan
                'level_persetujuan' => 2,
                'status_persetujuan' => 'ACC',
                'catatan' => 'Disetujui untuk dilaksanakan.',
                'tanggal_proses' => '2026-03-06 14:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Review untuk Pengajuan #3 (Ditolak oleh Finance)
            [
                'id_pengajuan' => 3,
                'id_reviewer' => 3, // ID Siti Keuangan
                'level_persetujuan' => 1,
                'status_persetujuan' => 'Ditolak',
                'catatan' => 'Kuota pembelian ATK untuk triwulan ini sudah melebihi batas.',
                'tanggal_proses' => '2026-03-11 08:30:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}