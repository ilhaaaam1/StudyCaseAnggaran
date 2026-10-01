# Panduan & Tutorial Styling Tombol Edit Menggunakan Tailwind CSS
**Studi Kasus:** Halaman Master Kategori & Pagu Anggaran (Role Finance)  
**File Target:** `resources/views/finance/kategori/index.blade.php`

---

## 1. Pendahuluan

Dokumen ini memandu langkah demi langkah cara melakukan styling pada tombol **"Edit"** di tabel data agar memiliki gaya (*visual design*) yang selaras dan konsisten dengan tombol **"Tambah Kategori"** pada halaman Master Kategori & Pagu Anggaran.

Tujuan utama panduan ini adalah:
1. Memahami fungsi masing-masing *utility class* Tailwind CSS yang membentuk tampilan tombol utama.
2. Menyesuaikan tombol aksi di dalam baris tabel (*table row action*) agar tetap proporsional dan tidak merusak tata letak tabel.
3. Memastikan fungsionalitas interaktif **Alpine.js** untuk memicu modal edit tetap berjalan normal.

---

## 2. Bedah Class Tailwind CSS Tombol "Tambah Kategori"

Tombol **"Tambah Kategori"** bertindak sebagai tombol aksi utama (*Call-to-Action / CTA*) pada header kartu.

### Kode Tombol Referensi:
```html
<button type="button" 
        @click="openModal = true; editMode = false; formAction = '{{ route('finance.kategori.store') }}'; formMethod = 'POST'; formKategori = ''; formDeskripsi = ''; formPagu = '';" 
        class="bg-[#2b337c] hover:bg-[#1e255e] text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm shrink-0 transition-colors">
  <i class="fa-solid fa-plus"></i> Tambah Kategori
</button>
```

### Rincian Utility Class yang Digunakan:

| Kategori | Class Tailwind | Penjelasan & Nilai CSS |
| :--- | :--- | :--- |
| **Warna Latar (*Background*)** | `bg-[#2b337c]` | Menggunakan arbitrary value warna hex `#2b337c` (biru navy utama tema SIRAB). |
| **Efek Sorot (*Hover State*)** | `hover:bg-[#1e255e]` | Mengubah warna background saat kursor diarahkan ke tombol menjadi navy yang lebih gelap (`#1e255e`). |
| **Warna Teks** | `text-white` | Mengatur warna teks dan ikon menjadi putih murni (`#ffffff`). |
| **Jarak Dalam (*Padding*)** | `px-5 py-2.5` | - `px-5`: Padding horizontal (kiri-kanan) 1.25rem (20px).<br>- `py-2.5`: Padding vertikal (atas-bawah) 0.625rem (10px). |
| **Sudut Melengkung (*Border Radius*)** | `rounded-xl` | Memberikan lengkungan sudut sebesar 0.75rem (12px). |
| **Tipografi** | `text-xs sm:text-sm font-semibold` | - `text-xs`: Ukuran font 12px pada layar ponsel.<br>- `sm:text-sm`: Ukuran font 14px pada layar desktop (>= 640px).<br>- `font-semibold`: Ketebalan font 600. |
| **Tata Letak (*Flexbox*)** | `flex items-center gap-2` | - `flex`: Mengaktifkan model layout fleksibel.<br>- `items-center`: Menyejajarkan teks dan ikon secara vertikal di tengah.<br>- `gap-2`: Memberikan jarak 8px antara ikon dan teks. |
| **Efek Bayangan (*Shadow*)** | `shadow-sm` | Memberikan efek kedalaman/elevasi halus (*box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05)*). |
| **Fleksibilitas & Transisi** | `shrink-0 transition-colors` | - `shrink-0`: Mencegah tombol gepeng saat ruang berkurang.<br>- `transition-colors`: Menghaluskan perubahan warna saat hover (durasi default 150ms). |

---

## 3. Penyesuaian Khusus untuk Tombol di Dalam Tabel

Meskipun ingin mengadopsi gaya visual yang sama persis, ada 2 aturan penting saat tombol diletakkan di dalam sel tabel (`<td>`):

1. **Gunakan `inline-flex` bukan `flex` murni**:
   Di dalam sel tabel, tombol Edit berdampingan dengan form tombol Hapus (`<form class="inline-block">`). Menggunakan `flex` murni dapat menyebabkan tombol berperilaku seperti *block element* dan memaksa tombol Hapus turun ke baris baru. Dengan `inline-flex`, kedua tombol tetap berdampingan horizontal.
