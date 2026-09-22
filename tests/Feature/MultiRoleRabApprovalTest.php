<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StatusPengajuan;
use App\Models\AlurPersetujuan;
use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MultiRoleRabApprovalTest extends TestCase
{
    use RefreshDatabase;

    protected Divisi $divisi;

    protected Pengguna $staff;

    protected Pengguna $finance;

    protected Pengguna $pimpinan;

    protected Pengguna $adminIt;

    protected function setUp(): void
    {
        parent::setUp();

        $this->divisi = Divisi::create([
            'nama_divisi' => 'Teknologi Informasi',
        ]);

        $this->staff = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Budi Staf',
            'jabatan' => 'Staf Operasional',
            'email' => 'budi.staff@sirab.local',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        $this->finance = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Siti Finance',
            'jabatan' => 'Analis Anggaran',
            'email' => 'siti.finance@sirab.local',
            'password' => Hash::make('password'),
            'role' => 'finance',
        ]);

        $this->pimpinan = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Dr. H. Hendra',
            'jabatan' => 'Direktur Utama',
            'email' => 'hendra.pimpinan@sirab.local',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);

        $this->adminIt = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Fajar Admin IT',
            'jabatan' => 'System Administrator',
            'email' => 'fajar.admin@sirab.local',
            'password' => Hash::make('password'),
            'role' => 'admin_it',
        ]);
    }

    /**
     * 1. Staff membuat pengajuan RAB -> status awal harus 'Pending'.
     */
    public function test_staff_can_create_rab_with_pending_status(): void
    {
        $this->actingAs($this->staff);

        $payload = [
            'id_divisi' => $this->divisi->id_divisi,
            'judul_pengajuan' => 'Pengadaan Lisensi Software 2026',
            'kategori_anggaran' => 'Belanja Modal / Alat Elektronik',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-05',
            'periode_penggunaan' => 'Semester I 2026',
            'latar_belakang' => 'Kebutuhan lisensi software untuk operasional tim IT.',
            'items' => [
                [
                    'uraian_barang' => 'Lisensi Cloud Server',
                    'satuan' => 'Bulan',
                    'volume' => 12,
                    'harga_satuan' => 1000000,
                ],
            ],
        ];

        $response = $this->post(route('staff.rab.store'), $payload);
        $response->assertRedirect(route('staff.riwayat'));
        $response->assertSessionHas('success');

        $rab = PengajuanRab::where('judul_pengajuan', 'Pengadaan Lisensi Software 2026')->first();
        $this->assertNotNull($rab);
        $this->assertSame(StatusPengajuan::MENUNGGU_FINANCE, $rab->status);
        $this->assertSame(12000000.0, (float) $rab->estimasi_total);
        $this->assertSame($this->staff->id_pengguna, $rab->id_pengguna);
    }

    /**
     * 2. Finance meninjau pengajuan 'Menunggu Verifikasi Finance' dan memberikan status 'ACC' (Tahap 1).
     */
    public function test_finance_can_review_and_acc_pending_rab(): void
    {
        $rab = PengajuanRab::create([
            'no_rab' => 'RAB-2026-TEST01',
            'judul_pengajuan' => 'Beli Komputer Kantor',
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'kategori_anggaran' => 'Belanja Modal / Alat Elektronik',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-05',
            'periode_penggunaan' => 'Januari 2026',
            'latar_belakang' => 'Penggantian PC lama.',
            'estimasi_total' => 8000000,
            'status' => StatusPengajuan::MENUNGGU_FINANCE,
            'tanggal_pengajuan' => now(),
        ]);

        $this->actingAs($this->finance);

        // Akses antrean
        $responseAntrean = $this->get(route('finance.antrean'));
        $responseAntrean->assertStatus(200);
        $responseAntrean->assertSee('RAB-2026-TEST01');

        // ACC Tahap 1
        $responseApprove = $this->post(route('finance.approve', $rab->id_pengajuan), [
            'status' => 'ACC',
            'catatan' => 'Anggaran tersedia dalam pagu operasional Q1.',
        ]);

        $responseApprove->assertRedirect(route('finance.antrean'));
        $responseApprove->assertSessionHas('success');

        $rab->refresh();
        $this->assertSame(StatusPengajuan::MENUNGGU_PIMPINAN, $rab->status);

        // Verifikasi alur_persetujuan tercatat level 1
        $log = AlurPersetujuan::where('id_pengajuan', $rab->id_pengajuan)->first();
        $this->assertNotNull($log);
        $this->assertSame(1, $log->level_persetujuan);
        $this->assertSame('ACC', $log->status_persetujuan);
        $this->assertSame($this->finance->id_pengguna, $log->id_reviewer);
    }

    /**
     * 3. Finance dapat menolak pengajuan 'Menunggu Verifikasi Finance' -> status menjadi 'Ditolak'.
     */
    public function test_finance_can_reject_pending_rab(): void
    {
        $rab = PengajuanRab::create([
            'no_rab' => 'RAB-2026-TEST02',
            'judul_pengajuan' => 'Renovasi Ruangan',
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'kategori_anggaran' => 'Pemeliharaan Sarana & Prasarana',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-05',
            'periode_penggunaan' => 'Februari 2026',
            'latar_belakang' => 'Renovasi berkala.',
            'estimasi_total' => 50000000,
            'status' => StatusPengajuan::MENUNGGU_FINANCE,
            'tanggal_pengajuan' => now(),
        ]);

        $this->actingAs($this->finance);

        $responseReject = $this->post(route('finance.approve', $rab->id_pengajuan), [
            'status' => 'Ditolak',
            'catatan' => 'Pagu anggaran tahun ini belum mencukupi untuk renovasi fisik.',
        ]);

        $responseReject->assertRedirect(route('finance.antrean'));

        $rab->refresh();
        $this->assertSame(StatusPengajuan::DITOLAK, $rab->status);

        $log = AlurPersetujuan::where('id_pengajuan', $rab->id_pengajuan)->first();
        $this->assertNotNull($log);
        $this->assertSame(1, $log->level_persetujuan);
        $this->assertSame('Ditolak', $log->status_persetujuan);
    }

    /**
     * 4. Pimpinan meninjau pengajuan 'Menunggu Persetujuan Pimpinan' dan memberikan persetujuan akhir ('Proses Pencairan').
     */
    public function test_pimpinan_can_review_and_acc_final_rab(): void
    {
        $rab = PengajuanRab::create([
            'no_rab' => 'RAB-2026-TEST03',
            'judul_pengajuan' => 'Pengadaan Server Backup',
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'kategori_anggaran' => 'Belanja Modal / Alat Elektronik',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-05',
            'periode_penggunaan' => 'Maret 2026',
            'latar_belakang' => 'Disaster recovery server.',
            'estimasi_total' => 25000000,
            'status' => StatusPengajuan::MENUNGGU_PIMPINAN,
            'tanggal_pengajuan' => now(),
        ]);

        // Catatan review Finance level 1
        AlurPersetujuan::create([
            'id_pengajuan' => $rab->id_pengajuan,
            'id_reviewer' => $this->finance->id_pengguna,
            'level_persetujuan' => 1,
            'status_persetujuan' => 'ACC',
            'catatan' => 'Pagu aman.',
            'tanggal_proses' => now(),
        ]);

        $this->actingAs($this->pimpinan);

        // Akses antrean Pimpinan
        $responseAntrean = $this->get(route('pimpinan.antrean'));
        $responseAntrean->assertStatus(200);
        $responseAntrean->assertSee('RAB-2026-TEST03');

        // ACC Final oleh Pimpinan
        $responseFinal = $this->post(route('pimpinan.approve', $rab->id_pengajuan), [
            'status' => 'ACC',
            'catatan' => 'Disetujui untuk realisasi segera.',
        ]);

        $responseFinal->assertRedirect(route('pimpinan.antrean'));

        $rab->refresh();
        $this->assertSame(StatusPengajuan::PROSES_PENCAIRAN, $rab->status);

        // Verifikasi log persetujuan level 2
        $finalLog = AlurPersetujuan::where('id_pengajuan', $rab->id_pengajuan)
            ->where('level_persetujuan', 2)
            ->first();

        $this->assertNotNull($finalLog);
        $this->assertSame('ACC', $finalLog->status_persetujuan);
        $this->assertSame($this->pimpinan->id_pengguna, $finalLog->id_reviewer);
    }

    /**
     * 5. Pimpinan TIDAK BISA memproses pengajuan yang masih 'Menunggu Verifikasi Finance'.
     */
    public function test_pimpinan_cannot_process_pending_rab_before_finance_review(): void
    {
        $rab = PengajuanRab::create([
            'no_rab' => 'RAB-2026-SKIP01',
            'judul_pengajuan' => 'Pengajuan Tanpa Review Finance',
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'kategori_anggaran' => 'Belanja Modal / Alat Elektronik',
            'tahun_ajaran_semester' => '2026/2027 - Semester Ganjil',
            'tahap_bos' => 'BOS Reguler Tahap 1 (Januari – Juni)',
            'tanggal_mulai' => '2026-08-01',
            'tanggal_selesai' => '2026-08-05',
            'periode_penggunaan' => 'April 2026',
            'latar_belakang' => 'Uji bypass flow.',
            'estimasi_total' => 10000000,
            'status' => StatusPengajuan::MENUNGGU_FINANCE,
            'tanggal_pengajuan' => now(),
        ]);

        $this->actingAs($this->pimpinan);

        $response = $this->post(route('pimpinan.approve', $rab->id_pengajuan), [
            'status' => 'ACC',
        ]);

        $response->assertStatus(422);

        $rab->refresh();
        $this->assertSame(StatusPengajuan::MENUNGGU_FINANCE, $rab->status);
    }

    /**
     * 6. Admin IT TIDAK IKUT SERTA dalam persetujuan RAB dan hak akses terisolasi ketat (RBAC).
     */
    public function test_admin_it_cannot_access_approval_routes_and_roles_are_isolated(): void
    {
        // Admin IT mengakses rute approval finance -> 403 Forbidden
        $this->actingAs($this->adminIt);
        $response = $this->get(route('finance.antrean'));
        $response->assertStatus(403);

        // Staff mengakses dashboard admin IT -> 403 Forbidden
        $this->actingAs($this->staff);
        $responseStaff = $this->get(route('admin-it.dashboard'));
        $responseStaff->assertStatus(403);

        // Finance mengakses dashboard pimpinan -> 403 Forbidden
        $this->actingAs($this->finance);
        $responseFinance = $this->get(route('pimpinan.dashboard'));
        $responseFinance->assertStatus(403);
    }

    /**
     * 7. Admin IT dapat mengelola data master divisi dan manajemen akun 4 role.
     */
    public function test_admin_it_can_manage_users_and_divisions(): void
    {
        $this->actingAs($this->adminIt);

        // 1. Tambah divisi
        $responseDivisi = $this->post(route('admin-it.divisi.store'), [
            'nama_divisi' => 'Sumber Daya Manusia',
        ]);
        $responseDivisi->assertRedirect(route('admin-it.divisi.index'));
        $this->assertDatabaseHas('divisi', ['nama_divisi' => 'Sumber Daya Manusia']);

        $sdmDivisi = Divisi::where('nama_divisi', 'Sumber Daya Manusia')->first();

        // 2. Buat akun baru dengan role pimpinan
        $responseUser = $this->post(route('admin-it.users.store'), [
            'nama_lengkap' => 'Ibu Pimpinan Dua',
            'email' => 'pimpinan2@sirab.local',
            'password' => 'secret123',
            'id_divisi' => $sdmDivisi->id_divisi,
            'role' => 'pimpinan',
            'jabatan' => 'Wakil Direktur',
        ]);
        $responseUser->assertRedirect(route('admin-it.users.index'));

        $newUser = Pengguna::where('email', 'pimpinan2@sirab.local')->first();
        $this->assertNotNull($newUser);
        $this->assertSame('pimpinan', $newUser->role);
        $this->assertTrue($newUser->isPimpinan());
    }
}
