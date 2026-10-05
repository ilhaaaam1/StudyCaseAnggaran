# Panduan Bedah Kode SIRAB: Arsitektur MVC (Pertemuan 8)

Dokumen ini disusun untuk membantu Anda membedah kodingan aplikasi **SIRAB** secara profesional di hadapan dosen, dengan fokus pada bagaimana aplikasi ini mengimplementasikan pola arsitektur **MVC (Model-View-Controller)** sesuai dengan materi presentasi Pertemuan 8.

---

## 1. Konsep Dasar MVC di SIRAB
Sesuai slide presentasi, MVC adalah pola arsitektur yang memisahkan aplikasi web menjadi tiga bagian utama agar kode lebih rapi, mudah di-*maintenance*, dan mudah di-*develop*. 

Jika dosen bertanya: *"Bagaimana alur MVC terjadi saat Staf membuka halaman Tambah RAB di aplikasi kamu?"*
**Jawaban/Alur yang bisa didemonstrasikan:**
1. **Request**: Staf mengklik tombol "Buat Pengajuan". Laravel meneruskan request URL `/staff/pengajuan/create` ke **Controller**.
2. **Controller (`StaffRabController@create`)**: Mengambil peran sebagai "Otak". Controller akan memanggil **Model** `KategoriAnggaran` dan `Divisi` untuk meminta data sisa pagu anggaran.
3. **Model (`KategoriAnggaran.php`)**: Model mengambil data dari **Database** dan mengembalikannya ke Controller.
4. **View (`staff/create.blade.php`)**: Controller membungkus data tersebut dan melemparnya ke View. View merender kode HTML + data dinamis tersebut dan menampilkannya di layar Staf.

---

## 2. Bedah Kodingan: CONTROLLER
Sesuai presentasi, **Tugas Controller** adalah: Mengolah request, Menentukan data, Memanggil Model, dan Mengirim data ke View.

Di dalam proyek Anda, salah satu Controller yang paling sibuk adalah `StaffRabController.php`. Mari kita bedah salah satu methodnya:

```php
// Terletak di app/Http/Controllers/StaffRabController.php
public function create(): View|RedirectResponse
{
    // 1. Mengolah Request (Mengambil data user yang sedang login)
    $user = Auth::user();

    // 2. Memanggil Model untuk mengambil data dari Database
    $divisiList = Divisi::orderBy('id_divisi')->get();
    $kategoriListDb = KategoriAnggaran::all();

    // 3. Menentukan Data (Memproses perhitungan sisa pagu anggaran)
    $sisaPaguList = [];
    foreach ($kategoriListDb as $kat) {
        $paguTerpakai = PengajuanRab::where('kategori_anggaran', $kat->nama_kategori)->sum('estimasi_total');
        $sisaPaguList[$kat->nama_kategori] = max(0, $kat->pagu_anggaran - $paguTerpakai);
    }

    // 4. Mengirim data hasil olahan ke View (Menggunakan compact)
    return view('staff.create', compact('divisiList', 'user', 'kategoriListDb', 'sisaPaguList'));
}
```
**Poin Presentasi:** Kodingan di atas membuktikan bahwa Controller di SIRAB benar-benar menjalankan 4 fungsi utamanya persis seperti yang diteorikan pada slide presentasi!

---

## 3. Bedah Kodingan: MODEL
Di slide disebutkan bahwa tugas Model adalah: Mengambil/Menyimpan data dari Database, dan **Mengatur Hubungan/Relasi antar Tabel**.

Mari kita bedah model `Pengguna.php` (berada di `app/Models/Pengguna.php`):

```php
class Pengguna extends Authenticatable
{
    // Konfigurasi Model (Sesuai slide: $table, $primaryKey, $fillable)
    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    
    // Perlindungan Mass-Assignment
    protected $fillable = [
        'id_divisi', 'nama_lengkap', 'email', 'password', 'role', 'jabatan'
    ];

    // MENGATUR RELASI ANTAR TABEL (Tugas Utama Model)
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'id_divisi', 'id_divisi');
    }

    public function pengajuanRab(): HasMany
    {
        return $this->hasMany(PengajuanRab::class, 'id_pengguna', 'id_pengguna');
    }
}
```
**Poin Presentasi:** 
* *"Model Pengguna kami mendefinisikan tabel dan primary key secara eksplisit karena kami menggunakan penamaan bahasa Indonesia (`pengguna` dan `id_pengguna`), bukan bawaan standar bahasa Inggris Laravel (`users` dan `id`)."*
* *"Di model ini juga kami mengatur relasi antar tabel (seperti metode `divisi()` dan `pengajuanRab()`), yang membuktikan bahwa Model bukan sekadar penyambung ke database, tetapi juga memetakan ERD (Entity Relationship Diagram) ke dalam bentuk kode OOP (Object Oriented Programming)."*

---

## 4. Bedah Kodingan: MIGRATION (Database)
Slide terakhir menyinggung **Migrasi Database**. Migrasi adalah cara membuat tabel database menggunakan kode PHP tanpa harus mengklik manual di phpMyAdmin.

Di SIRAB, Anda memiliki banyak file migrasi di folder `database/migrations/`. 

**Contoh Argumen Presentasi tentang Migration:**
> *"Pembuatan struktur tabel SIRAB sepenuhnya dilakukan menggunakan Migration. Contohnya saat membuat tabel Pengguna, kami menjalankan perintah `php artisan make:migration create_pengguna_table`. Di dalam file migrasi tersebut, kami mendefinisikan kolom string, integer, dan foreign key. Ketika kami mengetik `php artisan migrate`, Laravel otomatis menerjemahkan kode PHP tersebut menjadi query CREATE TABLE di MySQL. Ini memudahkan tim kami jika harus berpindah laptop, karena struktur database bisa di-build ulang hanya dalam hitungan detik tanpa perlu import/export file .sql manual."*

---

## 🎯 Kesimpulan Untuk Sidang/Presentasi
Proyek SIRAB Anda adalah representasi sempurna dari Arsitektur MVC. Tidak ada query SQL mentah yang ditulis di dalam file HTML (View), tidak ada logika bisnis rumit yang diletakkan di rute, dan setiap tabel database diwakili oleh Model yang terstruktur rapi. Ini memastikan bahwa jika kelak aplikasi perlu diperbesar (di-*scale*), *maintenance* akan menjadi jauh lebih mudah.
