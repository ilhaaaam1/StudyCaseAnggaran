{{-- PRESENTASI: Extend Layout Utama --}}
{{-- Perbaikan bug blank page dengan memanggil layout utama (app.blade.php) --}}
@extends('layouts.app')

@section('title', 'Master Kategori & Pagu Anggaran - SIRAB')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('finance.dashboard') }}" class="hover:text-slate-800">Dashboard Finance</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Master Kategori & Pagu</span>
  </div>

  @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-5 text-[13px] font-medium flex items-center justify-between">
      <div class="flex items-center gap-2.5">
        <i class="fa-solid fa-circle-check text-green-500 text-[15px]"></i>
        <span>{{ session('success') }}</span>
      </div>
      <button onclick="this.parentElement.remove()" class="text-green-800 hover:text-green-900"><i class="fa-solid fa-xmark"></i></button>
    </div>
  @endif

  @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-5 text-[13px] font-medium">
      <div class="flex items-center gap-2.5 mb-2">
        <i class="fa-solid fa-circle-exclamation text-red-500 text-[15px]"></i>
        <span class="font-bold">Terjadi Kesalahan!</span>
      </div>
      <ul class="list-disc pl-8">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- PRESENTASI: Implementasi Alpine.js untuk Modal --}}
  {{-- Membungkus section ini dengan x-data agar kita bisa mengendalikan state modal tambah/edit di satu tempat --}}
  <div x-data="{ openModal: false, editMode: false, currentId: '', formAction: '{{ route('finance.kategori.store') }}', formMethod: 'POST', formKategori: '', formDeskripsi: '', formPagu: '' }">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Master Kategori & Pagu Anggaran</h1>
        <p class="text-xs text-slate-500 mt-1">Kelola batas pengeluaran (pagu) untuk setiap kategori pengajuan RAB di sekolah.</p>
      </div>
      <button type="button" @click="openModal = true; editMode = false; formAction = '{{ route('finance.kategori.store') }}'; formMethod = 'POST'; formKategori = ''; formDeskripsi = ''; formPagu = '';" 
              class="bg-[#2b337c] hover:bg-[#1e255e] text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm shrink-0 transition-colors">
        <i class="fa-solid fa-plus"></i> Tambah Kategori
      </button>
    </div>

    <!-- Tabel Data -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
          <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
            <tr>
              <th class="px-5 py-3.5 w-10 text-center">No</th>
              <th class="px-5 py-3.5">Nama Kategori</th>
              <th class="px-5 py-3.5">Deskripsi</th>
              <th class="px-5 py-3.5 text-right">Pagu Anggaran</th>
              <th class="px-5 py-3.5 text-center w-48">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            @forelse($kategoriList as $idx => $kategori)
              <tr class="hover:bg-slate-50">
                <td class="px-5 py-3.5 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $kategori->nama_kategori }}</td>
                <td class="px-5 py-3.5 text-slate-500 max-w-xs truncate" title="{{ $kategori->deskripsi }}">{{ $kategori->deskripsi ?? '-' }}</td>
                <td class="px-5 py-3.5 text-right font-mono font-bold text-emerald-600">Rp {{ number_format($kategori->pagu_anggaran, 0, ',', '.') }}</td>
                <td class="px-5 py-3.5 text-center space-x-2">
                  <div class="inline-flex items-center justyfy-center gap-2">
                    <button type="button" 
                            @click="openModal = true; editMode = true; formAction = '{{ route('finance.kategori.update', $kategori->id) }}'; formMethod = 'PUT'; formKategori = @js($kategori->nama_kategori); formDeskripsi = @js($kategori->deskripsi ?? ''); formPagu = @js($kategori->pagu_anggaran);" 
                            class="bg-[#2b337c] hover:bg-[#1e255e] text-white px-3 py-1.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.2 shadow-sm transition-colors cursor-pointer"
                            title="Edit Kategori">
                      <i class="fa-solid fa-pen-to-square"></i>
                      <span>Edit</span>
                    </button>
                    <form action="{{ route('finance.kategori.destroy', $kategori->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" 
                      class="bg-rose-600 hover:bg-rose-700 text-white px-3 py-1.5 rounded-xl text-xs font-semibold inline-flex items-center gap-1.2 shadow-smtransition-colors cursor-pointer" 
                      title="Hapus Kategori">
                        <i class="fa-solid fa-trash-can"></i>
                        <span>Hapus</span>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-5 py-10 text-center text-slate-400">
                  Belum ada data Kategori & Pagu Anggaran.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Form Tambah/Edit -->
    <div x-show="openModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
      
      <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-xl"
           @click.outside="openModal = false"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
           x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
           x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
          <h3 class="font-bold text-slate-800 text-lg" x-text="editMode ? 'Edit Kategori & Pagu' : 'Tambah Kategori & Pagu Baru'"></h3>
          <button @click="openModal = false" class="text-slate-400 hover:text-slate-600 cursor-pointer">
            <i class="fa-solid fa-xmark text-lg"></i>
          </button>
        </div>

        <form :action="formAction" method="POST">
          @csrf
          <input type="hidden" name="_method" :value="formMethod">
          
          <div class="p-6 space-y-4">
            <div>
              <label class="block text-[13px] font-medium text-slate-700 mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
              <input type="text" name="nama_kategori" x-model="formKategori" required placeholder="Contoh: Pemeliharaan Sarana & Prasarana" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            
            <div>
              <label class="block text-[13px] font-medium text-slate-700 mb-1.5">Pagu Anggaran (Rp) <span class="text-red-500">*</span></label>
              <input type="number" name="pagu_anggaran" x-model="formPagu" required min="0" placeholder="Contoh: 15000000" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-mono">
              <p class="text-[10px] text-slate-500 mt-1">Masukkan angka tanpa titik atau koma.</p>
            </div>

            <div>
              <label class="block text-[13px] font-medium text-slate-700 mb-1.5">Deskripsi / Keterangan Tambahan</label>
              <textarea name="deskripsi" x-model="formDeskripsi" rows="3" placeholder="Jelaskan cakupan kategori ini..." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>
          </div>

          <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
            <button type="button" @click="openModal = false" class="px-4 py-2 text-[13px] font-semibold text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer">Batal</button>
            <button type="submit" class="px-5 py-2 text-[13px] font-semibold bg-[#2b337c] hover:bg-[#1e255e] text-white rounded-lg shadow-sm transition-colors cursor-pointer">Simpan Kategori</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection