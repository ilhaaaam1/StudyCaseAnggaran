@extends('layouts.app')

@section('title', 'Inventaris & Dokumentasi Barang')

@section('content')
<div class="space-y-6">
  <!-- Top Navigation & Header -->
  <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 text-xs font-medium text-indigo-600 mb-1">
          <a href="{{ url('/') }}" class="hover:underline flex items-center gap-1 text-slate-500 hover:text-indigo-600">
            <i class="fa-solid fa-house text-[11px]"></i> Dashboard
          </a>
          <span class="text-slate-300">/</span>
          <span class="text-indigo-600 font-semibold">Inventaris & Dokumentasi</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2.5">
          <span class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-base">
            <i class="fa-solid fa-boxes-stacked"></i>
          </span>
          Inventaris & Dokumentasi Barang
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
          Manajemen data aset, sarana dan dokumentasi fisik inventaris sekolah — SDN Sidokare 3.
        </p>
      </div>

      <div class="flex items-center gap-2.5">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
          <i class="fa-solid fa-arrow-left text-xs"></i>
          <span>Kembali</span>
        </a>
        <a href="{{ route('barang.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm hover:shadow transition-all">
          <i class="fa-solid fa-plus text-xs"></i>
          <span>Tambah Barang</span>
        </a>
      </div>
    </div>
  </div>

  <!-- Search & Filter Card -->
  <div class="bg-white rounded-2xl border border-slate-200/80 p-4 shadow-sm">
    <form action="{{ route('barang.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
      <div class="relative flex-1">
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
          <i class="fa-solid fa-magnifying-glass text-xs"></i>
        </div>
        <input 
          type="text" 
          name="q" 
          value="{{ $search ?? '' }}" 
          placeholder="Cari nama barang, kategori, atau sumber dana..." 
          class="w-full pl-9 pr-4 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-slate-800 transition-colors"
        >
      </div>
      <div class="flex items-center gap-2">
        <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs sm:text-sm font-medium transition-colors inline-flex items-center gap-1.5 cursor-pointer">
          <i class="fa-solid fa-filter text-xs"></i> Filter
        </button>
        @if(!empty($search))
          <a href="{{ route('barang.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs sm:text-sm font-medium transition-colors">
            Reset
          </a>
        @endif
      </div>
    </form>
  </div>

  <!-- Table Card -->
  <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
        <h2 class="text-sm font-semibold text-slate-800">Daftar Barang & Dokumentasi</h2>
      </div>
      <span class="text-xs text-slate-400">Total: <strong>{{ $barangs->total() }}</strong> item</span>
    </div>

    @if($barangs->isEmpty())
      <div class="py-16 text-center px-4">
        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-400 flex items-center justify-center text-2xl mx-auto mb-3">
          <i class="fa-solid fa-box-open"></i>
        </div>
        <h3 class="text-sm font-semibold text-slate-700">Belum Ada Data Barang</h3>
        <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 mb-4">
          @if(!empty($search))
            Tidak ada barang inventaris yang sesuai dengan kata kunci pencarian "{{ $search }}".
          @else
            Mulai tambahkan dokumentasi aset dan barang sekolah menggunakan formulir upload.
          @endif
        </p>
        <a href="{{ route('barang.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
          <i class="fa-solid fa-plus text-xs"></i> Tambah Barang Sekarang
        </a>
      </div>
    @else
      <div class="overflow-x-auto table-container">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50/80 text-[11px] font-semibold text-slate-500 uppercase tracking-wider border-b border-slate-100">
              <th class="py-3 px-4 w-12 text-center">No</th>
              <th class="py-3 px-4 w-20 text-center">GAMBAR</th>
              <th class="py-3 px-4">Nama Barang</th>
              <th class="py-3 px-4">Kategori</th>
              <th class="py-3 px-4 text-center">Jumlah</th>
              <th class="py-3 px-4 text-center">Kondisi</th>
              <th class="py-3 px-4">Keterangan</th>
              <th class="py-3 px-4 text-center w-32">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
            @foreach($barangs as $index => $item)
              <tr class="hover:bg-slate-50/60 transition-colors">
                <td class="py-3.5 px-4 text-center text-slate-400 font-mono-num">
                  {{ $barangs->firstItem() + $index }}
                </td>

                <!-- Tahap 5: Thumbnail Gambar (Slide 9 Standar PPT: 36px) -->
                <td class="py-3.5 px-4 text-center">
                  @if($item->gambar)
                    <a href="{{ asset($item->gambar) }}" target="_blank" title="Klik untuk memperbesar foto" class="inline-block group">
                      <img 
                        src="{{ asset($item->gambar) }}" 
                        alt="{{ $item->nama }}" 
                        class="w-9 h-9 object-cover rounded-lg border border-slate-200 group-hover:scale-105 group-hover:border-indigo-400 transition-all shadow-xs mx-auto"
                        style="width: 36px; height: 36px;"
                        loading="lazy"
                      >
                    </a>
                  @else
                    <div class="w-9 h-9 rounded-lg bg-slate-100 border border-slate-200/80 flex flex-col items-center justify-center text-slate-400 mx-auto" style="width: 36px; height: 36px;" title="Tanpa Foto Dokumentasi">
                      <i class="fa-solid fa-image text-xs"></i>
                      <span class="text-[8px] text-slate-400 leading-tight">N/A</span>
                    </div>
                  @endif
                </td>

                <td class="py-3.5 px-4">
                  <a href="{{ route('barang.show', $item->id) }}" class="font-semibold text-slate-900 hover:text-indigo-600 transition-colors">
                    {{ $item->nama }}
                  </a>
                  <div class="text-[11px] text-slate-400 mt-0.5">
                    ID: #{{ str_pad((string)$item->id, 4, '0', STR_PAD_LEFT) }} &bull; Diperbarui {{ $item->updated_at->diffForHumans() }}
                  </div>
                </td>

                <td class="py-3.5 px-4">
                  <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $item->kategori }}
                  </span>
                </td>

                <td class="py-3.5 px-4 text-center font-mono-num font-semibold text-slate-800">
                  {{ number_format($item->jumlah) }} unit
                </td>

                <td class="py-3.5 px-4 text-center">
                  @if($item->kondisi === 'baik')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Baik
                    </span>
                  @elseif($item->kondisi === 'rusak')
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Rusak
                    </span>
                  @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Hilang
                    </span>
                  @endif
                </td>

                <td class="py-3.5 px-4 max-w-xs truncate text-slate-500 text-[11px]">
                  {{ $item->keterangan ?: '—' }}
                </td>

                <!-- Aksi CRUD -->
                <td class="py-3.5 px-4 text-center">
                  <div class="inline-flex items-center gap-1">
                    <a href="{{ route('barang.show', $item->id) }}" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Lihat Detail">
                      <i class="fa-solid fa-eye text-xs"></i>
                    </a>
                    <a href="{{ route('barang.edit', $item->id) }}" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Data">
                      <i class="fa-solid fa-pen text-xs"></i>
                    </a>
                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini beserta berkas gambarnya di storage?')">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" title="Hapus Barang">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="px-6 py-4 border-t border-slate-100">
        {{ $barangs->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
