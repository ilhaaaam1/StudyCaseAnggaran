# Panduan Implementasi CRUD & Pencarian (Detail, Update, Delete, & Search Data)
**Studi Kasus:** Sistem Informasi RAB (SIRAB)
**Referensi:** Slide Pertemuan ke-11

Dokumen ini memetakan teori dari materi presentasi (Slide) secara langsung terhadap _source code_ yang berjalan pada proyek SIRAB tanpa mengubah kode aslinya. Dokumen ini sangat cocok digunakan sebagai acuan saat presentasi atau sidang.

---

## 1. Penerapan "Detail Data" (Referensi: Slide 2-7)

**Konsep Slide:** 
Menampilkan satu spesifik data tanpa proses *looping* (perulangan) dengan memanfaatkan rute dinamis (contoh: `/barang/1`).

**Penerapan di Proyek SIRAB:** 
Diterapkan pada fitur **Lihat Detail RAB** (Rute: `/finance/pengajuan/{id}`).

**A. Sisi Controller (`app/Http/Controllers/PimpinanController.php` & `FinanceController.php`):**
Di dalam *method* `show($id)`, aplikasi mengambil 1 spesifik data menggunakan fungsi `findOrFail($id)`. Agar performa lebih optimal, relasinya dimuat sekaligus (*Eager Loading* yang sejalan dengan teori `load()` pada slide):
```php
public function show(int $id): View
{
    // Mengambil 1 pengajuan RAB beserta relasi tabel secara efisien (Eager Loading)
    $pengajuan = PengajuanRab::with([
        'pengguna.divisi',
        'divisi',
        'rincianItem',
        'dokumenPendukung',
        'alurPersetujuan.reviewer',
    ])->findOrFail($id);

    return view('pimpinan.show', compact('pengajuan'));
}
```

**B. Sisi View (`resources/views/finance/show.blade.php`):**
Karena datanya hanya 1 (tunggal), *view* Blade tidak menggunakan direktif `@foreach`. Data langsung dipanggil menggunakan properti dari objek tersebut, persis seperti teori di slide:
```html
{{-- Contoh pemanggilan langsung tanpa looping --}}
<h2 class="font-bold text-xl">{{ $pengajuan->judul_pengajuan }}</h2>
<p class="text-sm">Nomor RAB: {{ $pengajuan->no_rab }}</p>
<p class="text-sm">Estimasi Total: Rp {{ number_format($pengajuan->estimasi_total, 0, ',', '.') }}</p>
```

---

## 2. Penerapan "Update Data" (Referensi: Slide 8-12)

**Konsep Slide:** 
Proses pembaruan membutuhkan rute `PUT`, simulasi formulir menggunakan direktif `@method('PUT')`, proses validasi data, dan penyimpanan menggunakan fungsi `update()`.

**Penerapan di Proyek SIRAB:** 
Diterapkan pada fitur **Master Kategori Anggaran** (Rute: `PUT /finance/kategori-pagu/{id}`).

**A. Sisi View (`resources/views/finance/kategori/index.blade.php`):**
Di dalam *modal* formulir untuk mengedit kategori, aplikasi menggunakan fitur *Method Spoofing*. Hal ini dilakukan agar formulir HTML (yang aslinya hanya mendukung metode GET dan POST) bisa terbaca oleh Laravel sebagai request PUT, yang merupakan syarat keamanan mutlak:
```html
<form action="{{ route('finance.kategori.update', $kategori->id) }}" method="POST">
    @csrf
    @method('PUT') 
    
    <label>Nama Kategori</label>
    <input type="text" name="nama_kategori" value="{{ $kategori->nama_kategori }}">
    
    <!-- Input fields lainnya... -->
</form>
```

**B. Sisi Controller (`app/Http/Controllers/KategoriAnggaranController.php`):**
Data yang dikirim akan ditangkap di *method* `update()`, divalidasi dengan aturan yang ketat, dan ditimpa menggunakan array kumpulan data yang sudah tervalidasi (`$validated`):
```php
public function update(Request $request, $id)
{
    $kategori = KategoriAnggaran::findOrFail($id);
    
    $validated = $request->validate([
        'nama_kategori' => 'required|string|max:255|unique:kategori_anggarans,nama_kategori,' . $kategori->id,
        'deskripsi' => 'nullable|string',
        'pagu_anggaran' => 'required|numeric|min:0',
    ]);
    
    // Menimpa data lama dengan data baru yang sudah tervalidasi
    $kategori->update($validated); 
    
    return redirect()->route('finance.kategori.index')
                     ->with('success', 'Kategori anggaran berhasil diperbarui.');
}
```

