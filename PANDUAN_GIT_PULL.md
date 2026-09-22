# Panduan Alur Kerja Tim SIRAB: Setelah Melakukan `git pull`

Dokumen ini adalah panduan resmi bagi seluruh pengembang tim **SIRAB (Sistem Informasi Rencana Anggaran Biaya Sekolah - SDN Sidokare 3)** untuk memahami apa yang terjadi secara otomatis dan apa yang harus dilakukan setelah menjalankan perintah `git pull`.

---

## 1. Ringkasan: Sistem Automasi Git Hooks

Di proyek ini, kita telah mengaktifkan **Automasi Git Hook (`post-merge`)**.

Setiap kali Anda menjalankan:
```bash
git pull origin main
```
Sistem Git secara otomatis memeriksa perubahan file dan mengeksekusi automasi berikut di latar belakang:
1. **Migrasi Database Otomatis**: Jika ada file baru di folder `database/migrations/`, sistem langsung menjalankan `php artisan migrate --force`. Data uji coba dan entri lokal Anda **TIDAK AKAN DIHAPUS** (tanpa flag fresh/drop).
2. **Master Data Sync**: Memutakhirkan data divisi sekolah dan akun pengguna default (`php artisan db:seed --force`) dengan strategi idempoten (tanpa duplikasi).
3. **Dependency PHP Otomatis**: Jika ada paket baru di `composer.lock`, sistem otomatis menjalankan `composer install`.
4. **Kompilasi Frontend Otomatis**: Jika ada perubahan file CSS/Blade/JS atau `package.json`, sistem otomatis mengompilasi aset (`npm run build`).

---

## 2. Apa yang Harus Dilakukan Setelah `git pull`?

Perhatikan pesan terminal / banner yang muncul di akhir perintah `git pull`:

### Skenario A: Muncul Banner Hijau "Sinkronisasi selesai!" (Kondisi Normal)
```text
╔══════════════════════════════════════════════════════════════════╗
║              SIRAB SDN SIDOKARE 3 - AUTO SYNC HOOK               ║
║                  (Post-Merge Automation Active)                  ║
╚══════════════════════════════════════════════════════════════════╝

✔ Database berhasil dimigrasi tanpa menghapus data lokal Anda.
✔ Master data (Divisi & Akun Default) termutakhirkan.
✔ Sinkronisasi selesai! Lingkungan kerja lokal Anda siap digunakan.
```
👉 **Tindakan Anda:** **TIDAK PERLU MELAKUKAN APA-APA LAGI.** Database, paket composer, dan aset frontend Anda sudah 100% mutakhir. Anda bisa langsung membuka browser dan melanjutkan koding!

---

### Skenario B: Muncul Notifikasi File `.env.example` Berubah
Jika di terminal muncul pesan:
```text
ℹ PERHATIAN: File .env.example telah diperbarui oleh tim.
  Bandingkan file .env lokal Anda untuk memastikan tidak ada kunci konfigurasi baru yang terlewat.
```
👉 **Tindakan Anda:**
1. Buka file `.env.example` dan bandingkan dengan file `.env` lokal Anda.
2. Jika ada variabel konfigurasi baru (misal: kunci API baru atau setting mailer), salin variabel tersebut ke `.env` lokal Anda.

---

### Skenario C: Muncul Pesan Merah "GAGAL menjalankan migrasi database"
Contoh error: *Access denied for user*, *Connection refused*, atau *Table not found*.

👉 **Penyebab Umum & Solusinya:**
1. **Layanan Database (MySQL) Belum Berjalan:**
   - Pastikan aplikasi Laragon / XAMPP / MySQL Service sudah dalam status **START** / aktif.
   - Setelah MySQL aktif, cukup jalankan migrasi manual:
     ```bash
     php artisan migrate
     ```
2. **Konfigurasi `.env` Berubah:**
   - Periksa apakah `DB_DATABASE`, `DB_USERNAME`, atau `DB_PASSWORD` di file `.env` Anda sudah sesuai dengan database MySQL lokal Anda.

