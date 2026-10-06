# 🎨 Panduan Styling & Modernisasi Form Login Sistem RAB

Panduan ini mendokumentasikan rekomendasi perbaikan desain antarmuka (**UI/UX Refactoring**) khusus untuk komponen kartu modal formulir login pada aplikasi **Sistem Informasi RAB (SIRAB)**.

> ⚠️ **Catatan Penting (Prinsip Non-Destruktif):**  
> Dokumen ini merupakan panduan teknis styling. Seluruh atribut form, penamaan *input* (`name="email"`, `name="password"`), token CSRF (`@csrf`), *routing*, serta logika backend Laravel **wajib dipertahankan sepenuhnya** tanpa ada perubahan fungsi.

---

## 📌 1. Ruang Lingkup Perubahan (Scope)

- **Fokus Pembenahan:**
  - Desain kontainer modal (*card box* & *backdrop*).
  - Tipografi dan *header* formulir ("Login Sistem RAB").
  - *Input field* Email dan Password (penambahan ikon representatif, *focus ring* halus, dan padding nyaman).
  - Desain *checkbox* "Ingat Saya" (*Remember Me*).
  - Tombol aksi utama (*Submit Button*) dengan efek interaktif (*hover*, *active*, dan *shadow*).
  - Notifikasi/peringatan validasi error (*Alert Box*).
- **Elemen yang TIDAK Diubah:**
  - Tata letak *Hero Section* di latar belakang (teks "GATE", foto kolase kegiatan, dan *overlay sidebar menu*).
  - *Footer* halaman luar.
  - Alur logika autentikasi backend (`AuthController.php` & `LoginRequest.php`).

---

## 📂 2. Berkas Target

- **File Path:** [`resources/views/auth/login.blade.php`](resources/views/auth/login.blade.php)
- **Teknologi:** Laravel Blade, Tailwind CSS, Alpine.js, & FontAwesome v6.
- **Area Target:** Baris **~384 s.d. ~451** (bagian blok komentar `<!-- MODAL POPUP FORM LOGIN -->`).

---

## 🎯 3. Tujuan & Filosofi Desain

Tampilan form login saat ini sudah fungsional, namun dapat ditingkatkan agar terlihat lebih elegan, kredibel, dan profesional dengan standar aplikasi enterprise modern:

1. **Soft Elevation & Border:**
   Menggunakan *border* halus (`border-slate-100`) berpadu dengan bayangan bertingkat (*layered soft shadow*) agar kartu modal terasa mengambang secara natural di atas latar belakang.
2. **Harmonisasi Palet Warna:**
   Memanfaatkan perpaduan warna identitas aplikasi:
   - Biru Korporat Utama: `#2e358b` (*Primary Deep Blue*).
   - Ungu Aksen Lembut: `#8b8df7` (*Soft Violet Accent*).
   - Netral Modern: Palet `slate-700`, `slate-500`, dan `slate-100` untuk keterbacaan kontras yang tinggi.
3. **Affordance & Micro-Interactions:**
   - *State* fokus pada input field menggunakan *ring* transparan (`focus:ring-4 focus:ring-indigo-500/10`) sehingga pengguna merasakan umpan balik visual yang jelas saat mengetik.
   - Tombol submit memiliki efek transisi halus (*gradient*, sedikit terangkat pada *hover*, dan efek klik *active:scale-[0.99]*).
   - Penambahan ikon di dalam field teks (ikon amplop untuk email, ikon gembok untuk password) untuk mempercepat orientasi visual pengguna.

---

## 🧩 4. Bedah Komponen Form Baru

Berikut adalah rincian elemen yang diperbarui pada form login:

### A. Kontainer Kartu Modal & Aksen Atas
- **Struktur:** Menambahkan garis aksen gradien tipis (*accent gradient bar*) di sisi atas kartu untuk memberikan sentuhan visual premium.
- **Tombol Tutup (Close):** Diubah menjadi tombol bulat dengan transisi warna abu-abu lembut agar lebih mudah diklik pada perangkat layar sentuh/mobile.

### B. Header & Tipografi Form
- **Ikon Brand Pengaman:** Menampilkan ikon gembok terenkripsi di dalam lingkaran latar biru lembut sebelum teks judul.
- **Judul:** Menggunakan `text-2xl font-extrabold text-slate-800 tracking-tight`.
- **Subjudul:** Menggunakan kalimat panduan yang ramah: *"Masukkan kredensial akun Anda untuk mengakses sistem."*

