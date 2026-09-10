<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Divisi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'divisi';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_divisi';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the primary key.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_divisi',
    ];

    /**
     * Relasi ke Pengguna (One to Many).
     */
    public function pengguna(): HasMany
    {
        return $this->hasMany(Pengguna::class, 'id_divisi', 'id_divisi');
    }

    /**
     * Relasi ke Pengajuan RAB (One to Many).
     */
    public function pengajuanRab(): HasMany
    {
        return $this->hasMany(PengajuanRab::class, 'id_divisi', 'id_divisi');
    }
}
