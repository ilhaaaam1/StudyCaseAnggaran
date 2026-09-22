<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengguna';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_pengguna';

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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_divisi',
        'nama_lengkap',
        'jabatan',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Cek apakah pengguna memiliki role Staff (Pemohon RAB).
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff' || $this->role === 'user';
    }

    /**
     * Cek apakah pengguna memiliki role Finance (Reviewer Tahap 1).
     */
    public function isFinance(): bool
    {
        return $this->role === 'finance';
    }

    /**
     * Cek apakah pengguna memiliki role Pimpinan (Reviewer Final / Tahap 2).
     */
    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }

    /**
     * Cek apakah pengguna memiliki role Admin IT (Administrator Sistem).
     */
    public function isAdminIt(): bool
    {
        return $this->role === 'admin_it' || $this->role === 'admin';
    }

    /**
     * Cek apakah pengguna memiliki role Admin (kompatibilitas).
     */
    public function isAdmin(): bool
    {
        return $this->isAdminIt();
    }

    /**
     * Cek apakah pengguna memiliki role User / Staf (kompatibilitas).
     */
    public function isUser(): bool
    {
        return $this->isStaff();
    }

    /**
     * Relasi ke Divisi (Many to One).
     */
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }

    /**
     * Relasi ke Pengajuan RAB yang dibuat oleh pengguna ini (One to Many).
     */
    public function pengajuanRab(): HasMany
    {
        return $this->hasMany(PengajuanRab::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Relasi ke Alur Persetujuan di mana pengguna bertindak sebagai reviewer (One to Many).
     */
    public function alurPersetujuan(): HasMany
    {
        return $this->hasMany(AlurPersetujuan::class, 'id_reviewer', 'id_pengguna');
    }

    // PRESENTASI: Logika Pengecekan Delegasi (Metode Pengecekan Aktif)
    // Fungsi ini mengecek apakah user yang sedang login memiliki record delegasi
    // di tabel delegation_authorities dengan status 'Aktif' dan memvalidasi
    // tanggal hari ini berada di antara start_date dan end_date.
    public function hasActiveDelegation(): bool
    {
        return DelegationAuthority::where('delegate_to_user_id', $this->id_pengguna)
            ->where('status', 'Aktif')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();
    }
}
