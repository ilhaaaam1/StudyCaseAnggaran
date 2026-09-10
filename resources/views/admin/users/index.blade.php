@extends('layouts.app')

@section('title', 'Manajemen Akun Staff - SIRAB Kelompok-3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-800">Panel Administrator</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Manajemen Akun Staff</span>
  </div>

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
        Administrator &bull; Kontrol Akses Pengguna
      </div>
      <h1 class="text-2xl font-bold text-slate-900">Manajemen Akun Staff (User)</h1>
      <p class="text-sm text-slate-500 mt-1">
        Kelola dan daftarkan akun baru untuk pemohon anggaran dari seluruh unit kerja / divisi.
      </p>
    </div>
    <div class="flex items-center gap-3 shrink-0">
      <a href="{{ route('admin.dashboard') }}" 
         class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-sm">
        &larr; Dashboard Admin
      </a>
      <a href="{{ route('admin.users.create') }}" 
         class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Staff Baru
      </a>
    </div>
  </div>

  <!-- Quick Metrics Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Akun Staff</div>
      <div class="text-2xl font-bold text-slate-800 font-mono mb-1">{{ $totalStaff }} Pengguna</div>
      <div class="text-xs text-slate-400">Akun dengan hak akses Pemohon RAB</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="text-xs font-bold text-indigo-600 uppercase tracking-wider mb-1">Unit Kerja / Divisi</div>
      <div class="text-2xl font-bold text-indigo-900 font-mono mb-1">{{ $divisiList->count() }} Divisi</div>
      <div class="text-xs text-slate-400">Terdaftar di master sistem</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Status Autentikasi</div>
      <div class="text-2xl font-bold text-emerald-700 font-mono mb-1 flex items-center gap-2">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        Aktif
      </div>
      <div class="text-xs text-slate-400">Role-Based Access Control (RBAC)</div>
    </div>
  </div>

  <!-- Table Card: Daftar Staff -->
  <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h2 class="font-semibold text-slate-800 text-sm sm:text-base">Daftar Akun Staff Aktif</h2>
        <p class="text-xs text-slate-400 mt-0.5">Seluruh staf yang memiliki wewenang membuat dan mengajukan RAB</p>
      </div>

      <!-- Filter & Search Controls -->
      <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-2.5">
        <div class="relative">
          <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, email, jabatan..."
                 class="w-48 sm:w-60 pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-200 focus:border-indigo-500 bg-slate-50 text-slate-700">
          <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>

        <select name="id_divisi" onchange="this.form.submit()"
                class="border border-slate-200 bg-slate-50 text-xs rounded-lg px-2.5 py-1.5 text-slate-700 cursor-pointer focus:border-indigo-500">
          <option value="">-- Semua Divisi --</option>
          @foreach($divisiList as $div)
            <option value="{{ $div->id_divisi }}" {{ (string) $divisiFilter === (string) $div->id_divisi ? 'selected' : '' }}>
              {{ $div->nama_divisi }}
            </option>
          @endforeach
        </select>

        @if($search || $divisiFilter)
          <a href="{{ route('admin.users.index') }}" 
             class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold transition-colors" title="Reset Filter">
            ✕ Reset
          </a>
        @endif
      </form>
    </div>

    <div class="overflow-x-auto table-container">
      <table class="w-full text-left text-sm whitespace-nowrap">
        <thead class="text-[10px] text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-semibold">
          <tr>
            <th class="px-5 py-3.5 w-12 text-center">No.</th>
            <th class="px-5 py-3.5">Nama Lengkap &amp; Akun</th>
            <th class="px-5 py-3.5">Alamat Email</th>
            <th class="px-5 py-3.5">Divisi / Unit Kerja</th>
            <th class="px-5 py-3.5">Jabatan</th>
            <th class="px-5 py-3.5 text-center">Role Akses</th>
            <th class="px-5 py-3.5 text-center">Terdaftar</th>
            <th class="px-5 py-3.5 text-center w-36">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs">
          @forelse($staffList as $index => $staff)
            <tr class="hover:bg-slate-50/80 transition-colors">
              <td class="px-5 py-4 text-center font-mono text-slate-400">
                {{ $staffList->firstItem() ? ($staffList->firstItem() + $index) : ($index + 1) }}
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-xs flex items-center justify-center shrink-0 border border-indigo-200">
                    {{ strtoupper(substr($staff->nama_lengkap ?? 'ST', 0, 2)) }}
                  </div>
                  <div>
                    <div class="font-semibold text-slate-900">{{ $staff->nama_lengkap }}</div>
                    <div class="text-[10px] text-slate-400 font-mono">ID: #{{ $staff->id_pengguna }}</div>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4 text-slate-700 font-mono">
                {{ $staff->email }}
              </td>
              <td class="px-5 py-4">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                  {{ $staff->divisi->nama_divisi ?? '-' }}
                </span>
              </td>
              <td class="px-5 py-4 text-slate-700">
                {{ $staff->jabatan ?? '-' }}
              </td>
              <td class="px-5 py-4 text-center">
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full border border-blue-100">
                  <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Staff (User)
                </span>
              </td>
              <td class="px-5 py-4 text-center font-mono text-slate-400 text-[11px]">
                {{ $staff->created_at ? $staff->created_at->format('d/m/Y') : '-' }}
              </td>
              <td class="px-5 py-4 text-center">
                <div class="flex items-center justify-center gap-1.5">
                  <!-- Tombol Edit -->
                  <a href="{{ route('admin.users.edit', $staff->id_pengguna) }}" 
                     class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 transition-colors shadow-sm"
                     title="Edit Akun Staff">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                  </a>

                  <!-- Tombol Hapus -->
                  <form action="{{ route('admin.users.destroy', $staff->id_pengguna) }}" method="POST" class="inline"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun staff {{ $staff->nama_lengkap }}? Tindakan ini tidak dapat dibatalkan.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors shadow-sm cursor-pointer"
                            title="Hapus Akun Staff">
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                <svg class="mx-auto h-10 w-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <div class="text-sm font-medium text-slate-600">Belum ada akun staff ditemukan</div>
                <p class="text-xs text-slate-400 mt-1">Silakan tambahkan akun staff baru menggunakan tombol di atas.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($staffList->hasPages())
      <div class="px-6 py-4 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white text-xs">
        <div class="text-slate-500">
          Menampilkan {{ $staffList->firstItem() ?? 0 }} - {{ $staffList->lastItem() ?? 0 }} dari {{ $staffList->total() }} akun staff
        </div>
        <div>
          {{ $staffList->links() }}
        </div>
      </div>
    @endif
  </div>
@endsection