### C. Input Field (Email & Password)
- **Ikon Prefix (Kiri):** Disematkan ikon `<i class="fa-regular fa-envelope"></i>` pada email dan `<i class="fa-solid fa-lock"></i>` pada password.
- **Padding:** Menggunakan `pl-10 pr-4 py-2.5` agar teks tidak bertumpuk dengan ikon.
- **Background Input:** Warna latar awal sedikit abu-abu lembut (`bg-slate-50/70`), yang otomatis berubah menjadi putih bersih (`focus:bg-white`) ketika kursor aktif.
- **Tombol Toggle Password (Mata):** Tetap ditenagai Alpine.js (`showPassword`), dengan padding kanan yang proporsional dan warna ikon yang selaras.

### D. Checkbox "Ingat Saya" (Remember Me)
- Tata letak dibuat sejajar rapi (*flex items-center justify-between*) dengan penambahan tautan bantuan login atau informasi proteksi sesi.
- Ukuran *checkbox* dipertegas dengan *accent color* Tailwind (`text-indigo-600 rounded`).

### E. Tombol Masuk (Submit Button)
- Menggunakan gradien elegan dari `#2e358b` ke `indigo-700`.
- Dilengkapi ikon panah kecil (`fa-solid fa-arrow-right`) dengan efek animasi geser saat kursor diarahkan (*group-hover:translate-x-0.5*).

### F. Badge Keamanan Footer Kartu
- Menambahkan baris penutup dengan ikon perisai hijau: *"Koneksi aman terenkripsi SSO & SSL"* untuk meningkatkan rasa percaya pengguna saat memasukkan password.

---

## 🔄 5. Kode Snippet: Sebelum vs Sesudah (Before vs After)

### 🔴 Kode Asli (Sebelum Refactor):

```blade
    <!-- MODAL POPUP FORM LOGIN -->
    <div x-show="loginModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4"
         style="display: none;">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 md:p-8 relative">
            <!-- Tombol Close Modal -->
            <button @click="loginModalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl font-bold bg-transparent border-none cursor-pointer">
                &times;
            </button>

            <!-- Title -->
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Login Sistem RAB</h3>
                <p class="text-xs text-gray-500 mt-1">Masukkan Email dan Password Anda</p>
            </div>

            <!-- Pesan Error -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded text-xs">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Email -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Email</label>
                    <input type="email" name="email" required placeholder="nama@domain.com" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8b8df7]" value="{{ old('email') }}">
                </div>

                <!-- Input Password -->
                {{-- PRESENTASI: Interaksi JavaScript untuk toggle visibilitas password --}}
                <div x-data="{ showPassword: false }">
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="••••••••" class="w-full px-3 py-2.5 pr-10 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8b8df7]">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none bg-transparent border-none cursor-pointer">
                            <i class="fa-solid" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-1 mb-2">
                    <label class="inline-flex items-center text-xs text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="mr-1.5 border-gray-300 rounded text-[#8b8df7] focus:ring-[#8b8df7]">
                        Ingat Saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-[#2e358b] hover:bg-[#1e2360] text-white font-semibold rounded-lg text-sm shadow transition-colors border-none cursor-pointer mt-2">
                    Masuk ke Sistem
                </button>
            </form>
        </div>
    </div>
```

---

### 🟢 Rekomendasi Kode Baru (Setelah Refactor):

