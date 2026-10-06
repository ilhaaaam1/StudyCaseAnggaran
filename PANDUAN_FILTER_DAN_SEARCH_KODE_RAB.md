# 🧠 Cheat Sheet & Panduan Cepat: Filter & Search Kode/Nomor RAB
> **Bahan Persiapan Ujian Live Coding (Tanpa Internet & AI)**  
> *Target: Hafal polanya dalam 5 menit, langsung paham logikanya, dan siap tulis tangan di editor!*

---

## 🧭 Daftar Isi
1. [Logika Dasar: Analogi "Laci dan Satpam Pencari Data"](#1-logika-dasar-analogi-laci-dan-satpam-pencari-data)
2. [Cara Mengatur Pencarian di Backend (Laravel Eloquent)](#2-cara-mengatur-pencarian-di-backend-laravel-eloquent)
   - [Perangkap Fatal: Mengapa Wajib Dibungkus Tanda Kurung `where(function)`?](#perangkap-fatal-mengapa-wajib-dibungkus-tanda-kurung-wherefunction)
   - [Kode Sebelum vs Sesudah (Boleh vs Tidak Boleh Dicari)](#kode-sebelum-vs-sesudah-boleh-vs-tidak-boleh-dicari)
3. [Cara Mengatur Pencarian di Frontend (DataTables / JS)](#3-cara-mengatur-pencarian-di-frontend-datatables--js)
4. [⚡ Cheat Sheet / Hafalan Cepat Live Coding (Cukup Ingat 5 Baris Ini!)](#4--cheat-sheet--hafalan-cepat-live-coding-cukup-ingat-5-baris-ini)
5. [Trik Cepat Uji Coba di Browser Saat Ujian](#5-trik-cepat-uji-coba-di-browser-saat-ujian)

---

## 1. Logika Dasar: Analogi "Laci dan Satpam Pencari Data"

Bayangkan database aplikasi adalah sebuah **lemari arsip** besar yang memiliki banyak laci:
- Laci 1: **Nomor / Kode RAB** (misal: `RAB-2026-001`)
- Laci 2: **Judul Pengajuan** (misal: `Pengadaan Buku Tematik Kelas 4`)
- Laci 3: **Nama Pengaju** (misal: `Sari Puspita`)
- Laci 4: **Nominal Anggaran** (misal: `15.000.000`)

Ketika staf mengetik kata kunci `"2026"` di kolom pencarian, sistem akan menugaskan **Satpam (Query SQL)** untuk mencari berkas:

```
[ Kata Kunci Pencarian: "2026" ]
                │
                ▼
      ┌──────────────────┐
      │  Satpam Database │
      └─────────┬────────┘
                │
    ┌───────────┴───────────┐
    ▼                       ▼
Laci Nomor RAB?       Laci Judul Kegiatan?
 [ Boleh Diperiksa ]    [ Boleh Diperiksa ]
```

### Pertanyaan Ujian: *"Mengapa kolom Kode RAB bisa ikut tercari atau sengaja dilarang dicari?"*
1. **Jika DIPERBOLEHKAN dicari:**  
   Kita menyuruh satpam: *"Cari di laci Judul, **ATAU (OR)** cari juga di laci Nomor RAB."*  
   *Hasil:* Saat user mengetik `RAB-001`, berkas langsung ketemu.
2. **Jika SENGAJA DIKECUALIKAN (Dilarang dicari):**  
   Penguji ujian mungkin memberi instruksi: *"Pencarian hanya berlaku untuk Judul Pengajuan, jangan cari berdasarkan Nomor RAB!"*  
   *Alasan praktis:* Agar jika seseorang mengetik angka tahun atau format acak, sistem tidak salah mencocokkan nomor dokumen, melainkan murni mencocokkan isi kegiatan saja.

---

## 2. Cara Mengatur Pencarian di Backend (Laravel Eloquent)

### ⚠️ Perangkap Fatal: Mengapa Wajib Dibungkus Tanda Kurung `where(function)`?

Ini adalah **kesalahan nomor satu** yang paling sering menjebak mahasiswa/developer saat ujian live coding!

Katakanlah di controller ada filter status:  
👉 **Hanya tampilkan berkas yang statusnya `diajukan`**

#### ❌ CARA YANG SALAH (Tanpa Kurung Pembungkus):
```php
// JANGAN DILAKUKAN SAAT UJIAN!
$query->where('status', 'diajukan');
$query->where('no_rab', 'like', "%{$search}%");
$query->orWhere('judul_pengajuan', 'like', "%{$search}%"); // 💣 RUSAK!
```
**Mengapa ini rusak?**  
Di SQL, kode di atas diterjemahkan menjadi:
```sql
SELECT * FROM pengajuan 
WHERE status = 'diajukan' AND no_rab LIKE '%buku%' 
OR judul_pengajuan LIKE '%buku%'
```
Karena hukum logika `OR`: Jika ada data yang judulnya mengandung kata `"buku"`, maka data tersebut **AKAN MUNCUL WALAUPUN STATUSNYA SUDAH DITOLAK ATAU MASIH DRAFT!** Filter status Anda langsung bocor/jebol!

---

#### ✅ CARA YANG BENAR (Wajib Dibungkus Grup Tanda Kurung):
```php
// SATU GRUP KHUSUS SEARCH
$query->where('status', 'diajukan'); // Filter status tetap aman di luar

$query->where(function ($q) use ($search) {
    $q->where('no_rab', 'like', "%{$search}%")
      ->orWhere('judul_pengajuan', 'like', "%{$search}%");
});
```
**Hasil di SQL:**
```sql
SELECT * FROM pengajuan 
WHERE status = 'diajukan' 
  AND (no_rab LIKE '%buku%' OR judul_pengajuan LIKE '%buku%')
```
*Tanda kurung `( ... )` mengisolasi pencarian sehingga tidak mengganggu filter status, tanggal, atau divisi!*

---

### 🔄 Kode Sebelum vs Sesudah (Boleh vs Tidak Boleh Dicari)

Perhatikan baris `$q->where('no_rab', ...)` berikut ini:

#### Kasus A: Ingin Nomor RAB IKUT DICARI (Standard)
```php
// File Controller (misal: StaffRabController atau AdminRabController)
$search = $request->input('q') ?? $request->input('search');

if ($search) {
    $query->where(function ($q) use ($search) {
        // Baris ini membuat Nomor RAB ikut diperiksa:
        $q->where('no_rab', 'like', "%{$search}%")
          ->orWhere('judul_pengajuan', 'like', "%{$search}%");
    });
}
```

#### Kasus B: Dosen Penguji Minta KODE RAB DIKECUALIKAN (Tidak Boleh Dicari)
*Cukup hapus atau ubah baris `no_rab`:*
```php
$search = $request->input('q') ?? $request->input('search');

if ($search) {
    $query->where(function ($q) use ($search) {
        // Cukup cari di judul saja (no_rab ditiadakan):
        $q->where('judul_pengajuan', 'like', "%{$search}%");
    });
}
```

---

## 3. Cara Mengatur Pencarian di Frontend (DataTables / JS)

Jika di ujian live coding Anda menggunakan pustaka **jQuery DataTables** di sisi frontend, DataTables memiliki opsi bawaan per kolom bernama `searchable`.

### Cara Kerja:
- `searchable: true` ➔ Kolom ini akan disisir oleh kotak search DataTables.
- `searchable: false` ➔ Kolom ini diabaikan oleh kotak search DataTables.

### Contoh Kode JavaScript DataTables:
```javascript
$('#tabelRab').DataTable({
    columns: [
        // 1. Kolom Nomor RAB
        { 
            data: 'no_rab', 
            searchable: true   // Ubah ke 'false' jika dilarang dicari!
        },
        
        // 2. Kolom Judul Kegiatan
        { 
            data: 'judul_pengajuan', 
            searchable: true 
        },
        
        // 3. Kolom Aksi / Tombol (Selalu set false agar tidak dicari)
        { 
            data: 'action', 
            searchable: false, 
            orderable: false 
        }
    ]
});
```

> 💡 **Tips Menghafal Ujian:**  
> Jika dosen bertanya: *"Gimana cara matiin search di kolom tertentu pada DataTables?"*  
> Jawabannya singkat: *"Beri properti `searchable: false` pada definisi kolom tersebut di script JS."*

---

## 4. ⚡ Cheat Sheet / Hafalan Cepat Live Coding (Cukup Ingat 5 Baris Ini!)

Saat ujian tanpa internet, **JANGAN MENGHAFAL NAMA VARIABEL PANJANG**. Hafalkan saja **kerangka tulangnya**:

### 🎯 Pola Hafalan: "IF ➜ BUNGKUS ➜ LIKE"

```php
if ($keyword = $request->q) {
    $query->where(function($q) use ($keyword) {
        $q->where('no_rab', 'like', "%$keyword%")
          ->orWhere('judul_pengajuan', 'like', "%$keyword%");
    });
}
```

### 🧠 Rumus Mudah Mengingat dalam 3 Detik:
1. **Baris 1:** Tangkap input teks dari form: `if ($keyword = $request->q)` *(atau gunakan `$request->search`)*.
2. **Baris 2:** Buat tanda kurung pengaman: `$query->where(function($q) use ($keyword) {`
3. **Baris 3:** Periksa kolom pertama: `$q->where('nama_kolom', 'like', "%$keyword%")`
4. **Baris 4:** Periksa kolom kedua (pakai `or`): `->orWhere('kolom_lain', 'like', "%$keyword%");`
5. **Baris 5:** Tutup kurung: `});`

---

### Alternatif dengan `$query->when()` (Gaya Modern Laravel):
Jika penguji lebih menyukai method bawaan Laravel `when()`:

```php
$query->when($request->search, function ($q, $cari) {
    $q->where(function ($sub) use ($cari) {
        $sub->where('no_rab', 'like', "%$cari%")
            ->orWhere('judul_pengajuan', 'like', "%$cari%");
    });
});
```
*(Keduanya menghasilkan query SQL yang persis sama, pilih mana yang paling lancar Anda ketik!)*

---

## 5. Trik Cepat Uji Coba di Browser Saat Ujian

Setelah Anda mengetik query di Controller, bagaimana cara memastikan kodenya bekerja 100% tanpa perlu repot membuat form input HTML baru?

### Trik Manual URL Parameter:
Cukup tambahkan parameter `?q=` atau `?search=` langsung di URL browser Anda!

1. Buka halaman tabel di browser:  
   👉 `http://127.0.0.1:8000/staff/pengajuan`
2. Tes pencarian Nomor RAB:  
   👉 `http://127.0.0.1:8000/staff/pengajuan?q=2026`  
   *(Lihat apakah hanya dokumen bertahun 2026 yang keluar)*
3. Tes pencarian Kata Kunci Acak (Harus Kosong):  
   👉 `http://127.0.0.1:8000/staff/pengajuan?q=xyz999tidakada`  
   *(Jika tabel kosong / menampilkan "Data tidak ditemukan", berarti query Anda berhasil!)*
4. Hapus parameter URL untuk kembali normal:  
   👉 `http://127.0.0.1:8000/staff/pengajuan`

---

## 📌 Rangkuman 1 Menit Sebelum Masuk Ruang Ujian:
- **Tujuan grouping `where(function...)`**: Mengunci logika `OR` agar tidak merusak filter status atau relasi lainnya.
- **Sintaks LIKE**: Selalu gunakan tanda persen ganda `"%{$search}%"` agar pencarian bersifat parsial (cocok di depan, tengah, maupun belakang kata).
- **Di DataTables**: Gunakan `searchable: false` untuk mematikan pencarian pada kolom tertentu.

*Selamat belajar & semoga ujian live coding-nya sukses mendapat nilai A! 🚀*
