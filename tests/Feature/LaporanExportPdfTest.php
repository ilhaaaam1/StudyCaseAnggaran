<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StatusPengajuan;
use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use App\Models\RincianItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LaporanExportPdfTest extends TestCase
{
    use RefreshDatabase;

    protected Pengguna $user;

    protected Pengguna $financeUser;

    protected Divisi $divisi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->divisi = Divisi::create([
            'nama_divisi' => 'Sarana Prasarana',
        ]);

        $this->user = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Budi Santoso',
            'jabatan' => 'Kepala Sarpras',
            'email' => 'sarpras@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        $this->financeUser = Pengguna::create([
            'id_divisi' => $this->divisi->id_divisi,
            'nama_lengkap' => 'Siti Rahmawati',
            'jabatan' => 'Bendahara Sekolah',
            'email' => 'finance@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'finance',
        ]);

        $pengajuan = PengajuanRab::create([
            'id_pengguna' => $this->user->id_pengguna,
            'id_divisi' => $this->divisi->id_divisi,
            'no_rab' => 'RAB-2026-SARPRAS-001',
            'judul_pengajuan' => 'Pengadaan Meja Kursi Kelas 1',
            'latar_belakang' => 'Kebutuhan peremajaan sarana belajar siswa kelas 1.',
            'kategori_anggaran' => 'BOS Reguler',
            'estimasi_total' => 15000000.00,
            'status' => StatusPengajuan::SELESAI,
            'tanggal_pengajuan' => now(),
        ]);

        RincianItem::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'uraian_barang' => 'Meja Siswa Kayu Jati',
            'satuan' => 'Unit',
            'volume' => 30,
            'harga_satuan' => 350000,
            'total_harga' => 10500000,
        ]);

        RincianItem::create([
            'id_pengajuan' => $pengajuan->id_pengajuan,
            'uraian_barang' => 'Kursi Siswa Kayu Jati',
            'satuan' => 'Unit',
            'volume' => 30,
            'harga_satuan' => 150000,
            'total_harga' => 4500000,
        ]);
    }

    /**
     * Tamu (Guest) yang belum login tidak boleh mengakses route ekspor PDF.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('laporan.pengajuan.pdf'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Pengguna terautentikasi dapat mengunduh Laporan Pengajuan RAB format PDF.
     */
    public function test_authenticated_user_can_export_pdf_pengajuan(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('laporan.pengajuan.pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');

        $contentDisposition = $response->headers->get('content-disposition');
        $this->assertNotNull($contentDisposition);
        $this->assertStringContainsString('attachment;', $contentDisposition);
        $this->assertStringContainsString('laporan-pengajuan-rab-'.date('Y-m-d').'.pdf', $contentDisposition);
    }

    /**
     * Opsi stream mengembalikan response PDF untuk preview tab browser.
     */
    public function test_authenticated_user_can_stream_pdf_pengajuan(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('laporan.pengajuan.pdf', ['stream' => '1']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    /**
     * Memverifikasi Eager Loading relasi user, divisi, dan items pada model PengajuanRab.
     */
    public function test_eager_loading_relations_on_pengajuan_rab(): void
    {
        $pengajuan = PengajuanRab::with(['user', 'divisi', 'items'])->first();

        $this->assertNotNull($pengajuan);
        $this->assertEquals('Budi Santoso', $pengajuan->user->nama_lengkap);
        $this->assertEquals('Budi Santoso', $pengajuan->user->name);
        $this->assertEquals('Sarana Prasarana', $pengajuan->divisi->nama_divisi);
        $this->assertCount(2, $pengajuan->items);
    }

    /**
     * Memverifikasi fitur Cetak PDF pada menu Rekapitulasi Laporan (Finance) mengunduh file A4 Landscape.
     */
    public function test_finance_can_export_pdf_rekapitulasi_download(): void
    {
        $response = $this->actingAs($this->financeUser)
            ->get(route('finance.rekapitulasi.pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');

        $contentDisposition = $response->headers->get('content-disposition');
        $this->assertNotNull($contentDisposition);
        $this->assertStringContainsString('attachment;', $contentDisposition);
        $this->assertStringContainsString('Laporan_Rekapitulasi_SIRAB_'.date('Y-m-d').'.pdf', $contentDisposition);
    }

    /**
     * Memverifikasi opsi preview/stream pada Cetak PDF Rekapitulasi Laporan.
     */
    public function test_finance_can_stream_pdf_rekapitulasi(): void
    {
        $response = $this->actingAs($this->financeUser)
            ->get(route('finance.rekapitulasi.pdf', ['action' => 'stream']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