---

## 3. Penerapan "Delete Data" secara Aman (Referensi: Slide 13-17)

**Konsep Slide:** 
Ditegaskan bahwa *Bad Practice* adalah menghapus data menggunakan tag link `<a>`. *Good Practice* mewajibkan penghapusan data lewat tombol di dalam tag `<form>` dengan menggunakan `@method('DELETE')`, perlindungan `@csrf`, serta pesan peringatan/konfirmasi sebelum dihapus.

**Penerapan di Proyek SIRAB:** 
Diterapkan pada fungsi hapus di fitur **Master Kategori Anggaran**.

**A. Sisi View (`resources/views/finance/kategori/index.blade.php`):**
Tombol hapusnya sudah diproteksi secara standar menggunakan form dan konfirmasi Javascript `onsubmit`:
```html
<form action="{{ route('finance.kategori.destroy', $kategori->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Hapus</button>
</form>
```

**B. Sisi Controller (`app/Http/Controllers/KategoriAnggaranController.php`):**
Sesuai prosedur pada slide, proses penghapusannya memanggil perintah `delete()` secara eksplisit pada *object model* yang dituju:
```php
public function destroy($id)
{
    $kategori = KategoriAnggaran::findOrFail($id);
    
    // Eksekusi penghapusan dari database
    $kategori->delete();
    
    return redirect()->route('finance.kategori.index')
                     ->with('success', 'Kategori anggaran berhasil dihapus.');
}
```

---

## 4. Penerapan "Search Data & Kondisi Kosong" (Referensi: Slide 18-24)

**Konsep Slide:** 
Pencarian bersifat dinamis dan tidak membutuhkan URL baru. Cukup menangkap parameter *query string* (misal: `?search=Keyword`) lalu memodifikasi *query builder* menggunakan operasi `LIKE`. Di bagian antarmuka (*view*), sangat disarankan menggunakan tag `@forelse` agar jika hasil pencarian kosong, tabel tidak rusak dan dapat memunculkan pesan alternatif.

**Penerapan di Proyek SIRAB:** 
Diterapkan secara komprehensif pada halaman **Antrean Verifikasi Finance** dan **Rekapitulasi Laporan**.

**A. Sisi Controller (`app/Http/Controllers/FinanceController.php`):**
Aplikasi menggunakan *logical check*. Jika variabel pencarian (`$search`) terisi, maka modifikasi *query database* akan dieksekusi:
```php
public function antrean(Request $request): View
{
    $search = $request->input('q'); 
    
    $query = PengajuanRab::with(['pengguna', 'divisi', 'rincianItem'])
                ->where('status', StatusPengajuan::MENUNGGU_FINANCE);

    // Identik dengan logika if($request->filled('search')) di slide PDF
    if ($search) {
        $query->where(function ($q) use ($search): void {
            $q->where('no_rab', 'like', "%{$search}%")
              ->orWhere('judul_pengajuan', 'like', "%{$search}%")
              ->orWhereHas('pengguna', function ($sub) use ($search): void {
                  $sub->where('nama_lengkap', 'like', "%{$search}%");
              });
        });
    }

    $pengajuanList = $query->latest('tanggal_pengajuan')->paginate(10)->withQueryString();
    
    return view('finance.antrean_approval', compact('pengajuanList', 'search'));
}
```

**B. Sisi View (`resources/views/finance/rekapitulasi/index.blade.php`):**
Aplikasi mematuhi slide ke-22 dengan menggunakan struktur blok `@forelse` ... `@empty` untuk memunculkan *Empty State* (pesan informatif) saat *keyword* pencarian tidak menemukan kecocokan di dalam tabel:
```html
<tbody>
    @forelse($rekapList as $idx => $item)
        <tr>
            <td>{{ $idx + 1 }}</td>
            <td>{{ $item->no_rab }}</td>
            <td>{{ $item->judul_pengajuan }}</td>
            <!-- Data kolom lainnya -->
        </tr>
    @empty
        <tr>
            <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                Tidak ada data RAB yang sesuai dengan filter pencarian Anda.
            </td>
        </tr>
    @endforelse
</tbody>
```
