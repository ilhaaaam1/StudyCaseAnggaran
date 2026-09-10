<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Tampilkan daftar seluruh akun Staff (User) beserta data divisinya.
     */
    public function index(Request $request): View
    {
        $search = $request->input('q');
        $divisiFilter = $request->input('id_divisi');

        $query = Pengguna::with('divisi')->where('role', 'user');

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        if ($divisiFilter) {
            $query->where('id_divisi', $divisiFilter);
        }

        $staffList = $query->latest('id_pengguna')->paginate(10)->withQueryString();
        $divisiList = Divisi::orderBy('nama_divisi')->get();
        $totalStaff = Pengguna::where('role', 'user')->count();

        return view('admin.users.index', compact(
            'staffList',
            'divisiList',
            'totalStaff',
            'search',
            'divisiFilter'
        ));
    }

    /**
     * Tampilkan form pembuatan akun Staff baru.
     */
    public function create(): View
    {
        $divisi = Divisi::orderBy('nama_divisi')->get();

        return view('admin.users.create', compact('divisi'));
    }

    /**
     * Simpan data akun Staff baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:pengguna,email'],
            'password' => ['required', 'string', 'min:6'],
            'id_divisi' => ['required', 'integer', 'exists:divisi,id_divisi'],
            'jabatan' => ['nullable', 'string', 'max:100'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap staf wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 150 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di sistem. Gunakan email lain.',
            'password.required' => 'Kata sandi / password wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 6 karakter.',
            'id_divisi.required' => 'Divisi wajib dipilih.',
            'id_divisi.exists' => 'Divisi yang dipilih tidak ditemukan dalam sistem.',
            'jabatan.max' => 'Nama jabatan maksimal 100 karakter.',
        ]);

        // Default jabatan jika dikosongkan
        $divisiObj = Divisi::find($validated['id_divisi']);
        $jabatan = ! empty($validated['jabatan'])
            ? trim((string) $validated['jabatan'])
            : ('Staf '.($divisiObj?->nama_divisi ?? 'Operasional'));

        Pengguna::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => strtolower(trim((string) $validated['email'])),
            'password' => Hash::make($validated['password']),
            'id_divisi' => (int) $validated['id_divisi'],
            'jabatan' => $jabatan,
            'role' => 'user',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Akun Staf {$validated['nama_lengkap']} berhasil dibuat dan siap digunakan.");
    }

    /**
     * Tampilkan form edit data akun Staff.
     */
    public function edit(int $id): View
    {
        $user = Pengguna::where('role', 'user')->findOrFail($id);
        $divisi = Divisi::orderBy('nama_divisi')->get();

        return view('admin.users.edit', compact('user', 'divisi'));
    }

    /**
     * Perbarui data akun Staff di database.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $user = Pengguna::where('role', 'user')->findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:pengguna,email,'.$id.',id_pengguna'],
            'id_divisi' => ['required', 'integer', 'exists:divisi,id_divisi'],
            'password' => ['nullable', 'string', 'min:6'],
            'jabatan' => ['nullable', 'string', 'max:100'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap staf wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 150 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'id_divisi.required' => 'Divisi wajib dipilih.',
            'id_divisi.exists' => 'Divisi yang dipilih tidak valid.',
            'password.min' => 'Password baru minimal terdiri dari 6 karakter.',
            'jabatan.max' => 'Nama jabatan maksimal 100 karakter.',
        ]);

        $divisiObj = Divisi::find($validated['id_divisi']);
        $jabatan = ! empty($validated['jabatan'])
            ? trim((string) $validated['jabatan'])
            : ('Staf '.($divisiObj?->nama_divisi ?? 'Operasional'));

        $updateData = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => strtolower(trim((string) $validated['email'])),
            'id_divisi' => (int) $validated['id_divisi'],
            'jabatan' => $jabatan,
        ];

        // Jika password diisi, update password baru yang telah di-hash
        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', "Data staff {$user->nama_lengkap} berhasil diperbarui.");
    }

    /**
     * Hapus akun Staff dari sistem (dengan proteksi relasi riwayat pengajuan).
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = Pengguna::where('role', 'user')->findOrFail($id);

        // PENTING: Proteksi Relasi Database terhadap Foreign Key
        $hasSubmissions = PengajuanRab::where('id_pengguna', $id)->exists();

        if ($hasSubmissions) {
            return redirect()->route('admin.users.index')
                ->with('error', "Akun {$user->nama_lengkap} tidak dapat dihapus karena sudah memiliki riwayat pengajuan RAB. Silakan nonaktifkan akun sebagai gantinya.");
        }

        $namaStaff = $user->nama_lengkap;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun staff {$namaStaff} berhasil dihapus dari sistem.");
    }
}
