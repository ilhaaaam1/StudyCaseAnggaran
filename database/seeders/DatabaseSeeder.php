<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AlurPersetujuan;
use App\Models\Divisi;
use App\Models\DokumenPendukung;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use App\Models\RincianItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Master Divisi
        $divTI = Divisi::firstOrCreate(['nama_divisi' => 'Teknologi Informasi']);
        $divKeuangan = Divisi::firstOrCreate(['nama_divisi' => 'Keuangan']);
        $divMarketing = Divisi::firstOrCreate(['nama_divisi' => 'Marketing']);
        $divHRD = Divisi::firstOrCreate(['nama_divisi' => 'HRD']);
        $divUmum = Divisi::firstOrCreate(['nama_divisi' => 'Umum & Fasilitas']);
        $divLogistik = Divisi::firstOrCreate(['nama_divisi' => 'Logistik']);
        $divAdmin = Divisi::firstOrCreate(['nama_divisi' => 'Administrasi']);

        // 2. Seed Pengguna (Authenticatable)
        // Admin
        $admin = Pengguna::firstOrCreate(
            ['email' => 'arif@sirab.local'],
            [
                'id_divisi' => $divKeuangan->id_divisi,
                'nama_lengkap' => 'Drs. Arif Rachman',
                'jabatan' => 'Direktur Keuangan',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Staf Users
        $sari = Pengguna::firstOrCreate(
            ['email' => 'sari@sirab.local'],
            [
                'id_divisi' => $divTI->id_divisi,
                'nama_lengkap' => 'Sari Dewi',
                'jabatan' => 'Staf IT',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $budi = Pengguna::firstOrCreate(
            ['email' => 'budi@sirab.local'],
            [
                'id_divisi' => $divUmum->id_divisi,
                'nama_lengkap' => 'Budi Santoso',
                'jabatan' => 'Staf Umum',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        $dina = Pengguna::firstOrCreate(
            ['email' => 'dina@sirab.local'],
            [
                'id_divisi' => $divMarketing->id_divisi,
                'nama_lengkap' => 'Dina Marlina',
                'jabatan' => 'Staf Pemasaran',
                'password' => Hash::make('password'),
                'role' => 'user',
            ]
        );

        // Seed tabel users untuk kompatibilitas pengujian legacy
        User::firstOrCreate(
            ['email' => 'arif@sirab.local'],
            [
                'name' => 'Drs. Arif Rachman',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'division' => 'Keuangan',
                'position' => 'Direktur Keuangan',
            ]
        );

        User::firstOrCreate(
            ['email' => 'sari@sirab.local'],
            [
                'name' => 'Sari Dewi',
                'password' => Hash::make('password'),
                'role' => UserRole::USER,
                'division' => 'Teknologi Informasi',
                'position' => 'Staf IT',
            ]
        );

        $financePengguna = Pengguna::firstOrCreate(
            ['email' => 'finance@sirab.local'],
            [
                'id_divisi' => $divKeuangan->id_divisi,
                'nama_lengkap' => 'Akun Finance',
                'jabatan' => 'Bendahara',
                'password' => Hash::make('password'),
                'role' => 'finance',
            ]
        );

        $pimpinanPengguna = Pengguna::firstOrCreate(
            ['email' => 'pimpinan@sirab.local'],
            [
                'id_divisi' => $divAdmin->id_divisi,
                'nama_lengkap' => 'Akun Pimpinan',
                'jabatan' => 'Kepala Sekolah',
                'password' => Hash::make('password'),
                'role' => 'pimpinan',
            ]
        );

        User::firstOrCreate(
            ['email' => 'finance@sirab.local'],
            [
                'name' => 'Akun Finance',
                'password' => Hash::make('password'),
                'role' => UserRole::FINANCE,
                'division' => 'Keuangan',
                'position' => 'Bendahara',
            ]
        );

        User::firstOrCreate(
            ['email' => 'pimpinan@sirab.local'],
            [
                'name' => 'Akun Pimpinan',
                'password' => Hash::make('password'),
                'role' => UserRole::PIMPINAN,
                'division' => 'Administrasi',
                'position' => 'Kepala Sekolah',
            ]
        );

        // 3. Seed Pengajuan RAB 1: Disetujui (ACC)
        $rab1 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-001'],
            [
                'id_pengguna' => $sari->id_pengguna,
                'id_divisi' => $divTI->id_divisi,
                'judul_pengajuan' => 'Pengadaan Perangkat Server & Jaringan',
                'periode_penggunaan' => 'Q4 2026',
                'prioritas' => 'Tinggi',
                'latar_belakang' => 'Peningkatan kapasitas infrastruktur server dan peremajaan switch jaringan untuk operasional kantor.',
                'estimasi_total' => 148500000.00,
                'status' => 'Selesai',
                'tanggal_pengajuan' => now()->subDays(5),
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab1->id_pengajuan, 'uraian_barang' => 'Laptop Dell XPS 15 (Core i7)'],
            [
                'satuan' => 'Unit',
                'volume' => 5,
                'harga_satuan' => 18500000.00,
                'total_harga' => 92500000.00,
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab1->id_pengajuan, 'uraian_barang' => 'Monitor 27" 4K UltraSharp'],
            [
                'satuan' => 'Unit',
                'volume' => 5,
                'harga_satuan' => 6200000.00,
                'total_harga' => 31000000.00,
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab1->id_pengajuan, 'uraian_barang' => 'Switch Jaringan 24-Port Managed'],
            [
                'satuan' => 'Unit',
                'volume' => 2,
                'harga_satuan' => 12500000.00,
                'total_harga' => 25000000.00,
            ]
        );

        DokumenPendukung::firstOrCreate(
            ['id_pengajuan' => $rab1->id_pengajuan, 'nama_file' => 'Surat_Permintaan_Pengadaan.pdf'],
            [
                'tipe_dokumen' => 'PDF',
                'path_file' => 'dokumen_rab/mock_surat.pdf',
                'waktu_unggah' => now()->subDays(5),
            ]
        );

        AlurPersetujuan::firstOrCreate(
            ['id_pengajuan' => $rab1->id_pengajuan, 'level_persetujuan' => 1],
            [
                'id_reviewer' => $admin->id_pengguna,
                'status_persetujuan' => 'ACC',
                'catatan' => 'Disetujui sesuai spesifikasi dan pagu anggaran IT triwulan berjalan.',
                'tanggal_proses' => now()->subDays(3),
            ]
        );

        // 4. Seed Pengajuan RAB 2: Pending (Menunggu Review)
        $rab2 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-002'],
            [
                'id_pengguna' => $sari->id_pengguna,
                'id_divisi' => $divTI->id_divisi,
                'judul_pengajuan' => 'Pelatihan Sertifikasi Cyber Security Staf IT',
                'periode_penggunaan' => 'Q4 2026',
                'prioritas' => 'Sedang',
                'latar_belakang' => 'Pelatihan sertifikasi keamanan informasi ISO 27001 untuk memperkuat tata kelola TI.',
                'estimasi_total' => 25000000.00,
                'status' => 'Menunggu Verifikasi Finance',
                'tanggal_pengajuan' => now()->subDay(),
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab2->id_pengajuan, 'uraian_barang' => 'Kursus & Sertifikasi CEH (Certified Ethical Hacker)'],
            [
                'satuan' => 'Peserta',
                'volume' => 2,
                'harga_satuan' => 12500000.00,
                'total_harga' => 25000000.00,
            ]
        );

        // 5. Seed Pengajuan RAB 3: Ditolak
        $rab3 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-003'],
            [
                'id_pengguna' => $budi->id_pengguna,
                'id_divisi' => $divUmum->id_divisi,
                'judul_pengajuan' => 'Peremajaan Sofa Ruang Tamu VIP',
                'periode_penggunaan' => 'Q3 2026',
                'prioritas' => 'Rendah',
                'latar_belakang' => 'Penggantian kursi sofa ruang tamu gedung direksi yang sudah usang.',
                'estimasi_total' => 38000000.00,
                'status' => 'Ditolak',
                'tanggal_pengajuan' => now()->subDays(10),
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab3->id_pengajuan, 'uraian_barang' => 'Sofa Kulit 3 Seater Premium'],
            [
                'satuan' => 'Set',
                'volume' => 2,
                'harga_satuan' => 19000000.00,
                'total_harga' => 38000000.00,
            ]
        );

        AlurPersetujuan::firstOrCreate(
            ['id_pengajuan' => $rab3->id_pengajuan, 'level_persetujuan' => 1],
            [
                'id_reviewer' => $admin->id_pengguna,
                'status_persetujuan' => 'Ditolak',
                'catatan' => 'Alokasi anggaran fasilitas kantor periode ini difokuskan untuk perbaikan AC sentral.',
                'tanggal_proses' => now()->subDays(8),
            ]
        );

        // 6. Seed Pengajuan RAB 4: Pending (Marketing)
        $rab4 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-004'],
            [
                'id_pengguna' => $dina->id_pengguna,
                'id_divisi' => $divMarketing->id_divisi,
                'judul_pengajuan' => 'Kampanye Iklan Digital & Pameran Akhir Tahun',
                'periode_penggunaan' => 'Q4 2026',
                'prioritas' => 'Tinggi',
                'latar_belakang' => 'Promosi kampanye akhir tahun di media sosial dan partisipasi pameran industri nasional.',
                'estimasi_total' => 65000000.00,
                'status' => 'Menunggu Verifikasi Finance',
                'tanggal_pengajuan' => now()->subHours(6),
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab4->id_pengajuan, 'uraian_barang' => 'Biaya Sewa Booth Pameran 3x3m'],
            [
                'satuan' => 'Hari',
                'volume' => 3,
                'harga_satuan' => 15000000.00,
                'total_harga' => 45000000.00,
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab4->id_pengajuan, 'uraian_barang' => 'Google Ads & Meta Ads Placement'],
            [
                'satuan' => 'Bulan',
                'volume' => 2,
                'harga_satuan' => 10000000.00,
                'total_harga' => 20000000.00,
            ]
        );
    }
}
