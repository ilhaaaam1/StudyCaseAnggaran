# 📚 Materi Presentasi: Laravel Blade Template Engine
**Project:** SIRAB / Study Case Anggaran

Dokumen ini berisi panduan untuk membedah kode (code review) bagian antarmuka (Frontend/View) dari aplikasi Anda yang menggunakan **Blade Template Engine** milik Laravel. *(Sesuai instruksi, bagian desain/konversi Figma dilewati agar kita murni fokus pada pembedahan kode arsitektur Blade).*

---

## 1. Konsep Dasar Blade
**Blade** adalah *template engine* bawaan Laravel yang memungkinkan kita merajut kode PHP di dalam HTML dengan sintaks yang jauh lebih bersih, ringkas, dan elegan. File ini harus memiliki ekstensi `.blade.php`.

**Kenapa menggunakan Blade?**
- Sintaks yang sangat ringkas (tidak perlu buka tutup tag `<?php ... ?>` yang membuat kode berantakan).
- Mendukung konsep *Template Inheritance* (Pewarisan kerangka layout).
- Sangat cepat, karena pada akhirnya Laravel akan men-*compile* file Blade menjadi *plain PHP* biasa.

---

## 2. Membedah Struktur Folder View
Saat presentasi, Anda bisa membuka folder `resources/views/` dan menjelaskan struktur *best practice* berikut (yang umum dipakai pada project Inventaris Universitas/SIRAB):
- **`layouts/`** : Berisi file pondasi/kerangka utama halaman web (misal: `app.blade.php`).
- **`layouts/partials/`** : Berisi potongan-potongan komponen UI (bagian kecil) yang bisa dipakai berulang kali di berbagai halaman, seperti `sidebar.blade.php` atau `topbar.blade.php`.
- **`pages/`** : Berisi halaman-halaman antarmuka spesifik, contohnya `pages/barang/index.blade.php` atau `pages/kategori/create.blade.php`.

---

## 3. Membedah Arsitektur Layouting (Inheritance)
Ini adalah inti dari materi Blade. Bagaimana satu halaman web yang utuh terbentuk dari berbagai potongan kode.

### A. File Master Layout (`layouts/app.blade.php`)
Buka file master layout, lalu jelaskan kode berikut:
```html
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Inventaris')</title>
    <!-- Asset Management (Memanggil CSS/JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Memanggil potongan UI sidebar secara statis -->
    @include('layouts.partials.sidebar')
    
    <div class="main-content">
        <!-- Memanggil potongan UI topbar secara statis -->
        @include('layouts.partials.topbar')
        
        <div class="page-content">
            <!-- Tempat dimana konten dinamis dari halaman anak akan disisipkan -->
            @yield('content')
        </div>
    </div>
</body>
</html>
```
*Penjelasan Presentasi:* 
- `@yield('nama_section')` berfungsi sebagai "lubang" atau wadah kosong yang nantinya akan disuntik/diisi oleh halaman-halaman anak (child view).
- `@include('nama_file')` berfungsi menyisipkan file Blade lain (seperti sidebar) langsung ke dalam file ini agar kode tidak kepanjangan di satu file.

### B. File Halaman Anak (`pages/barang/index.blade.php`)
Lalu buka salah satu file halaman fitur (misal index barang), lalu tunjukkan hubungannya:
```html
@extends('layouts.app')

@section('title', 'Daftar Barang')

@section('content')
    <div class="card">
        <h2>Data Barang</h2>
        <!-- Isi tabel dll diletakkan di sini -->
    </div>
@endsection
```
*Penjelasan Presentasi:*
- `@extends('layouts.app')` memberitahu Laravel bahwa halaman ini **meminjam/memakai** kerangka dari file `app.blade.php`.
- `@section('content')` adalah isi konten yang akan mengisi "lubang" `@yield('content')` yang ada di master layout. 

---

## 4. Membedah Sintaks & Logika (Directives)
Saat membedah tabel atau tampilan visual, soroti penggunaan perintah khusus (directives) Blade berikut:

### Looping Cerdas dengan `@forelse`
Sangat berguna saat me-render isi tabel data dari database.
```html
@forelse ($barangs as $b)
    <tr>
        <td>{{ $loop->iteration }}</td> <!-- Nomor urut otomatis -->
        <td>{{ $b->nama }}</td>
        <td>{{ $b->kategori->nama }}</td>
    </tr>
@empty
    <tr>
        <td colspan="3">Belum ada data barang (Empty State)</td>
    </tr>
@endforelse
```
*Penjelasan:* `@forelse` adalah gabungan dari *if* dan *foreach*. Jika data `$barangs` kosong dari database, ia akan otomatis memunculkan tampilan di dalam blok `@empty`.

### Conditional UI dengan `@if`
Ini digunakan untuk mengubah desain/warna tampilan berdasarkan status/kondisi data:
```html
@if ($b->kondisi === 'baik')
    <span class="badge" style="background: green;">Baik</span>
@elseif ($b->kondisi === 'rusak')
    <span class="badge" style="background: red;">Rusak</span>
@else
    <span class="badge" style="background: gray;">Hilang</span>
@endif
```

---

## 5. Membedah Route Helper & Form Handling
Blade memiliki helper pintar yang menghubungkan frontend dengan sistem Route Laravel.

**A. Membuat Link Otomatis (`route()`)**
```html
<a href="{{ route('barang.show', $b->id) }}">Detail Barang</a>
```
Sangat disarankan menggunakan fungsi `route('nama.route')` daripada menulis URL *hardcode* manual (`/barang/detail/1`), karena jika URL berubah, kode frontend tidak akan rusak.

**B. Membuat Sidebar Menu Aktif Otomatis**
```html
<a href="{{ route('dashboard') }}" 
   class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    Dashboard
</a>
```
Trik ini mengecek jika user sedang berada di halaman dashboard, maka tambahkan class CSS `active` (agar tombol menunya menyala terang).

**C. Form Handling (`@csrf`, `old()`, `@error`)**
Saat membedah halaman Form Tambah/Edit Data:
- Tunjukkan adanya **`@csrf`** di bawah tag `<form method="POST">`. Jelaskan ini adalah token keamanan wajib Laravel.
- Tunjukkan adanya fungsi **`{{ old('nama_input') }}`** di `value` form, berguna untuk mencegah ketikan user hilang saat form dikembalikan karena salah isi.
- Tunjukkan blok **`@error('nama_input')`** untuk menempelkan teks notifikasi merah jika validasinya gagal.

---

## 6. Skenario Demo Praktik & Presentasi
Jika dosen/reviewer meminta Anda membuktikan bahwa Blade yang Anda buat itu rapi dan benar-benar memakai konsep pewarisan (inheritance):
1. **Buka file `layouts/app.blade.php`.**
2. Coba tambahkan teks acak besar (misal `<h1>HALO REVIEWER</h1>`) tepat di atas `@yield('content')`.
3. Tunjukkan ke reviewer bahwa tulisan tersebut **akan muncul di semua halaman aplikasi** (di Dashboard, di Halaman Barang, di Kategori). Ini membuktikan bahwa sistem kerangka *Layouting/Inheritance* Anda bekerja sempurna sebagai satu komponen induk.
4. Jangan lupa hapus kembali teks tersebut setelah didemokan.
