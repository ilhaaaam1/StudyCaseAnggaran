# Panduan Bedah Kode SIRAB: Implementasi Route & View

Dokumen ini disusun khusus untuk membantu Anda memahami dan mempresentasikan konsep **Route** dan **View** di Laravel, dengan mengambil contoh **langsung dari *source code* aplikasi SIRAB (Sistem Informasi Rencana Anggaran Biaya)** yang sedang Anda bangun.

---

## 1. Apa itu Route? (Di dalam Konteks SIRAB)
**Route (Rute)** adalah jalur navigasi utama di aplikasi SIRAB. Fungsinya adalah **mengarahkan URL yang diakses oleh pengguna (seperti Staff, Finance, Pimpinan) ke tujuan yang tepat**, baik itu menampilkan halaman (View) atau memproses data (Controller).

Seluruh pengaturan rute aplikasi kita terpusat di file:
`routes/web.php`

### Jenis-Jenis Route yang Kita Gunakan di SIRAB

Dalam materi presentasi, terdapat beberapa jenis *Method Route*. Berikut adalah implementasi nyatanya di dalam kodingan aplikasi SIRAB Anda:

| Jenis Route di Slide | Fungsi | Bukti Implementasi di Kodingan SIRAB Anda (`routes/web.php`) |
| :--- | :--- | :--- |
| `Route::get()` | Menampilkan Halaman | `Route::get('/dashboard', [StaffRabController::class, 'dashboard'])`<br>*(Digunakan untuk menampilkan halaman dashboard staff)* |
| `Route::post()` | Menyimpan Data Baru | `Route::post('/rab', [StaffRabController::class, 'store'])`<br>*(Digunakan saat Staff men-submit formulir pengajuan RAB baru)* |
| `Route::put()` | Meng-update Data | `Route::put('/users/{id}', [AdminUserController::class, 'update'])`<br>*(Digunakan saat Admin IT mengubah profil/role pengguna)* |
| `Route::delete()` | Menghapus Data | `Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])`<br>*(Digunakan saat Admin IT menghapus akun pengguna dari sistem)* |
| `Route::fallback()`| URL Tidak Ditemukan | Jika ada user yang mengetik URL sembarangan, Laravel otomatis menanganinya dengan halaman 404 Not Found standar. |

---

## 2. Apa itu View? (Di dalam Konteks SIRAB)
**View** adalah bagian *User Interface* (antarmuka) atau kode HTML yang dilihat oleh pengguna di layar. Di SIRAB, kita menggunakan fitur **Blade Template** dari Laravel (sehingga file diakhiri `.blade.php`).

Lokasi folder View di proyek kita sangat terstruktur berdasarkan *Role* penggunanya:
* `resources/views/staff/` (Tampilan khusus pemohon RAB)
* `resources/views/finance/` (Tampilan khusus tim Finance)
* `resources/views/pimpinan/` (Tampilan khusus Kepala Sekolah)
* `resources/views/admin_it/` (Tampilan khusus Admin IT)

### Routing Langsung ke Folder View (Sesuai Slide)
Sesuai materi slide, untuk memanggil view di dalam sub-folder, kita menggunakan tanda titik (`.`).

**Contoh nyata di Controller SIRAB:**
```php
// Terletak di app/Http/Controllers/StaffRabController.php
return view('staff.create');
```
Kode di atas tidak memanggil `staff/create`, melainkan `staff.create`, yang artinya Laravel akan otomatis mencari file HTML di lokasi: `resources/views/staff/create.blade.php`.

---

## 3. Passing Data: Dari Route/Controller ke View
Sesuai materi "Tampilan View (Action)" di slide, aplikasi harus bisa melempar data (Passing Data) dari *backend* ke *frontend*. 

Di aplikasi SIRAB, saat seorang Staff ingin membuat Pengajuan RAB, halaman form tersebut tidak kosong. Halaman tersebut butuh data **Nomor RAB otomatis** dan **Sisa Pagu Anggaran**.

**Cara Kodingan SIRAB Melempar Data (Di Controller):**
```php
// Di dalam fungsi create() pada StaffRabController.php
$autoNoRab = 'RAB-2026-005';
$kategoriList = KategoriAnggaran::all();

// Mengirimkan variabel ke file staff/create.blade.php menggunakan compact()
return view('staff.create', compact('autoNoRab', 'kategoriList')); 
```

**Cara Kodingan SIRAB Menampilkan Data (Di Blade View):**
Sesuai materi slide, data ditampilkan dengan kurung kurawal ganda `{{ }}`. Di dalam file `staff/create.blade.php`, kita mencetak nomor RAB otomatis tersebut seperti ini:
```html
<input type="text" name="no_rab" value="{{ $autoNoRab }}" readonly>
```
Sehingga di layar pengguna, kolom input nomor RAB otomatis terisi "RAB-2026-005" dan tidak bisa diedit.

---

## 4. Route dengan Parameter (Dinamis)
Sesuai materi "Tampilan View (Another)" di slide, terkadang URL memiliki nilai ID yang berubah-ubah. Di SIRAB, ini sangat penting untuk **Melihat Detail Pengajuan RAB (Fitur Show/Review)**.

Misalnya, Finance ingin melihat detail pengajuan bernomor ID 12. Maka URL-nya adalah `/finance/pengajuan/12`.

**Bukti di `routes/web.php`:**
```php
Route::get('/pengajuan/{id}', [FinanceController::class, 'show'])->name('pengajuan.show');
```
Perhatikan ada `{id}` di dalam rute. Itu adalah **Parameter Dinamis**.

**Cara Menangkapnya di Controller:**
```php
// Di dalam FinanceController.php
public function show($id) {
    // Mencari data pengajuan di database yang ID-nya sama dengan parameter URL
    $pengajuan = PengajuanRab::findOrFail($id); 
    
    // Melempar data spesifik tersebut ke view
    return view('finance.show', compact('pengajuan'));
}
```

---

## 🎯 Kesimpulan Untuk Presentasi Anda
Jika dosen meminta Anda membedah alur aplikasi, gunakan skenario **"Staf membuat pengajuan RAB"** sebagai contoh terbaik penerapan Route & View:

1. Staf menekan tombol "Buat Pengajuan".
2. Aplikasi membaca **Route** `GET /staff/pengajuan/create`.
3. Route mengarah ke **Controller** `StaffRabController@create`.
4. Controller menghitung Sisa Pagu dan Nomor RAB otomatis (Action).
5. Controller mem-*passing data* tersebut ke **View** `staff.create`.
6. Tampilan form HTML (Blade) muncul di layar lengkap dengan isian dinamisnya.

Penjelasan ini membuktikan bahwa aplikasi SIRAB sepenuhnya mengimplementasikan teori Route, View, Passing Data, dan Parameter sesuai dengan materi pembelajaran!
