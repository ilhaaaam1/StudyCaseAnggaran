<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StatusPengajuan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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
        'tahun_ajaran',
        'semester',
        'tahun_ajaran_semester',
        'tahap_bos',
        'tanggal_mulai',
        'tanggal_selesai',
        'periode_penggunaan',
        'kategori_anggaran',
        'latar_belakang',
        'estimasi_total',
        'status',
        'bukti_pencairan',
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
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'estimasi_total' => 'decimal:2',
            'status' => StatusPengajuan::class,
        ];
    }

    /**
     * Format rentang waktu kegiatan dalam Bahasa Indonesia (contoh: 15 Okt 2026 s/d 18 Okt 2026).
     */
    public function getRentangTanggalFormattedAttribute(): string
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            return $this->tanggal_mulai->translatedFormat('d M Y').' s/d '.$this->tanggal_selesai->translatedFormat('d M Y');
        }

        return $this->periode_penggunaan ?? '-';
    }

    /**
     * Format rentang waktu kegiatan ringkas (contoh: 12–15 Okt 2026).
     */
    public function getRentangTanggalRingkasAttribute(): string
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            if ($this->tanggal_mulai->equalTo($this->tanggal_selesai)) {
                return $this->tanggal_mulai->translatedFormat('d M Y');
            }

            if ($this->tanggal_mulai->format('m Y') === $this->tanggal_selesai->format('m Y')) {
                return $this->tanggal_mulai->format('d').'–'.$this->tanggal_selesai->translatedFormat('d M Y');
            }

            if ($this->tanggal_mulai->format('Y') === $this->tanggal_selesai->format('Y')) {
                return $this->tanggal_mulai->translatedFormat('d M').' – '.$this->tanggal_selesai->translatedFormat('d M Y');
            }

            return $this->tanggal_mulai->translatedFormat('d M Y').' – '.$this->tanggal_selesai->translatedFormat('d M Y');
        }

        return $this->periode_penggunaan ?? '-';
    }

    /**
     * Format tahap penyaluran BOS ringkas (contoh: Tahap 1 atau Tahap 2).
     */
    public function getTahapBosRingkasAttribute(): string
    {
        if (! $this->tahap_bos) {
            return 'BOS Reguler';
        }

        if (str_contains($this->tahap_bos, 'Tahap 1')) {
            return 'Tahap 1';
        }

        if (str_contains($this->tahap_bos, 'Tahap 2')) {
            return 'Tahap 2';
        }

        return Str::limit($this->tahap_bos, 14);
    }

    /**
     * Hitung durasi hari pelaksanaan kegiatan.
     */
    public function getDurasiHariAttribute(): ?int
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            return (int) $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
        }

        return null;
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
