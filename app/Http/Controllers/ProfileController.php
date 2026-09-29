<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan form pengaturan akun.
     */
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui profil pengguna (Nama, No HP, dan Foto Profil).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // PRESENTASI: Validasi data profil (termasuk penambahan NIP)
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'nip' => ['nullable', 'string', 'max:25'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // PRESENTASI: Menangkap data dari request untuk disimpan (termasuk NIP)
        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'nip' => $request->nip,
            'no_hp' => $request->no_hp,
        ];

        // PRESENTASI: Logika Upload Foto Profil
        // 1. Cek apakah ada file foto_profil yang diunggah
        if ($request->hasFile('foto_profil')) {
            // 2. Jika user sudah punya foto sebelumnya, hapus foto lama dari storage
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            // 3. Simpan file baru ke folder 'profile-photos' di disk public (storage/app/public/profile-photos)
            $path = $request->file('foto_profil')->store('profile-photos', 'public');

            // 4. Masukkan path ke dalam array data yang akan di-update ke database
            $data['foto_profil'] = $path;
        }

        $user->update($data);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Menghapus foto profil pengguna.
     */
    public function deletePhoto()
    {
        $user = Auth::user();

        // PRESENTASI: Logika Penghapusan Foto Profil
        // 1. Cek apakah pengguna memiliki foto profil yang tersimpan
        if ($user->foto_profil) {
            // 2. Jika file foto fisik benar-benar ada di storage server, maka hapus filenya
            if (Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            // 3. Update nilai kolom 'foto_profil' di database menjadi null
            $user->update(['foto_profil' => null]);
        }

        return redirect()->route('profile.edit')->with('success', 'Foto profil berhasil dihapus.');
    }

    /**
     * Memperbarui password pengguna.
     */
    public function updatePassword(Request $request)
    {
        // PRESENTASI: Validasi Password Baru
        // Memastikan current_password diisi, password baru minimal 8 karakter dan harus dikonfirmasi (confirmed)
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        // PRESENTASI: Pengecekan Password Saat Ini (Hashing)
        // Mengecek kecocokan password yang diinput dengan yang ada di database menggunakan Hash::check()
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        // PRESENTASI: Update Password Baru (Hashing)
        // Menyimpan password baru ke database (Otomatis di-hash jika di model ada cast 'hashed',
        // atau kita bisa memanggil Hash::make($request->password) untuk keamanan tambahan)
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password berhasil diperbarui.');
    }
}
