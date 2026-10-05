# Panduan Bedah Kode SIRAB: Resource Controller (Pertemuan 12)

Dokumen ini membedah kodingan aplikasi **SIRAB** Anda menggunakan sudut pandang materi **Resource Controller**. Ini akan sangat membantu Anda saat ditanya oleh dosen tentang mengapa routing Anda ditulis seperti sekarang, dan bagaimana Anda bisa mengembangkannya ke depan.

---

## 1. Apa itu Resource Controller?
Sesuai slide presentasi, **Resource Controller** adalah fitur Laravel untuk membuat *routing* operasi CRUD (Create, Read, Update, Delete) menjadi **sangat singkat**.

Alih-alih menulis 7 baris rute manual (seperti `get`, `post`, `put`, `delete`), kita cukup menulis **1 baris saja**:
`Route::resource('nama_entitas', ControllerName::class);`

---

## 2. Bedah Kodingan SIRAB Anda: "Routing Manual vs Resource"

Coba perhatikan kodingan asli Anda di dalam file `routes/web.php` pada bagian **Manajemen Akun Pengguna**:

```php
// Kodingan Asli SIRAB (Routing Manual)
Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');
```

**Analisis untuk Dosen:**
* "Pak/Bu, di proyek SIRAB ini, awalnya saya merancang *routing* untuk fitur kelola User secara **manual** menggunakan 6-7 baris. Tujuannya agar saya paham betul alur HTTP Method-nya (GET untuk tampil, POST untuk simpan, PUT untuk update, dan DELETE untuk hapus)."
* "Namun, berdasarkan materi pertemuan 12, kodingan 6 baris saya di atas sebenarnya **bisa disederhanakan hanya menjadi 1 baris** menggunakan fitur Resource Controller."

**Bentuk Penyederhanaan (Refactoring):**
```php
// Jika menggunakan ilmu dari materi Pertemuan 12:
Route::resource('users', AdminUserController::class);
```
Satu baris `Route::resource()` di atas otomatis akan membangkitkan ke-7 *method* persis seperti yang Anda ketik manual sebelumnya!

---

## 3. Manfaat Resource Controller (Konteks SIRAB)

Jika Anda ditanya, *"Apa keuntungannya kalau diganti jadi Resource?"*, jawab dengan 4 poin dari slide yang dikaitkan ke aplikasi Anda:

1. **Tidak Mengetik Pattern yang Sama:** File `routes/web.php` SIRAB Anda saat ini sangat panjang (mencapai 115+ baris). Jika diubah pakai Resource, file routing Anda akan jauh lebih pendek dan enak dibaca.
2. **Nama Method Konsisten:** Di `AdminUserController.php` Anda, nama-nama methodnya sudah sangat bagus dan mengikuti standar Laravel (`index`, `create`, `store`, `edit`, `update`, `destroy`). Ini artinya *Controller* Anda **sudah siap 100%** untuk dijadikan *Resource Controller*!
3. **Lebih Cepat Jika Menambah Fitur:** Misalnya besok Anda disuruh dosen menambah fitur CRUD "Master Barang", Anda tidak perlu lagi mengetik 7 baris rute manual. Cukup ketik:
   - Di terminal: `php artisan make:controller BarangController --resource`
   - Di routes: `Route::resource('barang', BarangController::class);`
4. **Keamanan Mass Assignment:** Di model-model SIRAB Anda (seperti `Pengguna.php`, `Divisi.php`), kita sudah menggunakan `$fillable` untuk melindungi kolom-kolom database dari manipulasi form. Ini sudah sesuai dengan kaidah keamanan yang diajarkan di slide.

---

## 4. Method yang Tidak Dipakai (Pengecualian)
Di slide diajarkan `->except(['edit', 'update'])` jika ada fitur yang tidak kita butuhkan. 

Ini juga bisa Anda jadikan bahan presentasi:
* "Di SIRAB, untuk fitur **Master Divisi**, saya hanya butuh fitur Tampil (`index`), Tambah (`store`), dan Hapus (`destroy`). Saya tidak butuh fitur Edit Divisi."
* "Maka, jika saya menggunakan Resource, saya bisa menulisnya seperti ini:"
```php
Route::resource('divisi', AdminItController::class)->only(['index', 'store', 'destroy']);
```

---

## 🎯 Kesimpulan / Strategi Menjawab Dosen
Saat demonstrasi proyek (PBL), sampaikan pernyataan ini:

> *"Untuk saat ini, struktur routing di SIRAB masih saya tulis secara manual (eksplisit) lapis demi lapis agar tim kami benar-benar paham alur kerja GET, POST, PUT, dan DELETE. Namun, secara arsitektur Controller, kodingan kami (seperti AdminUserController) sudah sepenuhnya mematuhi standar **Resource Controller** bawaan Laravel (menggunakan penamaan index, store, update, destroy). Sebagai bentuk optimasi selanjutnya (Refactoring), puluhan baris routing ini siap diringkas menjadi beberapa baris Route::resource saja sesuai dengan materi perkuliahan."*
