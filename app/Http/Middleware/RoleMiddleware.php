<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Menangani pengecekan hak akses dinamis berbasis peran (role).
     *
     * @param  Closure(Request): (Response)  $next
     * @param  string  ...$roles  Daftar role yang diizinkan (misal: 'admin_it', 'finance', 'pimpinan', 'staff')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Pastikan pengguna sudah login (autentikasi)
        if (! auth()->check()) {
            abort(403, 'Akses Ditolak (403 Forbidden): Anda harus login terlebih dahulu.');
        }

        $user = auth()->user();

        // Ambil nilai role pengguna baik dari BackedEnum maupun string biasa
        $userRole = $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;

        // Toleransi sinonim role antar model (admin_it <=> admin, staff <=> user)
        $normalizedUserRole = match ($userRole) {
            'admin' => 'admin_it',
            'user' => 'staff',
            default => $userRole,
        };

        // Dukungan pendelegasian wewenang pimpinan jika diimplementasikan
        if (in_array('pimpinan', $roles, true) && method_exists($user, 'hasActiveDelegation') && $user->hasActiveDelegation()) {
            return $next($request);
        }

        // 2. Bandingkan role pengguna dengan daftar role yang diizinkan
        foreach ($roles as $role) {
            $normalizedRole = match ($role) {
                'admin' => 'admin_it',
                'user' => 'staff',
                default => $role,
            };

            if ($normalizedUserRole === $normalizedRole || $userRole === $role) {
                return $next($request);
            }
        }

        // 3. Jika tidak ada role yang cocok, tolak dengan 403 Forbidden
        abort(403, "Akses Ditolak (403 Forbidden): Peran '{$userRole}' Anda tidak memiliki izin untuk mengakses rute ini.");
    }
}
