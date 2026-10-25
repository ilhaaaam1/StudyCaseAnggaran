# Panduan Lengkap Oprek (Modifikasi) Sistem Informasi RAB (SIRAB)

Dokumen ini merupakan referensi teknis yang **sangat lengkap** untuk memodifikasi atau mengembangkan ulang (*oprek*) project SIRAB. Panduan ini menggabungkan prinsip dasar arsitektur dari **Materi Presentasi StudyCase** serta menyertakan tutorial modifikasi tingkat lanjut (*Out of the Box*).

---

## BAB 1: Mengingat Kembali Dasar Arsitektur Aplikasi
Sebelum mulai mengoprek, penting untuk memahami fondasi materi yang membangun project ini (merujuk pada folder `MD Presentasi StudyCase`):

### 1. Konsep MVC (Model, View, Controller)
Aplikasi ini berjalan dengan siklus MVC:
- **Route (`routes/web.php`)**: Menerima request dari URL yang diketik user.
- **Controller (`app/Http/Controllers/`)**: Otak yang memproses logika. Menerima request dari Route, meminta data ke Model, dan mengirimkannya ke View.
- **Model (`app/Models/`)**: Penghubung aplikasi dengan tabel di database MySQL.
- **View (`resources/views/`)**: Antarmuka yang dilihat pengguna (Blade Template).

### 2. Blade Template Engine
Alih-alih menulis PHP murni yang berantakan, kita menggunakan **Blade**:
- **Pewarisan (`@extends`, `@section`)**: Menggunakan `resources/views/layouts/app.blade.php` sebagai kerangka utama.
- **Partials (`@include`)**: Potongan kode seperti Sidebar atau Topbar disimpan di folder `partials` agar kode tidak berulang.

### 3. CRUD & Resource Controller
Sebagian besar fitur menggunakan pendekatan **Resource Controller** (`php artisan make:controller NamaController --resource`), di mana Laravel otomatis membuatkan method standar: `index` (Read), `create` & `store` (Create), `edit` & `update` (Update), dan `destroy` (Delete).

---

## BAB 2: Oprekan Dasar (Kebutuhan Umum)

### 1. Cara Mengganti Logo Aplikasi
Terdapat dua metode untuk mengganti identitas aplikasi:
- **Cara Statis (Hardcode):** Timpa file `public/images/logo-sdn3.png` dengan logo Anda. Atau edit file `resources/views/partials/sidebar.blade.php` pada baris ke-17 dan arahkan src ke file logo baru Anda (`asset('images/logo-baru.png')`).
- **Cara Dinamis:** Login sebagai Admin IT -> Pengaturan Sistem, lalu ubah logo dari sana. Sistem menggunakan helper `\App\Models\Setting::getSetting('app_logo')` untuk mengambil path logonya dari database.

### 2. Cara Membuat Controller, View, dan Route Baru
1. Buat Controller: `php artisan make:controller ModulBaruController`
2. Daftarkan di `routes/web.php`:
   ```php
   Route::get('/modul-baru', [App\Http\Controllers\ModulBaruController::class, 'index'])->name('modul.index');
   ```
3. Di dalam Controller method `index()`, return view: `return view('modul.index');`
4. Buat file `resources/views/modul/index.blade.php` dengan kerangka layout bawaan:
   ```blade
   @extends('layouts.app')
   @section('title', 'Modul Baru')
   @section('content')
       <div class="p-6 bg-white rounded-xl shadow-sm">Isi modul baru di sini</div>
   @endsection
   ```

### 3. Cara Membuat Pop Up (Modal) Menggunakan Alpine.js
Sistem ini menggunakan Alpine.js. Anda tidak perlu menggunakan jQuery. Cukup bungkus dengan `x-data`:
```blade
<div x-data="{ showModal: false }">
    <!-- Tombol Pembuka -->
    <button @click="showModal = true" class="bg-indigo-600 text-white px-4 py-2 rounded">Buka Modal</button>

    <!-- Background Gelap -->
    <div x-show="showModal" style="display:none;" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40" @click="showModal = false"></div>
    
    <!-- Konten Modal -->
    <div x-show="showModal" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-none">
        <div class="bg-white p-6 rounded-2xl w-full max-w-md pointer-events-auto">
            <h3 class="text-xl font-bold">Judul Pop Up</h3>
            <p class="mt-2 text-slate-600">Isi pesan atau form input di sini.</p>
            <div class="mt-4 flex justify-end gap-2">
                <button @click="showModal = false" class="bg-slate-200 px-4 py-2 rounded">Batal</button>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </div>
    </div>
</div>
```

---

## BAB 3: Oprekan *Out of the Box* (Tingkat Lanjut)

Bagian ini berisi tutorial modifikasi keren yang tidak diajarkan secara eksplisit di materi dasar, namun akan membuat project Anda terasa seperti aplikasi level Enterprise!

### 🔥 Oprek 1: Membuat Helper Global Custom (Misal: Format Rupiah)
Daripada memanggil fungsi format uang di mana-mana, kita bisa membuat fungsi `formatRupiah()` yang bisa diakses langsung di Controller maupun Blade.

**Langkah-langkah:**
1. Buat folder dan file baru di `app/Helpers/AplikasiHelper.php`
2. Isi file tersebut dengan kode:
   ```php
   <?php
   if (!function_exists('formatRupiah')) {
       function formatRupiah($angka) {
           return "Rp " . number_format($angka, 0, ',', '.');
       }
   }
   ```
3. Buka file `composer.json` di *root* project. Cari block `"autoload"` dan tambahkan blok `"files"`:
   ```json
   "autoload": {
       "psr-4": {
           "App\\": "app/",
           "Database\\Factories\\": "database/factories/",
           "Database\\Seeders\\": "database/seeders/"
       },
       "files": [
           "app/Helpers/AplikasiHelper.php"
       ]
   }
   ```
