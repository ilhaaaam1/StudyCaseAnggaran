<?php

declare(strict_types=1);

use App\Http\Controllers\AdminItController;
use App\Http\Controllers\AdminRabController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PimpinanController;
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
    Route::get('/kategori-pagu', function () {
        return 'Master Kategori & Pagu';
    })->name('kategori.index');
    Route::get('/rekapitulasi', function () {
        return 'Rekapitulasi Laporan';
    })->name('rekapitulasi.index');
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
