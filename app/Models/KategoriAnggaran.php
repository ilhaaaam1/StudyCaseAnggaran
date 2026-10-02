<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriAnggaran extends Model
{
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'pagu_anggaran',
    ];

    public function pengajuanRabs()
    {
        return $this->hasMany(PengajuanRab::class, 'kategori_anggaran', 'nama_kategori');
    }
}