---

## 3. Standarisasi Penulisan Migration Tim (Mencegah Bentrok Skema)

Agar tidak terjadi bentrok skema (*migration conflict*) antar anggota tim:

> [!IMPORTANT]
> ### 3 Aturan Wajib Database Tim:
> 1. **JANGAN PERNAH MENGUBAH / MENGEDIT FILE MIGRATION LAMA** yang sudah di-push dan di-merge ke branch `main` atau `dev`.
> 2. **WAJIB MEMBUAT MIGRATION BARU** untuk setiap perubahan tabel, penambahan kolom, atau perubahan tipe data:
>    ```bash
>    php artisan make:migration add_nama_kolom_to_nama_tabel_table
>    ```
> 3. **GUNAKAN DEFENSIVE CHECK** (`Schema::hasColumn` / `Schema::hasTable`) pada migrasi alter:
>    ```php
>    public function up(): void
>    {
>        Schema::table('pengajuan_rab', function (Blueprint $table) {
>            if (! Schema::hasColumn('pengajuan_rab', 'kategori_anggaran')) {
>                $table->string('kategori_anggaran')->nullable()->after('judul_pengajuan');
>            }
>        });
>    }
>    ```
>    Teknik ini mencegah kegagalan migrasi jika ada anggota tim yang sudah sempat mengeksekusi sebagian alter di database lokalnya.

---

## 4. Reset Database Lokal ke Kondisi Bersih (Fresh Start)

Jika sewaktu-waktu data lokal Anda berantakan dan Anda ingin mereset database ke kondisi awal yang bersih:

1. **Jalankan Fresh Migration & Master Data Seeding:**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *Perintah ini akan membuat ulang semua tabel dan mengisi master unit kerja sekolah serta akun default.*

2. **(Opsional) Injeksi Data Pengajuan Dummy untuk Uji Coba:**
   Jika Anda membutuhkan contoh data pengajuan RAB siap pakai (`RAB-2026-001` s/d `RAB-2026-004` dengan rincian barang, dokumen mock, dan status approval bertingkat):
   ```bash
   php artisan db:seed --class=DummyDataSeeder
   ```

---

## 5. Daftar Akun Pengujian Default

Semua akun default memiliki password yang sama: **`password`**

| Role / Jabatan | Nama Pengguna | Email Login | Akses & Wewenang |
| :--- | :--- | :--- | :--- |
| **Admin IT / Ka. TU** | Drs. Arif Rachman | `arif@sirab.local` | Master Unit Kerja, Manajemen Akun, Setting Sistem |
| **Bendahara BOS (Finance)** | Akun Finance | `finance@sirab.local` | Review Verifikasi Tahap 1, Pagu Anggaran, Validasi SPJ & Pencairan Dana |
| **Kepala Sekolah (Pimpinan)** | Akun Pimpinan | `pimpinan@sirab.local` | Review Persetujuan Akhir (Approval Tahap 2), Monitoring Anggaran BOS |
| **Staf Kurikulum** | Sari Dewi | `sari@sirab.local` | Pengajuan RAB Operasional Kurikulum & Buku Pembelajaran |
| **Staf Sarpras** | Budi Santoso | `budi@sirab.local` | Pengajuan RAB Pemeliharaan Bangunan & Alat Sekolah |
| **Staf Kesiswaan** | Dina Marlina | `dina@sirab.local` | Pengajuan RAB Kegiatan Lomba Siswa & Ekstrakurikuler |

---

## 6. Verifikasi & Pemasangan Ulang Hook

Jika Git Hook belum aktif di komputer Anda (misal baru clone atau pindah folder), cukup jalankan salah satu perintah berikut:

```bash
# Opsi 1: Pasang hooks saja
php artisan dev:setup-hooks

# Opsi 2: Setup lengkap otomatis (env, key, hooks, migrasi, dan seed)
php artisan dev:setup
```

Sistem akan memastikan `git config core.hooksPath .githooks` terpasang dan siap bekerja secara otomatis!
