# 📑 Cara Kerja Fitur Cetak / Export PDF di Project SIRAB

---

### 1. File Dependensi: [`composer.json`](composer.json)
* **Peran:** "Menyediakan mesin pencetak PDF"
* **Kodenya:**
  ```json
  "barryvdh/laravel-dompdf": "^3.1"
  ```
* **Penjelasan Mudah:**  
  Laravel aslinya tidak bisa membuat PDF sendiri. Baris ini memberitahu Laravel untuk memasang library DomPDF, yaitu "mesin" yang bertugas menyulap kode HTML & CSS menjadi lembaran dokumen PDF.

---

### 2. File Pemicu (Frontend): [`resources/views/finance/rekapitulasi/index.blade.php`](resources/views/finance/rekapitulasi/index.blade.php)
* **Peran:** "Tombol merah 'Cetak PDF' yang diklik oleh user"
* **Kodenya:**
  ```html
  <a href="{{ route('finance.rekapitulasi.pdf', request()->query()) }}" target="_blank">
      <i class="fa-solid fa-file-pdf"></i> Cetak PDF
  </a>
  ```
* **Penjelasan Mudah:**  
  Ketika tombol merah ini diklik, dia tidak hanya memanggil rute cetak, tetapi juga menyertakan `request()->query()`. Artinya: filter tanggal, status, atau kategori yang sedang dipilih user di layar akan ikut dibawa agar data yang tercetak di PDF persis sama dengan yang ada di layar.

---

### 3. File Jalur Akses: [`routes/web.php`](routes/web.php)
* **Peran:** "Satpam penjaga jalur URL cetak"
* **Kodenya:**
  ```php
  Route::middleware(['auth', 'role:finance'])->prefix('finance')->name('finance.')->group(function () {
      Route::get('/rekapitulasi/pdf', [RekapitulasiController::class, 'exportPdf'])->name('rekapitulasi.pdf');
  });
  ```
* **Penjelasan Mudah:**  
  Menghubungkan link dari tombol tadi ke fungsi Controller. Jalur ini dikunci: orang asing tidak bisa asal tembak link ini karena dijaga oleh `auth` (harus login) dan `role:finance` (khusus bendahara).

---

### 4. File Otak Pemrosesan (Backend): [`app/Http/Controllers/RekapitulasiController.php`](app/Http/Controllers/RekapitulasiController.php)
* **Peran:** "Koki yang mengambil data dari database lalu memanggil mesin cetak"
* **Kodenya (Method `exportPdf`):**
  ```php
  use Barryvdh\DomPDF\Facade\Pdf;

  public function exportPdf(Request $request)
  {
      // 1. Ambil data dari database sesuai filter pencarian
      $query = PengajuanRab::with(['pengguna', 'divisi'])
          ->when($request->start_date, fn($q, $date) => $q->whereDate('tanggal_pengajuan', '>=', $date))
          ->when($request->status, fn($q, $status) => $q->where('status', $status));

      $rekapList = $query->latest('tanggal_pengajuan')->get();

      // 2. Masukkan data ke cetakan Blade & atur ukuran kertas A4 Portrait
      $pdf = Pdf::loadView('finance.rekapitulasi.pdf', compact('rekapList'))
          ->setPaper('A4', 'portrait');

      // 3. Tampilkan PDF langsung di tab baru browser
      return $pdf->stream('Laporan_Rekapitulasi_SIRAB.pdf');
  }
  ```
* **Penjelasan Mudah:**  
  1. Controller membaca filter tanggal/status dari URL.
  2. Mengambil data RAB dari database MySQL.
  3. Mengoper data tersebut ke template cetak (`pdf.blade.php`), menyetel ukuran kertas A4 Portrait, lalu mengirimkannya ke browser menggunakan `stream()` sehingga otomatis terbuka di tab baru.

---

### 5. File Desain Kertas Cetak: [`resources/views/finance/rekapitulasi/pdf.blade.php`](resources/views/finance/rekapitulasi/pdf.blade.php)
* **Peran:** "Kertas cetak fisik digital (Kop Surat, Logo, Tabel, & Total)"
* **Bagian Penting di Dalamnya:**
  * **Konversi Logo ke Base64 (Line 20–33):**
    ```php
    // Mengubah file logo jadi string base64 agar DomPDF tidak gagal memuat gambar
    $logoUrl = 'data:image/' . $type . ';base64,' . base64_encode($data);
    ```
  * **Kop Surat Sekolah:** Memuat nama "SD NEGERI SIDOKARE 3", alamat, nomor telepon, dan garis pembatas hitam tebal.
  * **Tabel Data RAB:** Melakukan perulangan `@forelse($rekapList as $item)` untuk menampilkan No, No RAB, Pemohon, Kategori, Nominal Uang (Rp), dan Status.
  * **Kalkulasi Total Keseluruhan:** Menjumlahkan seluruh nominal anggaran di baris paling bawah.

---

## 💡 Rangkuman 1 Detik untuk Presentasi ke Dosen:

> *"Pak/Bu, alurnya sangat simpel: **Tombol Cetak** di view membawa filter $\rightarrow$ diterima oleh **Controller** $\rightarrow$ Controller mengambil datanya dari database $\rightarrow$ datanya dimasukkan ke template **`pdf.blade.php`** $\rightarrow$ diubah jadi berkas PDF oleh package **`laravel-dompdf`** $\rightarrow$ langsung ditampilkan di layar tab baru browser via perintah **`$pdf->stream()`**."*
