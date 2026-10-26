<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StatusPengajuan;
use App\Models\ActivityLog;
use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class MiddlewareRbacThrottleTest extends TestCase
{
    use RefreshDatabase;

    protected Divisi $divisi;

    protected Pengguna $adminIt;

    protected Pengguna $pimpinan;

    protected Pengguna $finance;

    protected Pengguna $staff;

    protected function setUp(): void
    {
        parent::setUp();

        RateLimiter::clear('adminit@sirab|127.0.0.1');
        RateLimiter::clear('pimpinan@sirab|127.0.0.1');
        RateLimiter::clear('finance@sirab|127.0.0.1');
        RateLimiter::clear('staff@sirab|127.0.0.1');

        $this->divisi = Divisi::create(['nama_divisi' => 'Teknologi Informasi']);

        $this->adminIt = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Admin IT Master',
            'jabatan' => 'Kepala IT',
            'email' => 'adminit@sirab',
            'password' => Hash::make('password123'),
            'role' => 'admin_it',
        ]);

        $this->pimpinan = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Kepala Sekolah Pimpinan',
            'jabatan' => 'Kepala Sekolah',
            'email' => 'pimpinan@sirab',
            'password' => Hash::make('password123'),
            'role' => 'pimpinan',
        ]);

        $this->finance = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Bendahara Finance',
            'jabatan' => 'Bendahara Sekolah',
            'email' => 'finance@sirab',
            'password' => Hash::make('password123'),
            'role' => 'finance',
        ]);

        $this->staff = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Staff Pemohon',
            'jabatan' => 'Staff Perpustakaan',
            'email' => 'staff@sirab',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);
    }

    /**
     * Uji Poin 1, 2, & 6: Simulasi alias role berfungsi dan menolak role lain dengan 403.
     */
    public function test_alias_role_allows_authorized_users_and_blocks_unauthorized_with_403(): void
    {
        // 1. Staff mencoba akses simulasi role admin-it -> 403 Forbidden
        $response = $this->actingAs($this->staff)->get('/simulasi-role/admin-it');
        $response->assertStatus(403);

        // 2. Staff mengakses simulasi role staff -> 200 OK
        $response = $this->actingAs($this->staff)->get('/simulasi-role/staff');
        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => 'success']);

        // 3. Finance mengakses simulasi role finance -> 200 OK
        $response = $this->actingAs($this->finance)->get('/simulasi-role/finance');
        $response->assertStatus(200);

        // 4. Finance mencoba akses simulasi role pimpinan -> 403 Forbidden
        $response = $this->actingAs($this->finance)->get('/simulasi-role/pimpinan');
        $response->assertStatus(403);

        // 5. Admin IT mengakses simulasi role admin-it -> 200 OK
        $response = $this->actingAs($this->adminIt)->get('/simulasi-role/admin-it');
        $response->assertStatus(200);
    }

    /**
     * Uji Poin 3: Rate Limiting role tingkat tinggi (admin_it / pimpinan) dikunci setelah 3 kali gagal.
     */
    public function test_high_level_role_is_throttled_after_3_failed_attempts(): void
    {
        // Percobaan 1, 2, 3 gagal
        for ($i = 1; $i <= 3; $i++) {
            $response = $this->from('/login')->post('/login', [
                'email' => 'adminit@sirab',
                'password' => 'passwordsalah',
            ]);
            $response->assertSessionHasErrors('email');
        }

        // Percobaan ke-4 harus terkunci (Validation Exception / Lockout pesan terkunci)
        $response = $this->from('/login')->post('/login', [
            'email' => 'adminit@sirab',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errorMessage = session('errors')->first('email');
        $this->assertStringContainsString('dikunci', strtolower($errorMessage));
    }

    /**
     * Uji Poin 3: Rate Limiting role operasional (staff / finance) dikunci setelah 5 kali gagal.
     */
    public function test_operational_role_is_throttled_after_5_failed_attempts(): void
    {
        // Percobaan 1 s.d. 5 gagal
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->from('/login')->post('/login', [
                'email' => 'staff@sirab',
                'password' => 'passwordsalah',
            ]);
            $response->assertSessionHasErrors('email');
        }

        // Percobaan ke-6 harus terkunci
        $response = $this->from('/login')->post('/login', [
            'email' => 'staff@sirab',
            'password' => 'passwordsalah',
        ]);

        $response->assertSessionHasErrors(['email']);
        $errorMessage = session('errors')->first('email');
        $this->assertStringContainsString('dikunci', strtolower($errorMessage));
    }

    /**
     * Uji Poin 4: Log aktivitas mencatat Email, Role, IP, Waktu, dan Status (Berhasil/Gagal).
     */
    public function test_login_attempts_are_recorded_to_activity_logs(): void
    {
        // 1. Catat login gagal
        $this->post('/login', [
            'email' => 'adminit@sirab',
            'password' => 'salah',
        ]);

        $failedLog = ActivityLog::where('activity', 'like', '%Status: Gagal%')
            ->where('activity', 'like', '%adminit@sirab%')
            ->first();

        $this->assertNotNull($failedLog);
        $this->assertStringContainsString('admin_it', $failedLog->activity);

        // 2. Catat login berhasil
        $this->post('/login', [
            'email' => 'adminit@sirab',
            'password' => 'password123',
        ]);

        $successLog = ActivityLog::where('activity', 'like', '%Status: Berhasil%')
            ->where('activity', 'like', '%adminit@sirab%')
            ->first();

        $this->assertNotNull($successLog);
        $this->assertStringContainsString('admin_it', $successLog->activity);
        $this->assertNotEmpty($successLog->ip_address);
    }

    /**
     * Uji Poin 5: Proteksi CRUD - Staff bisa Read & Create pengajuan, tapi Ditolak (403) pada Antrean, Approve, dan Destroy.
     */
    public function test_staff_cannot_create_update_or_delete_budget_rab(): void
    {
        $pengajuan = PengajuanRab::create([
            'id_pengguna' => $this->staff->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'no_rab' => 'RAB-2026-999',
            'judul_pengajuan' => 'Pengadaan Buku',
            'periode_penggunaan' => 'Q1 2026',
            'prioritas' => 'Sedang',
            'latar_belakang' => 'Kebutuhan perpustakaan',
            'estimasi_total' => 1000000,
            'status' => StatusPengajuan::MENUNGGU_FINANCE->value,
            'tanggal_pengajuan' => now(),
        ]);

        // Staff buka index -> 200 OK
        $response = $this->actingAs($this->staff)->get('/pengajuan');
        $response->assertStatus(200);

        // Staff buka show -> 200 OK
        $response = $this->actingAs($this->staff)->get('/pengajuan/'.$pengajuan->id_pengajuan);
        $response->assertStatus(200);

        // Staff buka create -> 200 OK (Alur normal: Staff diizinkan mengajukan anggaran)
        $response = $this->actingAs($this->staff)->get('/pengajuan/create');
        $response->assertStatus(200);

        // Staff coba bypass buka antrean persetujuan verifikasi finance -> 403 Forbidden!
        $response = $this->actingAs($this->staff)->get('/pengajuan/antrean');
        $response->assertStatus(403);

        // Staff coba eksekusi persetujuan (approve) anggaran -> 403 Forbidden!
        $response = $this->actingAs($this->staff)->post('/pengajuan/'.$pengajuan->id_pengajuan.'/approve', [
            'status_persetujuan' => 'disetujui',
        ]);
        $response->assertStatus(403);

        // Staff coba destroy / hapus anggaran -> 403 Forbidden!
        $response = $this->actingAs($this->staff)->delete('/pengajuan/'.$pengajuan->id_pengajuan);
        $response->assertStatus(403);

        // Staff coba bypass buka modul finance -> 403 Forbidden!
        $response = $this->actingAs($this->staff)->get('/finance/antrean');
        $response->assertStatus(403);

        // Staff coba bypass buka modul pimpinan -> 403 Forbidden!
        $response = $this->actingAs($this->staff)->get('/pimpinan/antrean');
        $response->assertStatus(403);

        // Staff coba bypass buka modul admin-it -> 403 Forbidden!
        $response = $this->actingAs($this->staff)->get('/admin-it/users');
        $response->assertStatus(403);
    }

    /**
     * Uji Poin 5: Finance & Pimpinan diizinkan mengakses Create, Update, dan Delete.
     */
    public function test_finance_and_pimpinan_can_access_crud_mutations(): void
    {
        // Finance buka create -> 200 OK
        $response = $this->actingAs($this->finance)->get('/pengajuan/create');
        $response->assertStatus(200);

        // Pimpinan buka create -> 200 OK
        $response = $this->actingAs($this->pimpinan)->get('/pengajuan/create');
        $response->assertStatus(200);
    }
}
