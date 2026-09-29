@php
  $kategoriList = $kategoriList ?? collect();
  $hasErrors = isset($errors) && $errors->any();
@endphp

{{-- PRESENTASI: Extend Layout Utama --}}
{{-- Perbaikan bug blank page dengan memanggil layout utama (app.blade.php) --}}
@extends('layouts.app')

@section('title', 'Master Kategori & Pagu Anggaran - SIRAB')
@section('breadcrumb', 'Master Kategori & Pagu')

@section('content')
  <!-- Breadcrumb Navigation -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('finance.dashboard') }}" class="hover:text-slate-800 transition-colors">Dashboard Finance</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Master Kategori & Pagu</span>
  </div>

  {{-- PRESENTASI: Implementasi Alpine.js untuk Modal --}}
  {{-- Membungkus section ini dengan x-data agar state modal tambah/edit dikendalikan di satu tempat --}}
  <div x-data="{
    openModal: {{ $hasErrors ? 'true' : 'false' }},
    editMode: {{ old('_method') === 'PUT' ? 'true' : 'false' }},
    currentId: '{{ old('current_id', '') }}',
    formAction: '{{ old('_method') === 'PUT' && old('current_id') ? url('finance/kategori-pagu/' . old('current_id')) : route('finance.kategori.store') }}',
    formKategori: @js(old('nama_kategori', '')),
    formDeskripsi: @js(old('deskripsi', '')),
    formPagu: @js(old('pagu_anggaran', '')),
    isSubmitting: false,

    init() {
      if (this.editMode && this.currentId) {
        this.formAction = '{{ url('finance/kategori-pagu') }}/' + this.currentId;
      }
    },

    openAddModal() {
      this.editMode = false;
      this.currentId = '';
      this.formAction = '{{ route('finance.kategori.store') }}';
      this.formKategori = '';
      this.formDeskripsi = '';
      this.formPagu = '';
      this.isSubmitting = false;
      this.openModal = true;
    },

    openEditModal(item) {
      this.editMode = true;
      this.currentId = item.id;
      this.formAction = '{{ url('finance/kategori-pagu') }}/' + item.id;
      this.formKategori = item.nama_kategori ?? '';
      this.formDeskripsi = item.deskripsi ?? '';
      this.formPagu = item.pagu_anggaran ? Math.floor(Number(item.pagu_anggaran)) : 0;
      this.isSubmitting = false;
      this.openModal = true;
    },

    closeModal() {
      this.openModal = false;
      this.isSubmitting = false;
    }
  }"
  x-on:keydown.escape.window="closeModal()">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
            Role: Finance
          </span>
          <span class="text-xs text-slate-400">&bull;</span>
          <span class="text-xs text-slate-500 font-medium">Dana BOS & Komite</span>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Master Kategori & Pagu Anggaran</h1>
        <p class="text-xs text-slate-500 mt-1">Kelola batas alokasi pengeluaran (pagu) untuk setiap kategori pos belanja pengajuan RAB sekolah.</p>
      </div>
      <button type="button" 
              @click="openAddModal()" 
              class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm shrink-0 transition-colors cursor-pointer">
        <i class="fa-solid fa-plus text-xs"></i> Tambah Kategori
      </button>
    </div>

    <!-- Ringkasan Statistik Singkat -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
      <div class="bg-white p-4.5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
        <div>
          <span class="text-[11px] font-bold uppercase text-slate-400 tracking-wider">Total Kategori</span>
          <div class="text-2xl font-bold text-slate-800 font-mono mt-0.5">{{ $kategoriList->count() }}</div>
          <span class="text-[11px] text-slate-400">Pos anggaran terdaftar</span>
        </div>
        <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg">
          <i class="fa-solid fa-layer-group"></i>
        </div>
      </div>

      <div class="bg-white p-4.5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
        <div>
          <span class="text-[11px] font-bold uppercase text-slate-400 tracking-wider">Total Alokasi Pagu</span>
          <div class="text-2xl font-bold text-emerald-600 font-mono mt-0.5">
            Rp {{ number_format((float) $kategoriList->sum('pagu_anggaran'), 0, ',', '.') }}
          </div>
          <span class="text-[11px] text-slate-400">Akumulasi pagu seluruh kategori</span>
        </div>
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
          <i class="fa-solid fa-wallet"></i>
        </div>
      </div>

      <div class="bg-white p-4.5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
        <div>
          <span class="text-[11px] font-bold uppercase text-slate-400 tracking-wider">Rata-rata Pagu</span>
          <div class="text-2xl font-bold text-blue-600 font-mono mt-0.5">
            Rp {{ number_format((float) ($kategoriList->count() > 0 ? $kategoriList->avg('pagu_anggaran') : 0), 0, ',', '.') }}
          </div>
          <span class="text-[11px] text-slate-400">Rerata alokasi per pos</span>
        </div>
        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
          <i class="fa-solid fa-chart-pie"></i>
        </div>
      </div>
    </div>

    <!-- Tabel Data Kategori & Pagu -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
          <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
            <tr>
              <th class="px-5 py-3.5 w-12 text-center">#</th>
              <th class="px-5 py-3.5">Nama Kategori</th>
              <th class="px-5 py-3.5">Deskripsi / Ruang Lingkup</th>
              <th class="px-5 py-3.5 text-right">Pagu Anggaran</th>
              <th class="px-5 py-3.5 text-center w-28">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($kategoriList as $idx => $kategori)
              <tr class="hover:bg-slate-50/80 transition-colors">
                <td class="px-5 py-4 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                <td class="px-5 py-4 font-semibold text-slate-800">
                  <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 shrink-0"></span>
                    <span>{{ $kategori->nama_kategori }}</span>
                  </div>
                </td>
                <td class="px-5 py-4 text-slate-500 max-w-sm">
                  <p class="truncate" title="{{ $kategori->deskripsi ?? 'Tidak ada deskripsi' }}">
                    {{ $kategori->deskripsi ?: '-' }}
                  </p>
                </td>
                <td class="px-5 py-4 text-right font-mono font-bold text-emerald-600 whitespace-nowrap">
                  Rp {{ number_format((float) ($kategori->pagu_anggaran ?? 0), 0, ',', '.') }}
                </td>
                <td class="px-5 py-4 text-center whitespace-nowrap">
                  <div class="flex items-center justify-center gap-1.5">
                    {{-- Tombol Edit: Mengirim seluruh data kategori via JSON safe (@js) --}}
                    <button type="button" 
                            @click="openEditModal(@js($kategori))" 
                            class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors cursor-pointer" 
                            title="Edit Kategori & Pagu">
                      <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </button>

                    {{-- Form Hapus Kategori --}}
                    <form action="{{ route('finance.kategori.destroy', $kategori->id) }}" 
                          method="POST" 
                          class="inline-block" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori anggaran \'{{ addslashes($kategori->nama_kategori) }}\'? Tindakan ini tidak dapat dibatalkan.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" 
                              class="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer" 
                              title="Hapus Kategori">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-5 py-12 text-center text-slate-400">
                  <div class="flex flex-col items-center justify-center gap-2">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-lg">
                      <i class="fa-regular fa-folder-open"></i>
                    </div>
                    <p class="font-medium text-slate-600">Belum ada data Kategori & Pagu Anggaran.</p>
                    <p class="text-[11px] text-slate-400">Klik tombol "Tambah Kategori" di atas untuk menambahkan pos pagu anggaran baru.</p>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Form Tambah / Edit Kategori (Tema Seragam SIRAB) -->
    <div x-show="openModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
      
      <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl border border-slate-200 text-left"
           @click.outside="closeModal()"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 scale-95"
           x-transition:enter-end="opacity-100 scale-100"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-xs">
              <i class="fa-solid fa-layer-group text-sm"></i>
            </div>
            <div>
              <h3 class="font-bold text-slate-800 text-base" x-text="editMode ? 'Edit Kategori & Pagu' : 'Tambah Kategori & Pagu Baru'"></h3>
              <p class="text-xs text-slate-500">Master Pos Anggaran Dana BOS / RKAS Sekolah</p>
            </div>
          </div>
          <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-200/50 transition-colors cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
          </button>
        </div>

        <!-- Modal Form: data-no-submit-lock='true' mencegah konflik double submit global -->
        <form :action="formAction" 
              method="POST" 
              data-no-submit-lock="true"
              @submit="isSubmitting = true">
          @csrf
          
          {{-- Method spoofing PUT dinamis yang hanya aktif saat editMode bernilai true --}}
          <input type="hidden" name="_method" value="PUT" :disabled="!editMode">
          <input type="hidden" name="current_id" :value="currentId" :disabled="!editMode">
          
          <div class="p-6 space-y-4">
            <!-- Input Nama Kategori -->
            <div>
              <label for="modal_nama_kategori" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Nama Kategori <span class="text-rose-500">*</span>
              </label>
              <input type="text" 
                     id="modal_nama_kategori"
                     name="nama_kategori" 
                     x-model="formKategori" 
                     required 
                     maxlength="255"
                     placeholder="Contoh: Pemeliharaan Sarana & Prasarana" 
                     class="w-full px-3.5 py-2.5 border rounded-xl text-xs transition-colors @if($hasErrors && $errors->has('nama_kategori')) border-rose-500 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-rose-50/20 @else border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @endif">
              @if($hasErrors && $errors->has('nama_kategori'))
                <p class="text-[11px] text-rose-500 mt-1.5 font-medium flex items-center gap-1">
                  <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                  {{ $errors->first('nama_kategori') }}
                </p>
              @endif
            </div>
            
            <!-- Input Pagu Anggaran -->
            <div>
              <label for="modal_pagu_anggaran" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Pagu Anggaran (Rp) <span class="text-rose-500">*</span>
              </label>
              <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-xs text-slate-400 font-semibold font-mono pointer-events-none">Rp</span>
                <input type="number" 
                       id="modal_pagu_anggaran"
                       name="pagu_anggaran" 
                       x-model="formPagu" 
                       required 
                       min="0" 
                       step="1"
                       placeholder="Contoh: 15000000" 
                       class="w-full pl-10 pr-3.5 py-2.5 border rounded-xl text-xs font-mono font-medium transition-colors @if($hasErrors && $errors->has('pagu_anggaran')) border-rose-500 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-rose-50/20 @else border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @endif">
              </div>
              <p class="text-[11px] text-slate-400 mt-1">Masukkan nominal angka bulat tanpa pemisah titik atau koma.</p>
              @if($hasErrors && $errors->has('pagu_anggaran'))
                <p class="text-[11px] text-rose-500 mt-1.5 font-medium flex items-center gap-1">
                  <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                  {{ $errors->first('pagu_anggaran') }}
                </p>
              @endif
            </div>

            <!-- Input Deskripsi -->
            <div>
              <label for="modal_deskripsi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Deskripsi / Cakupan Anggaran
              </label>
              <textarea id="modal_deskripsi"
                        name="deskripsi" 
                        x-model="formDeskripsi" 
                        rows="3" 
                        placeholder="Jelaskan ruang lingkup atau batasan pengeluaran kategori ini..." 
                        class="w-full px-3.5 py-2.5 border rounded-xl text-xs transition-colors @if($hasErrors && $errors->has('deskripsi')) border-rose-500 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-rose-50/20 @else border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @endif"></textarea>
              @if($hasErrors && $errors->has('deskripsi'))
                <p class="text-[11px] text-rose-500 mt-1.5 font-medium flex items-center gap-1">
                  <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                  {{ $errors->first('deskripsi') }}
                </p>
              @endif
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="px-6 py-3.5 border-t border-slate-200 bg-slate-50 flex justify-end gap-2.5 text-xs">
            <button type="button" 
                    @click="closeModal()" 
                    class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 font-semibold rounded-xl border border-slate-300 transition-colors cursor-pointer">
              Batal
            </button>
            <button type="submit" 
                    :disabled="isSubmitting"
                    class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-xs transition-colors cursor-pointer flex items-center gap-1.5 disabled:opacity-75 disabled:cursor-not-allowed">
              <span x-show="!isSubmitting" class="flex items-center gap-1.5">
                <i class="fa-solid fa-check text-xs"></i>
                <span x-text="editMode ? 'Simpan Perubahan' : 'Tambah Kategori'"></span>
              </span>
              <span x-show="isSubmitting" class="flex items-center gap-1.5" style="display: none;">
                <i class="fa-solid fa-circle-notch fa-spin text-xs"></i>
                <span>Menyimpan...</span>
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection