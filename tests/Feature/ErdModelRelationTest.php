<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\AlurPersetujuan;
use App\Models\Divisi;
use App\Models\DokumenPendukung;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use App\Models\RincianItem;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErdModelRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_erd_models_and_relationships(): void
    {
        // 1. Buat Divisi
        $divisi = Divisi::create([
            'nama_divisi' => 'Teknologi Informasi',
        ]);
        $this->assertDatabaseHas('divisi', [
            'id_divisi' => $divisi->id_divisi,
            'nama_divisi' => 'Teknologi Informasi',
        ]);

        // 2. Buat Pengguna (Pemohon & Reviewer)
        $pemohon = Pengguna::create([
            'id_divisi' => $divisi->id_divisi,
            'nama_lengkap' => 'Budi Santoso',
            'jabatan' => 'Staff IT',
            'email' => 'budi@example.com',
        ]);

        $reviewer = Pengguna::create([
            'id_divisi' => $divisi->id_divisi,
            'nama_lengkap' => 'Dewi Sartika',
            'jabatan' => 'Kepala Divisi IT',
            'email' => 'dewi@example.com',
        ]);

        // Verifikasi relasi Pengguna -> Divisi
        $this->assertEquals($divisi->id_divisi, $pemohon->divisi->id_divisi);
        $this->assertEquals('Teknologi Informasi', $pemohon->divisi->nama_divisi);
        $this->assertCount(2, $divisi->pengguna);

        // 3. Buat Pengajuan RAB
        $pengajuan = PengajuanRab::create([
            'id_pengguna' => $pemohon->id_pengguna,
            'id_divisi' => $divisi->id_divisi,
            'no_rab' => 'RAB-2026-IT-001',
            'judul_pengajuan' => 'Pengadaan Server Cloud',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'tinggi',
            'latar_belakang' => 'Kebutuhan peningkatan kapasitas server.',
            'estimasi_total' => 15000000.00,
            'status' => 'diajukan',
            'tanggal_pengajuan' => now(),
        ]);

        // Verifikasi relasi Pengajuan -> Pengguna & Divisi
        $this->assertEquals($pemohon->id_pengguna, $pengajuan->pengguna->id_pengguna);
        $this->assertEquals($divisi->id_divisi, $pengajuan->divisi->id_divisi);
        $this->assertCount(1, $pemohon->pengajuanRab);
        $this->assertCount(1, $divisi->pengajuanRab);

        // 4. Buat Rincian Item
        $item = RincianItem::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'uraian_barang' => 'Cloud VPS High Memory 32GB',
            'satuan' => 'Unit/Bulan',
            'volume' => 12,
            'harga_satuan' => 1250000.00,
            'total_harga' => 15000000.00,
        ]);

        $this->assertEquals($pengajuan->id_pengajuan, $item->pengajuanRab->id_pengajuan);
        $this->assertCount(1, $pengajuan->rincianItem);

        // 5. Buat Dokumen Pendukung
        $dokumen = DokumenPendukung::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'nama_file' => 'proposal_cloud_server.pdf',
            'tipe_dokumen' => 'Proposal Teknis',
            'path_file' => 'documents/proposal_cloud_server.pdf',
            'waktu_unggah' => now(),
        ]);

        $this->assertEquals($pengajuan->id_pengajuan, $dokumen->pengajuanRab->id_pengajuan);
        $this->assertCount(1, $pengajuan->dokumenPendukung);

        // 6. Buat Alur Persetujuan
        $persetujuan = AlurPersetujuan::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'id_reviewer' => $reviewer->id_pengguna,
            'level_persetujuan' => 1,
            'status_persetujuan' => 'disetujui',
            'catatan' => 'Disetujui sesuai spesifikasi kebutuhan tahun anggaran 2026.',
            'tanggal_proses' => now(),
        ]);

        $this->assertEquals($pengajuan->id_pengajuan, $persetujuan->pengajuanRab->id_pengajuan);
        $this->assertEquals($reviewer->id_pengguna, $persetujuan->reviewer->id_pengguna);
        $this->assertCount(1, $pengajuan->alurPersetujuan);
        $this->assertCount(1, $reviewer->alurPersetujuan);

        // 7. Test Cascade Delete pada PengajuanRab
        $pengajuanId = $pengajuan->id_pengajuan;
        $pengajuan->delete();

        $this->assertDatabaseMissing('pengajuan_rab', ['id_pengajuan' => $pengajuanId]);
        $this->assertDatabaseMissing('rincian_item', ['id_item' => $item->id_item]);
        $this->assertDatabaseMissing('dokumen_pendukung', ['id_dokumen' => $dokumen->id_dokumen]);
        $this->assertDatabaseMissing('alur_persetujuan', ['id_persetujuan' => $persetujuan->id_persetujuan]);
    }

    public function test_foreign_key_restrict_prevents_deleting_referenced_divisi(): void
    {
        $divisi = Divisi::create([
            'nama_divisi' => 'Keuangan',
        ]);

        Pengguna::create([
            'id_divisi' => $divisi->id_divisi,
            'nama_lengkap' => 'Ahmad Yani',
            'jabatan' => 'Staff Keuangan',
            'email' => 'ahmad@example.com',
        ]);

        $this->expectException(QueryException::class);
        $divisi->delete();
    }
}
