<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\AlurPersetujuan;
use App\Models\Divisi;
use App\Models\DokumenPendukung;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use App\Models\RincianItem;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Seed sample / dummy school RAB submissions for local testing and development.
     * Idempotent: Can be run multiple times safely without duplicating records.
     */
    public function run(): void
    {
        // Ambil referensi master unit kerja dan pengguna yang sudah dibuat oleh DatabaseSeeder
        $unitKurikulum = Divisi::where('nama_divisi', 'Kurikulum & Pembelajaran')->first();
        $unitSarpras = Divisi::where('nama_divisi', 'Sarana & Prasarana (Sarpras)')->first();
        $unitKesiswaan = Divisi::where('nama_divisi', 'Kesiswaan & Ekstrakurikuler')->first();
        $unitTU = Divisi::where('nama_divisi', 'Tata Usaha & Operasional (TU)')->first();

        $admin = Pengguna::where('email', 'arif@sirab.local')->first();
        $sari = Pengguna::where('email', 'sari@sirab.local')->first();
        $budi = Pengguna::where('email', 'budi@sirab.local')->first();
        $dina = Pengguna::where('email', 'dina@sirab.local')->first();

        if (! $sari || ! $unitKurikulum) {
            $this->command->warn('Master data pengguna/divisi belum siap. Menjalankan DatabaseSeeder terlebih dahulu...');
            $this->call(DatabaseSeeder::class);

            $unitKurikulum = Divisi::where('nama_divisi', 'Kurikulum & Pembelajaran')->first();
            $unitSarpras = Divisi::where('nama_divisi', 'Sarana & Prasarana (Sarpras)')->first();
            $unitKesiswaan = Divisi::where('nama_divisi', 'Kesiswaan & Ekstrakurikuler')->first();
            $admin = Pengguna::where('email', 'arif@sirab.local')->first();
            $sari = Pengguna::where('email', 'sari@sirab.local')->first();
            $budi = Pengguna::where('email', 'budi@sirab.local')->first();
            $dina = Pengguna::where('email', 'dina@sirab.local')->first();
        }

        // 1. RAB 1: Selesai / Disetujui (Kurikulum)
        $rab1 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-001'],
            [
                'id_pengguna' => $sari->id_pengguna,
                'id_divisi' => $unitKurikulum->id_divisi,
                'judul_pengajuan' => 'Pengadaan Modul Pembelajaran & Buku Kurikulum Merdeka',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
                'tanggal_mulai' => now()->subDays(10)->format('Y-m-d'),
                'tanggal_selesai' => now()->subDays(5)->format('Y-m-d'),
                'periode_penggunaan' => 'BOS Reguler Tahap 1 (2026/2027 - Semester Ganjil)',
                'kategori_anggaran' => 'Pengembangan Perpustakaan & Literasi',
                'latar_belakang' => 'Peningkatan kapasitas literasi dan buku teks ajar Kurikulum Merdeka untuk siswa fase B dan C.',
                'estimasi_total' => 14850000.00,
                'status' => 'Selesai',
                'tanggal_pengajuan' => now()->subDays(5),
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab1->id_pengajuan, 'uraian_barang' => 'Buku Siswa Kurikulum Merdeka Kelas 4 & 5'],
            [
                'satuan' => 'Eksemplar',
                'volume' => 100,
                'harga_satuan' => 85000.00,
                'total_harga' => 8500000.00,
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab1->id_pengajuan, 'uraian_barang' => 'Buku Panduan Pendidik & Guru'],
            [
                'satuan' => 'Eksemplar',
                'volume' => 20,
                'harga_satuan' => 95000.00,
                'total_harga' => 1900000.00,
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab1->id_pengajuan, 'uraian_barang' => 'Paket Modul Literasi & Numerasi ANBK'],
            [
                'satuan' => 'Paket',
                'volume' => 50,
                'harga_satuan' => 89000.00,
                'total_harga' => 4450000.00,
            ]
        );

        DokumenPendukung::firstOrCreate(
            ['id_pengajuan' => $rab1->id_pengajuan, 'nama_file' => 'Proposal_Pengadaan_Modul_Literasi.pdf'],
            [
                'tipe_dokumen' => 'PDF',
                'path_file' => 'dokumen_rab/mock_surat.pdf',
                'waktu_unggah' => now()->subDays(5),
            ]
        );

        if ($admin) {
            AlurPersetujuan::firstOrCreate(
                ['id_pengajuan' => $rab1->id_pengajuan, 'level_persetujuan' => 1],
                [
                    'id_reviewer' => $admin->id_pengguna,
                    'status_persetujuan' => 'ACC',
                    'catatan' => 'Disetujui sesuai spesifikasi acuan BOS dan pagu anggaran literasi.',
                    'tanggal_proses' => now()->subDays(3),
                ]
            );
        }

        // 2. RAB 2: Menunggu Verifikasi Finance (Kurikulum)
        $rab2 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-002'],
            [
                'id_pengguna' => $sari->id_pengguna,
                'id_divisi' => $unitKurikulum->id_divisi,
                'judul_pengajuan' => 'Workshop & Pelatihan Penguatan Implementasi Kurikulum Merdeka Guru',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
                'tanggal_mulai' => now()->addDays(5)->format('Y-m-d'),
                'tanggal_selesai' => now()->addDays(7)->format('Y-m-d'),
                'periode_penggunaan' => 'BOS Reguler Tahap 1 (2026/2027 - Semester Ganjil)',
                'kategori_anggaran' => 'Peningkatan Kompetensi Guru (SDM)',
                'latar_belakang' => 'Peningkatan kompetensi pendidik dalam penyusunan modul ajar berdiferensiasi dan asesmen sumatif.',
                'estimasi_total' => 5000000.00,
                'status' => 'Menunggu Verifikasi Finance',
                'tanggal_pengajuan' => now()->subDay(),
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab2->id_pengajuan, 'uraian_barang' => 'Honorarium Narasumber Pelatihan Kurikulum (2 Sesi)'],
            [
                'satuan' => 'Orang/Sesi',
                'volume' => 2,
                'harga_satuan' => 1500000.00,
                'total_harga' => 3000000.00,
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab2->id_pengajuan, 'uraian_barang' => 'Konsumsi & ATK Peserta Pelatihan Guru'],
            [
                'satuan' => 'Paket',
                'volume' => 20,
                'harga_satuan' => 100000.00,
                'total_harga' => 2000000.00,
            ]
        );

        // 3. RAB 3: Ditolak (Sarpras)
        $rab3 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-003'],
            [
                'id_pengguna' => $budi->id_pengguna,
                'id_divisi' => $unitSarpras->id_divisi,
                'judul_pengajuan' => 'Perbaikan Pintu & Pengecatan Ruang Kelas 4',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
                'tanggal_mulai' => now()->subDays(15)->format('Y-m-d'),
                'tanggal_selesai' => now()->subDays(12)->format('Y-m-d'),
                'periode_penggunaan' => 'BOS Reguler Tahap 1 (2026/2027 - Semester Ganjil)',
                'kategori_anggaran' => 'Pemeliharaan Sarana & Prasarana',
                'latar_belakang' => 'Perbaikan perlengkapan kelas yang rusak menjelang semester baru.',
                'estimasi_total' => 2800000.00,
                'status' => 'Ditolak',
                'tanggal_pengajuan' => now()->subDays(10),
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab3->id_pengajuan, 'uraian_barang' => 'Cat Tembok Ruang Kelas (Pail 25kg)'],
            [
                'satuan' => 'Pail',
                'volume' => 2,
                'harga_satuan' => 900000.00,
                'total_harga' => 1800000.00,
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab3->id_pengajuan, 'uraian_barang' => 'Engsel & Kunci Pintu Kelas'],
            [
                'satuan' => 'Set',
                'volume' => 4,
                'harga_satuan' => 250000.00,
                'total_harga' => 1000000.00,
            ]
        );

        if ($admin) {
            AlurPersetujuan::firstOrCreate(
                ['id_pengajuan' => $rab3->id_pengajuan, 'level_persetujuan' => 1],
                [
                    'id_reviewer' => $admin->id_pengguna,
                    'status_persetujuan' => 'Ditolak',
                    'catatan' => 'Alokasi pemeliharaan sarpras periode ini dialihkan untuk perbaikan instalasi sanitasi/toilet.',
                    'tanggal_proses' => now()->subDays(8),
                ]
            );
        }

        // 4. RAB 4: Menunggu Verifikasi Finance (Kesiswaan)
        $rab4 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-004'],
            [
                'id_pengguna' => $dina->id_pengguna,
                'id_divisi' => $unitKesiswaan->id_divisi,
                'judul_pengajuan' => 'Pemberangkatan Kontingen Lomba O2SN & FLS2N Tingkat Kecamatan',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
                'tanggal_mulai' => now()->addDays(10)->format('Y-m-d'),
                'tanggal_selesai' => now()->addDays(12)->format('Y-m-d'),
                'periode_penggunaan' => 'BOS Reguler Tahap 1 (2026/2027 - Semester Ganjil)',
                'kategori_anggaran' => 'Kegiatan Kesiswaan & Lomba',
                'latar_belakang' => 'Transportasi, pendaftaran, dan akomodasi siswa perwakilan sekolah dalam ajang talenta O2SN & FLS2N.',
                'estimasi_total' => 3500000.00,
                'status' => 'Menunggu Verifikasi Finance',
                'tanggal_pengajuan' => now()->subHours(6),
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab4->id_pengajuan, 'uraian_barang' => 'Biaya Registrasi Lomba 5 Cabang'],
            [
                'satuan' => 'Cabang',
                'volume' => 5,
                'harga_satuan' => 300000.00,
                'total_harga' => 1500000.00,
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab4->id_pengajuan, 'uraian_barang' => 'Konsumsi & Transportasi Kontingen Siswa & Pembina'],
            [
                'satuan' => 'Paket',
                'volume' => 1,
                'harga_satuan' => 2000000.00,
                'total_harga' => 2000000.00,
            ]
        );
    }
}
