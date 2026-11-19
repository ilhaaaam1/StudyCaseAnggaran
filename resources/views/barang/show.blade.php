@extends('layouts.app')

@section('title', 'Detail Barang Inventaris - ' . $barang->nama)

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
  <!-- Header & Breadcrumbs -->
  <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm print:hidden">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-medium text-indigo-600 mb-1">
          <a href="{{ route('barang.index') }}" class="hover:underline flex items-center gap-1 text-slate-500 hover:text-indigo-600">
            <i class="fa-solid fa-boxes-stacked text-[11px]"></i> Inventaris
          </a>
          <span class="text-slate-300">/</span>
          <span class="text-indigo-600 font-semibold">Detail Barang</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
          <span class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base">
            <i class="fa-solid fa-circle-info"></i>
          </span>
          {{ $barang->nama }}
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
          Kode Registrasi: <strong class="font-mono text-indigo-600 font-bold">{{ $barang->kode_barang }}</strong> &bull; Didaftarkan {{ $barang->created_at->isoFormat('D MMMM Y, HH:mm') }}
        </p>
      </div>

      <div class="flex items-center gap-2.5">
        <a href="{{ route('barang.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
          <i class="fa-solid fa-arrow-left text-xs"></i>
          <span>Daftar Barang</span>
        </a>
        <a href="{{ route('barang.edit', $barang->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-white bg-amber-600 hover:bg-amber-700 transition-colors shadow-sm">
          <i class="fa-solid fa-pen-to-square text-xs"></i>
          <span>Edit Barang</span>
        </a>
        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini beserta berkas gambarnya di storage?')">
          @csrf
          @method('DELETE')
          <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors cursor-pointer">
            <i class="fa-solid fa-trash-can text-xs"></i>
            <span>Hapus</span>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Detail Content Grid -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 print:hidden">
    <!-- Left Column: Foto Dokumentasi -->
    <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col items-center text-center">
      <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4 self-start">
        Foto Dokumentasi
      </h3>

      @if($barang->gambar)
        <div class="w-full flex flex-col items-center">
          <div class="relative group rounded-2xl overflow-hidden border border-slate-200 shadow-sm bg-slate-50" style="max-width: 300px;">
            <img 
              src="{{ asset($barang->gambar) }}" 
              alt="{{ $barang->nama }}" 
              class="w-full h-auto object-cover max-h-80 rounded-2xl group-hover:scale-105 transition-transform duration-300"
            >
            <a 
              href="{{ asset($barang->gambar) }}" 
              target="_blank" 
              class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-medium transition-opacity gap-1.5"
            >
              <i class="fa-solid fa-up-right-from-square"></i> Buka Ukuran Penuh
            </a>
          </div>

          <div class="mt-4 w-full p-3 rounded-xl bg-slate-50 border border-slate-100 text-left">
            <div class="text-[11px] text-slate-500 flex items-center justify-between mb-1">
              <span>Status Berkas:</span>
              <span class="text-emerald-700 font-semibold bg-emerald-100 px-1.5 py-0.5 rounded text-[10px]">Tersimpan Fisik</span>
            </div>
            <div class="text-[10px] text-slate-400 font-mono break-all">
              Path: {{ $barang->gambar }}
            </div>
          </div>
        </div>
      @else
        <div class="w-full py-12 flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50 text-slate-400" style="max-width: 300px;">
          <i class="fa-solid fa-image-slash text-4xl mb-2 text-slate-300"></i>
          <p class="text-xs font-medium text-slate-600">Tidak ada foto dokumentasi</p>
          <p class="text-[11px] text-slate-400 mt-0.5 max-w-[200px]">Barang ini belum memiliki lampiran berkas gambar.</p>
          <a href="{{ route('barang.edit', $barang->id) }}" class="mt-3 text-xs text-indigo-600 hover:underline font-medium inline-flex items-center gap-1">
            <i class="fa-solid fa-cloud-arrow-up text-[10px]"></i> Unggah Sekarang
          </a>
        </div>
      @endif
    </div>

    <!-- Right Column: Detail Spesifikasi Aset -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-sm space-y-6">
      <div>
        <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-4">
          Spesifikasi & Informasi Barang
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide block mb-1">Kategori</span>
            <span class="text-sm font-semibold text-slate-900 inline-flex items-center gap-2">
              <i class="fa-solid fa-tag text-indigo-500 text-xs"></i>
              {{ $barang->kategori }}
            </span>
          </div>

          <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide block mb-1">Jumlah Unit</span>
            <span class="text-sm font-bold text-slate-900 font-mono-num inline-flex items-center gap-2">
              <i class="fa-solid fa-cubes-stacked text-indigo-500 text-xs"></i>
              {{ number_format($barang->jumlah) }} Unit
            </span>
          </div>

          <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide block mb-1">Kondisi Fisik</span>
            <div>
              @if($barang->kondisi === 'baik')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                  <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Baik (Siap Pakai)
                </span>
              @elseif($barang->kondisi === 'rusak')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                  <span class="w-2 h-2 rounded-full bg-amber-500"></span> Rusak (Perlu Perbaikan / Afkir)
                </span>
              @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                  <span class="w-2 h-2 rounded-full bg-rose-500"></span> Hilang (Dalam Penelusuran)
                </span>
              @endif
            </div>
          </div>

          <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide block mb-1">Pembaruan Terakhir</span>
            <span class="text-xs font-medium text-slate-700 inline-flex items-center gap-2">
              <i class="fa-solid fa-clock-rotate-left text-indigo-500 text-xs"></i>
              {{ $barang->updated_at->isoFormat('D MMMM Y, HH:mm') }}
            </span>
          </div>
        </div>
      </div>

      <!-- Keterangan / Sumber Anggaran -->
      <div class="pt-4 border-t border-slate-100">
        <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
          Keterangan & Catatan Sumber Anggaran
        </h4>
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 text-xs sm:text-sm text-slate-700 leading-relaxed">
          {{ $barang->keterangan ?: 'Tidak ada catatan tambahan atau keterangan sumber anggaran untuk barang ini.' }}
        </div>
      </div>

      <!-- Arsitektur Upload Info Card -->
      <div class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-100 text-xs text-indigo-900 space-y-1.5">
        <div class="font-semibold flex items-center gap-1.5 text-indigo-800">
          <i class="fa-solid fa-circle-check text-indigo-600"></i>
          Standar Arsitektur Berkas Terpadu:
        </div>
        <ul class="list-disc list-inside text-[11px] text-indigo-800/80 space-y-0.5 pl-1">
          <li>Tahap 1 Form: <code>multipart/form-data</code></li>
          <li>Tahap 2 Validasi: <code>image|mimes:jpeg,png,jpg,gif|max:2048</code></li>
          <li>Tahap 3 Storage: File fisik tersimpan di disk <code>public</code> (<code>storage/app/public/barang/</code>)</li>
          <li>Tahap 4 Database: Menyimpan path <code>{{ $barang->gambar ?: 'null' }}</code></li>
          <li>Tahap 5 Display: Dirender via helper <code>asset($barang->gambar)</code> dengan symlink <code>public/storage</code> aktif</li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Barcode Identification Card (Sesuai Slide 4 & 13 Materi Milon Barcode) -->
  <div id="barcodeCardSection" class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <!-- Header Card (Disembunyikan saat dicetak) -->
    <div class="print:hidden px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-slate-50/50">
      <div class="flex items-center gap-3">
        <span class="w-9 h-9 rounded-xl bg-indigo-100/70 text-indigo-700 flex items-center justify-center text-base">
          <i class="fa-solid fa-barcode"></i>
        </span>
        <div>
          <h2 class="text-sm font-bold text-slate-900">Barcode Barang</h2>
          <p class="text-xs text-slate-500">
            Kode ID <strong>{{ $barang->id }}</strong> dengan format Code 39 (C39)
          </p>
        </div>
      </div>
      
      <div class="flex items-center gap-2">
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm cursor-pointer">
          <i class="fa-solid fa-print text-xs"></i>
          <span>Cetak Barcode</span>
        </button>
      </div>
    </div>

    <!-- Area Stiker Barcode (Tampil di Web & Tercetak Sempurna di Kertas/PDF) -->
    <div class="p-8 sm:p-12 flex flex-col items-center justify-center text-center">
      <!-- Kop Label (Hanya Muncul Saat Print / Tersembunyi di Layar Web) -->
      <div class="print-only hidden print:block mb-4 border-b-2 border-slate-900 pb-2 w-full text-center">
        <div class="text-[12px] font-black uppercase tracking-widest text-slate-900">SD NEGERI SIDOKARE 3 &bull; SIRAB</div>
        <div class="text-[9px] font-bold uppercase tracking-wider text-slate-600">LABEL STIKER INVENTARIS ASET BARANG</div>
      </div>

      <div class="text-xs sm:text-sm font-bold text-slate-700 print:text-black uppercase tracking-wider mb-4">
        {{ $barang->nama }}
      </div>
      
      <div class="my-2 p-4 bg-white rounded-xl border border-slate-100 print:border-none shadow-2xs print:shadow-none max-w-full overflow-x-auto flex justify-center">
        {!! $barang->barcodeHtml(3, 100) !!}
      </div>
      
      <div class="font-mono text-base sm:text-lg font-extrabold tracking-widest text-slate-900 print:text-black mt-4">
        {{ $barang->kode_barang }}
      </div>

      <!-- Keterangan Web (Disembunyikan saat cetak) -->
      <p class="print:hidden text-[11px] text-slate-400 mt-3 max-w-md">
        Kode {{ $barang->kode_barang }} siap dipindai scanner fisik atau dicetak sebagai label identitas inventaris barang.
      </p>

      <!-- Keterangan Stiker Print (Hanya Muncul Saat Print) -->
      <div class="print-only-flex hidden print:flex justify-between items-center w-full mt-5 pt-2 border-t border-dashed border-slate-700 text-[10px] text-slate-800">
        <span>Kategori: <strong>{{ $barang->kategori }}</strong></span>
        <span>Kondisi: <strong>{{ ucfirst($barang->kondisi) }}</strong></span>
        <span>ID Sistem: <strong>#{{ $barang->id }}</strong></span>
      </div>
    </div>
  </div>
