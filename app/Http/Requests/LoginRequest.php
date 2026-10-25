<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Hitung batas maksimum percobaan gagal berdasarkan role target (Poin 3 Tugas):
     * - Role tingkat tinggi (admin_it, pimpinan): maksimal 3 kali gagal.
     * - Role operasional (finance, staff): maksimal 5 kali gagal.
     */
    public function maxAttempts(): int
    {
        $email = (string) $this->input('email');

        // Cari calon pengguna berdasarkan email
        $user = Pengguna::where('email', $email)->first()
            ?? User::where('email', $email)->first();

        if ($user) {
            $role = $user->role instanceof \BackedEnum ? $user->role->value : (string) $user->role;

            // Role Tingkat Tinggi: admin_it dan pimpinan (maks 3 kali)
            if (in_array($role, ['admin_it', 'admin', 'pimpinan'], true)) {
                return 3;
            }

            // Role Operasional: finance dan staff (maks 5 kali)
            if (in_array($role, ['finance', 'staff', 'user'], true)) {
                return 5;
            }
        }

        // Nilai bawaan jika email belum terdaftar di sistem: 5 kali
        return 5;
    }

    /**
     * Pastikan request login belum melebihi batas percobaan gagal (Rate Limiting).
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $maxAttempts = $this->maxAttempts();

        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $maxAttempts)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login gagal untuk akun ini. Akun/IP Anda dikunci sementara. Silakan coba lagi dalam {$seconds} detik.",
        ]);
    }

    /**
     * Dapatkan unique throttle key yang memadukan email dan IP address.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower((string) $this->input('email')).'|'.$this->ip());
    }

    /**
     * Custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => 'Alamat Email',
            'password' => 'Kata Sandi',
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
        ];
    }
}