4. Buka terminal, jalankan perintah: `composer dump-autoload`
5. Selesai! Sekarang di file `.blade.php` mana pun Anda bisa menulis `{{ formatRupiah($pengajuan->total) }}`.

### 🔥 Oprek 2: Inject Data Otomatis ke Semua View (View Composer)
Pernahkah Anda bingung bagaimana cara menampilkan jumlah notifikasi atau nama aplikasi di Sidebar, tanpa harus memanggil *query database* berulang kali di **setiap** Controller? Gunakan *View Composer*.

**Langkah-langkah:**
1. Buka `app/Providers/AppServiceProvider.php`
2. Di dalam method `boot()`, tambahkan kode ini:
   ```php
   use Illuminate\Support\Facades\View;
   use App\Models\Setting;

   public function boot(): void
   {
       // Setiap kali partials.sidebar dipanggil, otomatis kirimkan variabel $appName
       View::composer('partials.sidebar', function ($view) {
           $appName = Setting::where('key', 'app_name')->value('value') ?? 'Aplikasi SIRAB';
           $view->with('appName', $appName);
       });
   }
   ```
3. Di file `sidebar.blade.php`, Anda cukup memanggil `{{ $appName }}` secara langsung.

### 🔥 Oprek 3: Real-Time Search AJAX dengan Alpine.js (Tanpa jQuery)
Ingin tabel Anda bisa dicari datanya tanpa perlu me-reload halaman? Kita bisa menggabungkan Alpine.js dengan Fetch API.

**Langkah-langkah di file Blade:**
```blade
<div x-data="{
    searchQuery: '',
    results: [],
    loading: false,
    
    // Fungsi untuk memanggil API
    async performSearch() {
        this.loading = true;
        // Panggil endpoint API (buat routenya di web.php / api.php)
        const response = await fetch(`/api/search?q=${this.searchQuery}`);
        const data = await response.json();
        this.results = data;
        this.loading = false;
    }
}">
    <!-- Input Pencarian -->
    <input type="text" x-model="searchQuery" @input.debounce.500ms="performSearch" 
           placeholder="Ketik untuk mencari..." class="border p-2 rounded w-full">
           
    <!-- Status Loading -->
    <span x-show="loading" class="text-sm text-slate-500">Mencari data...</span>

    <!-- Looping Hasil -->
    <ul class="mt-4 border rounded" x-show="results.length > 0">
        <template x-for="item in results" :key="item.id">
            <li class="p-2 border-b" x-text="item.nama"></li>
        </template>
    </ul>
</div>
```
*(Catatan: Pastikan Anda membuat route `/api/search` di backend yang me-return `response()->json($data)`).*

### 🔥 Oprek 4: Integrasi SweetAlert2 untuk Konfirmasi "Delete" Keren
Alert bawaan browser yang berbunyi *"Are you sure?"* sudah usang. Mari oprek menggunakan **SweetAlert2**.

1. Tambahkan CDN SweetAlert2 di bagian `<head>` file `resources/views/layouts/app.blade.php`:
   ```html
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   ```
2. Ganti tombol delete form Anda yang membosankan menjadi seperti ini:
   ```blade
   <form id="delete-form-{{ $item->id }}" action="{{ route('item.destroy', $item->id) }}" method="POST" class="inline">
       @csrf
       @method('DELETE')
       <button type="button" onclick="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-800">
           Hapus
       </button>
   </form>
   ```
3. Tambahkan script JS ini di bagian bawah layout Anda (`@stack('scripts')`):
   ```javascript
   <script>
       function confirmDelete(id) {
           Swal.fire({
               title: 'Apakah Anda Yakin?',
               text: "Data yang dihapus tidak dapat dikembalikan!",
               icon: 'warning',
               showCancelButton: true,
               confirmButtonColor: '#4f46e5', // indigo-600
               cancelButtonColor: '#ef4444', // red-500
               confirmButtonText: 'Ya, Hapus!'
           }).then((result) => {
               if (result.isConfirmed) {
                   document.getElementById('delete-form-' + id).submit();
               }
           })
       }
   </script>
   ```

### 🔥 Oprek 5: Fitur Toggle "Maintenance Mode" Tanpa Terminal
Terkadang admin ingin mematikan aplikasi sementara waktu, tapi tidak punya akses ke terminal server (untuk menjalankan `php artisan down`).

**Cara Oprek:**
1. Tambahkan pengecekan middleware kustom atau langsung di `app/Http/Middleware/CheckMaintenance.php`.
2. Di dalam middleware:
   ```php
   use Closure;
   use App\Models\Setting;

   public function handle($request, Closure $next)
   {
       $isMaintenance = Setting::getSetting('maintenance_mode') === '1';
       
       // Izinkan Admin IT tetap bisa akses, selain itu dilempar ke halaman error
       if ($isMaintenance && auth()->check() && auth()->user()->role !== 'admin_it') {
           abort(503, 'Sistem sedang dalam perbaikan berkala.');
       }
       return $next($request);
   }
   ```
3. Daftarkan middleware ini secara global di `bootstrap/app.php` atau `app/Http/Kernel.php` (tergantung versi, di Laravel 11/13 menggunakan `bootstrap/app.php` dengan `->withMiddleware()`).
4. Buatkan *toggle switch* sederhana di View Pengaturan Admin untuk mengubah nilai database `maintenance_mode` menjadi `1` (On) atau `0` (Off).

---
Dengan menerapkan panduan ini, Anda bukan hanya sekadar mengedit teks atau logo, melainkan telah memahami dan memodifikasi fundamental sistem secara *pro*. Selamat bereksperimen!
