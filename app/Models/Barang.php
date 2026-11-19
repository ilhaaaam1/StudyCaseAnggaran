<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    /**
     * Nama tabel database.
     *
     * @var string
     */
    protected $table = 'barangs';

    /**
     * Kolom yang dapat diisi secara massal (mass assignable).
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'kategori',
        'jumlah',
        'kondisi',
        'keterangan',
        'gambar',
    ];

    /**
     * Tipe data casting atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
        ];
    }

    /**
     * Accessor: Kode unik barang dari kolom id dengan format tetap (INV-XXXXX).
     */
    public function getKodeBarangAttribute(): string
    {
        return 'INV-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Method: Menghasilkan representasi visual Barcode 1D (Code 39) dalam format raw HTML.
     */
    public function barcodeHtml(int $width = 3, int $height = 100): string
    {
        return \DNS1D::getBarcodeHTML($this->kode_barang, 'C39', $width, $height);
    }

    /**
     * Method: Menghasilkan representasi visual Barcode 2D (QR Code) dalam format raw HTML.
     */
    public function qrcodeHtml(int $width = 5, int $height = 5): string
    {
        return \DNS2D::getBarcodeHTML($this->kode_barang, 'QRCODE', $width, $height);
    }
}