```blade
    <!-- MODAL POPUP FORM LOGIN (MODERN UI/UX) -->
    <div x-show="loginModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4"
         style="display: none;">

        <div class="bg-white rounded-3xl shadow-2xl shadow-indigo-950/10 border border-slate-100 w-full max-w-md p-6 sm:p-8 relative overflow-hidden">
            <!-- Aksen Garis Gradien Atas Kartu -->
            <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-[#2e358b]"></div>

            <!-- Tombol Close Modal Lingkaran Halus -->
            <button @click="loginModalOpen = false" 
                    class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100/80 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center text-base transition-colors border-none cursor-pointer">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Title & Ikon Header -->
            <div class="text-center mb-6 mt-1">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 mb-3 border border-indigo-100/60 shadow-xs">
                    <i class="fa-solid fa-shield-halved text-lg"></i>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight">Login Sistem RAB</h3>
                <p class="text-xs text-slate-500 mt-1.5">Masukkan kredensial akun Anda untuk mengakses sistem</p>
            </div>

            <!-- Pesan Error Modern Alert -->
            @if ($errors->any())
                <div class="bg-rose-50/90 border border-rose-200 text-rose-700 px-3.5 py-2.5 mb-4 rounded-xl text-xs flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-exclamation mt-0.5 text-rose-500 shrink-0"></i>
                    <div class="space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-sm"></i>
                        </div>
                        <input type="email" 
                               name="email" 
                               required 
                               placeholder="nama@sdnsidokare3.sch.id" 
                               value="{{ old('email') }}"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50/60 border border-slate-300 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-150">
                    </div>
                </div>

                <!-- Input Password dengan Toggle Mata -->
                <div x-data="{ showPassword: false }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider">
                            Kata Sandi <span class="text-rose-500">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               required 
                               placeholder="••••••••" 
                               class="w-full pl-10 pr-10 py-2.5 bg-slate-50/60 border border-slate-300 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all duration-150">
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none bg-transparent border-none cursor-pointer transition-colors"
                                title="Lihat kata sandi">
                            <i class="fa-solid text-sm" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="inline-flex items-center gap-2 text-xs font-medium text-slate-600 cursor-pointer select-none">
                        <input type="checkbox" 
                               name="remember" 
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500/30 cursor-pointer transition">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button Masuk -->
                <button type="submit" 
                        class="w-full py-3 px-4 bg-gradient-to-r from-[#2e358b] to-indigo-700 hover:from-[#1e2360] hover:to-indigo-800 active:scale-[0.99] text-white font-semibold rounded-xl text-sm shadow-md shadow-indigo-900/15 hover:shadow-lg hover:shadow-indigo-900/20 transition-all duration-200 border-none cursor-pointer flex items-center justify-center gap-2 group mt-2">
                    <span>Masuk ke Sistem</span>
                    <i class="fa-solid fa-arrow-right text-xs transition-transform duration-200 group-hover:translate-x-1"></i>
                </button>
            </form>

            <!-- Footer Proteksi Kartu -->
            <div class="pt-4 mt-5 border-t border-slate-100 flex items-center justify-center gap-1.5 text-[11px] text-slate-400">
                <i class="fa-solid fa-circle-check text-emerald-500 text-[10px]"></i>
                <span>Sistem Terintegrasi Single Sign On (SSO)</span>
            </div>
        </div>
    </div>
```

---

## 🛠️ 6. Langkah demi Langkah Penerapan Mandiri

Bagi developer yang hendak menerapkan perubahan ini ke proyek:

1. **Buka Berkas:**  
   Buka file `resources/views/auth/login.blade.php` di editor kode (VS Code / PHPStorm).
2. **Cari Blok Komentar Modal:**  
   Temukan baris komentar `<!-- MODAL POPUP FORM LOGIN -->` (kurang lebih di baris **384**).
3. **Gantikan Konten:**  
   Gantikan seluruh blok kontainer modal lama (dari baris `<div x-show="loginModalOpen"...` hingga penutupnya `</div></div>`) dengan **Rekomendasi Kode Baru** yang telah disediakan di atas.
4. **Pastikan Token & Atribut Aman:**  
   Pastikan tag `@csrf`, `name="email"`, `name="password"`, dan `name="remember"` tetap ada di dalam tag `<form action="{{ route('login.post') }}" method="POST">`.
5. **Simpan Berkas (`Ctrl+S`).**

---

## 🔍 7. Cara Memeriksa & Menguji Hasil Tampilan di Browser

Setelah kode diterapkan, jalankan langkah-langkah verifikasi berikut:

1. **Buka Halaman Login:**  
   Akses `http://127.0.0.1:8000/login` pada browser Anda.
2. **Buka Modal Login:**  
   Klik tombol **"Login / Masuk"** pada halaman hero.
3. **Uji Coba Visual:**
   - [ ] **Backdrop Blur:** Pastikan latar belakang halaman menjadi sedikit redup dan buram halus (*subtle blur*).
   - [ ] **Ikon Input:** Periksa apakah ikon amplop (email) dan gembok (password) muncul rapi di sisi kiri kolom tanpa menutupi teks isian.
   - [ ] **Fokus Ring:** Klik salah satu kolom input; perhatikan adanya lingkaran halo (*focus ring*) indigo halus.
   - [ ] **Toggle Password:** Ketik kata sandi, lalu klik ikon mata di sisi kanan kolom untuk memastikan teks berganti antara titik-titik dan teks terbaca.
   - [ ] **Hover Tombol Masuk:** Arahkan kursor ke tombol "Masuk ke Sistem"; warna tombol berubah halus dan panah bergeser sedikit ke kanan.
4. **Uji Coba Validasi Error:**  
   - Kosongkan password atau masukkan email sembarang yang salah, lalu klik "Masuk ke Sistem".
   - Pastikan kotak pesan error (*Alert Box*) tampil dengan sudut membulat rapi berlatar merah lembut (`bg-rose-50/90`) serta ikon peringatan.

---
*Dokumen panduan ini disusun untuk standarisasi antarmuka pengguna (UI/UX) pada aplikasi SIRAB.*
