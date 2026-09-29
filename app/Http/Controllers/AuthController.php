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
            'email' => ['nullable', 'string'],
            'id' => ['nullable'],
        ]);

        /** @var class-string<Pengguna|User> $modelClass */
        $modelClass = config('auth.providers.users.model', Pengguna::class);
        $primaryKey = (new $modelClass)->getKeyName();
        $user = null;

        // 1. Cari berdasarkan ID pada model aktif yang dikonfigurasi
        if (! empty($validated['id'])) {
            $user = $modelClass::where($primaryKey, $validated['id'])->first();
        }

        // 2. Fallback pencarian berdasarkan Email jika lewat ID tidak ditemukan
        if (! $user && ! empty($validated['email'])) {
            $user = $modelClass::where('email', trim((string) $validated['email']))->first();
        }

        // 3. Fallback pencarian berdasarkan Role jika ID dan Email belum berhasil
        if (! $user && ! empty($validated['role'])) {
            $roleInput = strtolower(trim((string) $validated['role']));
            $targetRoles = match ($roleInput) {
                'admin', 'admin_it' => ['admin_it', 'admin'],
                'staff', 'user' => ['staff', 'user'],
                'finance' => ['finance'],
                'pimpinan' => ['pimpinan'],
                default => [$roleInput],
            };

            $user = $modelClass::whereIn('role', $targetRoles)->first();
        }

        // 4. Fallback ke model alternatif jika model utama tidak menemukan data (dukungan interoperabilitas User vs Pengguna)
        if (! $user) {
            $altModelClass = ($modelClass === Pengguna::class) ? User::class : Pengguna::class;
            if (class_exists($altModelClass)) {
                $altPrimaryKey = (new $altModelClass)->getKeyName();
                if (! empty($validated['id'])) {
                    $user = $altModelClass::where($altPrimaryKey, $validated['id'])->first();
                }
                if (! $user && ! empty($validated['email'])) {
                    $user = $altModelClass::where('email', trim((string) $validated['email']))->first();
                }
                if (! $user && ! empty($validated['role'])) {
                    $roleInput = strtolower(trim((string) $validated['role']));
                    $targetRoles = match ($roleInput) {
                        'admin', 'admin_it' => ['admin_it', 'admin'],
                        'staff', 'user' => ['staff', 'user'],
                        'finance' => ['finance'],
                        'pimpinan' => ['pimpinan'],
                        default => [$roleInput],
                    };
                    $user = $altModelClass::whereIn('role', $targetRoles)->first();
                }

                // Jika user ditemukan di model alternatif, sinkronkan ke instans modelClass bila record tersedia
                if ($user && get_class($user) !== $modelClass && ! empty($user->email)) {
                    $synced = $modelClass::where('email', $user->email)->first();
                    if ($synced) {
                        $user = $synced;
                    }
                }
            }
        }

        if (! $user) {
            return back()->with('error', 'Akun pengguna untuk berpindah peran tidak ditemukan di database.');
        }

        // Hindari kegagalan Auth::loginUsingId() jika model menggunakan custom primary key (id_pengguna).
        // Gunakan Auth::login($user) secara langsung setelah instans model ditemukan.
        Auth::login($user);

        // Pastikan session diperbarui dan di-regenerate dengan aman
        $request->session()->regenerate();

        $roleVal = $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;

        try {
            ActivityLog::log('Beralih peran ke: '.$roleVal);
        } catch (\Throwable) {
            // Diamkan error pencatatan log agar switch akun tidak gagal
        }

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
