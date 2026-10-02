@extends('layouts.app')

@section('title', 'Dashboard Admin IT - SIRAB')
@section('breadcrumb', 'Dashboard Administrator')

@section('content')
  <!-- Alert Banner -->
  <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between mb-5 text-[13px] font-medium">
    <div class="flex items-center gap-2.5">
      <i class="fa-solid fa-circle-check text-[15px] text-green-500"></i>
      <span>Selamat datang kembali, {{ Auth::user()->nama_lengkap }}!</span>
    </div>
    <button type="button" onclick="this.parentElement.remove()" class="text-green-800 hover:text-green-900 cursor-pointer text-sm">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4">
    <div>
      <span class="text-[11px] uppercase text-[#2b337c] font-bold tracking-wide">Administrator IT &bull; Role: Admin</span>
      <h2 class="text-[22px] text-slate-800 font-bold mt-0.5">Dashboard IT &amp; Manajemen</h2>
      <p class="text-[13px] text-slate-500 mt-0.5">
        Kelola pengguna, pengaturan sistem, master divisi, dan pantau aktivitas aplikasi.
      </p>
    </div>
    <a href="{{ route('admin-it.users.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-lg text-[13px] font-semibold flex items-center gap-2 shadow-sm shrink-0 transition-colors">
      <i class="fa-solid fa-users"></i> Kelola Pengguna
    </a>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
    <!-- Card 1: Total Pengguna -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
      <div>
        <div class="flex items-start justify-between gap-2 mb-2">
          <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Pengguna</span>
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm shrink-0">
            <i class="fa-solid fa-users text-[15px]"></i>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
          {{ number_format($totalUsers ?? 0) }}
        </div>
      </div>
      <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
        <i class="fa-regular fa-id-badge text-slate-400 text-[11px] shrink-0"></i>
        <span>Seluruh akun terdaftar</span>
      </div>
    </div>

    <!-- Card 2: Role Pimpinan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
      <div>
        <div class="flex items-start justify-between gap-2 mb-2">
          <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Role Pimpinan</span>
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm shrink-0">
            <i class="fa-solid fa-user-tie text-[15px]"></i>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
          {{ number_format($countPimpinan ?? 0) }}
        </div>
      </div>
      <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 shrink-0"></span>
        <span>Akun reviewer final</span>
      </div>
    </div>

    <!-- Card 3: Role Finance -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
      <div>
        <div class="flex items-start justify-between gap-2 mb-2">
          <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Role Finance</span>
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shrink-0">
            <i class="fa-solid fa-calculator text-[15px]"></i>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
          {{ number_format($countFinance ?? 0) }}
        </div>
      </div>
      <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
        <span>Akun reviewer tahap 1</span>
      </div>
    </div>

    <!-- Card 4: Role Staff -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
      <div>
        <div class="flex items-start justify-between gap-2 mb-2">
          <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Role Staff</span>
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
            <i class="fa-solid fa-user-pen text-[15px]"></i>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
          {{ number_format($countStaff ?? 0) }}
        </div>
      </div>
      <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
        <span>Akun pemohon RAB</span>
      </div>
    </div>
  </div>

  <!-- Table Section -->
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
      <div>
        <h3 class="text-[15px] font-bold text-slate-800">Aktivitas Sistem Terkini</h3>
        <p class="text-xs text-slate-500 mt-0.5">Log aktivitas terbaru dari seluruh pengguna</p>
      </div>
      <a href="{{ route('admin-it.log.index') }}" class="text-[13px] text-blue-600 font-semibold hover:underline">
        Lihat Semua Log &rarr;
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead>
          <tr>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide">Waktu</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide">Pengguna</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide">Aktivitas</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide text-right">IP Address</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentLogs ?? [] as $log)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="text-[13px] text-slate-800 px-3 py-3.5 border-b border-slate-200">{{ $log->created_at->format('d/m/Y H:i') }}</td>
              <td class="text-[13px] text-slate-800 px-3 py-3.5 border-b border-slate-200 font-medium">{{ $log->user->nama_lengkap ?? 'Sistem' }}</td>
              <td class="text-[13px] text-slate-800 px-3 py-3.5 border-b border-slate-200">{{ $log->activity }}</td>
              <td class="text-[13px] text-slate-800 px-3 py-3.5 border-b border-slate-200 text-right font-mono text-[11px]">{{ $log->ip_address }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-[13px] text-slate-500 px-3 py-8 border-b border-slate-200 text-center">
                Tidak ada data aktivitas sistem saat ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
