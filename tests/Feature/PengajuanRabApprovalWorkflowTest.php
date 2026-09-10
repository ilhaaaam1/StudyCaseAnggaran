<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\AlurPersetujuan;
use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajuanRabApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_staff_submission_defaults_to_diajukan_status(): void
    {
        $divisi = Divisi::first();
        $pengguna = Pengguna::first();

        $postData = [
            'no_rab' => 'RAB-2026-TEST-PENDING',
            'id_pengguna' => $pengguna->id_pengguna,
            'id_divisi' => $divisi->id_divisi,
            'judul_pengajuan' => 'Pengadaan Monitor LED Desain',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'sedang',
            'latar_belakang' => 'Kebutuhan monitor tambahan untuk desainer grafis.',
            // status tidak dikirimkan, harus default ke 'diajukan'
            'rincian' => [
                [
                    'uraian_barang' => 'Monitor 24 inch IPS',
                    'satuan' => 'Unit',
                    'volume' => 3,
                    'harga_satuan' => 2500000,
                ],
            ],
        ];

        $response = $this->post(route('pengajuan.store'), $postData);
        $response->assertRedirect(route('pengajuan.index'));

        $pengajuan = PengajuanRab::where('no_rab', 'RAB-2026-TEST-PENDING')->first();
        $this->assertNotNull($pengajuan);
        $this->assertEquals('diajukan', $pengajuan->status);
    }

    public function test_admin_can_view_pending_approval_queue(): void
    {
        $response = $this->get(route('pengajuan.persetujuan.index'));
        $response->assertStatus(200);

        // RAB-2026-002 pada seeder memiliki status 'diajukan'
        $response->assertSee('RAB-2026-002');
        $response->assertSee('Pelatihan SDM &amp; Sertifikasi Karyawan Q4', false);

        // RAB-2026-001 berstatus 'disetujui', jadi tidak boleh muncul di antrean pending
        $response->assertDontSee('Pengadaan Perangkat Server &amp; Jaringan', false);
    }

    public function test_admin_can_approve_pengajuan_using_db_transaction(): void
    {
        $admin = User::where('email', 'arif@sirab.local')->first();
        $this->actingAs($admin);

        // Cari pengajuan yang berstatus 'diajukan'
        $pengajuan = PengajuanRab::where('status', 'diajukan')->firstOrFail();
        $pengajuanId = $pengajuan->id_pengajuan;

        $response = $this->put(route('pengajuan.approval', $pengajuanId), [
            'status_persetujuan' => 'disetujui',
            'catatan' => 'Disetujui. Spesifikasi teknis dan anggaran telah sesuai plafon belanja.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // 1. Verifikasi tabel pengajuan_rab ter-update statusnya menjadi 'disetujui'
        $pengajuan->refresh();
        $this->assertEquals('disetujui', $pengajuan->status);

        // 2. Verifikasi tabel alur_persetujuan memiliki log baru dengan reviewer dan catatan
        $logPersetujuan = AlurPersetujuan::where('id_pengajuan', $pengajuanId)->latest('id_persetujuan')->first();
        $this->assertNotNull($logPersetujuan);
        $this->assertEquals('disetujui', $logPersetujuan->status_persetujuan);
        $this->assertEquals('Disetujui. Spesifikasi teknis dan anggaran telah sesuai plafon belanja.', $logPersetujuan->catatan);
        $this->assertEquals(1, $logPersetujuan->level_persetujuan);
        $this->assertNotNull($logPersetujuan->tanggal_proses);

        // Pastikan id_reviewer terisi dengan id_pengguna admin (Drs. Arif Rachman = 1)
        $this->assertEquals(1, $logPersetujuan->id_reviewer);

        // 3. Verifikasi halaman detail menampilkan catatan dan riwayat
        $detailResponse = $this->get(route('pengajuan.show', $pengajuanId));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Disetujui. Spesifikasi teknis dan anggaran telah sesuai plafon belanja.');
    }

    public function test_admin_can_reject_pengajuan_with_note(): void
    {
        $admin = User::where('email', 'arif@sirab.local')->first();
        $this->actingAs($admin);

        $pengajuan = PengajuanRab::where('status', 'diajukan')->firstOrFail();
        $pengajuanId = $pengajuan->id_pengajuan;

        $response = $this->put(route('pengajuan.approval', $pengajuanId), [
            'status_persetujuan' => 'ditolak',
            'catatan' => 'Ditolak karena tidak sesuai dengan prioritas divisi kuartal ini.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $pengajuan->refresh();
        $this->assertEquals('ditolak', $pengajuan->status);

        $log = AlurPersetujuan::where('id_pengajuan', $pengajuanId)->latest('id_persetujuan')->first();
        $this->assertNotNull($log);
        $this->assertEquals('ditolak', $log->status_persetujuan);
        $this->assertEquals('Ditolak karena tidak sesuai dengan prioritas divisi kuartal ini.', $log->catatan);
    }
}
