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
}
