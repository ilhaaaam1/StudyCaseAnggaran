<?php

declare(strict_types=1);

use App\Http\Controllers\AdminItController;
use App\Http\Controllers\AdminRabController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PengajuanRABController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffRabController;
use App\Http\Controllers\UserRabController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Pengajuan dan Persetujuan RAB (4 Role RBAC)
|--------------------------------------------------------------------------
*/

// Root redirect ke login atau dashboard sesuai role
Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    $role = Auth::user()->role;

    return match ($role) {
        'admin_it', 'admin' => redirect()->route('admin-it.dashboard'),
        'finance' => redirect()->route('finance.dashboard'),
        'pimpinan' => redirect()->route('pimpinan.dashboard'),
        default => redirect()->route('staff.dashboard'),
    };
});

// -------------------------------------------------------------------------
// Rute Autentikasi (Tamu / Guest)
// -------------------------------------------------------------------------
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Logout (Pengguna Terautentikasi)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Switch Role & Model (Pengguna Terautentikasi)
Route::post('/switch-role', [AuthController::class, 'switchRole'])->name('role.switch')->middleware('auth');

// -------------------------------------------------------------------------
// 1. RUTE STAFF / PEMOHON RAB (Role: staff)
// -------------------------------------------------------------------------
Route::middleware(['auth', 'role:staff,user'])->prefix('staff')->name('staff.')->group(function (): void {
    Route::get('/dashboard', [StaffRabController::class, 'index'])->name('dashboard');
    Route::get('/rab/create', [StaffRabController::class, 'create'])->name('rab.create');
    Route::post('/rab', [StaffRabController::class, 'store'])->name('rab.store');
    Route::get('/rab/{id}/edit', [StaffRabController::class, 'edit'])->name('rab.edit');
    Route::put('/rab/{id}', [StaffRabController::class, 'update'])->name('rab.update');
    Route::delete('/rab/{id}', [StaffRabController::class, 'destroy'])->name('rab.destroy');
    Route::get('/rab/{id}', [StaffRabController::class, 'show'])->name('rab.show');
    Route::get('/riwayat', [StaffRabController::class, 'riwayat'])->name('riwayat');

    // New placeholder routes
    Route::get('/draft', [StaffRabController::class, 'draft'])->name('draft');
    Route::get('/panduan', [StaffRabController::class, 'panduan'])->name('panduan');
});

