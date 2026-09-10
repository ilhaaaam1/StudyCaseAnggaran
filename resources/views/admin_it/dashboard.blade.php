@extends('layouts.app')

@section('title', 'Dashboard Administrator IT - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Dashboard Administrator IT</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
        Manajemen Sistem &amp; Akses &bull; Role: Admin IT
      </div>
      <h1 class="text-2xl font-bold text-slate-900">Dashboard Administrator IT</h1>
      <p class="text-sm text-slate-500 mt-1">
        Pusat kendali hak akses pengguna (Staff, Finance, Pimpinan, Admin IT) dan pengelolaan master data unit kerja divisi.
      </p>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('admin-it.users.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm">
        + Daftarkan Pengguna
      </a>
      <a href="{{ route('admin-it.divisi.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold shadow-sm">
        Master Divisi
      </a>
    </div>
  </div>

  <!-- Metric Cards 4 Role -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">TOTAL PENGGUNA</div>
      <div class="text-2xl font-bold text-slate-800 font-mono">{{ $totalPengguna ?? 0 }}</div>
      <div class="text-xs text-slate-400 mt-1">Seluruh akun aktif</div>
    </div>

    <div class="bg-blue-50/70 border border-blue-200 rounded-xl p-5 shadow-sm">
      <div class="text-[10px] font-bold text-blue-800 uppercase tracking-wider mb-1">STAFF (PEMOHON)</div>
      <div class="text-2xl font-bold text-blue-700 font-mono">{{ $totalStaff ?? 0 }}</div>
      <div class="text-xs text-blue-600 mt-1">Pembuat pengajuan RAB</div>
    </div>

    <div class="bg-amber-50/70 border border-amber-200 rounded-xl p-5 shadow-sm">
      <div class="text-[10px] font-bold text-amber-800 uppercase tracking-wider mb-1">FINANCE</div>
      <div class="text-2xl font-bold text-amber-700 font-mono">{{ $totalFinance ?? 0 }}</div>
      <div class="text-xs text-amber-600 mt-1">Reviewer Tahap 1</div>
    </div>

    <div class="bg-indigo-50/70 border border-indigo-200 rounded-xl p-5 shadow-sm">
      <div class="text-[10px] font-bold text-indigo-800 uppercase tracking-wider mb-1">PIMPINAN</div>
      <div class="text-2xl font-bold text-indigo-700 font-mono">{{ $totalPimpinan ?? 0 }}</div>
      <div class="text-xs text-indigo-600 mt-1">Reviewer Final Tahap 2</div>
    </div>

    <div class="bg-slate-100 border border-slate-300 rounded-xl p-5 shadow-sm">
      <div class="text-[10px] font-bold text-slate-700 uppercase tracking-wider mb-1">ADMIN IT</div>
      <div class="text-2xl font-bold text-slate-800 font-mono">{{ $totalAdminIt ?? 0 }}</div>
      <div class="text-xs text-slate-500 mt-1">Pengelola sistem</div>
    </div>
  </div>

  <!-- Pengguna Terbaru -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
      <div>
        <h2 class="text-lg font-bold text-slate-900">Pengguna Terdaftar Terkini</h2>
        <p class="text-xs text-slate-500">Daftar akun pengguna terbaru di dalam sistem</p>
      </div>
      <a href="{{ route('admin-it.users.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
        Kelola Semua Pengguna &rarr;
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3">Nama Lengkap</th>
            <th class="px-5 py-3">Email</th>
            <th class="px-5 py-3">Unit Kerja</th>
            <th class="px-5 py-3">Jabatan</th>
            <th class="px-5 py-3 text-center">Role Sistem</th>
            <th class="px-5 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($penggunaTerbaru ?? [] as $user)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3.5 font-semibold text-slate-900">{{ $user->nama_lengkap }}</td>
              <td class="px-5 py-3.5 font-mono text-slate-600">{{ $user->email }}</td>
              <td class="px-5 py-3.5 text-slate-700">{{ $user->divisi->nama_divisi ?? '-' }}</td>
              <td class="px-5 py-3.5 text-slate-600">{{ $user->jabatan ?? '-' }}</td>
              <td class="px-5 py-3.5 text-center">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                  {{ $user->role === 'admin_it' || $user->role === 'admin' ? 'bg-slate-800 text-white' : ($user->role === 'pimpinan' ? 'bg-indigo-100 text-indigo-800' : ($user->role === 'finance' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800')) }}">
                  {{ $user->role }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-center">
                <a href="{{ route('admin-it.users.edit', $user->id_pengguna) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold">
                  Edit
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada data pengguna.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
