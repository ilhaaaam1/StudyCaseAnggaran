# 📖 Panduan Praktis: Mengatur Batas Minimal Karakter pada Form Pengajuan RAB

Selamat datang di panduan pembuatan batasan panjang isian (karakter) formulir! Dokumen ini dirancang khusus dengan bahasa yang santai, sederhana, dan mudah dimengerti—bahkan jika Anda belum pernah mendalami pemrograman web sebelumnya.

---

## 🎯 Daftar Isi
1. [Mengapa Batasan Karakter Sangat Diperlukan?](#1-mengapa-batasan-karakter-sangat-diperlukan)
2. [Konsep Keamanan Dua Lapis: Layar Depan vs Penjaga Pintu Server](#2-konsep-keamanan-dua-lapis-layar-depan-vs-penjaga-pintu-server)
3. [Lapisan 1: Pengecekan di Tampilan Layar (Frontend)](#3-lapisan-1-pengecekan-di-tampilan-layar-frontend)
   - [A. Menggunakan Atribut HTML `minlength`](#a-menggunakan-atribut-html-minlength)
   - [B. Menampilkan Indikator Penghitung Karakter (Live Counter)](#b-menampilkan-indikator-penghitung-karakter-live-counter)
4. [Lapisan 2: Penjaga Pintu di Server (Backend Laravel)](#4-lapisan-2-penjaga-pintu-di-server-backend-laravel)
   - [A. Menambahkan Aturan `min` di Form Request](#a-menambahkan-aturan-min-di-form-request)
   - [B. Menyiapkan Pesan Peringatan Ramah Bahasa Indonesia](#b-menyiapkan-pesan-peringatan-ramah-bahasa-indonesia)
5. [Langkah demi Langkah Praktik Mandiri di Proyek SIRAB](#5-langkah-demi-langkah-praktik-mandiri-di-proyek-sirab)
   - [Langkah 1: Perbarui Validator Server](#langkah-1-perbarui-validator-server)
   - [Langkah 2: Perbarui Formulir di Tampilan Web](#langkah-2-perbarui-formulir-di-tampilan-web)
   - [Langkah 3: Uji Coba Langsung di Browser](#langkah-3-uji-coba-langsung-di-browser)
6. [💡 Tips Tambahan & Best Practice](#6--tips-tambahan--best-practice)

---

## 1. Mengapa Batasan Karakter Sangat Diperlukan?

Bayangkan jika seorang staf mengajukan anggaran dana sekolah bernilai jutaan rupiah, namun pada kolom **Judul Pengajuan** ia hanya menulis:
> *"Beli buku"* atau *"Acara"*

Lalu pada kolom **Latar Belakang / Urgensi Kegiatan**, ia hanya mengisi:
> *"Penting"* atau *"Butuh cepat"*

Tentu pihak **Finance (Bendahara)** dan **Pimpinan (Kepala Sekolah)** akan kesulitan memahami:
- Buku apa yang dibeli? Untuk kelas berapa?
- Kenapa kegiatan tersebut mendesak? Apa dampaknya bagi siswa?

### 🌟 Manfaat Menerapkan Batas Minimal Karakter:
1. **Mencegah Isian Asal-asalan:** Mengharuskan staf menulis penjelasan yang lebih deskriptif dan bertanggung jawab.
2. **Mempermudah Proses Verifikasi:** Pihak yang menyetujui anggaran tidak perlu bolak-balik bertanya maksud dari pengajuan tersebut.
3. **Meningkatkan Kerapian Data Arsip:** Laporan pertanggungjawaban (SPJ) memiliki rekam jejak nama dan alasan kegiatan yang jelas dan profesional.

---

## 2. Konsep Keamanan Dua Lapis: Layar Depan vs Penjaga Pintu Server

Dalam pembuatan aplikasi web modern (seperti Laravel), pengamanan formulir diibaratkan seperti sistem keamanan di gedung sekolah:

```
[ PENGGUNA / STAF ]
        │
        ▼
┌───────────────────────────────────────────────┐
│ LAPISAN 1: Pintu Pagar Depan (Frontend/Layar)  │
│ - Menghitung jumlah huruf secara langsung      │
│ - Memberi tahu staf sebelum tombol ditekan    │
│ - Cepat & ramah bagi pengguna                 │
└───────────────────────────────────────────────┘
        │ (Jika lolos / tombol Submit ditekan)
        ▼
┌───────────────────────────────────────────────┐
│ LAPISAN 2: Satpam Utama di Ruang Server       │
│ - Memeriksa ulang secara ketat di Backend     │
│ - Menolak data jika ada yang memotong kompas  │
│ - Menyimpan data ke Database jika sudah sah   │
└───────────────────────────────────────────────┘
```

- **Frontend (Tampilan Layar):** Berfungsi sebagai pemandu ramah. Memberi tahu pengguna: *"Ups, judulnya masih kurang 4 huruf lagi ya!"* sebelum form terkirim.
- **Backend (Server Laravel):** Berfungsi sebagai satpam resmi. Walaupun seseorang mematikan validasi di browser, server tetap akan menolak isian yang terlalu pendek.

---

## 3. Lapisan 1: Pengecekan di Tampilan Layar (Frontend)

Ada dua trik praktis yang bisa dipasang pada formulir di browser:

### A. Menggunakan Atribut HTML `minlength`
HTML bawaan memiliki atribut sederhana bernama `minlength`. Browser akan otomatis menahan form agar tidak terkirim jika panjang huruf belum memenuhi syarat.

```html
<!-- Contoh pada Judul Pengajuan: Minimal 10 karakter -->
<input type="text" 
       name="judul_pengajuan" 
       minlength="10" 
       required 
       placeholder="Contoh: Pengadaan Modul Literasi ANBK...">
```

```html
<!-- Contoh pada Latar Belakang: Minimal 20 karakter -->
<textarea name="latar_belakang" 
          minlength="20" 
          required 
          placeholder="Jelaskan kebutuhan pengajuan ini..."></textarea>
```

---

### B. Menampilkan Indikator Penghitung Karakter (Live Counter)
Pengguna sering kali merasa frustrasi jika tidak tahu berapa jumlah karakter yang sudah mereka ketik. Karena aplikasi SIRAB menggunakan pustaka **Alpine.js**, kita dapat membuat penghitung karakter otomatis dengan sangat mudah!

#### Contoh Penerapan pada Judul Pengajuan (Target: Minimal 10 Karakter):

```html
<div x-data="{ judul: '' }">
  <div class="flex items-center justify-between mb-1">
    <label class="text-xs font-semibold text-slate-700">
      Judul Pengajuan Kegiatan <span class="text-rose-500">*</span>
    </label>
    
    <!-- Indikator Live Counter: Berubah hijau jika sudah mencapai 10 karakter -->
    <span class="text-xs font-medium" 
          :class="judul.length >= 10 ? 'text-emerald-600' : 'text-slate-400'">
      <span x-text="judul.length"></span> / 10 karakter minimum
    </span>
  </div>

  <input type="text" 
         name="judul_pengajuan" 
         x-model="judul"
         minlength="10"
         placeholder="Contoh: Pengadaan Buku Referensi Perpustakaan..."
         class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300">
</div>
```

**Cara Kerja Indikator Ini:**
- Saat staf mengetik huruf pertama (`B`), indikator menampilkan: `1 / 10 karakter minimum` (warna abu-abu/merah).
- Saat ketikan mencapai 10 huruf atau lebih, warna angka otomatis berubah menjadi **hijau segar** (`text-emerald-600`), menandakan judul sudah memenuhi syarat minimum!

---

## 4. Lapisan 2: Penjaga Pintu di Server (Backend Laravel)

Validasi di sisi server adalah benteng pertahanan utama. Di Laravel, aturan validasi dikumpulkan secara rapi di dalam berkas **Form Request**.

### A. Menambahkan Aturan `min` di Form Request
Untuk memasang batas minimal karakter di Laravel, cukup tambahkan aturan `'min:angka'` pada field yang diinginkan.

Contoh aturan:
- `'judul_pengajuan' => ['required', 'string', 'min:10', 'max:255']`  
  *(Wajib diisi, berupa teks, minimal 10 huruf, maksimal 255 huruf)*
- `'latar_belakang' => ['required', 'string', 'min:20']`  
  *(Wajib diisi, berupa teks, minimal 20 huruf)*

---

### B. Menyiapkan Pesan Peringatan Ramah Bahasa Indonesia
Secara bawaan (*default*), Laravel mungkin menampilkan pesan dalam bahasa Inggris seperti *"The judul pengajuan field must be at least 10 characters."*

Agar lebih komunikatif dan ramah bagi staf sekolah, kita bisa menyesuaikan pesannya di fungsi `messages()`:

```php
/**
 * Pesan error kustom agar ramah dipahami oleh staf sekolah.
 */
public function messages(): array
{
    return [
        'judul_pengajuan.min' => 'Judul pengajuan terlalu pendek (minimal 10 karakter). Mohon buat judul yang lebih jelas dan spesifik.',
        'latar_belakang.min' => 'Latar belakang & urgensi minimal 20 karakter agar bagian Verifikator memahami tujuan kegiatan ini.',
    ];
}
```

---

## 5. Langkah demi Langkah Praktik Mandiri di Proyek SIRAB

Jika Anda ingin menerapkan batasan karakter ini secara mandiri di proyek SIRAB, berikut panduan berkas dan posisi baris yang relevan:

### Langkah 1: Perbarui Validator Server
- **Lokasi Berkas:**  
  `app/Http/Requests/StorePengajuanRabRequest.php`

- **Posisi Baris:**  
  Sekitar baris **27 - 47** (di dalam fungsi `rules()`).

- **Potongan Kode yang Ditambahkan:**
  ```php
  public function rules(): array
  {
      return [
          'id_pengguna' => ['required', 'integer', 'exists:pengguna,id_pengguna'],
          'id_divisi' => ['required', 'integer', 'exists:divisi,id_divisi'],
          'no_rab' => ['required', 'string', 'max:50', 'unique:pengajuan_rab,no_rab'],
          
          // ➜ Tambahkan 'min:10' di judul_pengajuan:
          'judul_pengajuan' => ['required', 'string', 'min:10', 'max:255'],
          
          'periode_penggunaan' => ['required', 'string', 'max:50'],
          'prioritas' => ['required', 'in:rendah,sedang,tinggi'],
          
          // ➜ Tambahkan 'min:20' di latar_belakang:
          'latar_belakang' => ['required', 'string', 'min:20'],
          
          // ... rincian lainnya ...
      ];
  }
  ```

- **Menambahkan Pesan Kustom:**  
  Tambahkan fungsi `messages()` di bawah fungsi `attributes()` pada berkas tersebut:
  ```php
  public function messages(): array
  {
      return [
          'judul_pengajuan.min' => 'Judul pengajuan minimal 10 karakter agar jelas bagi verifikator.',
          'latar_belakang.min'  => 'Latar belakang minimal 20 karakter untuk menguraikan urgensi kegiatan.',
      ];
  }
  ```

---

### Langkah 2: Perbarui Formulir di Tampilan Web
- **Lokasi Berkas:**  
  `resources/views/staff/create.blade.php`

- **Posisi Baris 1 (Judul Pengajuan):**  
  Sekitar baris **60 - 70**.
  
  Tambahkan atribut `minlength="10"` dan hubungkan Alpine.js jika menginginkan indikator live counter:
  ```blade
  <!-- Judul Pengajuan Kegiatan -->
  <div class="sm:col-span-2" x-data="{ judul: '{{ old('judul_pengajuan', '') }}' }">
    <div class="flex items-center justify-between mb-1.5">
      <label class="block text-xs font-semibold text-slate-700">
        Judul Pengajuan Kegiatan <span class="text-rose-500">*</span>
      </label>
      <span class="text-[11px] font-medium" :class="judul.length >= 10 ? 'text-emerald-600 font-semibold' : 'text-slate-400'">
        <span x-text="judul.length"></span>/10 karakter
      </span>
    </div>
    <input type="text" 
           name="judul_pengajuan" 
           x-model="judul"
           minlength="10"
           value="{{ old('judul_pengajuan') }}" 
           required
           placeholder="Contoh: Pengadaan Modul Literasi ANBK dan Alat Peraga Kelas 5"
           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
    <p class="text-[11px] text-slate-400 mt-1">Buat nama kegiatan yang spesifik dan jelas sesuai sasaran kegiatan sekolah.</p>
    @error('judul_pengajuan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
  </div>
  ```

- **Posisi Baris 2 (Latar Belakang & Urgensi):**  
  Sekitar baris **227 - 235**.
  
  Tambahkan atribut `minlength="20"` dan live counter:
  ```blade
  <!-- Latar Belakang & Urgensi -->
  <div x-data="{ alasan: '{{ old('latar_belakang', '') }}' }">
    <div class="flex items-center justify-between mb-1">
      <label class="block text-xs font-semibold text-slate-700">
        Latar Belakang & Urgensi Kegiatan <span class="text-rose-500">*</span>
      </label>
      <span class="text-[11px] font-medium" :class="alasan.length >= 20 ? 'text-emerald-600 font-semibold' : 'text-slate-400'">
        <span x-text="alasan.length"></span>/20 karakter
      </span>
    </div>
    <textarea name="latar_belakang" 
              x-model="alasan"
              minlength="20"
              rows="3" 
              required
              placeholder="Uraikan justifikasi kebutuhan kegiatan, target peserta siswa/guru, serta urgensi alokasi anggaran ini..."
              class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500">{{ old('latar_belakang') }}</textarea>
    @error('latar_belakang') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
  </div>
  ```

---

### Langkah 3: Uji Coba Langsung di Browser

Setelah kode dipasang, lakukan pengetesan melalui langkah berikut:

1. Buka browser dan login sebagai **Staf**.
2. Masuk ke menu **Buat Pengajuan Baru** (`/staff/pengajuan/create`).
3. **Uji Skenario Gagal:**
   - Masukkan judul pengajuan pendek, misalnya: `"Tes"` (hanya 3 huruf).
   - Masukkan latar belakang pendek, misalnya: `"Butuh dana"` (10 huruf).
   - Perhatikan indikator: angka masih berwarna abu-abu (`3/10` dan `10/20`).
   - Coba klik tombol simpan: Browser akan langsung menolak dan meminta pengguna melengkapi karakter yang kurang.
4. **Uji Skenario Berhasil:**
   - Ubah judul menjadi: `"Pengadaan Buku Pembelajaran Tematik Kelas 4"` (44 huruf).
   - Ubah latar belakang menjadi: `"Dibutuhkan untuk mendukung kurikulum merdeka semester genap."` (58 huruf).
   - Perhatikan indikator: angka otomatis berubah menjadi **hijau** (`44/10` dan `58/20`).
   - Klik tombol simpan: Formulir berhasil lolos ke server! 🎉

---

## 6. 💡 Tips Tambahan & Best Practice

1. **Gunakan Angka Batas yang Masuk Akal:**
   - Untuk **Judul Pengajuan**: Batas `min:10` sampai `min:15` sudah ideal. Jangan terlalu panjang (misal `min:50`) karena akan menyulitkan staf yang kegiatannya memang bernama singkat.
   - Untuk **Latar Belakang**: Batas `min:20` sampai `min:30` sangat baik agar staf terbiasa menulis minimal 1 kalimat utuh.
2. **Sediakan Teks Contoh (*Placeholder*) yang Edukatif:**
   - Berikan contoh nyata pada atribut `placeholder="..."` agar staf memiliki gambaran kalimat seperti apa yang diharapkan.
3. **Pesan Error yang Solutif:**
   - Hindari pesan yang menyalahkan pengguna. Alih-alih menulis *"Input salah!"*, gunakan kalimat bantuan seperti *"Judul pengajuan minimal 10 karakter, ya. Silakan lengkapi nama kegiatannya."*

---
*Dokumen ini dibuat sebagai panduan edukasi teknis untuk pengembangan formulir pengajuan anggaran (SIRAB).*