// -------------------------------------------------------------------------
// 2. RUTE FINANCE / REVIEWER TAHAP 1 (Role: finance)
// -------------------------------------------------------------------------
Route::middleware(['auth', 'role:finance'])->prefix('finance')->name('finance.')->group(function (): void {
    Route::get('/dashboard', [FinanceController::class, 'index'])->name('dashboard');
    Route::get('/antrean', [FinanceController::class, 'antrean'])->name('antrean');
    Route::get('/pengajuan/{id}', [FinanceController::class, 'show'])->name('show');
    Route::post('/pengajuan/{id}/approval', [FinanceController::class, 'processApproval'])->name('approve');
    Route::get('/pencairan', [FinanceController::class, 'antreanPencairan'])->name('pencairan');
    Route::post('/pengajuan/{id}/pencairan', [FinanceController::class, 'uploadBuktiPencairan'])->name('upload_bukti');
    Route::get('/riwayat', [FinanceController::class, 'riwayat'])->name('riwayat');

    // New placeholder routes
    Route::get('/kategori-pagu', [KategoriAnggaranController::class, 'index'])->name('kategori.index');
    Route::post('/kategori-pagu', [KategoriAnggaranController::class, 'store'])->name('kategori.store');
    Route::put('/kategori-pagu/{id}', [KategoriAnggaranController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori-pagu/{id}', [KategoriAnggaranController::class, 'destroy'])->name('kategori.destroy');
    Route::get('/rekapitulasi', [RekapitulasiController::class, 'index'])->name('rekapitulasi.index');
    Route::get('/rekapitulasi/pdf', [RekapitulasiController::class, 'exportPdf'])->name('rekapitulasi.pdf');
    Route::get('/rekapitulasi/excel', [RekapitulasiController::class, 'exportExcel'])->name('rekapitulasi.excel');
});

// -------------------------------------------------------------------------
// 3. RUTE PIMPINAN / REVIEWER FINAL (Role: pimpinan)
// -------------------------------------------------------------------------
Route::middleware(['auth', 'role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function (): void {
    Route::get('/dashboard', [PimpinanController::class, 'index'])->name('dashboard');
    Route::get('/antrean', [PimpinanController::class, 'antrean'])->name('antrean');
    Route::get('/pengajuan/{id}', [PimpinanController::class, 'show'])->name('show');
    Route::post('/pengajuan/{id}/approval', [PimpinanController::class, 'processApproval'])->name('approve');
    Route::get('/riwayat', [PimpinanController::class, 'riwayat'])->name('riwayat');

    // New placeholder routes
    Route::get('/statistik', [PimpinanController::class, 'statistik'])->name('statistik.index');
    Route::get('/delegasi', [PimpinanController::class, 'delegasiIndex'])->name('delegasi.index');
    Route::post('/delegasi', [PimpinanController::class, 'delegasiStore'])->name('delegasi.store');
    Route::delete('/delegasi/{id}', [PimpinanController::class, 'delegasiDestroy'])->name('delegasi.destroy');
    Route::put('/delegasi/{id}/batal', [PimpinanController::class, 'delegasiCancel'])->name('delegasi.cancel');
});

// -------------------------------------------------------------------------
// 4. RUTE ADMIN IT / SYSTEM ADMINISTRATOR (Role: admin_it)
// -------------------------------------------------------------------------
Route::middleware(['auth', 'role:admin_it,admin'])->prefix('admin-it')->name('admin-it.')->group(function (): void {
    Route::get('/dashboard', [AdminItController::class, 'dashboard'])->name('dashboard');

    // Manajemen Akun Pengguna (4 Role)
    Route::get('/users', [AdminItController::class, 'usersIndex'])->name('users.index');
    Route::get('/users/create', [AdminItController::class, 'userCreate'])->name('users.create');
    Route::post('/users', [AdminItController::class, 'userStore'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminItController::class, 'userEdit'])->name('users.edit');
    Route::put('/users/{id}', [AdminItController::class, 'userUpdate'])->name('users.update');
    Route::delete('/users/{id}', [AdminItController::class, 'userDestroy'])->name('users.destroy');

    // Master Data Divisi
    Route::get('/divisi', [AdminItController::class, 'divisiIndex'])->name('divisi.index');
    Route::post('/divisi', [AdminItController::class, 'divisiStore'])->name('divisi.store');
    Route::delete('/divisi/{id}', [AdminItController::class, 'divisiDestroy'])->name('divisi.destroy');

    // New placeholder routes
    Route::get('/log-aktivitas', [AdminItController::class, 'logIndex'])->name('log.index');
    Route::get('/pengaturan', [AdminItController::class, 'pengaturanIndex'])->name('pengaturan.index');
    Route::put('/pengaturan', [AdminItController::class, 'pengaturanUpdate'])->name('pengaturan.update');
});

// -------------------------------------------------------------------------
// Rute Dokumen Bersama (Terautentikasi Semua Role)
// -------------------------------------------------------------------------
Route::middleware('auth')->group(function (): void {
    Route::get('/dokumen/{id}/preview', [AdminRabController::class, 'previewDokumen'])->name('dokumen.preview');
    Route::get('/dokumen/{id}/download', [AdminRabController::class, 'downloadDokumen'])->name('dokumen.download');

    // PRESENTASI: Route Pengaturan Akun
    // Menambahkan route profile yang diakses oleh semua role dengan middleware auth
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Route Notifikasi
    Route::get('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
});

// -------------------------------------------------------------------------
// Kompatibilitas Rute Eksisting (Admin & User Legacy)
// -------------------------------------------------------------------------
Route::middleware(['auth', 'role:admin_it,admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/dashboard', [AdminRabController::class, 'index'])->name('dashboard');
    Route::get('/persetujuan', [AdminRabController::class, 'approvalList'])->name('approval.list');
    Route::get('/persetujuan-antrean', [AdminRabController::class, 'approvalList'])->name('rab.index');
    Route::get('/pengajuan/{id}', [AdminRabController::class, 'show'])->name('pengajuan.show');
    Route::post('/pengajuan/{id}/approval', [AdminRabController::class, 'processApproval'])->name('pengajuan.approve');
    Route::get('/laporan', [AdminRabController::class, 'laporan'])->name('laporan');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth', 'role:staff,user'])->prefix('user')->name('user.')->group(function (): void {
    Route::get('/dashboard', [UserRabController::class, 'index'])->name('dashboard');
    Route::get('/rab/create', [UserRabController::class, 'create'])->name('rab.create');
    Route::post('/rab', [UserRabController::class, 'store'])->name('rab.store');
    Route::get('/rab/{id}', [UserRabController::class, 'show'])->name('rab.show');
    Route::get('/laporan', [UserRabController::class, 'laporan'])->name('laporan');
});

// -------------------------------------------------------------------------
// CONTOH TUGAS: Eloquent ORM & Query Builder
// -------------------------------------------------------------------------
use App\Http\Controllers\KategoriAnggaranController;
use App\Http\Controllers\RekapitulasiController;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// 2. Contoh Penerapan Eloquent ORM
Route::get('/tugas/eloquent', function () {
    // Menggunakan Eloquent Model Pengguna beserta relasinya (Eager Loading), Filtering, dan Sorting
    $data = Pengguna::with('divisi')
        ->where('role', 'user')
        ->orderBy('nama_lengkap', 'asc')
        ->take(5)
        ->get();

    return response()->json([
        'pesan' => 'Berhasil menggunakan Eloquent ORM (dengan Relasi, Filter, dan Sort)',
        'data' => $data,
    ]);
});

// 3. Contoh Penerapan SQL Query Builder
Route::get('/tugas/query-builder', function () {
    // Menggunakan Query Builder (DB facade) untuk JOIN tabel, SELECT spesifik, dan WHERE
    $data = DB::table('rincian_item')
        ->join('pengajuan_rab', 'rincian_item.id_pengajuan', '=', 'pengajuan_rab.id_pengajuan')
        ->select('rincian_item.uraian_barang', 'rincian_item.total_harga', 'pengajuan_rab.no_rab')
        ->where('rincian_item.harga_satuan', '>', 50000)
        ->orderBy('rincian_item.total_harga', 'desc')
        ->limit(5)
        ->get();

    return response()->json([
        'pesan' => 'Berhasil menggunakan SQL Query Builder (dengan JOIN, Select, Where, Order, Limit)',
        'data' => $data,
    ]);
});

// 1. Rute untuk MENAMPILKAN form tambah divisi
Route::get('/tugas/tambah-divisi', function () {
    return view('uji_tambah_divisi');
})->name('uji.divisi.create');

// 2. Rute untuk MEMPROSES data (Fungsi 'store' disederhanakan dalam rute)
Route::post('/tugas/tambah-divisi', function (Request $request) {
    // Validasi
    $validated = $request->validate([
        'nama_divisi' => 'required|string|max:50', // Wajib, teks, maks 50 huruf
    ], [
        'nama_divisi.required' => 'Nama divisi wajib diisi, tidak boleh kosong!',
        'nama_divisi.max' => 'Nama divisi terlalu panjang, maksimal 50 huruf.',
    ]);

    // Simpan ke database (menggunakan Query Builder)
    DB::table('divisi')->insert([ // Pastikan nama tabel benar
        'nama_divisi' => $validated['nama_divisi'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('sukses', 'Divisi baru berhasil ditambahkan ke database!');
})->name('uji.divisi.store');

// Rute untuk fitur Pengajuan RAB
Route::get('/pengajuan', [PengajuanRABController::class, 'index'])->name('pengajuan.index');
Route::get('/pengajuan/create', [PengajuanRABController::class, 'create'])->name('pengajuan.create');
Route::post('/pengajuan', [PengajuanRABController::class, 'store'])->name('pengajuan.store');
Route::get('/pengajuan/antrean', [PengajuanRABController::class, 'antreanPersetujuan'])->name('pengajuan.antrean');
Route::post('/pengajuan/{id}/approve', [PengajuanRABController::class, 'processApproval'])->name('pengajuan.approve');
Route::get('/pengajuan/{id}', [PengajuanRABController::class, 'show'])->name('pengajuan.show');
