@extends('layouts.app')

@section('title', 'Tambah Barang Inventaris')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
  <!-- Header & Breadcrumbs -->
  <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-medium text-indigo-600 mb-1">
          <a href="{{ route('barang.index') }}" class="hover:underline flex items-center gap-1 text-slate-500 hover:text-indigo-600">
            <i class="fa-solid fa-boxes-stacked text-[11px]"></i> Inventaris
          </a>
          <span class="text-slate-300">/</span>
          <span class="text-indigo-600 font-semibold">Tambah Barang Baru</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
          <span class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base">
            <i class="fa-solid fa-folder-plus"></i>
          </span>
          Tambah Barang Inventaris
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
          Unggah berkas gambar dokumentasi dan data inventaris aset sekolah.
        </p>
      </div>

      <div>
        <a href="{{ route('barang.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
          <i class="fa-solid fa-arrow-left text-xs"></i>
          <span>Batal & Kembali</span>
        </a>
      </div>
    </div>
  </div>

  <!-- Form Card (Tahap 1: enctype="multipart/form-data") -->
  <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Nama Barang -->
        <div class="md:col-span-2">
          <label for="nama" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
            Nama Barang / Dokumentasi <span class="text-rose-500">*</span>
          </label>
          <input 
            type="text" 
            name="nama" 
            id="nama" 
            value="{{ old('nama') }}" 
            placeholder="Contoh: Proyektor Epson EB-X500 Ruang Multimedia"
            maxlength="150"
            required
            class="w-full px-4 py-2.5 text-xs sm:text-sm rounded-xl border @error('nama') border-rose-500 bg-rose-50/30 @else border-slate-200 @enderror focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-800 transition-colors"
          >
          @error('nama')
            <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
              <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
            </p>
          @enderror
        </div>

        <!-- Kategori -->
        <div>
          <label for="kategori" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
            Kategori Aset <span class="text-rose-500">*</span>
          </label>
          <select 
            name="kategori" 
            id="kategori" 
            required
            class="w-full px-4 py-2.5 text-xs sm:text-sm rounded-xl border @error('kategori') border-rose-500 bg-rose-50/30 @else border-slate-200 @enderror focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-800 transition-colors bg-white"
          >
            <option value="" disabled {{ old('kategori') ? '' : 'selected' }}>Pilih Kategori Barang</option>
            <option value="Sarana & Prasarana" {{ old('kategori') === 'Sarana & Prasarana' ? 'selected' : '' }}>Sarana & Prasarana</option>
            <option value="Alat Elektronik" {{ old('kategori') === 'Alat Elektronik' ? 'selected' : '' }}>Alat Elektronik</option>
            <option value="Buku & Literasi" {{ old('kategori') === 'Buku & Literasi' ? 'selected' : '' }}>Buku & Literasi</option>
            <option value="ATK & Operasional" {{ old('kategori') === 'ATK & Operasional' ? 'selected' : '' }}>ATK & Operasional</option>
            <option value="Peralatan Olahraga & Seni" {{ old('kategori') === 'Peralatan Olahraga & Seni' ? 'selected' : '' }}>Peralatan Olahraga & Seni</option>
            <option value="Lainnya" {{ old('kategori') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
          </select>
          @error('kategori')
            <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
              <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
            </p>
          @enderror
        </div>

        <!-- Jumlah -->
        <div>
          <label for="jumlah" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
            Jumlah Unit <span class="text-rose-500">*</span>
          </label>
          <input 
            type="number" 
            name="jumlah" 
            id="jumlah" 
            value="{{ old('jumlah', 1) }}" 
            min="0"
            required
            class="w-full px-4 py-2.5 text-xs sm:text-sm rounded-xl border @error('jumlah') border-rose-500 bg-rose-50/30 @else border-slate-200 @enderror focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-800 transition-colors font-mono-num"
          >
          @error('jumlah')
            <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
              <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
            </p>
          @enderror
        </div>

        <!-- Kondisi -->
        <div>
          <label for="kondisi" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
            Kondisi Barang <span class="text-rose-500">*</span>
          </label>
          <select 
            name="kondisi" 
            id="kondisi" 
            required
            class="w-full px-4 py-2.5 text-xs sm:text-sm rounded-xl border @error('kondisi') border-rose-500 bg-rose-50/30 @else border-slate-200 @enderror focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-800 transition-colors bg-white"
          >
            <option value="baik" {{ old('kondisi', 'baik') === 'baik' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
            <option value="rusak" {{ old('kondisi') === 'rusak' ? 'selected' : '' }}>Rusak (Perlu Perbaikan / Afkir)</option>
            <option value="hilang" {{ old('kondisi') === 'hilang' ? 'selected' : '' }}>Hilang (Dalam Penelusuran)</option>
          </select>
          @error('kondisi')
            <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
              <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
            </p>
          @enderror
        </div>

        <!-- Keterangan -->
        <div class="md:col-span-2">
          <label for="keterangan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
            Keterangan / Sumber Dana Anggaran
          </label>
          <textarea 
            name="keterangan" 
            id="keterangan" 
            rows="3" 
            placeholder="Contoh: Pengadaan bersumber dari Dana BOS Reguler TA 2026. Ditempatkan di Lab Komputer Lantai 2."
            class="w-full px-4 py-2.5 text-xs sm:text-sm rounded-xl border @error('keterangan') border-rose-500 bg-rose-50/30 @else border-slate-200 @enderror focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-800 transition-colors"
          >{{ old('keterangan') }}</textarea>
          @error('keterangan')
            <p class="text-rose-600 text-xs mt-1.5 flex items-center gap-1">
              <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
            </p>
          @enderror
        </div>

        <!-- Upload Foto Berkas -->
        <div class="md:col-span-2 pt-2 border-t border-slate-100">
          <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
            Foto Dokumentasi Barang
          </label>

          <div class="flex flex-col sm:flex-row gap-5 items-start">
            <!-- Pratinjau Interaktif -->
            <div class="w-32 h-32 rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 flex flex-col items-center justify-center overflow-hidden shrink-0 relative group">
              <img id="imagePreview" src="" alt="Pratinjau" class="w-full h-full object-cover hidden">
              <div id="previewPlaceholder" class="text-center p-3 text-slate-400">
                <i class="fa-solid fa-cloud-arrow-up text-2xl mb-1 text-slate-300"></i>
                <span class="block text-[10px] leading-tight">Pratinjau Foto</span>
              </div>
            </div>

            <!-- Kontrol Berkas -->
            <div class="flex-1 space-y-2">
              <input 
                type="file" 
                name="gambar" 
                id="gambar" 
                accept="image/*"
                onchange="previewUpload(this)"
                class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer border border-slate-200 rounded-xl"
              >
              <div class="text-[11px] text-slate-400 space-y-1">
                <p>&bull; Format didukung: <strong>JPEG, PNG, JPG, GIF</strong></p>
                <p>&bull; Ukuran maksimal: <strong>2 MB (2048 KB)</strong></p>
                <p>&bull; Penyimpanan fisik: disk <code>public</code> di folder <code>storage/app/public/barang/</code></p>
              </div>

              @error('gambar')
                <p class="text-rose-600 text-xs font-medium mt-1 flex items-center gap-1">
                  <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                </p>
              @enderror
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
        <a href="{{ route('barang.index') }}" class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
          Batal
        </a>
        <button type="submit" class="px-6 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm hover:shadow transition-all inline-flex items-center gap-2 cursor-pointer">
          <i class="fa-solid fa-floppy-disk text-xs"></i>
          <span>Simpan Data Barang</span>
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function previewUpload(input) {
    const preview = document.getElementById('imagePreview');
    const placeholder = document.getElementById('previewPlaceholder');

    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
      };
      reader.readAsDataURL(input.files[0]);
    } else {
      preview.src = '';
      preview.classList.add('hidden');
      placeholder.classList.remove('hidden');
    }
  }
</script>
@endsection
