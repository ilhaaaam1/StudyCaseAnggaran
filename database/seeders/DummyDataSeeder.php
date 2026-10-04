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
    public function run(): void
    {
        $unitKurikulum = Divisi::where('nama_divisi', 'like', '%Kurikulum%')->first();
        $unitSarpras = Divisi::where('nama_divisi', 'like', '%Sarana%')->first();
        $unitKesiswaan = Divisi::where('nama_divisi', 'like', '%Kesiswaan%')->first();
        $unitTU = Divisi::where('nama_divisi', 'like', '%Tata Usaha%')->first();

        $admin = Pengguna::where('email', 'pimpinan@sirab')->first();
        $finance = Pengguna::where('email', 'finance@sirab')->first();
        $sari = Pengguna::where('email', 'staff@sirab')->first();
        $budi = Pengguna::where('email', 'staff@sirab')->first();
        $dina = Pengguna::where('email', 'staff@sirab')->first();

        if (! $sari || ! $budi || ! $dina || ! $admin) {
            return;
        }

        PengajuanRab::whereIn('no_rab', ['RAB-2026-001', 'RAB-2026-002', 'RAB-2026-003', 'RAB-2026-004'])->delete();

        $rab1 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-001'],
            [
                'id_pengguna' => $sari->id_pengguna,
                'id_divisi' => $unitTU ? $unitTU->id_divisi : 1,
                'judul_pengajuan' => 'Pengadaan Modul Literasi Digital & Buku Perpustakaan',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari - Juni)',
                'tanggal_mulai' => now()->addDays(10)->format('Y-m-d'),
                'tanggal_selesai' => now()->addDays(20)->format('Y-m-d'),
                'periode_penggunaan' => 'BOS Reguler Tahap 1 (2026/2027 - Semester Ganjil)',
                'kategori_anggaran' => 'Pengembangan Perpustakaan',
                'latar_belakang' => 'Memenuhi standar minimal literasi perpustakaan sekolah sesuai panduan BOS terbaru.',
                'estimasi_total' => 14850000.00,
                'status' => 'Selesai',
                'tanggal_pengajuan' => now()->subDays(7),
                'bukti_pencairan' => 'bukti_pencairan/mock_bukti_transfer.jpg',
            ]
        );

        RincianItem::firstOrCreate(
            ['id_pengajuan' => $rab1->id_pengajuan, 'uraian_barang' => 'Buku Fiksi & Non-Fiksi Siswa SD'],
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

        if ($finance) {
            AlurPersetujuan::firstOrCreate(
                ['id_pengajuan' => $rab1->id_pengajuan, 'level_persetujuan' => 1],
                [
                    'id_reviewer' => $finance->id_pengguna,
                    'status_persetujuan' => 'ACC',
                    'catatan' => 'Disetujui, sesuai pagu perpustakaan.',
                    'tanggal_proses' => now()->subDays(5),
                ]
            );
        }

        if ($admin) {
            AlurPersetujuan::firstOrCreate(
                ['id_pengajuan' => $rab1->id_pengajuan, 'level_persetujuan' => 2],
                [
                    'id_reviewer' => $admin->id_pengguna,
                    'status_persetujuan' => 'ACC',
                    'catatan' => 'Disetujui Kepala Sekolah.',
                    'tanggal_proses' => now()->subDays(3),
                ]
            );
        }

        $rab2 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-002'],
            [
                'id_pengguna' => $sari->id_pengguna,
                'id_divisi' => $unitKurikulum ? $unitKurikulum->id_divisi : 1,
                'judul_pengajuan' => 'Workshop & Pelatihan Penguatan Implementasi Kurikulum Merdeka Guru',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari - Juni)',
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

        $rab3 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-003'],
            [
                'id_pengguna' => $budi->id_pengguna,
                'id_divisi' => $unitSarpras ? $unitSarpras->id_divisi : 1,
                'judul_pengajuan' => 'Perbaikan Pintu & Pengecatan Ruang Kelas 4',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari - Juni)',
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

        if ($finance) {
            AlurPersetujuan::firstOrCreate(
                ['id_pengajuan' => $rab3->id_pengajuan, 'level_persetujuan' => 1],
                [
                    'id_reviewer' => $finance->id_pengguna,
                    'status_persetujuan' => 'Ditolak',
                    'catatan' => 'Alokasi pemeliharaan sarpras periode ini dialihkan untuk perbaikan instalasi sanitasi/toilet.',
                    'tanggal_proses' => now()->subDays(8),
                ]
            );
        }

        $rab4 = PengajuanRab::firstOrCreate(
            ['no_rab' => 'RAB-2026-004'],
            [
                'id_pengguna' => $dina->id_pengguna,
                'id_divisi' => $unitKesiswaan ? $unitKesiswaan->id_divisi : 1,
                'judul_pengajuan' => 'Pemberangkatan Kontingen Lomba O2SN & FLS2N Tingkat Kecamatan',
                'tahun_ajaran' => '2026/2027',
                'semester' => 'Ganjil',
                'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
                'tahap_bos' => 'BOS Reguler Tahap 1 (Januari - Juni)',
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