# SIRAB &bull; Sistem Informasi Rencana Anggaran Biaya Sekolah

**Aplikasi Manajemen Anggaran Operasional Sekolah Dasar / Menengah (Studi Kasus: SDN Sidokare 3)**

Sistem informasi berbasis web menggunakan framework **Laravel 11 & Tailwind CSS** yang dirancang untuk mengelola siklus perencanaan, pengajuan, verifikasi bertingkat (Bendahara BOS &amp; Kepala Sekolah), serta pencairan dana anggaran operasional sekolah (BOS &amp; RKAS).

---

## ⚡ Panduan Cepat untuk Tim Pengembang

> [!IMPORTANT]
> **PANDUAN LENGKAP SETELAH `git pull`:**
> Untuk memahami apa yang terjadi secara otomatis saat `git pull` dan panduan troubleshooting tim, baca:
> 📖 **[PANDUAN_GIT_PULL.md](file:///c:/laragon/www/StudyCaseAnggaran/PANDUAN_GIT_PULL.md)**

---

### 1. Onboarding Anggota Baru (Pertama Kali Clone)

Jika Anda anggota tim baru yang baru saja meng-clone repositori ini:

```bash
# 1. Clone repositori
git clone <repository_url>
cd StudyCaseAnggaran

# 2. Jalankan instalasi dependensi (otomatis memasang Git Hooks)
composer install

# 3. Jalankan setup lingkungan otomatis
# (Akan membuat .env, generate key, setup git hooks, migrasi, dan seed master data)
php artisan dev:setup
```

*(Opsional: Jika ingin menambahkan data pengajuan dummy untuk uji coba, pilih opsi `yes` saat ditanya oleh command `dev:setup`, atau jalankan `php artisan db:seed --class=DummyDataSeeder`).*

Terakhir, kompilasi aset frontend dan jalankan server pengembangan:
```bash
npm install
npm run build
php artisan serve
```

---

### 2. Rutinitas Kerja Harian Tim (Automasi Penuh)

Anda **TIDAK PERLU LAGI** mengimpor file `.sql` secara manual atau menjalankan migrasi manual setiap kali rekan kerja Anda memperbarui skema database.

Cukup jalankan:
```bash
git pull origin main
```

**Git Hook (`post-merge`) akan bekerja secara otomatis di latar belakang:**
- ⚡ Menjalankan `php artisan migrate --force` jika ada migrasi baru (tanpa menghapus data lokal Anda).
- ⚡ Memutakhirkan master data sekolah via `php artisan db:seed --force` (idempoten).
- ⚡ Menjalankan `composer install` jika `composer.lock` diperbarui.
- ⚡ Menjalankan `npm run build` jika ada perubahan template / aset frontend.

---

### 3. Reset Database Lokal ke Kondisi Bersih (Fresh Start)

Jika sewaktu-waktu database lokal Anda berantakan dan ingin mulai dari kondisi bersih awal:

```bash
# Reset semua tabel dan isi master data esensial
php artisan migrate:fresh --seed

# (Opsional) Tambahkan berkas RAB dummy untuk pengujian
php artisan db:seed --class=DummyDataSeeder
```

---

### 4. Aturan Pembuatan Migration Tim (Mencegah Bentrok Skema)

1. **Dilarang Mengedit Migration Lama**: Jangan pernah mengubah file migrasi yang sudah masuk ke branch `main`.
2. **Wajib Buat Migration Baru**: Gunakan `php artisan make:migration [nama_perubahan]`.
3. **Defensive Check**: Gunakan `Schema::hasColumn(...)` atau `Schema::hasTable(...)` pada migrasi alter.

---

## 👥 Akun Login Pengujian Default

Password untuk seluruh akun default: **`password`**

| Role | Nama | Email | Divisi / Unit Kerja |
| :--- | :--- | :--- | :--- |
| **Admin IT / Ka. TU** | Drs. Arif Rachman | `arif@sirab.local` | Tata Usaha & Operasional (TU) |
| **Bendahara BOS (Finance)** | Akun Finance | `finance@sirab.local` | Tata Usaha & Operasional (TU) |
| **Kepala Sekolah (Pimpinan)** | Akun Pimpinan | `pimpinan@sirab.local` | Tata Usaha & Operasional (TU) |
| **Staf Kurikulum** | Sari Dewi | `sari@sirab.local` | Kurikulum & Pembelajaran |
| **Staf Sarpras** | Budi Santoso | `budi@sirab.local` | Sarana & Prasarana (Sarpras) |
| **Staf Kesiswaan** | Dina Marlina | `dina@sirab.local` | Kesiswaan & Ekstrakurikuler |

---

## 🛠 Tech Stack

- **Backend**: Laravel 11 / PHP 8.3
- **Database**: MySQL / MariaDB (Didukung SQLite untuk automated test)
- **Frontend**: Blade Templating, Tailwind CSS, FontAwesome 6, Alpine.js
- **Build Tool**: Vite
- **Testing**: PHPUnit / Feature Tests
