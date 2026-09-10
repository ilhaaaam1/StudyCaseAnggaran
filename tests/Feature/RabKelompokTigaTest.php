<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Divisi;
use App\Models\DokumenPendukung;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RabKelompokTigaTest extends TestCase
{
    use RefreshDatabase;

    protected Divisi $divisi;

    protected Pengguna $admin;

    protected Pengguna $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->divisi = Divisi::create([
            'nama_divisi' => 'Teknologi Informasi',
        ]);

        $this->admin = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Drs. Arif Rachman',
            'jabatan' => 'Direktur Keuangan',
            'email' => 'arif@sirab.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->user = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Sari Dewi',
            'jabatan' => 'Staf IT',
            'email' => 'sari@sirab.local',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }

    /**
     * Test tampilan halaman login.
     */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Sistem Informasi RAB');
    }

    /**
     * Test autentikasi login untuk Administrator diarahkan ke Dashboard Admin.
     */
    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'arif@sirab.local',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    /**
     * Test autentikasi login untuk Staf (User) diarahkan ke Dashboard User.
     */
    public function test_user_login_redirects_to_user_dashboard(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'sari@sirab.local',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($this->user);
    }

    /**
     * Test login gagal dengan kredensial yang salah.
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post(route('login.post'), [
            'email' => 'sari@sirab.local',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test logout pengguna.
     */
    public function test_user_can_logout(): void
    {
        $this->actingAs($this->user);

        $response = $this->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /**
     * Test Dashboard User menampilkan data pengajuan miliknya.
     */
    public function test_user_can_view_dashboard(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('user.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Dashboard Pemohon RAB');
    }

    /**
     * Test Create RAB dengan transaksi DB: pengajuan_rab, rincian_item, upload dokumen, estimasi_total sum.
     */
    public function test_user_can_create_rab_with_transaction_items_and_file_upload(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user);

        $dummyPdf = UploadedFile::fake()->create('proposal_server.pdf', 1024, 'application/pdf');

        $payload = [
            'id_divisi' => $this->divisi->id_divisi,
            'judul_pengajuan' => 'Pengadaan Server Cloud AWS',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'Tinggi',
            'latar_belakang' => 'Kebutuhan migrasi infrastruktur data center.',
            'items' => [
                [
                    'uraian_barang' => 'EC2 Instance Large',
                    'satuan' => 'Bulan',
                    'volume' => 12,
                    'harga_satuan' => 2500000, // 12 * 2.500.000 = 30.000.000
                ],
                [
                    'uraian_barang' => 'RDS Database Instance',
                    'satuan' => 'Bulan',
                    'volume' => 12,
                    'harga_satuan' => 1500000, // 12 * 1.500.000 = 18.000.000
                ],
            ],
            'dokumen' => $dummyPdf,
        ];

        $response = $this->post(route('user.rab.store'), $payload);

        $response->assertSessionHasNoErrors();

        // 1. Verifikasi pengajuan_rab tersimpan dengan status 'Pending' dan total akumulasi
        $pengajuan = PengajuanRab::where('judul_pengajuan', 'Pengadaan Server Cloud AWS')->first();
        $this->assertNotNull($pengajuan);
        $this->assertSame('Pending', $pengajuan->status);
        $this->assertSame($this->user->id_pengguna, $pengajuan->id_pengguna);
        $this->assertEquals(48000000.00, (float) $pengajuan->estimasi_total);

        $response->assertRedirect(route('user.rab.show', $pengajuan->id_pengajuan));

        // 2. Verifikasi rincian_item tersimpan dengan perhitungan $total_harga = $volume * $harga_satuan
        $this->assertDatabaseHas('rincian_item', [
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'uraian_barang' => 'EC2 Instance Large',
            'volume' => 12,
            'harga_satuan' => 2500000.00,
            'total_harga' => 30000000.00,
        ]);

        $this->assertDatabaseHas('rincian_item', [
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'uraian_barang' => 'RDS Database Instance',
            'volume' => 12,
            'harga_satuan' => 1500000.00,
            'total_harga' => 18000000.00,
        ]);

        // 3. Verifikasi dokumen_pendukung tersimpan di storage dan database
        $this->assertDatabaseHas('dokumen_pendukung', [
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'nama_file' => 'proposal_server.pdf',
            'tipe_dokumen' => 'PDF',
        ]);

        $dokumen = DokumenPendukung::where('id_pengajuan', $pengajuan->id_pengajuan)->first();
        $this->assertNotNull($dokumen);
        Storage::disk('public')->assertExists($dokumen->path_file);
    }

    /**
     * Test Administrator memproses approval dengan keputusan 'ACC'.
     */
    public function test_admin_can_approve_rab_with_acc_status(): void
    {
        $pengajuan = PengajuanRab::create([
            'id_pengguna' => $this->user->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'no_rab' => 'RAB-TEST-001',
            'judul_pengajuan' => 'Pengadaan Lisensi Software',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'Sedang',
            'latar_belakang' => 'Lisensi tahunan tim IT.',
            'estimasi_total' => 10000000.00,
            'status' => 'Pending',
            'tanggal_pengajuan' => now(),
        ]);

        $this->actingAs($this->admin);

        $response = $this->post(route('admin.pengajuan.approve', $pengajuan->id_pengajuan), [
            'status' => 'ACC',
            'catatan' => 'Disetujui untuk kebutuhan operasional IT.',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('success');

        // Status di pengajuan_rab harus berubah menjadi 'ACC'
        $pengajuan->refresh();
        $this->assertSame('ACC', $pengajuan->status);

        // Record harus tersimpan di tabel alur_persetujuan
        $this->assertDatabaseHas('alur_persetujuan', [
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'id_reviewer' => $this->admin->id_pengguna,
            'status_persetujuan' => 'ACC',
            'catatan' => 'Disetujui untuk kebutuhan operasional IT.',
        ]);
    }

    /**
     * Test Administrator memproses approval dengan keputusan 'Ditolak'.
     */
    public function test_admin_can_reject_rab_with_ditolak_status(): void
    {
        $pengajuan = PengajuanRab::create([
            'id_pengguna' => $this->user->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'no_rab' => 'RAB-TEST-002',
            'judul_pengajuan' => 'Pengadaan Kursi Gaming',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'Rendah',
            'latar_belakang' => 'Keperluan santai karyawan.',
            'estimasi_total' => 15000000.00,
            'status' => 'Pending',
            'tanggal_pengajuan' => now(),
        ]);

        $this->actingAs($this->admin);

        $response = $this->post(route('admin.pengajuan.approve', $pengajuan->id_pengajuan), [
            'status' => 'Ditolak',
            'catatan' => 'Pengadaan kursi gaming tidak masuk prioritas anggaran saat ini.',
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $pengajuan->refresh();
        $this->assertSame('Ditolak', $pengajuan->status);

        $this->assertDatabaseHas('alur_persetujuan', [
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'id_reviewer' => $this->admin->id_pengguna,
            'status_persetujuan' => 'Ditolak',
        ]);
    }

    /**
     * Test validasi upload dokumen menolak file di atas 5MB (5120 KB).
     */
    public function test_validation_rejects_file_larger_than_5mb(): void
    {
        $this->actingAs($this->user);

        // Buat fake file 6MB (6144 KB)
        $oversizedFile = UploadedFile::fake()->create('dokumen_besar.pdf', 6144, 'application/pdf');

        $response = $this->post(route('user.rab.store'), [
            'id_divisi' => $this->divisi->id_divisi,
            'judul_pengajuan' => 'Pengujian File Besar',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'Sedang',
            'latar_belakang' => 'Testing validasi ukuran file.',
            'items' => [
                [
                    'uraian_barang' => 'Item Test',
                    'satuan' => 'Pcs',
                    'volume' => 1,
                    'harga_satuan' => 10000,
                ],
            ],
            'dokumen' => $oversizedFile,
        ]);

        $response->assertSessionHasErrors('dokumen');
    }

    /**
     * Test hak akses mencegah role User biasa mengakses halaman Admin Dashboard (403).
     */
    public function test_user_cannot_access_admin_dashboard(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    /**
     * Test user dapat melihat histori laporan pengajuannya.
     */
    public function test_user_can_view_laporan_histori(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('user.laporan'));
        $response->assertStatus(200);
        $response->assertSee('Histori Pengajuan Anggaran');
    }

    /**
     * Test admin dapat melihat antrean persetujuan (approvalList).
     */
    public function test_admin_can_view_approval_list(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.approval.list'));
        $response->assertStatus(200);
        $response->assertSee('Antrean Persetujuan RAB');
    }

    /**
     * Test admin dapat melihat rekapitulasi laporan.
     */
    public function test_admin_can_view_laporan_rekapitulasi(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.laporan'));
        $response->assertStatus(200);
        $response->assertSee('Rekapitulasi Realisasi');
    }

    /**
     * Test hak akses mencegah role Admin mengakses halaman User Dashboard (403).
     */
    public function test_admin_cannot_access_user_dashboard(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('user.dashboard'));
        $response->assertStatus(403);
    }

    /**
     * Test Admin Dashboard menghitung nominal riil (tanpa rumus dummy) dan link aksi mengarah ke detail review.
     */
    public function test_admin_dashboard_displays_real_metrics_and_direct_review_links(): void
    {
        $this->actingAs($this->admin);

        $pengajuan1 = PengajuanRab::create([
            'id_pengguna' => $this->user->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'no_rab' => 'RAB-METRIK-001',
            'judul_pengajuan' => 'Pengadaan Laptop Staff',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'Tinggi',
            'latar_belakang' => 'Operasional staf baru.',
            'estimasi_total' => 20000000.00,
            'status' => 'ACC',
            'tanggal_pengajuan' => now(),
        ]);

        $pengajuan2 = PengajuanRab::create([
            'id_pengguna' => $this->user->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'no_rab' => 'RAB-METRIK-002',
            'judul_pengajuan' => 'Pengadaan Lisensi Tool',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'Sedang',
            'latar_belakang' => 'Lisensi pengembangan.',
            'estimasi_total' => 5000000.00,
            'status' => 'Pending',
            'tanggal_pengajuan' => now(),
        ]);

        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Pastikan nominal riil 25.000.000 tampil di Blade
        $response->assertSee('25.000.000');

        // Pastikan link aksi di tabel langsung mengarah ke detail pengajuan
        $response->assertSee(route('admin.pengajuan.show', $pengajuan1->id_pengajuan));
        $response->assertSee(route('admin.pengajuan.show', $pengajuan2->id_pengajuan));
    }

    /**
     * Test Antrean Persetujuan dapat difilter berdasarkan status dan kata kunci pencarian.
     */
    public function test_admin_approval_queue_filters_by_status_and_search(): void
    {
        $this->actingAs($this->admin);

        $pengajuanAcc = PengajuanRab::create([
            'id_pengguna' => $this->user->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'no_rab' => 'RAB-FILTER-ACC',
            'judul_pengajuan' => 'Pengadaan Server Database',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'Tinggi',
            'latar_belakang' => 'Database infra.',
            'estimasi_total' => 50000000.00,
            'status' => 'ACC',
            'tanggal_pengajuan' => now(),
        ]);

        $pengajuanPending = PengajuanRab::create([
            'id_pengguna' => $this->user->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'no_rab' => 'RAB-FILTER-PENDING',
            'judul_pengajuan' => 'Pelatihan AI Developer',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'Sedang',
            'latar_belakang' => 'Upgrading SDM.',
            'estimasi_total' => 12000000.00,
            'status' => 'Pending',
            'tanggal_pengajuan' => now(),
        ]);

        // 1. Filter status Pending
        $responsePending = $this->get(route('admin.approval.list', ['status' => 'Pending']));
        $responsePending->assertStatus(200);
        $responsePending->assertSee('RAB-FILTER-PENDING');
        $responsePending->assertDontSee('RAB-FILTER-ACC');

        // 2. Filter status ACC
        $responseAcc = $this->get(route('admin.approval.list', ['status' => 'ACC']));
        $responseAcc->assertStatus(200);
        $responseAcc->assertSee('RAB-FILTER-ACC');
        $responseAcc->assertDontSee('RAB-FILTER-PENDING');

        // 3. Search kata kunci
        $responseSearch = $this->get(route('admin.approval.list', ['q' => 'Database']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('RAB-FILTER-ACC');
        $responseSearch->assertDontSee('RAB-FILTER-PENDING');
    }

    /**
     * Test Administrator dapat melihat daftar akun staff dan halaman form tambah staff.
     */
    public function test_admin_can_view_staff_management_and_create_form(): void
    {
        $this->actingAs($this->admin);

        // Index
        $responseIndex = $this->get(route('admin.users.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Manajemen Akun Staff');
        $responseIndex->assertSee($this->user->nama_lengkap);

        // Create form
        $responseCreate = $this->get(route('admin.users.create'));
        $responseCreate->assertStatus(200);
        $responseCreate->assertSee('Tambah Akun Staff Baru');
        $responseCreate->assertSee($this->divisi->nama_divisi);
    }

    /**
     * Test Administrator berhasil membuat akun staff baru dengan validasi dan hashing password.
     */
    public function test_admin_can_create_new_staff_account(): void
    {
        $this->actingAs($this->admin);

        $payload = [
            'nama_lengkap' => 'Ahmad Fajar',
            'email' => 'ahmad.fajar@sirab.local',
            'password' => 'secret123',
            'id_divisi' => $this->divisi->id_divisi,
            'jabatan' => 'Junior Network Engineer',
        ];

        $response = $this->post(route('admin.users.store'), $payload);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        // Verifikasi pengguna baru tersimpan dengan role 'user'
        $newStaff = Pengguna::where('email', 'ahmad.fajar@sirab.local')->first();
        $this->assertNotNull($newStaff);
        $this->assertSame('Ahmad Fajar', $newStaff->nama_lengkap);
        $this->assertSame('user', $newStaff->role);
        $this->assertSame($this->divisi->id_divisi, $newStaff->id_divisi);
        $this->assertTrue(Hash::check('secret123', $newStaff->password));
    }

    /**
     * Test validasi pendaftaran akun staff gagal jika field wajib kosong atau email duplikat.
     */
    public function test_admin_create_staff_validation_fails_for_invalid_data(): void
    {
        $this->actingAs($this->admin);

        // Uji email duplikat dan password terlalu pendek
        $response = $this->post(route('admin.users.store'), [
            'nama_lengkap' => '',
            'email' => $this->user->email, // Email duplikat
            'password' => '123', // Kurang dari 6 karakter
            'id_divisi' => 99999, // Divisi tidak ada
        ]);

        $response->assertSessionHasErrors(['nama_lengkap', 'email', 'password', 'id_divisi']);
    }

    /**
     * Test hak akses Staff biasa tidak boleh mengakses halaman manajemen staff admin (403).
     */
    public function test_staff_cannot_access_staff_management(): void
    {
        $this->actingAs($this->user);

        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(403);

        $responseCreate = $this->get(route('admin.users.create'));
        $responseCreate->assertStatus(403);

        $responseEdit = $this->get(route('admin.users.edit', $this->user->id_pengguna));
        $responseEdit->assertStatus(403);

        $responseUpdate = $this->put(route('admin.users.update', $this->user->id_pengguna), []);
        $responseUpdate->assertStatus(403);

        $responseDelete = $this->delete(route('admin.users.destroy', $this->user->id_pengguna));
        $responseDelete->assertStatus(403);
    }

    /**
     * Test Administrator dapat membuka form edit staff dan melihat data lama.
     */
    public function test_admin_can_view_edit_staff_form(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('admin.users.edit', $this->user->id_pengguna));
        $response->assertStatus(200);
        $response->assertSee('Edit Data Akun Staff');
        $response->assertSee($this->user->nama_lengkap);
        $response->assertSee($this->user->email);
        $response->assertSee($this->divisi->nama_divisi);

        // Membuka form edit akun admin harus 404 (hanya user/staff yang dapat diedit di sini)
        $responseAdmin = $this->get(route('admin.users.edit', $this->admin->id_pengguna));
        $responseAdmin->assertStatus(404);
    }

    /**
     * Test Administrator memperbarui data staff tanpa mengganti password lama.
     */
    public function test_admin_can_update_staff_without_changing_password(): void
    {
        $this->actingAs($this->admin);
        $oldPasswordHash = $this->user->password;

        $newDivisi = Divisi::create(['nama_divisi' => 'Sumber Daya Manusia']);

        $payload = [
            'nama_lengkap' => 'Sari Dewi Siregar, S.Kom',
            'email' => 'sari@sirab.local', // Email yang sama (harus lolos unique rule)
            'id_divisi' => $newDivisi->id_divisi,
            'jabatan' => 'Lead IT Support',
            'password' => '', // Password kosong tidak mengganti password lama
        ];

        $response = $this->put(route('admin.users.update', $this->user->id_pengguna), $payload);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->user->refresh();
        $this->assertSame('Sari Dewi Siregar, S.Kom', $this->user->nama_lengkap);
        $this->assertSame('Lead IT Support', $this->user->jabatan);
        $this->assertSame($newDivisi->id_divisi, $this->user->id_divisi);
        $this->assertSame($oldPasswordHash, $this->user->password);
    }

    /**
     * Test Administrator memperbarui password staff dengan password baru.
     */
    public function test_admin_can_update_staff_with_new_password(): void
    {
        $this->actingAs($this->admin);

        $payload = [
            'nama_lengkap' => $this->user->nama_lengkap,
            'email' => $this->user->email,
            'id_divisi' => $this->user->id_divisi,
            'jabatan' => $this->user->jabatan,
            'password' => 'newsecretpass789',
        ];

        $response = $this->put(route('admin.users.update', $this->user->id_pengguna), $payload);
        $response->assertRedirect(route('admin.users.index'));

        $this->user->refresh();
        $this->assertTrue(Hash::check('newsecretpass789', $this->user->password));
    }

    /**
     * Test validasi edit staff gagal jika email bentrok dengan akun staff lain.
     */
    public function test_admin_update_staff_fails_if_email_taken_by_another_user(): void
    {
        $this->actingAs($this->admin);

        // Buat staff lain
        $otherStaff = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Budi Santoso',
            'jabatan' => 'Staf Gudang',
            'email' => 'budi.santoso@sirab.local',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Coba update user pertama menggunakan email user kedua
        $response = $this->put(route('admin.users.update', $this->user->id_pengguna), [
            'nama_lengkap' => 'Sari Dewi',
            'email' => 'budi.santoso@sirab.local', // Duplikat
            'id_divisi' => $this->divisi->id_divisi,
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test proteksi foreign key: Akun staff tidak boleh dihapus jika memiliki riwayat pengajuan RAB.
     */
    public function test_admin_cannot_delete_staff_with_existing_rab_submissions(): void
    {
        $this->actingAs($this->admin);

        // Buat data pengajuan RAB milik user
        PengajuanRab::create([
            'no_rab' => 'RAB-PROTECT-001',
            'judul_pengajuan' => 'Pengadaan Laptop Staff',
            'id_pengguna' => $this->user->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'periode_penggunaan' => 'Maret 2026',
            'prioritas' => 'Sedang',
            'latar_belakang' => 'Kebutuhan perangkat kerja.',
            'estimasi_total' => 15000000,
            'status' => 'Pending',
            'tanggal_pengajuan' => now()->toDateString(),
        ]);

        $response = $this->delete(route('admin.users.destroy', $this->user->id_pengguna));
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('error');

        // Pastikan akun staff tetap ada di database
        $this->assertDatabaseHas('pengguna', [
            'id_pengguna' => $this->user->id_pengguna,
        ]);
    }

    /**
     * Test Administrator berhasil menghapus akun staff yang belum memiliki riwayat pengajuan RAB.
     */
    public function test_admin_can_delete_staff_without_rab_submissions(): void
    {
        $this->actingAs($this->admin);

        $staffToDelete = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Staff Uji Hapus',
            'jabatan' => 'Magang',
            'email' => 'hapus.saya@sirab.local',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $response = $this->delete(route('admin.users.destroy', $staffToDelete->id_pengguna));
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $this->assertModelMissing($staffToDelete);
    }
}