</div>

@push('styles')
<style>
  /* Sembunyikan elemen cetak di layar browser biasa */
  .print-only,
  .print-only-flex {
    display: none !important;
  }

  @media print {
    /* 1. Sembunyikan navigasi, sidebar, topbar dan elemen web lainnya */
    #mainSidebar, 
    #sidebarBackdrop,
    #sirabProgressBar, 
    header, 
    nav, 
    aside,
    footer,
    .no-print,
    .print\:hidden {
      display: none !important;
    }

    /* 2. Tampilkan kop stiker dan info hanya di cetakan */
    .print-only {
      display: block !important;
    }
    .print-only-flex {
      display: flex !important;
    }

    body {
      display: block !important;
      background-color: #ffffff !important;
      color: #000000 !important;
      margin: 0 !important;
      padding: 0 !important;
    }

    main {
      padding: 0 !important;
      margin: 0 !important;
      max-width: 100% !important;
    }

    /* 3. Format kotak stiker barcode rapi di tengah halaman */
    #barcodeCardSection {
      border: 2px dashed #000000 !important;
      border-radius: 12px !important;
      max-width: 540px !important;
      margin: 30px auto !important;
      box-shadow: none !important;
      page-break-inside: avoid !important;
      background: #ffffff !important;
    }

    /* 4. Wajib: Garis hitam barcode selalu tercetak pekat */
    * {
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
      color-adjust: exact !important;
    }

    @page {
      size: auto;
      margin: 10mm;
    }
  }
</style>
@endpush
@endsection
