<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\ActivityLog;
use App\Models\Pengguna;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Tampilkan formulir login.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route($this->getDashboardRoute(Auth::user()));
        }

        return view('auth.login');
    }

    /**
     * Proses autentikasi pengguna dan redirect berdasarkan role.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            ActivityLog::log('Berhasil login ke dalam sistem.');

            /** @var Pengguna $user */
            $user = Auth::user();

            return redirect()->intended(route($this->getDashboardRoute($user)))
                ->with('success', 'Selamat datang kembali, '.$user->nama_lengkap.'!');
        }

        return back()
            ->withErrors(['email' => 'Email atau kata sandi yang Anda masukkan salah.'])
            ->onlyInput('email');
    }

    /**
     * Tentukan nama rute dashboard berdasarkan role akun pengguna.
     */
    private function getDashboardRoute(Pengguna $user): string
    {
        // PRESENTASI: Memperbaiki bug routing post-login
        // Sebelumnya, role 'user' diarahkan ke 'user.dashboard' (view lama). 
        // Disamakan dengan rute sidebar yang mengarah ke 'staff.dashboard' agar memuat view yang sudah dirombak.
        return match ($user->role) {
            'admin' => 'admin.dashboard',
            'admin_it' => 'admin-it.dashboard',
            'finance' => 'finance.dashboard',
            'pimpinan' => 'pimpinan.dashboard',
            'user', 'staff' => 'staff.dashboard',
            default => 'staff.dashboard',
        };
    }

    /**
     * Logout pengguna dari sistem.
     */
    public function logout(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            ActivityLog::log('Logout dari sistem.');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'Anda telah berhasil keluar dari sistem.');
    }
}
