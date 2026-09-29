<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriAnggaran extends Model
{
    protected $table = 'kategori_anggarans';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'pagu_anggaran',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'pagu_anggaran' => 'decimal:2',
    ];
}
