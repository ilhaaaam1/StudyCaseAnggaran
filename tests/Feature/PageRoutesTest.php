<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_redirects_to_dashboard(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/dashboard');
    }

    public function test_dashboard_page_returns_success(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Anggaran');
    }

    public function test_pengajuan_page_returns_success(): void
    {
        $response = $this->get('/pengajuan');
        $response->assertStatus(200);
        $response->assertSee('Daftar Pengajuan Anggaran');
    }

    public function test_pengajuan_create_page_returns_success(): void
    {
        $response = $this->get('/pengajuan/create');
        $response->assertStatus(200);
        $response->assertSee('Buat Pengajuan Anggaran');
    }

    public function test_rab_page_returns_success(): void
    {
        $response = $this->get('/rab');
        $response->assertStatus(200);
        $response->assertSee('Daftar Pengajuan RAB');
    }

    public function test_persetujuan_page_returns_success(): void
    {
        $response = $this->get('/persetujuan');
        $response->assertStatus(200);
        $response->assertSee('Antrean Persetujuan');
    }

    public function test_laporan_page_returns_success(): void
    {
        $response = $this->get('/laporan');
        $response->assertStatus(200);
        $response->assertSee('Laporan Anggaran');
    }

    public function test_pengajuan_persetujuan_page_returns_success(): void
    {
        $response = $this->get(route('pengajuan.persetujuan.index'));
        $response->assertStatus(200);
        $response->assertSee('Antrean Persetujuan RAB');
    }
}
