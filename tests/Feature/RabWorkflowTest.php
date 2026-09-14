<?php

namespace Tests\Feature;

use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RabWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private $staff;

    private $finance;

    private $pimpinan;

    protected function setUp(): void
    {
        parent::setUp();

        // Siapkan Divisi
        $divisi = Divisi::create([
            'nama_divisi' => 'IT Department',
            'kode_divisi' => 'IT-01',
        ]);

        // Siapkan Users
        $this->staff = Pengguna::create([
            'id_divisi' => $divisi->id_divisi,
            'nama_lengkap' => 'Staff Tester',
            'jabatan' => 'Staff IT',
            'email' => 'staff@test.com',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        $this->finance = Pengguna::create([
            'id_divisi' => $divisi->id_divisi,
            'nama_lengkap' => 'Finance Tester',
            'jabatan' => 'Manager Finance',
            'email' => 'finance@test.com',
            'password' => bcrypt('password'),
            'role' => 'finance',
        ]);

        $this->pimpinan = Pengguna::create([
            'id_divisi' => $divisi->id_divisi,
            'nama_lengkap' => 'Pimpinan Tester',
            'jabatan' => 'Direktur Utama',
            'email' => 'pimpinan@test.com',
            'password' => bcrypt('password'),
            'role' => 'pimpinan',
        ]);
    }

    public function test_full_rab_approval_workflow()
    {
        Storage::fake('public');

        // ==========================================
        // 1. Staff Membuat Pengajuan RAB
        // ==========================================
        $response = $this->actingAs($this->staff)->post('/staff/rab', [
            'judul_pengajuan' => 'Pengadaan Laptop Baru',
            'prioritas' => 'Sedang',
            'id_divisi' => $this->staff->id_divisi,
            'periode_penggunaan' => 'Q1 2027',
            'latar_belakang' => 'Laptop rusak',
            'items' => [
                ['uraian_barang' => 'Laptop Dell', 'satuan' => 'Unit', 'volume' => 2, 'harga_satuan' => 15000000],
            ],
            // Asumsi dokumen opsional atau menggunakan faker file
        ]);

        $response->assertRedirect(route('staff.riwayat'));

        $pengajuan = PengajuanRab::first();
        $this->assertNotNull($pengajuan);
        $this->assertEquals(PengajuanRab::STATUS_MENUNGGU_FINANCE, $pengajuan->status);

        // ==========================================
        // 2. Finance Menyetujui Pengajuan (ACC Tahap 1)
        // ==========================================
        $response = $this->actingAs($this->finance)->post("/finance/pengajuan/{$pengajuan->id_pengajuan}/approval", [
            'status_decision' => 'ACC',
            'catatan' => 'Anggaran tersedia',
        ]);

        $response->assertRedirect(route('finance.antrean'));
        $pengajuan->refresh();
        $this->assertEquals(PengajuanRab::STATUS_MENUNGGU_PIMPINAN, $pengajuan->status);

        // ==========================================
        // 3. Pimpinan Menyetujui Pengajuan (ACC Final)
        // ==========================================
        $response = $this->actingAs($this->pimpinan)->post("/pimpinan/pengajuan/{$pengajuan->id_pengajuan}/approval", [
            'status_decision' => 'ACC',
            'catatan' => 'Disetujui untuk dibeli',
        ]);

        $response->assertRedirect(route('pimpinan.antrean'));
        $pengajuan->refresh();
        $this->assertEquals(PengajuanRab::STATUS_PROSES_PENCAIRAN, $pengajuan->status);

        // ==========================================
        // 4. Finance Upload Bukti Pencairan
        // ==========================================
        $file = UploadedFile::fake()->image('bukti_transfer.jpg');

        $response = $this->actingAs($this->finance)->post("/finance/pengajuan/{$pengajuan->id_pengajuan}/pencairan", [
            'bukti_pencairan' => $file,
        ]);

        $response->assertRedirect(route('finance.pencairan'));
        $pengajuan->refresh();
        $this->assertEquals(PengajuanRab::STATUS_SELESAI, $pengajuan->status);
        $this->assertNotNull($pengajuan->bukti_pencairan);
        Storage::disk('public')->assertExists($pengajuan->bukti_pencairan);
    }
}