2. **Sesuaikan Padding Menjadi Lebih Proporsional (`px-3 py-1.5`)**:
   Padding `px-5 py-2.5` cocok untuk tombol header utama. Jika digunakan di dalam tabel, tombol akan terlalu besar dan membuat baris tabel menjadi terlalu tinggi. Padding `px-3 py-1.5` dengan font `text-xs` mempertahankan gaya kapsul yang sama namun tetap proporsional.

---

## 4. Perbandingan Kode (Before vs After)

### Kode Sebelum Perubahan:
Tombol sebelumnya hanya berupa tautan ikon biru tanpa background:
```html
<button type="button" 
        @click="openModal = true; editMode = true; formAction = '{{ route('finance.kategori.update', $kategori->id) }}'; formMethod = 'PUT'; formKategori = @js($kategori->nama_kategori); formDeskripsi = @js($kategori->deskripsi ?? ''); formPagu = @js($kategori->pagu_anggaran);" 
        class="text-blue-600 hover:text-blue-800 transition-colors" 
        title="Edit">
  <i class="fa-solid fa-pen-to-square"></i>
</button>
```

### Kode Sesudah Perubahan:
Tombol kini memiliki gaya solid navy, rounded-xl, teks label "Edit", dan efek shadow:
```html
<button type="button" 
        @click="openModal = true; editMode = true; formAction = '{{ route('finance.kategori.update', $kategori->id) }}'; formMethod = 'PUT'; formKategori = @js($kategori->nama_kategori); formDeskripsi = @js($kategori->deskripsi ?? ''); formPagu = @js($kategori->pagu_anggaran);" 
        class="bg-[#2b337c] hover:bg-[#1e255e] text-white px-3 py-1.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 shadow-sm transition-colors cursor-pointer" 
        title="Edit Kategori">
  <i class="fa-solid fa-pen-to-square"></i>
  <span>Edit</span>
</button>
```

### Ringkasan Diff Perubahan:
```diff
- class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
-   <i class="fa-solid fa-pen-to-square"></i>
+ class="bg-[#2b337c] hover:bg-[#1e255e] text-white px-3 py-1.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 shadow-sm transition-colors cursor-pointer" title="Edit Kategori">
+   <i class="fa-solid fa-pen-to-square"></i>
+   <span>Edit</span>
```

---

## 5. Menjaga Integritas Fungsionalitas Alpine.js

Pastikan atribut direktif `@click` **tidak diubah** saat memodifikasi class styling. Direktif ini mengendalikan state reaktif Alpine.js pada parent kontainer (`x-data`):

```javascript
@click="
  openModal = true; 
  editMode = true; 
  formAction = '{{ route('finance.kategori.update', $kategori->id) }}'; 
  formMethod = 'PUT'; 
  formKategori = @js($kategori->nama_kategori); 
  formDeskripsi = @js($kategori->deskripsi ?? ''); 
  formPagu = @js($kategori->pagu_anggaran);
"
```

- **`openModal = true`**: Mengubah visibilitas modal form menjadi tampil (`x-show="openModal"`).
- **`editMode = true`**: Menandai form dalam mode edit sehingga judul modal berubah menjadi *"Edit Kategori & Pagu"*.
- **`formAction` & `formMethod`**: Mengarahkan route form ke endpoint update dengan HTTP verb `PUT`.
- **`@js(...)`**: Helper Blade Laravel untuk melakukan sanitasi dan konversi data PHP ke format JSON JavaScript secara aman agar input terisi otomatis dengan data yang dipilih.

---

## 6. Langkah-Langkah Menerapkan & Pengujian

1. **Buka Berkas View**:
   Buka file `resources/views/finance/kategori/index.blade.php`.
2. **Temukan Baris Tombol Aksi**:
   Cari blok perulangan `@forelse($kategoriList as $idx => $kategori)` di dalam elemen `<tbody>`.
3. **Perbarui Class & Konten Tombol**:
   Gantikan atribut `class` pada tombol edit dan tambahkan teks `<span>Edit</span>` di samping ikon FontAwesome.
4. **Bersihkan Cache Template Blade**:
   Jalankan perintah berikut di terminal:
   ```bash
   php artisan view:clear
   ```
5. **Uji di Browser**:
   - Masuk sebagai role **Finance** (email: `finance@sirab.local`).
   - Buka menu **Master Kategori & Pagu**.
   - Perhatikan tombol **Edit** kini berwarna biru navy dengan sudut melengkung dan label teks yang seragam dengan tombol **Tambah Kategori**.
   - Klik tombol **Edit** untuk memastikan modal form tetap terbuka dan kolom form terisi sesuai data baris yang diklik.
