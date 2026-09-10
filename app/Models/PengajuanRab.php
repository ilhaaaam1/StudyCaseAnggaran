<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanRab extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengajuan_rab';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_pengajuan';

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
        'id_pengguna',
        'id_divisi',
        'no_rab',
        'judul_pengajuan',
        'periode_penggunaan',
        'prioritas',
        'latar_belakang',
        'estimasi_total',
        'status',
        'tanggal_pengajuan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_pengajuan' => 'datetime',
            'estimasi_total' => 'decimal:2',
        ];
    }

    /**
     * Relasi ke Pengguna pemohon RAB (Many to One).
     */
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Relasi ke Divisi unit kerja RAB (Many to One).
     */
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }

    /**
     * Relasi ke Rincian Item belanja RAB (One to Many).
     */
    public function rincianItem(): HasMany
    {
        return $this->hasMany(RincianItem::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Alias relasi snake_case agar support eager loading rincian_item.
     */
    public function rincian_item(): HasMany
    {
        return $this->rincianItem();
    }

    /**
     * Relasi ke Dokumen Pendukung RAB (One to Many).
     */
    public function dokumenPendukung(): HasMany
    {
        return $this->hasMany(DokumenPendukung::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Alias relasi snake_case agar support eager loading dokumen_pendukung.
     */
    public function dokumen_pendukung(): HasMany
    {
        return $this->dokumenPendukung();
    }

    /**
     * Relasi ke Log/Alur Persetujuan RAB (One to Many).
     */
    public function alurPersetujuan(): HasMany
    {
        return $this->hasMany(AlurPersetujuan::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Alias relasi snake_case agar support eager loading alur_persetujuan.
     */
    public function alur_persetujuan(): HasMany
    {
        return $this->alurPersetujuan();
    }
}
