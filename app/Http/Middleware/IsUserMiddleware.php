<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Pastikan pengguna sudah terautentikasi
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Periksa apakah pengguna memiliki role 'user'
        if (Auth::user()->role !== 'user') {
            abort(403, 'Akses ditolak. Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
