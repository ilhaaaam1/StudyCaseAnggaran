<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RabStatus;
use App\Enums\UserRole;
use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use App\Models\Rab;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BudgetWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_submit_new_budget_request(): void
    {
        Storage::fake('public');

        $divisi = Divisi::first();
        $pengguna = Pengguna::first();

        $postData = [
            'no_rab' => 'RAB-2026-888',
            'id_pengguna' => $pengguna->id_pengguna,
            'id_divisi' => $divisi->id_divisi,
            'judul_pengajuan' => 'Pengadaan Laptop Pengembang 2026',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'tinggi',
            'latar_belakang' => 'Peremajaan perangkat kerja untuk tim engineering.',
            'status' => 'diajukan',
            'rincian' => [
                [
                    'uraian_barang' => 'MacBook Pro M3 Max',
                    'satuan' => 'Unit',
                    'volume' => 2,
                    'harga_satuan' => 35000000,
                ],
                [
                    'uraian_barang' => 'Mouse Logitech MX Master 3S',
                    'satuan' => 'Unit',
                    'volume' => 2,
                    'harga_satuan' => 1500000,
                ],
            ],
            'dokumen' => [
                UploadedFile::fake()->create('penawaran_vendor.pdf', 300, 'application/pdf'),
            ],
        ];

        $response = $this->post(route('pengajuan.store'), $postData);

        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success');

        // Pastikan RAB tersimpan di database pengajuan_rab
        $pengajuan = PengajuanRab::where('no_rab', 'RAB-2026-888')->first();
        $this->assertNotNull($pengajuan);
        $this->assertEquals('diajukan', $pengajuan->status);
        $this->assertEquals('tinggi', $pengajuan->prioritas);
        $this->assertEquals(73000000.00, (float) $pengajuan->estimasi_total);

        // Pastikan items dan lampiran tersimpan di tabel anak
        $this->assertCount(2, $pengajuan->rincianItem);
        $this->assertCount(1, $pengajuan->dokumenPendukung);
    }

    public function test_admin_can_approve_rab_with_note(): void
    {
        $admin = User::where('role', UserRole::ADMIN)->first();
        $this->actingAs($admin);

        $pendingRab = Rab::where('status', RabStatus::DIAJUKAN)->first();
        $this->assertNotNull($pendingRab);

        $response = $this->post(route('persetujuan.update', $pendingRab), [
            'action' => 'approve',
            'admin_note' => 'Disetujui untuk diproses ke bagian pengadaan.',
        ]);

        $response->assertRedirect(route('persetujuan.index'));
        $response->assertSessionHas('success');

        $pendingRab->refresh();
        $this->assertEquals(RabStatus::DISETUJUI, $pendingRab->status);
        $this->assertEquals('Disetujui untuk diproses ke bagian pengadaan.', $pendingRab->admin_note);
        $this->assertEquals($admin->id, $pendingRab->approved_by);
        $this->assertNotNull($pendingRab->approved_at);
    }

    public function test_admin_can_reject_rab(): void
    {
        $admin = User::where('role', UserRole::ADMIN)->first();
        $this->actingAs($admin);

        $pendingRab = Rab::where('status', RabStatus::DIAJUKAN)->first();

        $response = $this->post(route('persetujuan.update', $pendingRab), [
            'action' => 'reject',
            'admin_note' => 'Alokasi anggaran tahun ini sudah habis.',
        ]);

        $response->assertRedirect(route('persetujuan.index'));
        $pendingRab->refresh();
        $this->assertEquals(RabStatus::DITOLAK, $pendingRab->status);
        $this->assertEquals('Alokasi anggaran tahun ini sudah habis.', $pendingRab->admin_note);
    }

    public function test_user_can_switch_role(): void
    {
        $response = $this->get(route('user.switch', 'user'));
        $response->assertRedirect();
        $this->assertTrue(auth()->check());
        $this->assertEquals(UserRole::USER, auth()->user()->role);

        $response = $this->get(route('user.switch', 'admin'));
        $response->assertRedirect();
        $this->assertEquals(UserRole::ADMIN, auth()->user()->role);
    }
}
