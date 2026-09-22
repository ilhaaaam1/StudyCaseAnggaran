<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'division', 'position'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string|class-string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * Cek apakah user memiliki peran Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Relasi ke pengajuan RAB yang dibuat oleh user ini.
     */
    public function rabs(): HasMany
    {
        return $this->hasMany(Rab::class, 'user_id');
    }

    /**
     * Relasi ke pengajuan RAB yang disetujui/ditinjau oleh user ini.
     */
    public function approvedRabs(): HasMany
    {
        return $this->hasMany(Rab::class, 'approved_by');
    }

    // PRESENTASI: Pengecekan status delegasi aktif
    // Method ini mengecek apakah pengguna yang sedang login menerima delegasi wewenang
    // yang masih berlaku (berdasarkan tanggal mulai dan selesai serta status aktif).
    public function hasActiveDelegation(): bool
    {
        return DelegationAuthority::where('delegate_to_user_id', $this->id)
            ->where('status', 'Aktif')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->exists();
    }
}
