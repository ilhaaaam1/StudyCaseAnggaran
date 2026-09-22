<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Divisi;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected Divisi $divisi;

    protected Pengguna $admin;

    protected Pengguna $staff;

    protected Pengguna $finance;

    protected Pengguna $pimpinan;

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

        $this->staff = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Sari Dewi',
            'jabatan' => 'Staf IT',
            'email' => 'sari@sirab.local',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        $this->finance = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Akun Finance',
            'jabatan' => 'Bendahara',
            'email' => 'finance@sirab.local',
            'password' => Hash::make('password'),
            'role' => 'finance',
        ]);

        $this->pimpinan = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Akun Pimpinan',
            'jabatan' => 'Kepala Sekolah',
            'email' => 'pimpinan@sirab.local',
            'password' => Hash::make('password'),
            'role' => 'pimpinan',
        ]);
    }

    public function test_guest_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login - Sistem Informasi RAB');
    }

    public function test_user_can_login_successfully(): void
    {
        $response = $this->post('/login', [
            'email' => 'arif@sirab.local',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin-it.dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'arif@sirab.local',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_authenticated_user_can_switch_role_from_header(): void
    {
        // Login as staff
        $this->actingAs($this->staff);

        // Switch to finance
        $response = $this->post(route('role.switch'), [
            'role' => 'finance',
            'email' => 'finance@sirab.local',
        ]);

        $response->assertRedirect(route('finance.dashboard'));
        $this->assertAuthenticatedAs($this->finance);

        // Switch to pimpinan
        $response = $this->post(route('role.switch'), [
            'role' => 'pimpinan',
            'email' => 'pimpinan@sirab.local',
        ]);

        $response->assertRedirect(route('pimpinan.dashboard'));
        $this->assertAuthenticatedAs($this->pimpinan);

        // Switch to admin
        $response = $this->post(route('role.switch'), [
            'role' => 'admin',
            'email' => 'arif@sirab.local',
        ]);

        $response->assertRedirect(route('admin-it.dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_authenticated_user_sees_role_switcher_in_header(): void
    {
        $this->actingAs($this->staff);

        $response = $this->get(route('staff.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Role Model Switch');
        $response->assertSee('Staff');
        $response->assertSee('arif@sirab.local');
        $response->assertSee('finance@sirab.local');
        $response->assertSee('pimpinan@sirab.local');
    }

    public function test_user_can_logout(): void
    {
        $this->actingAs($this->staff);

        $response = $this->post('/logout');
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
