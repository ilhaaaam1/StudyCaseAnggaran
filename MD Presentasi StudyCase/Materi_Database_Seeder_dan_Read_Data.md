# 📚 Materi Presentasi: Database Seeder & Read Data
**Project:** SIRAB / Study Case Anggaran

Dokumen ini dirancang untuk membantu Anda membedah kode (code review) atau mempresentasikan materi terkait **Database Seeder** dan proses **Read Data (Menampilkan Data)** dengan *Eloquent ORM* maupun *Query Builder* di project Laravel Anda.

---

## 1. Membedah Kode Database Seeder
**Konsep:** *Seeder* adalah fitur bawaan Laravel yang bertugas untuk mengisi tabel database dengan data awal atau *dummy data* secara otomatis. Menggunakan seeder jauh lebih cepat dan profesional daripada harus memasukkan data satu per satu secara manual di phpMyAdmin.

### A. File Seeder (Contoh: `BarangSeeder.php` atau `UserSeeder.php`)
Saat membedah, buka folder `database/seeders/` dan jelaskan bagian method `run()`:
```php
public function run(): void
{
    // Contoh memasukkan 1 baris data menggunakan Eloquent
    Barang::create([
        'kategori_id' => 4,
        'nama'        => 'Laptop HP Pavilion',
        'jumlah'      => 10,
        'kondisi'     => 'baik'
    ]);
    
    // (Bisa dilanjut menggunakan perulangan atau Factory untuk membuat 20+ data)
}
```
*Penjelasan:* Fungsi `run()` adalah fungsi utama. Semua perintah logika insert database diletakkan di dalam block fungsi ini.

### B. File Induk (`DatabaseSeeder.php`)
File ini adalah *master* yang mengatur seeder mana saja yang akan dieksekusi:
```php
public function run(): void
{
    $this->call([
        UserSeeder::class,      // Dipanggil pertama
        KategoriSeeder::class,  // Dipanggil kedua
        BarangSeeder::class,    // Dipanggil ketiga (Tabel yang punya Foreign Key harus dipanggil belakangan)
    ]);
}
```
*Catatan Presentasi:* Tekankan pada audiens/reviewer bahwa **urutan di dalam array `$this->call` sangat penting!** Kita tidak bisa meng-insert `Barang` sebelum tabel `Kategori` diisi, karena `Barang` membutuhkan `kategori_id`.

### C. Perintah Eksekusi
Tunjukkan ke audiens terminal Anda dan jelaskan command untuk menjalankan seeder:
```bash
php artisan db:seed
```

---

## 2. Membedah Konsep Menampilkan Data (Read Data)
Jika ditanya *"Bagaimana cara aplikasi ini menarik data dari database?"*, jelaskan bahwa ada 2 cara yang umum digunakan, dan sebutkan perbedaannya.

| Aspek Pembanding | Eloquent ORM | Query Builder (SQL Builder) |
|---|---|---|
| **Pendekatan** | Memakai Model OOP (`Barang::...`) | Memakai Query Manual (`DB::table(...)`) |
| **Pengelolaan Relasi** | Sangat Otomatis & mudah | Manual (harus menulis fungsi `join`) |
| **Performa Eksekusi** | Sedikit lebih lambat | Lebih cepat (karena mengeksekusi *raw query*) |
| **Kelebihan** | Kode bersih, sangat cocok untuk proses CRUD (Create, Read, Update, Delete). | Fleksibel, sangat cocok untuk query laporan/statistik yang rumit. |

---

## 3. Membedah Kode: Mengambil Data dengan Eloquent ORM
Buka Controller Anda (misalnya `BarangController.php`) di bagian method `index()`.

```php
// Wajib import model di bagian atas
use App\Models\Barang; 

public function index(Request $request)
{
    // Menggunakan Eloquent dengan Eager Loading (memanggil tabel relasi kategori)
    $query = Barang::with('kategori');

    // ... (kode pencarian) ...

    // Membagi data (Pagination) sebanyak 10 per halaman 
    $barangs = $query->paginate(10)->withQueryString();

    // Melempar variabel $barangs ke file View Blade
    return view('pages.barang.index', compact('barangs', 'kategoris'));
}
```
*Penjelasan Presentasi:* 
- `Barang::with('kategori')` adalah trik untuk mengatasi masalah performa yang dikenal dengan *N+1 Query Problem*. 
- `paginate(10)` secara otomatis memotong ribuan data menjadi 10 baris per halaman, lengkap dengan tombol next/prev di halaman frontend.
- `withQueryString()` berguna agar saat user pindah halaman (page 2, page 3), keyword pencarian yang diketik tidak hilang (reset).

---

## 4. Membedah Kode: Mengambil Data dengan Query Builder
Terkadang, aplikasi membutuhkan Query Builder untuk dashboard laporan.

```php
use Illuminate\Support\Facades\DB; // Wajib di-import

// Contoh kasus: Menghitung total barang per masing-masing kategori
$kategoris = DB::table('kategoris')
               ->join('barangs', 'barangs.kategori_id', '=', 'kategoris.id')
               ->select('kategoris.*', DB::raw('COUNT(barangs.id) as total_barang'))
               ->groupBy('kategoris.id')
               ->get();
```
*Penjelasan Presentasi:*
- Di sini kita tidak menggunakan kata kunci Model (`Kategori::`), melainkan langsung tembak ke tabel (`DB::table('kategoris')`).
- Kita harus secara imperatif (manual) menyambungkan tabel menggunakan sintaks `->join()`.
- Serta menggunakan perintah `DB::raw()` agar Laravel mengizinkan penulisan SQL asli (contoh fungsi SQL: `COUNT`).

---

## 5. Membedah Logika Filter & Pencarian
Buka bagian di mana aplikasi mem-filter data yang tampil:

```php
// Jika form pencarian tidak kosong, maka lakukan filter 'nama'
if ($request->filled('search')) {
    $query->where('nama', 'like', "%{$request->search}%");
}

// Jika dropdown filter kategori dipilih
if ($request->filled('kategori')) {
    $query->where('kategori_id', $request->kategori);
}
```
*Penjelasan:* Fungsi `$request->filled()` digunakan untuk mengecek "Apakah ada variabel bernama `search` yang dikirim dari URL?". Jika ada nilainya, maka query database akan disisipkan perintah `where`.

---

## 6. Tips Presentasi & Debugging
- **Menggunakan `dd()`:** Jika di tengah presentasi ada pertanyaan mengenai struktur isi dari sebuah variabel, gunakan *Dump and Die* (`dd()`).
  ```php
  dd($barangs); 
  ```
  Fungsi ini ibarat *X-Ray*; dia akan mematikan proses web dan menampilkan struktur lengkap array/object dari variabel ke layar browser. Berguna untuk menunjukkan data murni yang ditarik dari database sebelum masuk ke dalam Blade HTML.

## 7. Ceklist Demo / Praktik
Saat mendemonstrasikan aplikasi:
- [ ] Buka tabel barang yang masih kosong.
- [ ] Buka terminal, ketik `php artisan db:seed`, lalu refresh halaman web. (Buktikan data berhasil terserap otomatis).
- [ ] Sorot bagian bawah tabel untuk menunjukkan bahwa **Fitur Pagination** bekerja (berubah otomatis 10 baris per halaman).
- [ ] Praktikkan fungsi fitur **Pencarian (Search)**, dan jelaskan bahwa ini terjadi karena kode *if filled search* pada langkah 5.
