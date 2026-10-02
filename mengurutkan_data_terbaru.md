# Cara Menampilkan Data Terbaru di Urutan Paling Atas

Dokumentasi ini menjelaskan cara mengubah urutan daftar data (misalnya Divisi/Unit Kerja) agar data yang baru saja ditambahkan (baik melalui Seeder maupun input aplikasi) muncul paling atas, menggantikan urutan sebelumnya yang menyesuaikan abjad.

### 💡 Penjelasan Code Logic-nya:

Perubahan ini dilakukan pada file **`app/Http/Controllers/AdminItController.php`** di dalam fungsi `divisiIndex()`.

**Kode Sebelumnya:**
```php
$divisiList = Divisi::withCount('pengguna')->orderBy('nama_divisi')->get();
```
* **`orderBy('nama_divisi')`**: Secara *default*, fungsi ini mengurutkan data dari **A sampai Z** (Ascending). Itulah mengapa huruf "k" (misal: kebersihan, kesehatan, dst) berada di atas, dan "S" (Sarana) berada di bawah.

**Kode Baru (Sudah Diubah):**
```php
$divisiList = Divisi::withCount('pengguna')->latest('id_divisi')->get();
```
* **`latest('id_divisi')`**: Ini adalah *helper* dari Laravel yang sama fungsinya dengan penulisan `orderBy('id_divisi', 'desc')`. 
* **Logikanya**: Sistem sekarang akan mengurutkan baris dari nilai *Primary Key* (`id_divisi`) yang **terbesar ke yang terkecil (Descending)**. Karena setiap kali Anda menambahkan data baru maka ID-nya akan terus bertambah (misalnya ID #10 masuk setelah ID #9), maka dengan cara ini data dengan ID paling besar (terbaru) akan dipaksa untuk tampil di urutan paling atas halaman.
