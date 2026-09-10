<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlurPersetujuan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alur_persetujuan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_persetujuan';

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
        'id_pengajuan',
        'id_reviewer',
        'level_persetujuan',
        'status_persetujuan',
        'catatan',
        'tanggal_proses',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level_persetujuan' => 'integer',
            'tanggal_proses' => 'datetime',
        ];
    }

    /**
     * Relasi ke Pengajuan RAB (Many to One).
     */
    public function pengajuanRab(): BelongsTo
    {
        return $this->belongsTo(PengajuanRab::class, 'id_pengajuan', 'id_pengajuan');
    }

    /**
     * Relasi ke Pengguna Reviewer (Many to One).
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_reviewer', 'id_pengguna');
    }
}
