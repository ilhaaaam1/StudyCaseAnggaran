<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengajuanRabCustomPkTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_index_displays_pengajuan_rab_with_relations(): void
    {
        $response = $this->get(route('pengajuan.index'));
        $response->assertStatus(200);

        // Pastikan kolom ERD tampil di Blade
        $response->assertSee('RAB-2026-001');
        $response->assertSee('Pengadaan Perangkat Server &amp; Jaringan', false);
        $response->assertSee('Teknologi Informasi');
        $response->assertSee('Sari Dewi');
    }

    public function test_create_form_displays_properly(): void
    {
        $response = $this->get(route('pengajuan.create'));
        $response->assertStatus(200);
        $response->assertSee('name="judul_pengajuan"', false);
        $response->assertSee('name="id_divisi"', false);
        $response->assertSee('name="id_pengguna"', false);
        $response->assertSee('name="rincian[0][uraian_barang]"', false);
    }

    public function test_store_creates_pengajuan_rab_with_nested_items_and_documents(): void
    {
        Storage::fake('public');

        $divisi = Divisi::first();
        $pengguna = Pengguna::first();

        $postData = [
            'no_rab' => 'RAB-2026-999',
            'id_pengguna' => $pengguna->id_pengguna,
            'id_divisi' => $divisi->id_divisi,
            'judul_pengajuan' => 'Pengadaan Laptop Workstation ERD',
            'periode_penggunaan' => 'Q4 2026',
            'prioritas' => 'tinggi',
            'latar_belakang' => 'Kebutuhan workstation baru untuk analisis data proyek.',
            'status' => 'diajukan',
            'rincian' => [
                [
                    'uraian_barang' => 'Workstation Lenovo ThinkStation P620',
                    'satuan' => 'Unit',
                    'volume' => 2,
                    'harga_satuan' => 45000000,
                ],
                [
                    'uraian_barang' => 'UPS Online 2000VA',
                    'satuan' => 'Unit',
                    'volume' => 2,
                    'harga_satuan' => 5000000,
                ],
            ],
            'dokumen' => [
                UploadedFile::fake()->create('spesifikasi_teknis.pdf', 500, 'application/pdf'),
            ],
        ];

        $response = $this->post(route('pengajuan.store'), $postData);

        $response->assertRedirect(route('pengajuan.index'));
        $response->assertSessionHas('success');

        // Verifikasi data induk di tabel pengajuan_rab
        $pengajuan = PengajuanRab::where('no_rab', 'RAB-2026-999')->first();
        $this->assertNotNull($pengajuan);
        $this->assertNotNull($pengajuan->id_pengajuan);
        $this->assertEquals('Pengadaan Laptop Workstation ERD', $pengajuan->judul_pengajuan);
        $this->assertEquals(100000000.00, (float) $pengajuan->estimasi_total);

        // Verifikasi tabel anak rincian_item tersimpan dengan custom foreign key id_pengajuan
        $this->assertCount(2, $pengajuan->rincianItem);
        $firstItem = $pengajuan->rincianItem->first();
        $this->assertEquals('Workstation Lenovo ThinkStation P620', $firstItem->uraian_barang);
        $this->assertEquals($pengajuan->id_pengajuan, $firstItem->id_pengajuan);
        $this->assertEquals(90000000.00, (float) $firstItem->total_harga);

        // Verifikasi tabel anak dokumen_pendukung
        $this->assertCount(1, $pengajuan->dokumenPendukung);
        $doc = $pengajuan->dokumenPendukung->first();
        $this->assertEquals('spesifikasi_teknis.pdf', $doc->nama_file);
        $this->assertEquals($pengajuan->id_pengajuan, $doc->id_pengajuan);
    }

    public function test_show_displays_detail_using_custom_primary_key(): void
    {
        $pengajuan = PengajuanRab::where('no_rab', 'RAB-2026-001')->first();
        $this->assertNotNull($pengajuan);

        $response = $this->get(route('pengajuan.show', $pengajuan->id_pengajuan));
        $response->assertStatus(200);
        $response->assertSee($pengajuan->no_rab);
        $response->assertSee('Pengadaan Perangkat Server &amp; Jaringan', false);
        $response->assertSee('Laptop Dell XPS 15 (Core i7)');
    }
}
