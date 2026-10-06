# ⚡ Panduan Kilat: Menguasai Controller Laravel untuk Ujian Live Coding
> **Mode Belajar Darurat (Tanpa Internet & AI)**  
> *Target: Pahami logikanya dalam 3 menit, hafal polanya dalam 5 menit, dan langsung siap ketik saat live coding!*

---

## 🧭 Daftar Isi
1. [Analogi Dasar: Apa Itu Controller Sebenarnya?](#1-analogi-dasar-apa-itu-controller-sebenarnya)
2. [Perintah Terminal Sakti Pembuat Controller](#2-perintah-terminal-sakti-pembuat-controller)
3. [Anatomi Wajib Controller (Struktur Tulang Punggung)](#3-anatomi-wajib-controller-struktur-tulang-punggung)
4. [Pola 7 Method Sakti (CRUD Resource) yang Wajib Dihafal](#4-pola-7-method-sakti-crud-resource-yang-wajib-dihafal)
5. [Pasangan Sejati: Menghubungkan Controller ke Route (`web.php`)](#5-pasangan-sejati-menghubungkan-controller-ke-route-webphp)
6. [📋 Cheat Sheet Siap Tulis Tangan (Template 1 Menit)](#6--cheat-sheet-siap-tulis-tangan-template-1-menit)
7. [🚨 Trik Penyelamat & Debugging Darurat Saat Ujian](#7--trik-penyelamat--debugging-darurat-saat-ujian)

---

## 1. Analogi Dasar: Apa Itu Controller Sebenarnya?

Bayangkan alur aplikasi web seperti sebuah **Restoran Mewah**:

```
[ PENGUNJUNG / BROWSER ]
          │ (Memesan makanan / Klik link URL)
          ▼
    ┌────────────┐
    │ 1. ROUTE   │ ➔ Kasir / Buku Menu: Menentukan siapa yang melayani pesanan.
    └─────┬──────┘
          │ (Menugaskan tugas)
          ▼
 ┌─────────────────┐
 │ 2. CONTROLLER   │ ➔ PELAYAN RESTORAN (Otak Alur):
 └────────┬────────┘    - Menerima pesanan dari pelanggan
          │             - Menyuruh koki (Model) mengambil bahan di kulkas (Database)
          │             - Menata makanan di piring cantik (View / Blade)
          │             - Menyajikan kembali ke pelanggan
          ▼
    ┌────────────┐
    │ 3. MODEL   │ ➔ Koki & Gudang Bahan: Yang berurusan langsung dengan isi Database.
    └────────────┘
          │
          ▼
    ┌────────────┐
    │ 4. VIEW    │ ➔ Piring & Tata Saji: Tampilan HTML/Blade yang dilihat pengguna.
    └────────────┘
```

### Pertanyaan Ujian: *"Kenapa logika nggak ditaruh di Route (`web.php`) saja?"*
> **Jawaban Cerdas:**  
> *"Jika semua logika ditaruh di Route, file `web.php` akan menjadi berantakan, sulit dirawat, melanggar prinsip MVC (Model-View-Controller), dan menyulitkan pengujian program."*

---

## 2. Perintah Terminal Sakti Pembuat Controller

Buka terminal proyek Anda dan gunakan perintah Artisan berikut:

### A. Perintah Paling Sakti untuk Ujian Live Coding:
```bash
php artisan make:controller PengajuanRabController --resource
```
⭐ **Wajib diingat:** Tambahan flag `--resource` akan **otomatis membuatkan 7 kerangka method CRUD** (index, create, store, show, edit, update, destroy) di dalam file controller. Anda tidak perlu mengetik nama method satu per satu dari nol!

### B. Perintah Controller Polosan (Tanpa Method CRUD):
```bash
php artisan make:controller AuthController
```
Gunakan perintah biasa tanpa `--resource` untuk controller khusus yang tugasnya bukan CRUD standar (misal: halaman Login, Dashboard, atau Export PDF).

---

## 3. Anatomi Wajib Controller (Struktur Tulang Punggung)

Jika di ujian Anda diminta membuat file controller secara manual tanpa terminal, berikut 5 baris wajib yang tidak boleh ketinggalan:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;   // 1. Wajib untuk menangkap input formulir
use App\Models\PengajuanRab;   // 2. Wajib memanggil Model yang bersangkutan

class PengajuanRabController extends Controller
{
    // Method-method fungsi Anda berada di sini...
}
```

---

## 4. Pola 7 Method Sakti (CRUD Resource) yang Wajib Dihafal

Ketujuh method ini adalah standar resmi CRUD di Laravel. Setiap method rata-rata hanya butuh **2 sampai 3 baris kode**:

```
Method         Tugas Utama                    Kode Ringkas Inti
─────────────────────────────────────────────────────────────────────────────
index()        Ambil semua data -> Tampilkan  $data = Model::all(); 
                                              return view('nama.index', compact('data'));

create()       Tampilkan form tambah kosong   return view('nama.create');

store(Req)     Validasi -> Simpan -> Balik    $valid = $request->validate([...]);
                                              Model::create($valid);
                                              return redirect()->route('nama.index');

show($id)      Tampilkan detail 1 data        $item = Model::findOrFail($id);
                                              return view('nama.show', compact('item'));

edit($id)      Ambil data lama -> Form edit   $item = Model::findOrFail($id);
                                              return view('nama.edit', compact('item'));

update(Req,$id) Validasi -> Perbarui -> Balik  $item = Model::findOrFail($id);
                                              $item->update($request->validate([...]));
                                              return redirect()->route('nama.index');

destroy($id)   Hapus data -> Balik            $item = Model::findOrFail($id);
                                              $item->delete();
                                              return redirect()->route('nama.index');
```

---

## 5. Pasangan Sejati: Menghubungkan Controller ke Route (`web.php`)

Controller tidak akan pernah jalan sebelum didaftarkan di `routes/web.php`.

### A. Jalan Tol (1 Baris Sakti Meng-cover Seluruh 7 Method):
```php
use App\Http\Controllers\PengajuanRabController;

// Satu baris ini otomatis menghubungkan index, create, store, show, edit, update, destroy!
Route::resource('pengajuan', PengajuanRabController::class);
```

### B. Jalur Manual (Jika Dosen Minta Buat Route Satuan):
```php
use App\Http\Controllers\PengajuanRabController;

Route::get('/pengajuan', [PengajuanRabController::class, 'index'])->name('pengajuan.index');
Route::get('/pengajuan/create', [PengajuanRabController::class, 'create'])->name('pengajuan.create');
Route::post('/pengajuan', [PengajuanRabController::class, 'store'])->name('pengajuan.store');
Route::get('/pengajuan/{id}', [PengajuanRabController::class, 'show'])->name('pengajuan.show');
Route::get('/pengajuan/{id}/edit', [PengajuanRabController::class, 'edit'])->name('pengajuan.edit');
Route::put('/pengajuan/{id}', [PengajuanRabController::class, 'update'])->name('pengajuan.update');
Route::delete('/pengajuan/{id}', [PengajuanRabController::class, 'destroy'])->name('pengajuan.destroy');
```

---

## 6. 📋 Cheat Sheet Siap Tulis Tangan (Template 1 Menit)

Salin atau hafal pola controller minimalis berikut. Cocok untuk semua studi kasus CRUD apa pun di ujian!

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang; // Ganti sesuai nama model Anda

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::latest()->get();
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required|numeric',
        ]);

        Barang::create($validated);

        return redirect()->route('barang.index')->with('success', 'Data berhasil disimpan!');
    }

    public function show($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.show', compact('barang'));
    }

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga'       => 'required|numeric',
        ]);

        $barang->update($validated);

        return redirect()->route('barang.index')->with('success', 'Data berhasil diubah!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Data berhasil dihapus!');
    }
}
```

---

## 7. 🚨 Trik Penyelamat & Debugging Darurat Saat Ujian

Jika terjadi error di layar browser saat live coding, jangan panik! Gunakan 3 trik penyelamat ini:

### 1. `dd()` — Jurus Pengintip Data (Dump and Die)
Ingin tahu apakah data dari form berhasil masuk ke controller? Pasang `dd()` di baris paling atas:
```php
public function store(Request $request)
{
    dd($request->all()); // Program akan berhenti dan menampilkan seluruh isi data form!
    // ...
}
```
Jika layar menampilkan array data form Anda, berarti **form dan route Anda sudah 100% benar**.

### 2. Cek Route dengan Terminal:
Lupa apa nama route atau URL-nya? Jalankan:
```bash
php artisan route:list
```
Periksa kolom `URI`, `Method` (GET/POST/PUT/DELETE), dan `Action` untuk melihat controller mana yang terpanggil.

### 3. Tiga Error Klasik & Cara Cepat Mengatasinya:
- **Error 419 Page Expired:**  
  *Penyebab:* Lupa memasang `@csrf` di dalam tag `<form>` pada file Blade.
- **Error 404 Not Found:**  
  *Penyebab:* ID data tidak ditemukan di database saat memanggil `findOrFail($id)`.
- **Error 405 Method Not Allowed:**  
  *Penyebab:* Salah pasang method HTTP di route atau form (misal: route menuntut `POST`, tapi form mengirimkan `GET`; atau untuk edit lupa `@method('PUT')`).

---
*Tetap tenang, perhatikan alur **Route ➡️ Controller ➡️ Model/View**, dan semoga sukses dalam ujian live coding! 🚀*
