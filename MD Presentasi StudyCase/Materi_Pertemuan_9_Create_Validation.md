# 📚 Materi Pertemuan 9: Create & Validation Data
**Project:** SIRAB / Study Case Anggaran

Dokumen ini berisi rangkuman, konsep, dan panduan untuk membedah kode (code review) terkait fitur **Create (Tambah Data)** dan **Validation (Validasi Input)** pada project Laravel. Anda dapat menggunakan panduan ini untuk memahami dan menjelaskan alur codingan saat mempresentasikan atau membedah project.

---

## 1. Konsep Dasar Create Data
**Create Data** adalah proses menerima inputan data baru dari pengguna aplikasi (melalui form) untuk disimpan ke dalam database.
- **Perbedaan dengan Seeder:** Seeder digunakan oleh *developer* untuk mengisi data *dummy* secara otomatis melalui script. Sedangkan *Create Data* adalah interaksi dinamis yang dilakukan langsung dari antarmuka (UI) aplikasi oleh pengguna.
- **HTTP Method:** Selalu menggunakan **POST** untuk mengirim dan menyimpan data baru, karena ini lebih aman dan sesuai dengan standar web (berbeda dengan GET yang digunakan untuk mengambil data).
- **Alur (Workflow):** `Form Input` ➔ `Menerima Request (POST)` ➔ `Validasi Data` ➔ `Simpan ke Database` ➔ `Redirect dengan Feedback (Notifikasi)`.

---

## 2. Alur Persiapan Fitur
Untuk membangun atau membedah fitur tambah data di Laravel, cari 3 komponen utama (Konsep MVC) ini dalam folder project Anda:
1. **View (Blade):** File form HTML (misal: `create.blade.php`).
2. **Route:** Jalur yang menghubungkan URL dengan Controller (berada di `routes/web.php`).
3. **Controller:** Logika utama aplikasi (misal: `BarangController.php` atau `KategoriController.php`).

---

## 3. Membedah Kode Form Blade (Frontend)
Ketika Anda disuruh menjelaskan file form UI, soroti dan jelaskan baris-baris kode penting berikut:

```html
<form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
    <!-- Token Keamanan Wajib -->
    @csrf
    
    <!-- Contoh Input -->
    <div class="form-group">
        <label>Nama Barang</label>
        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control">
        
        <!-- Menampilkan Pesan Error Spesifik -->
        @error('nama')
            <p style="color:red;">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
```

**Penjelasan yang harus Anda sampaikan:**
- **`method="POST"`**: Digunakan karena kita mengirim data ke server. (Gunakan `enctype="multipart/form-data"` jika form mengandung upload gambar/file).
- **`@csrf` (Cross-Site Request Forgery):** Ini adalah *token* keamanan. Jika kode ini lupa ditulis, form tidak akan bisa disimpan dan Laravel akan menampilkan error *"419 Page Expired"*.
- **`name="nama"`**: Sangat penting! Atribut ini mendefinisikan *key* (variabel) yang akan ditangkap oleh Backend (Controller) sebagai `$request->nama`.
- **`value="{{ old('nama') }}"`**: Fungsi agar form tidak mereset inputan (menjadi kosong) saat pengguna salah mengisi data dan diredirect kembali oleh validasi.
- **`@error('nama') ... @enderror`**: Menangkap pesan validasi otomatis dari Laravel (misal: "The nama field is required").

---

## 4. Membedah Kode Route & Controller (Backend)

### A. Routing (`routes/web.php`)
Pastikan ada routing dengan method POST untuk menyimpan, contoh:
```php
// Jika menggunakan resource
Route::resource('kategori', KategoriController::class);

// Jika route manual
Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
```

### B. Controller (Method `store`)
Di Controller, proses penangkapan data dan penyimpanan terjadi. Jelaskan bagian ini secara detail:
```php
public function store(Request $request)
{
    // 1. Proses Validasi Laravel
    $request->validate([
        'kategori_id' => 'required|exists:kategoris,id',
        'nama'        => 'required|string|max:150',
        'jumlah'      => 'required|integer|min:0',
        'kondisi'     => 'required|in:baik,rusak,hilang',
        'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    // 2. Mengambil Semua Data yang Lolos Validasi (kecuali gambar, karena perlu diproses khusus)
    $data = $request->except('gambar');

    // (Opsional: Disini tempat proses Upload File jika ada)
    // ...

    // 3. Query Simpan ke Database
    Barang::create($data); // atau Kategori::create()

    // 4. Pengalihan Halaman (Redirect) beserta Notifikasi Sukses
    return redirect()->route('barang.index')
                     ->with('success', 'Barang berhasil ditambahkan');
}
```

**Penjelasan Validasi (Rules):**
Saat membedah, jelaskan arti *rules* yang dibuat di atas:
- `required`: Form ini wajib diisi, tidak boleh dikosongkan.
- `max:150`: Karakter teks dibatasi maksimal 150 huruf (mencegah error di database).
- `exists:kategoris,id`: Validasi relasi, memastikan nilai yang dipilih (misal dropdown kategori) benar-benar ada di tabel `kategoris`.
- `in:baik,rusak,hilang`: Validasi agar inputan persis dengan salah satu dari opsi tersebut (biasanya untuk input tipe enum).
- `nullable`: Boleh dikosongkan (tidak wajib, misalnya form deskripsi).
- `image|mimes:jpeg,png...`: Mencegah user mengupload file selain format gambar.

---

## 5. Custom Validasi & Notifikasi (Logika Khusus)
Kadangkala aplikasi butuh validasi di luar tipe data, misal "stok tidak mencukupi" ketika melakukan transaksi. Anda bisa membuat validasi manual menggunakan kondisi `if` sebelum operasi simpan.

```php
// Contoh validasi stok
if ($barang->jumlah < $request->jumlah_diminta) {
    // Fungsi back() untuk mengembalikan user ke form sebelumnya
    // Fungsi with() untuk membawa pesan peringatan
    return back()->with('error', 'Stok tidak mencukupi. Tersedia: ' . $barang->jumlah);
}
```

Untuk menampilkan pesan `success` (nomor 4) atau `error` (seperti di atas) di halaman tujuan, periksa file `index.blade.php` apakah sudah memiliki penangkap *session*:
```html
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
```

---

## 6. Ceklist Implementasi pada Project
Jika Anda disuruh mempraktikkan atau mereview, lakukan langkah berikut:
1. Buka halaman **Tambah Data** pada aplikasi.
2. Coba **tekan tombol Simpan tanpa mengisi apa-apa**. Pastikan sistem memblokirnya dan mengeluarkan tulisan merah (*The field is required*). (Ini membuktikan Validasi berfungsi).
3. Isi data dengan benar, upload file jika ada, dan simpan.
4. Cek pada antarmuka aplikasi apakah **Notifikasi berhasil/hijau** muncul.
5. Terakhir, buka `phpMyAdmin` atau *tools database* Anda, lalu pastikan **baris data baru benar-benar muncul di tabel** terkait.
