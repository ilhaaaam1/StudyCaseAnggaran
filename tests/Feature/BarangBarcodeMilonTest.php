<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Divisi;
use App\Models\Pengguna;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BarangBarcodeMilonTest extends TestCase
{
    use RefreshDatabase;

    protected Pengguna $user;

    protected Barang $barang;

    protected function setUp(): void
    {
        parent::setUp();

        $divisi = Divisi::create([
            'nama_divisi' => 'Sarana & Prasarana',
        ]);

        $this->user = Pengguna::create([
            'id_divisi' => $divisi->id_divisi,
            'nama_lengkap' => 'Staff Inventaris',
            'jabatan' => 'Pengelola Aset',
            'email' => 'aset@sekolah.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'staff',
        ]);

        $this->barang = Barang::create([
            'nama' => 'Laptop HP Pavilion 14',
            'kategori' => 'Komputer & Laptop',
            'jumlah' => 10,
            'kondisi' => 'baik',
            'keterangan' => 'Pengadaan BOS Sarpras',
        ]);
    }

    /**
     * Memverifikasi accessor kode_barang menghasilkan format INV-XXXXX.
     */
    public function test_barang_has_kode_barang_accessor(): void
    {
        $this->assertEquals('INV-'.str_pad((string) $this->barang->id, 5, '0', STR_PAD_LEFT), $this->barang->kode_barang);
    }

    /**
     * Memverifikasi method barcodeHtml menghasilkan elemen HTML Code 39 (Tugas 1 & 2).
     */
    public function test_barang_can_generate_1d_barcode_html(): void
    {
        $standardBarcode = $this->barang->barcodeHtml(3, 100);
        $compactBarcode = $this->barang->barcodeHtml(2, 50);

        $this->assertNotEmpty($standardBarcode);
        $this->assertStringContainsString('background-color:black', $standardBarcode);
        $this->assertStringContainsString('528px', $standardBarcode);

        $this->assertNotEmpty($compactBarcode);
        $this->assertStringContainsString('background-color:black', $compactBarcode);
        $this->assertStringContainsString('50px', $compactBarcode);
    }

    /**
     * Memverifikasi method qrcodeHtml menghasilkan elemen QR Code 2D (Tugas 3).
     */
    public function test_barang_can_generate_qr_code_html(): void
    {
        $qrcode = $this->barang->qrcodeHtml(4, 4);

        $this->assertNotEmpty($qrcode);
        $this->assertStringContainsString('background-color:black', $qrcode);
    }

    /**
     * Memverifikasi halaman detail barang (/barang/{id}) menampilkan barcode dan identitas barang.
     */
    public function test_barang_show_page_displays_barcode_and_qr_code(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('barang.show', $this->barang->id));

        $response->assertOk();
        $response->assertSee('Barcode Barang');
        $response->assertSee($this->barang->kode_barang);
        $response->assertSee('Cetak Barcode');
    }
}
