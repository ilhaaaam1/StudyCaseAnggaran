<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\ActivityLog;
use App\Models\Pengguna;
use App\Models\User;
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
     * Beralih peran / akun pengguna secara langsung dari header (Role & Model Switcher).
     */
    public function switchRole(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['nullable', 'string'],
            'email' => ['nullable', 'string', 'email'],
            'id' => ['nullable', 'integer'],
        ]);

        /** @var class-string<Pengguna|User> $modelClass */
        $modelClass = config('auth.providers.users.model', Pengguna::class);
        $user = null;

        if (! empty($validated['id'])) {
            $user = $modelClass::find($validated['id']);
        } elseif (! empty($validated['email'])) {
            $user = $modelClass::where('email', $validated['email'])->first();
        } elseif (! empty($validated['role'])) {
            $role = $validated['role'];
            $defaultEmail = match ($role) {
                'admin', 'admin_it' => 'arif@sirab.local',
                'finance' => 'finance@sirab.local',
                'pimpinan' => 'pimpinan@sirab.local',
                'user', 'staff' => 'sari@sirab.local',
                default => 'sari@sirab.local',
            };
            $user = $modelClass::where('email', $defaultEmail)->first()
                ?? $modelClass::where('role', $role)->first();
        }

        if (! $user) {
            return back()->with('error', 'Akun pengguna untuk peran tersebut tidak ditemukan.');
        }

        Auth::login($user);
        $request->session()->regenerate();

        $roleVal = $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;
        ActivityLog::log('Beralih peran ke: '.$roleVal);

        $roleName = match ($roleVal) {
            'admin', 'admin_it' => 'Administrator IT',
            'finance' => 'Finance (Reviewer Tahap 1)',
            'pimpinan' => 'Pimpinan (Approval Final)',
            'staff', 'user' => 'Staff Pemohon RAB',
            default => ucfirst($roleVal),
        };

        $userName = $user->nama_lengkap ?? $user->name ?? 'Pengguna';

        return redirect()->route($this->getDashboardRoute($user))
            ->with('success', "Berhasil beralih ke peran {$roleName} ({$userName}).");
    }

    /**
     * Tentukan nama rute dashboard berdasarkan role akun pengguna.
     */
    private function getDashboardRoute(Pengguna|User $user): string
    {
        // PRESENTASI: Memperbaiki bug routing post-login
        // Sebelumnya, role 'user' diarahkan ke 'user.dashboard' (view lama).
        // Disamakan dengan rute sidebar yang mengarah ke 'staff.dashboard' agar memuat view yang sudah dirombak.
        $role = $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;

        return match ($role) {
            'admin_it', 'admin' => 'admin-it.dashboard',
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
