<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\ActivityLog;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Log;

class LogAuthenticationActivity
{
    /**
     * Catat log aktivitas saat percobaan login BERHASIL.
     */
    public function handleLogin(Login $event): void
    {
        $user = $event->user;
        $email = $user->email ?? 'unknown';
        $role = $user->role instanceof \BackedEnum ? $user->role->value : (string) ($user->role ?? 'unknown');
        $ip = request()->ip() ?? '127.0.0.1';
        $waktu = now()->toDateTimeString();
        $userId = $user->id_pengguna ?? $user->id ?? null;

        // 1. Simpan ke database (Tabel activity_logs)
        ActivityLog::create([
            'user_id' => $userId ? (int) $userId : null,
            'activity' => "Percobaan login [Status: Berhasil] | Email: {$email} | Role: {$role}",
            'ip_address' => $ip,
        ]);

        // 2. Simpan ke file log sistem (storage/logs/laravel.log)
        Log::info("Percobaan Login: BERHASIL | Email: {$email} | Role: {$role} | IP: {$ip} | Waktu: {$waktu}", [
            'email' => $email,
            'role' => $role,
            'ip_address' => $ip,
            'waktu' => $waktu,
            'status' => 'Berhasil',
        ]);
    }

    /**
     * Catat log aktivitas saat percobaan login GAGAL.
     */
    public function handleFailed(Failed $event): void
    {
        $credentials = $event->credentials;
        $email = (string) ($credentials['email'] ?? 'unknown');
        $ip = request()->ip() ?? '127.0.0.1';
        $waktu = now()->toDateTimeString();

        // Cari calon pengguna untuk memperoleh informasi role jika email terdaftar
        $user = $event->user
            ?? Pengguna::where('email', $email)->first()
            ?? User::where('email', $email)->first();

        $role = $user
            ? ($user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role)
            : 'tidak_terdaftar';

        $userId = $user ? ($user->id_pengguna ?? $user->id) : null;

        // 1. Simpan ke database (Tabel activity_logs)
        ActivityLog::create([
            'user_id' => $userId ? (int) $userId : null,
            'activity' => "Percobaan login [Status: Gagal] | Email: {$email} | Role: {$role}",
            'ip_address' => $ip,
        ]);

        // 2. Simpan ke file log sistem (storage/logs/laravel.log)
        Log::warning("Percobaan Login: GAGAL | Email: {$email} | Role: {$role} | IP: {$ip} | Waktu: {$waktu}", [
            'email' => $email,
            'role' => $role,
            'ip_address' => $ip,
            'waktu' => $waktu,
            'status' => 'Gagal',
        ]);
    }

    /**
     * Catat log peringatan saat akun/IP terkena Lockout (Rate Limit).
     */
    public function handleLockout(Lockout $event): void
    {
        $email = (string) $event->request->input('email', 'unknown');
        $ip = $event->request->ip() ?? '127.0.0.1';
        $waktu = now()->toDateTimeString();

        Log::alert("Percobaan Login: TERKUNCI (Lockout) | Email: {$email} | IP: {$ip} | Waktu: {$waktu}");
    }
}
