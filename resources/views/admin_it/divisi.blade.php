@extends('layouts.app')

@section('title', 'Master Data Divisi - Administrator IT')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('admin-it.dashboard') }}" class="hover:text-slate-800">Administrator IT</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Master Divisi</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Master Data Unit Kerja / Divisi</h1>
      <p class="text-xs text-slate-500 mt-1">Kelola data unit kerja pengaju anggaran dalam organisasi.</p>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Tambah Divisi -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm h-fit">
      <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3">Tambah Divisi Baru</h2>
      <form action="{{ route('admin-it.divisi.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Unit / Divisi <span class="text-rose-500">*</span></label>
          <input type="text" name="nama_divisi" required placeholder="Contoh: Divisi Logistik"
                 class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-sm focus:border-indigo-500">
          @error('nama_divisi') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold">
          Simpan Divisi
        </button>
      </form>
    </div>

    <!-- Tabel Daftar Divisi -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
      <div class="p-4 border-b border-slate-100">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Daftar Divisi Terdaftar</h2>
      </div>
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3 w-12 text-center">ID</th>
            <th class="px-5 py-3">Nama Divisi</th>
            <th class="px-5 py-3 text-center">Jumlah Anggota</th>
            <th class="px-5 py-3 text-center w-28">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($divisiList ?? [] as $d)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3.5 text-center text-slate-400 font-mono">#{{ $d->id_divisi }}</td>
              <td class="px-5 py-3.5 font-semibold text-slate-900">{{ $d->nama_divisi }}</td>
              <td class="px-5 py-3.5 text-center font-mono text-slate-600">{{ $d->pengguna_count ?? 0 }} staf</td>
              <td class="px-5 py-3.5 text-center">
                <form action="{{ route('admin-it.divisi.destroy', $d->id_divisi) }}" method="POST" class="inline"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi {{ $d->nama_divisi }}?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold border border-rose-200">
                    Hapus
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-5 py-8 text-center text-slate-400">Belum ada data divisi.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
