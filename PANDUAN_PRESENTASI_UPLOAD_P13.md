# 📋 PANDUAN LENGKAP PRESENTASI & DEMO DOSEN
## Modul Inventaris & Dokumentasi — Upload Architecture (Pertemuan 13)
**Studi Kasus:** Sistem Informasi RAB & Inventaris Sekolah — SDN Sidokare 3  
**Framework:** Laravel 12 / 13 (PHP 8.3)  
**Referensi:** Slide Materi Kuliah *Pertemuan 13: Upload Architecture Gambar Barang Inventaris*

---

## 🎯 DAFTAR ISI
1. [Prinsip Inti & Analogi Gudang (Slide 1–3)](#1-prinsip-inti--analogi-gudang-slide-13)
2. [Lima Tahap Arsitektur Upload dalam Satu Request (Slide 4)](#2-lima-tahap-arsitektur-upload-dalam-satu-request-slide-4)
3. [Walkthrough Kode & Implementasi Tahap 1 s.d. 5 (Slide 5–9)](#3-walkthrough-kode--implementasi-tahap-1-sd-5-slide-59)
4. [Studi Kasus Perjalanan Berkas: `laptop.jpg` (Slide 10)](#4-studi-kasus-perjalanan-berkas-laptopjpg-slide-10)
5. [Perawatan Berkas: Update & Destroy Anti-File Sampah (Slide 11–12)](#5-perawatan-berkas-update--destroy-anti-file-sampah-slide-1112)
6. [Empat Lapis Pengaman Upload (Slide 13)](#6-empat-lapis-pengaman-upload-slide-13)
7. [Troubleshooting & Solusi Demo (Slide 14)](#7-troubleshooting--solusi-demo-slide-14)
8. [Skenario Live Demo & Pengujian Dosen (Slide 17 - Penugasan)](#8-skenario-live-demo--pengujian-dosen-slide-17---penugasan)
9. [Cheat Sheet Jawaban Pertanyaan Kritis Dosen (Q&A)](#9-cheat-sheet-jawaban-pertanyaan-kritis-dosen-qa)

---

## 1. Prinsip Inti & Analogi Gudang (Slide 1–3)

### 💡 Konsep Satu Kalimat
> **"Database hanya menyimpan alamat (path string) — Storage menyimpan berkas fisiknya — `asset()` dan `storage:link` yang menyambungkan keduanya ke browser."**

### 📦 Tabel Analogi Penitipan Barang ke Gudang
| Tahap | Istilah Web / Laravel | Analogi Dunia Nyata |
| :--- | :--- | :--- |
| **Form** | `enctype="multipart/form-data"` | Mengisi formulir tanda terima dan membungkus barang dengan kardus khusus. |
| **Validasi** | `validate(['gambar' => '...'])` | Petugas satpam gudang memeriksa: *Apakah barang berbahaya? Apakah ukurannya muat di rak?* |
| **Storage** | `$file->storeAs('barang', $filename, 'public')` | Barang fisik benar-benar diletakkan di dalam rak gudang penyimpanan. |
| **Database** | Kolom `gambar` = `'storage/barang/...'` | Buku catatan registrasi: mencatat *"Barang ada di Rak B-3"*, **bukan memasukkan barang ke buku**. |
| **Tampil** | `asset($barang->gambar)` & `storage:link` | Loket pengambilan: jalur akses resmi bagi pengunjung luar untuk melihat/mengambil barang. |

---

## 2. Lima Tahap Arsitektur Upload dalam Satu Request (Slide 4)

Diagram alur eksekusi saat pengguna menekan tombol **Simpan**:

```text
[ Browser Pengguna ]
       │
       ▼ (1. Form POST via multipart/form-data)
[ Web Server / Controller ]
       │
       ▼ (2. Validasi Server: image, mimes, max:2048)
   ┌───┴────────────────────────┐
 [Gagal?]                     [Lolos?]
   │                             │
   ▼                             ▼ (3. Storage Fisik)
Kembali ke Form               Tulis file ke: storage/app/public/barang/
+ Pesan Error                 (Awalan time() agar unik)
(Disk tetap bersih)              │
                                 ▼ (4. Database)
                              Catat path string ke kolom barangs.gambar
                                 │
                                 ▼ (5. Tampil ke Browser)
                              Browser membaca lewat Symlink: asset($barang->gambar)
```

> **Catatan Kunci Slide 4:**  
> Jika validasi gagal di tahap 2, siklus langsung diputus dan dikembalikan ke form. Tahap 3–5 **tidak pernah dieksekusi**, sehingga disk storage aman dari file sampah (*junk files*).

---

## 3. Walkthrough Kode & Implementasi Tahap 1 s.d. 5 (Slide 5–9)

### 📌 Tahap 1: Form Multipart (`resources/views/barang/create.blade.php`)
```html
<!-- Slide 5: Form wajib multipart dan token CSRF -->
<form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Input file dengan filter browser accept="image/*" -->
    <input type="file" name="gambar" accept="image/*">

    <!-- Pesan validasi server inline -->
    @error('gambar')
        <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
    @enderror
</form>
```
* **Poin Penjelasan:**
  1. `enctype="multipart/form-data"` wajib ada agar browser mengirim berkas dalam bentuk *binary stream*, bukan hanya nama teksnya.
  2. `@csrf` mencegah serangan CSRF (tanpa ini Laravel memunculkan error `419 Page Expired`).
  3. `name="gambar"` harus sinkron dengan *key* validasi pada Controller.
  4. `accept="image/*"` sebagai penyaring awal sisi klien, namun keamanan mutlak tetap berada di sisi server.

---

### 📌 Tahap 2: Validasi Server-Side (`App\Http\Controllers\BarangController@store`)
```php
// Slide 6: Aturan validasi ketat sebelum menyentuh storage
$request->validate([
    'kategori' => 'required|string|max:100',
    'nama'     => 'required|string|max:150',
    'jumlah'   => 'required|integer|min:0',
    'kondisi'  => 'required|in:baik,rusak,hilang',
    'gambar'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
]);
```
* **Poin Penjelasan:**
  1. `nullable`: Upload gambar bersifat opsional (barang tanpa foto tetap sah tersimpan).
  2. `image`: Memeriksa tipe konten riil (MIME content-type), bukan sekadar melihat ekstensi nama file.
  3. `mimes:jpeg,png,jpg,gif`: Membatasi format berkas yang diizinkan.
  4. `max:2048`: Batas ukuran maksimal 2048 KB (2 MB).

---

### 📌 Tahap 3: Simpan File Fisik ke Disk Public (`BarangController@store`)
```php
// Slide 7: Pisahkan data teks dan file fisik sejak awal
$data = $request->except('gambar');

if ($request->hasFile('gambar')) {
    $file = $request->file('gambar');

    // Penamaan unik mencegah tabrakan nama file
    $filename = time() . '_' . $file->getClientOriginalName();

    // Simpan fisik ke storage/app/public/barang/
    $file->storeAs('barang', $filename, 'public');

    // Siapkan string path relatif untuk database
    $data['gambar'] = 'storage/barang/' . $filename;
}

Barang::create($data);
```
* **Poin Penjelasan Laravel 12/13:**
  Pada Laravel 12+, disk `local` bawaan mengarah ke `storage/app/private`. Oleh sebab itu, parameter disk ketiga wajib ditulis eksplisit `'public'`: `$file->storeAs('barang', $filename, 'public')`.

---

### 📌 Tahap 4: Skema Database & Penyimpanan Path (`database/migrations/...`)
```php
// Slide 8: Kolom database hanya string path berukuran ringan
Schema::create('barangs', function (Blueprint $table) {
    $table->id();
    $table->string('nama', 150);
    $table->string('kategori', 100);
    $table->integer('jumlah')->default(1);
    $table->enum('kondisi', ['baik', 'rusak', 'hilang'])->default('baik');
    $table->text('keterangan')->nullable();
    $table->string('gambar')->nullable(); // Menyimpan string: "storage/barang/173000_foto.jpg"
    $table->timestamps();
});
```

* **Perbandingan: Simpan Path (String) vs Simpan Isi File (BLOB):**
  - ✅ **Simpan Path:** Database sangat ringan, pencadangan (*backup*) kilat, query cepat, dan mudah jika suatu saat bermigrasi ke Cloud Storage (AWS S3 / Google Cloud Storage).
  - ❌ **Simpan Isi File (BLOB):** Satu file berukuran 2 MB akan membuat tabel database membengkak, query lambat, serta membebani RAM database server.

---

### 📌 Tahap 5: Menampilkan Gambar & Standarisasi Ukuran (Slide 9)

Koneksi Symlink (Jalan Pintas Web Publik):
```bash
php artisan storage:link
# Memetakan: public/storage ──(symlink)──> storage/app/public
```

Sintaks Pemanggilan Blade:
```blade
@if ($barang->gambar)
    <img src="{{ asset($barang->gambar) }}" alt="{{ $barang->nama }}">
@else
    <p>Tidak ada gambar</p>
@endif
```

**Standar Dimensi Gambar pada 3 Halaman (Slide 9 Standar Dosen):**
1. **Halaman Index (`barang/index.blade.php`):**  
   Thumbnail tabel berukuran presisi **36 px** (`style="width: 36px; height: 36px;"`).
2. **Halaman Detail / Show (`barang/show.blade.php`):**  
   Foto detail berukuran proporsional dengan batas maksimal **300 px** (`style="max-width: 300px;"`).
3. **Halaman Edit (`barang/edit.blade.php`):**  
   Pratinjau foto tersimpan berukuran **80 px** (`style="width: 80px; height: 80px;"`).

---

## 4. Studi Kasus Perjalanan Berkas: `laptop.jpg` (Slide 10)

Berikut alur eksekusi saat berkas nyata diuji coba:

| Tahapan | Skenario Sukses (`laptop.jpg`, 850 KB) | Skenario Ditolak (`notes.txt`, 12 KB) |
| :--- | :--- | :--- |
| **1. Form** | Pengguna memilih `laptop.jpg`, klik Simpan (`POST /barang`). | Pengguna memilih `notes.txt`, klik Simpan (`POST /barang`). |
| **2. Validasi** | `image` ✅, `mimes:jpg` ✅, ukuran 850 KB < 2048 KB ✅ (Lolos). | `image` ❌, MIME bukan gambar (Gagal). |
| **3. Storage** | Ditulis ke `storage/app/public/barang/1730000000_laptop.jpg`. | **Tidak dieksekusi.** File tidak menyentuh disk. |
| **4. Database** | Kolom `gambar` mencatat string `'storage/barang/1730000000_laptop.jpg'`. | **Tidak dieksekusi.** Record tidak tersimpan. |
| **5. Tampil** | Tampil via `<img src="http://localhost:8000/storage/barang/...">`. | Kembali ke form + pesan error: *"The gambar field must be an image."* |

---

## 5. Perawatan Berkas: Update & Destroy Anti-File Sampah (Slide 11–12)

Jika file lama tidak dihapus saat terjadi update atau delete, folder penyimpanan akan mengalami penumpukan berkas yatim (*orphaned files*).

### 🔄 Saat Update Gambar (`BarangController@update` - Slide 11)
```php
// Jika pengguna mengunggah foto pengganti baru
if ($request->hasFile('gambar')) {
    // 1. HAPUS FILE LAMA DULU dari disk public
    if ($barang->gambar) {
        Storage::disk('public')->delete('barang/' . basename($barang->gambar));
    }

    // 2. SIMPAN FILE BARU
    $file = $request->file('gambar');
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->storeAs('barang', $filename, 'public');

    // 3. UPDATE PATH BARU KE DATABASE
    $data['gambar'] = 'storage/barang/' . $filename;
}
```

> **Mengapa menggunakan `basename()`?**  
> Database menyimpan path relatif publik: `'storage/barang/123_foto.jpg'`.  
> Sedangkan method `Storage::disk('public')->delete()` bekerja dari akar disk `storage/app/public/`.  
> Fungsi `basename($barang->gambar)` mengekstrak nama murni filenya (`123_foto.jpg`), lalu digabungkan menjadi `'barang/' . basename(...)`.

### 🗑️ Saat Hapus Data Barang (`BarangController@destroy` - Slide 12)
```php
public function destroy(Barang $barang): RedirectResponse
{
    // 1. Hapus file fisik terlebih dahulu selagi path masih ada di record
    if ($barang->gambar) {
        Storage::disk('public')->delete('barang/' . basename($barang->gambar));
    }

    // 2. Hapus baris data dari tabel database
    $barang->delete();

    return redirect()->route('barang.index')
        ->with('success', 'Barang beserta berkas gambarnya berhasil dihapus.');
}
```

---

## 6. Empat Lapis Pengaman Upload (Slide 13)

1. **Lapis 1 — Validasi Server-Side Ketat:**  
   Tidak mengandalkan `accept="image/*"` di browser karena mudah dibobol via Inspect Element/Postman. Filter utama adalah aturan `image` dan `mimes:jpeg,png,jpg,gif` di Laravel.
2. **Lapis 2 — Pembatasan Ukuran File:**  
   Rule `max:2048` membatasi file maksimal 2 MB agar server terhindar dari serangan *Denial of Service (DoS)* kehabisan memori.
3. **Lapis 3 — Penamaan Berkas Unik:**  
   Menambahkan `time().'_'` di depan nama berkas untuk mencegah penimpaan file (*file collision*) saat dua pengguna mengunggah file dengan nama yang sama.
4. **Lapis 4 — Lokasi Penyimpanan di Luar Web Root:**  
   File disimpan di `storage/app/public/`, bukan di folder root `public/index.php`. Folder storage dikonfigurasi agar web server tidak mengeksekusi script PHP berbahaya di dalamnya.

---

## 7. Troubleshooting & Solusi Demo (Slide 14)

| Gejala Masalah | Penyebab Utama | Solusi Instan |
| :--- | :--- | :--- |
| **Gambar 404 Not Found** setelah diupload | Symlink `public/storage` belum dibuat atau terputus. | Jalankan `php artisan storage:link` lalu pastikan shortcut folder `public/storage` muncul. |
| **Upload Gagal / Error 413** *(Payload Too Large)* | File melebihi batasan `upload_max_filesize` di file `php.ini`. | Buka konfigurasi `php.ini` di Laragon/XAMPP, naikkan `upload_max_filesize = 10M` dan `post_max_size = 12M`. |
| **"Must be an image"** padahal memilih file foto | Format berkas belum terdaftar di validasi (misal `.webp`) atau header file rusak. | Tambahkan format ke aturan `mimes` atau gunakan berkas standard `.jpg` / `.png`. |
| **File lama menumpuk di storage** | Method `update()` dan `destroy()` belum dilengkapi perintah penghapusan berkas. | Terapkan `Storage::disk('public')->delete('barang/' . basename(...))`. |
| **File masuk ke storage privat** (Laravel 12+) | Menggunakan format lama `storeAs('public/barang')` yang secara default mengarah ke private. | Gunakan format eksplisit disk publik: `storeAs('barang', $filename, 'public')`. |

---

## 8. Skenario Live Demo & Pengujian Dosen (Slide 17 - Penugasan)

Ikuti urutan langkah berikut saat melakukan demonstrasi di layar proyektor:

```text
Alur Demo:
Landing Page (Read-Only) ──> Login ──> Dashboard ──> Tambah Barang (Test Validasi & Sukses) 
   ──> Cek Tiga Tampilan (36px, 300px, 80px) ──> Edit & Ganti Foto ──> Hapus & Cek Storage Fisik
```

### 🔹 Langkah 1: Tampilkan Landing Page & Keamanan Akses
1. Buka browser pada URL utama: `http://localhost:8000/`.
2. Tunjukkan bagian **Hero Section (kanan atas)**: 4 bingkai kolase foto statis sekolah tetap rapi.
3. Scroll ke bawah pada section **Galeri Dokumentasi & Inventaris Sekolah**:
   - Tunjukkan slider interaktif (Alpine.js Carousel) yang berjalan mulus.
   - Klik tombol panah kiri/kanan dan titik-titik navigasi (*dots*).
   - Jelaskan bahwa landing page bersifat **Read-Only** (tidak ada tombol tambah/edit/hapus).
4. **Uji Keamanan Akses:**
   - Coba ketik URL `/barang` secara manual di address bar.
   - Perlihatkan kepada dosen bahwa sistem secara aman melempar pengguna ke halaman `/login` karena seluruh rute CRUD dilindungi middleware `auth`.

### 🔹 Langkah 2: Login & Masuk ke Modul CRUD
1. Login menggunakan akun staf (Email: `sarpras@sirab.local`, Password: `password`).
2. Klik menu **Inventaris & Dokumentasi** pada Sidebar Dashboard.
3. Tekan tombol **Tambah Barang**.

### 🔹 Langkah 3: Uji Coba Form & Validasi Keamanan (Slide 6 & 17)
1. **Uji Penolakan File Non-Gambar:**
   - Isi form, lalu pilih berkas berekstensi `.txt` atau `.pdf`.
   - Klik Simpan. Tunjukkan pesan error validasi:  
     `The gambar field must be an image.`
2. **Uji Penolakan Ukuran File > 2 MB:**
   - Coba pilih foto berukuran di atas 2 MB (misal 3.5 MB).
   - Klik Simpan. Tunjukkan pesan error validasi:  
     `The gambar field must not be greater than 2048 kilobytes.`

### 🔹 Langkah 4: Uji Sukses Upload Berkas Fisik
1. Pilih foto barang yang valid (misal: `proyektor.jpg`, format JPG/PNG, ukuran 500 KB).
2. Isi Nama: `Proyektor EPSON EB-X500`, Kategori: `Alat Elektronik`, Jumlah: `2`, Kondisi: `Baik`.
3. Tekan **Simpan Data Barang**.
4. Tunjukkan notifikasi sukses muncul.

### 🔹 Langkah 5: Pembuktian Standar Dimensi 3 Halaman (Slide 9)
1. **Halaman Index:** Tunjukkan kolom thumbnail berukuran presisi **36 px**.
2. **Halaman Detail (Show):** Klik tombol detail barang (ikon mata). Tunjukkan foto tampil proporsional dengan batasan maksimal **300 px**.
3. **Halaman Edit:** Klik tombol edit barang. Tunjukkan pratinjau foto lama tampil rapi berukuran **80 px**.

### 🔹 Langkah 6: Uji Perawatan Berkas (Update Foto) (Slide 11)
1. Buka folder Windows Explorer: `c:\laragon\www\StudyCaseAnggaran\storage\app\public\barang\`.
2. Perlihatkan file foto lama yang baru saja dibuat.
3. Di halaman Edit barang, pilih foto baru pengganti (misal: `proyektor_baru.png`).
4. Tekan **Perbarui Data Barang**.
5. Refresh folder Windows Explorer: Perlihatkan kepada dosen bahwa file lama **otomatis terhapus** dan berganti dengan nama file baru.

### 🔹 Langkah 7: Uji Hapus Barang & Kebersihan Storage (Slide 12)
1. Pada tabel index atau detail, tekan tombol **Hapus** barang.
2. Konfirmasi dialog peringatan.
3. Tunjukkan bahwa data terhapus dari tabel database dan file fotonya di folder `storage/app/public/barang/` **bersih seketika tanpa meninggalkan file sampah**.

### 🔹 Langkah 8: Tampilkan Hasil Pengujian Otomatis (Automated Testing)
Buka terminal dan jalankan test suite resmi:
```bash
php artisan test --compact tests/Feature/BarangUploadPertemuan13Test.php
```
Hasil: **11 passed (75 assertions)** membuktikan seluruh fitur upload telah teruji secara sistematis dan lulus uji fungsionalitas.

---

## 9. Cheat Sheet Jawaban Pertanyaan Kritis Dosen (Q&A)

| Pertanyaan Dosen | Jawaban Teknis yang Tepat |
| :--- | :--- |
| **1. Mengapa gambar disimpan di storage, bukan langsung di kolom database?** | *"Jika disimpan di database dalam bentuk BLOB, ukuran database akan membengkak, proses backup memakan waktu lama, dan query data menjadi berat. Dengan menyimpan file di disk dan path-nya di database, database tetap ringan, responsif, dan siap jika sewaktu-waktu dipindahkan ke Cloud Storage seperti Amazon S3."* |
| **2. Mengapa di Laravel 12/13 kita harus menuliskan disk `'public'` secara eksplisit?** | *"Karena sejak Laravel 12, disk default `local` diarahkan ke direktori privat `storage/app/private`. Jika kita menulis format lama `storeAs('public/barang')`, file justru tersimpan di `storage/app/private/public/barang` sehingga browser akan mengalami error 404 saat mengaksesnya via symlink."* |
| **3. Apa fungsi perintah `php artisan storage:link`?** | *"Secara default, browser hanya diizinkan mengakses folder `public/`. Berkas unggahan tersimpan di folder aman `storage/app/public/`. Perintah `storage:link` membuat symbolic link (jalan pintas) dari `public/storage` ke `storage/app/public` sehingga berkas bisa diakses publik secara aman tanpa mengekspos struktur server."* |
| **4. Mengapa kita butuh fungsi `basename()` saat menghapus gambar lama?** | *"Database kita menyimpan string path URL berupa `'storage/barang/nama_file.jpg'`. Sementara itu, facade `Storage::disk('public')->delete()` membaca berkas relatif dari folder `storage/app/public/`. Fungsi `basename()` mengambil nama filenya saja, lalu kita hubungkan dengan folder `'barang/' . basename(...)` agar path penghapusan tepat sasaran."* |
| **5. Kenapa atribut `accept="image/*"` di form HTML saja tidak cukup untuk keamanan?** | *"Atribut HTML `accept` hanya penyaring visual di sisi klien dan sangat mudah dimanipulasi atau dilewati melalui tool seperti Postman atau cURL. Keamanan sejati wajib ditegakkan di sisi server melalui validasi Laravel menggunakan rule `image` dan `mimes` yang memeriksa Magic Bytes berkas."* |

---
*Dokumen ini disusun untuk mendukung demonstrasi ujian/presentasi materi Pertemuan 13 Web Framework.*
