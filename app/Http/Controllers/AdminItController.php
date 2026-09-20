<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Divisi;
use App\Models\PengajuanRab;
use App\Models\Pengguna;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminItController extends Controller
{
    /**
     * Dashboard Administrator IT - Fokus pada manajemen sistem dan pengguna.
     */
    public function dashboard(): View
    {
        $totalPengguna = Pengguna::count();
        $totalStaff = Pengguna::whereIn('role', ['staff', 'user'])->count();
        $totalFinance = Pengguna::where('role', 'finance')->count();
        $totalPimpinan = Pengguna::where('role', 'pimpinan')->count();
        $totalAdminIt = Pengguna::whereIn('role', ['admin_it', 'admin'])->count();
        $totalDivisi = Divisi::count();

        $penggunaTerbaru = Pengguna::with('divisi')->latest('id_pengguna')->take(6)->get();

        return view('admin_it.dashboard', compact(
            'totalPengguna',
            'totalStaff',
            'totalFinance',
            'totalPimpinan',
            'totalAdminIt',
            'totalDivisi',
            'penggunaTerbaru'
        ));
    }

    // -------------------------------------------------------------------------
    // MANAJEMEN AKUN PENGGUNA (CRUD LINTAS 4 ROLE)
    // -------------------------------------------------------------------------

    public function usersIndex(Request $request): View
    {
        $roleFilter = $request->input('role');
        $divisiFilter = $request->input('id_divisi');
        $search = $request->input('q');

        $query = Pengguna::with('divisi');

        if ($roleFilter) {
            $query->where('role', $roleFilter);
        }

        if ($divisiFilter) {
            $query->where('id_divisi', $divisiFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        $users = $query->latest('id_pengguna')->paginate(10)->withQueryString();
        $divisiList = Divisi::orderBy('nama_divisi')->get();

        return view('admin_it.manajemen_user', compact('users', 'divisiList', 'roleFilter', 'divisiFilter', 'search'));
    }

    public function userCreate(): View
    {
        $divisi = Divisi::orderBy('nama_divisi')->get();
        $roles = [
            'staff' => 'Staff (Pemohon RAB)',
            'finance' => 'Finance (Reviewer Tahap 1)',
            'pimpinan' => 'Pimpinan (Reviewer Final)',
            'admin_it' => 'Admin IT (System Administrator)',
        ];

        return view('admin_it.user_create', compact('divisi', 'roles'));
    }

    public function userStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:pengguna,email'],
            'password' => ['required', 'string', 'min:6'],
            'id_divisi' => ['required', 'integer', 'exists:divisi,id_divisi'],
            'role' => ['required', 'in:staff,finance,pimpinan,admin_it'],
            'jabatan' => ['nullable', 'string', 'max:100'],
        ]);

        $divisiObj = Divisi::find($validated['id_divisi']);
        $jabatan = ! empty($validated['jabatan'])
            ? trim((string) $validated['jabatan'])
            : ('Staf '.($divisiObj?->nama_divisi ?? 'Operasional'));

        Pengguna::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => strtolower(trim((string) $validated['email'])),
            'password' => Hash::make($validated['password']),
            'id_divisi' => (int) $validated['id_divisi'],
            'role' => $validated['role'],
            'jabatan' => $jabatan,
        ]);

        ActivityLog::log("Menambahkan pengguna baru: {$validated['nama_lengkap']} ({$validated['role']}).");

        return redirect()->route('admin-it.users.index')
            ->with('success', "Akun {$validated['nama_lengkap']} berhasil didaftarkan dengan role {$validated['role']}.");
    }

    public function userEdit(int $id): View
    {
        $user = Pengguna::findOrFail($id);
        $divisi = Divisi::orderBy('nama_divisi')->get();
        $roles = [
            'staff' => 'Staff (Pemohon RAB)',
            'finance' => 'Finance (Reviewer Tahap 1)',
            'pimpinan' => 'Pimpinan (Reviewer Final)',
            'admin_it' => 'Admin IT (System Administrator)',
        ];

        return view('admin_it.user_edit', compact('user', 'divisi', 'roles'));
    }

    public function userUpdate(Request $request, int $id): RedirectResponse
    {
        $user = Pengguna::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:pengguna,email,'.$id.',id_pengguna'],
            'id_divisi' => ['required', 'integer', 'exists:divisi,id_divisi'],
            'role' => ['required', 'in:staff,finance,pimpinan,admin_it'],
            'password' => ['nullable', 'string', 'min:6'],
            'jabatan' => ['nullable', 'string', 'max:100'],
        ]);

        $divisiObj = Divisi::find($validated['id_divisi']);
        $jabatan = ! empty($validated['jabatan'])
            ? trim((string) $validated['jabatan'])
            : ('Staf '.($divisiObj?->nama_divisi ?? 'Operasional'));

        $updateData = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'email' => strtolower(trim((string) $validated['email'])),
            'id_divisi' => (int) $validated['id_divisi'],
            'role' => $validated['role'],
            'jabatan' => $jabatan,
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        ActivityLog::log("Memperbarui data pengguna: {$user->nama_lengkap}.");

        return redirect()->route('admin-it.users.index')
            ->with('success', "Data akun {$user->nama_lengkap} berhasil diperbarui.");
    }

    public function userDestroy(int $id): RedirectResponse
    {
        $user = Pengguna::findOrFail($id);

        // Proteksi jika memiliki riwayat pengajuan RAB
        if (PengajuanRab::where('id_pengguna', $id)->exists()) {
            return redirect()->route('admin-it.users.index')
                ->with('error', "Akun {$user->nama_lengkap} tidak dapat dihapus karena memiliki riwayat pengajuan RAB.");
        }

        // Proteksi jika pernah menjadi reviewer (data jejak audit persetujuan)
        if (\DB::table('alur_persetujuan')->where('id_reviewer', $id)->exists()) {
            return redirect()->route('admin-it.users.index')
                ->with('error', "Akun {$user->nama_lengkap} tidak dapat dihapus karena memiliki riwayat sebagai reviewer/penyetuju pengajuan RAB.");
        }

        $nama = $user->nama_lengkap;
        $user->delete();

        ActivityLog::log("Menghapus pengguna: {$nama}.");

        return redirect()->route('admin-it.users.index')
            ->with('success', "Akun {$nama} berhasil dihapus dari sistem.");
    }

    // -------------------------------------------------------------------------
    // MASTER DATA DIVISI (CRUD)
    // -------------------------------------------------------------------------

    public function divisiIndex(): View
    {
        $divisiList = Divisi::withCount('pengguna')->orderBy('nama_divisi')->get();

        return view('admin_it.divisi', compact('divisiList'));
    }

    public function divisiStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_divisi' => ['required', 'string', 'max:100', 'unique:divisi,nama_divisi'],
        ], [
            'nama_divisi.required' => 'Nama divisi wajib diisi.',
            'nama_divisi.unique' => 'Divisi dengan nama tersebut sudah terdaftar.',
        ]);

        Divisi::create($validated);

        return redirect()->route('admin-it.divisi.index')
            ->with('success', "Divisi {$validated['nama_divisi']} berhasil ditambahkan.");
    }

    public function divisiDestroy(int $id): RedirectResponse
    {
        $divisi = Divisi::findOrFail($id);

        if ($divisi->pengguna()->exists() || PengajuanRab::where('id_divisi', $id)->exists()) {
            return redirect()->route('admin-it.divisi.index')
                ->with('error', "Divisi {$divisi->nama_divisi} tidak dapat dihapus karena masih digunakan oleh pengguna atau pengajuan aktif.");
        }

        $nama = $divisi->nama_divisi;
        $divisi->delete();

        return redirect()->route('admin-it.divisi.index')
            ->with('success', "Divisi {$nama} berhasil dihapus.");
    }

    // -------------------------------------------------------------------------
    // AUDIT TRAIL / LOG AKTIVITAS
    // -------------------------------------------------------------------------

    public function logIndex(Request $request): View
    {
        $search = $request->input('q');

        $query = ActivityLog::with('user');

        if ($search) {
            $query->where('activity', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
        }

        $logs = $query->latest()->paginate(15)->withQueryString();

        return view('admin_it.log_aktivitas', compact('logs', 'search'));
    }

    // -------------------------------------------------------------------------
    // PENGATURAN SISTEM
    // -------------------------------------------------------------------------

    public function pengaturanIndex(): View
    {
        $settings = [
            'app_name' => Setting::getSetting('app_name', 'SIRAB Kelompok-3'),
            'academic_year' => Setting::getSetting('academic_year', '2025/2026'),
            'admin_email' => Setting::getSetting('admin_email', 'admin@sekolah.sch.id'),
            'maintenance_mode' => Setting::getSetting('maintenance_mode', '0'),
            'app_logo' => Setting::getSetting('app_logo', ''),
        ];

        return view('admin_it.pengaturan', compact('settings'));
    }

    public function pengaturanUpdate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:150',
            'academic_year' => 'required|string|max:20',
            'admin_email' => 'required|email|max:150',
            'maintenance_mode' => 'nullable|in:1,0',
            'app_logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        Setting::setSetting('app_name', $validated['app_name']);
        Setting::setSetting('academic_year', $validated['academic_year']);
        Setting::setSetting('admin_email', $validated['admin_email']);
        Setting::setSetting('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0');

        if ($request->hasFile('app_logo')) {
            $file = $request->file('app_logo');
            $path = $file->store('settings', 'public');

            // Delete old logo if exists
            $oldLogo = Setting::getSetting('app_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            Setting::setSetting('app_logo', $path);
        }

        ActivityLog::log('Memperbarui konfigurasi pengaturan sistem.');

        return redirect()->route('admin-it.pengaturan.index')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
