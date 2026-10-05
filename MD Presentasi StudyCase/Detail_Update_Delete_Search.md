# Panduan Bedah Kode SIRAB: Detail, Update, Delete, & Search Data (Pertemuan 11)

Dokumen ini disusun untuk membantu Anda membedah kodingan aplikasi **SIRAB** secara profesional di hadapan dosen, dengan mencocokkan logika kode Anda dengan materi presentasi Pertemuan 11.

---

## 1. Siklus Data (Konsep Dasar)
Sesuai slide presentasi, aplikasi berbasis Laravel selalu melewati 3 gerbang utama:
**Route -> Controller -> View**

Pada aplikasi SIRAB, jika Anda ingin membedah fitur **Manajemen Pengguna (Staff)**, alurnya adalah:
1. **Route**: `routes/web.php` menerjemahkan URL.
2. **Controller**: `AdminUserController.php` mengeksekusi logika dan *Query Database* (Eloquent).
3. **View**: File-file di dalam `resources/views/admin/users/` (seperti `index.blade.php`, `edit.blade.php`) menampilkan antarmuka.

---

## 2. Alur Update Data (Edit & Simpan)
Sesuai presentasi, proses *Update* membutuhkan **DUA** route: satu untuk menampilkan form, dan satu lagi untuk menyimpan perubahan (PUT).

Di kodingan Anda:
```php
// 1. Tampilkan form edit (Method: GET)
Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('users.edit');

// 2. Simpan ke database (Method: PUT)
Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('users.update');
```

### Method Spoofing pada Form Edit
Dosen sering bertanya: *"HTML form cuma mendukung GET dan POST, lalu bagaimana cara kamu mengirim request PUT?"*
**Jawaban untuk Dosen:**
> *"Di dalam file `resources/views/admin/users/edit.blade.php`, saya menggunakan fitur Method Spoofing milik Laravel yaitu penanda `@method('PUT')` di bawah `@csrf`. Ini akan memanipulasi form HTML biasa agar dikenali oleh Laravel sebagai aksi Update (PUT)."*

### Eksekusi Update di Controller
Di dalam `AdminUserController@update`, Anda sudah melakukan:
1. **Validasi Input**: Memastikan email tidak kembar (`'unique:pengguna,email...'`).
2. **Update Record**: Memanggil perintah `$user->update(...)` untuk menyimpan ke database.
3. **Notifikasi (Flash Message)**: Diakhiri dengan `return redirect()->...->with('success', 'Data staff berhasil diperbarui')`.

---

## 3. Keamanan Menghapus Data (Delete)
Pada slide ditekankan dengan status **"Bad Practice"** jika menghapus data langsung lewat link `<a>` (GET).
Di kodingan Anda (`admin/users/index.blade.php`), Anda sudah mengimplementasikan **"Good Practice"** dengan sangat sempurna:

```html
<form action="{{ route('admin.users.destroy', $staff->id_pengguna) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun staff ini?');">
    @csrf
    @method('DELETE')
    <button type="submit">Hapus</button>
</form>
```
Ini sangat aman dari ancaman *Cross-Site Request Forgery (CSRF)* maupun klik tidak sengaja.

### Validasi Hapus di Controller (Proteksi Relasi)
Poin terpenting dalam menghapus adalah mengecek apakah data tersebut sedang dipakai di tempat lain. Pada `AdminUserController@destroy`, kodingan Anda memiliki proteksi cerdas:
```php
$hasSubmissions = PengajuanRab::where('id_pengguna', $id)->exists();

if ($hasSubmissions) {
    return redirect()->route('admin.users.index')
        ->with('error', "Akun tidak dapat dihapus karena sudah memiliki riwayat pengajuan RAB.");
}
```
**Argumen Presentasi:** *"Sesuai dengan panduan keamanan penghapusan data, saya tidak sembarangan menghapus User. Jika User tersebut sudah pernah membuat Pengajuan RAB, sistem akan memblokir proses *delete* untuk mencegah error / data relasi yang putus (Orphan Data)."*

---

## 4. Pencarian Dinamis (Search) & Kondisi Kosong
Di slide diajarkan bahwa pencarian tidak butuh rute baru, cukup memodifikasi *method* `index()` yang sudah ada. 

**Implementasi di `AdminUserController@index` Anda:**
```php
$search = $request->input('q'); // Menangkap keyword dari URL (?q=nama)

if ($search) {
    $query->where(function ($q) use ($search): void {
        $q->where('nama_lengkap', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%");
    });
}

// Menyisipkan URL pencarian ke tombol paginasi (Next/Prev)
$staffList = $query->paginate(10)->withQueryString();
```

### Menangani Tabel Kosong (@forelse)
Ketika user mencari nama "Zzzzz" dan data tidak ada, tabel tidak boleh *error* atau tampil jelek. Pada kodingan View `admin/users/index.blade.php` Anda, Anda sudah menghindari `@foreach` dan lebih memilih menggunakan `@forelse`:

```html
@forelse($staffList as $index => $staff)
    <!-- Cetak baris tabel -->
@empty
    <tr>
        <td colspan="5">Belum ada data staff pemohon yang terdaftar.</td>
    </tr>
@endforelse
```

---

## 🎯 Kesimpulan Untuk Sidang/Presentasi
Semua teori dari Pertemuan 11 (Update, Delete yang aman, Validasi Relasi, Pencarian Dinamis, hingga *Method Spoofing*) **sudah 100% diimplementasikan dengan standar tinggi** di dalam proyek SIRAB ini. Anda bisa dengan percaya diri membuka kodingan `AdminUserController` dan `index.blade.php` sebagai alat peraga saat menjelaskan konsep-konsep tersebut!
