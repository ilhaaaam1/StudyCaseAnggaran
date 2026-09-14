@extends('layouts.app')

@section('title', 'Dashboard Staf Pemohon - SIRAB Kelompok-3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Dashboard Staf Pemohon</span>
  </div>

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
        Portal Pemohon RAB &bull; Role: Staff
      </div>
      <h1 class="text-2xl font-bold text-slate-900">Dashboard Staf Pemohon</h1>
      <p class="text-sm text-slate-500 mt-1">
        Selamat datang, <span class="font-semibold text-slate-700">{{ Auth::user()->nama_lengkap }}</span> ({{ Auth::user()->jabatan ?? 'Staf' }} &bull; {{ Auth::user()->divisi->nama_divisi ?? 'Unit Kerja' }}).
      </p>
    </div>
    <a href="{{ route('staff.rab.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm transition-all shrink-0">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
      </svg>
      + Buat Pengajuan RAB
    </a>
  </div>

  <!-- Metric Cards Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">TOTAL DIAJUKAN</div>
      <div class="text-2xl font-bold text-slate-800 font-mono">Rp {{ number_format($totalAnggaranDiajukan ?? 0, 0, ',', '.') }}</div>
      <div class="text-xs text-slate-400 mt-1">{{ $totalPengajuan ?? 0 }} berkas pengajuan</div>
    </div>

    <div class="bg-amber-50/70 border border-amber-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-amber-800 uppercase tracking-wider mb-1">REVIEW FINANCE (PENDING)</div>
      <div class="text-2xl font-bold text-amber-700 font-mono">{{ $totalPending ?? 0 }}</div>
      <div class="text-xs text-amber-600 mt-1">Menunggu verifikasi Tahap 1</div>
    </div>

    <div class="bg-blue-50/70 border border-blue-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-blue-800 uppercase tracking-wider mb-1">REVIEW PIMPINAN (ACC FINANCE)</div>
      <div class="text-2xl font-bold text-blue-700 font-mono">{{ $totalAccFinance ?? 0 }}</div>
      <div class="text-xs text-blue-600 mt-1">Lolos Tahap 1, antre Tahap 2</div>
    </div>

    <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider mb-1">DISETUJUI (ACC FINAL)</div>
      <div class="text-2xl font-bold text-emerald-700 font-mono">{{ $totalAccFinal ?? 0 }}</div>
      <div class="text-xs text-emerald-600 mt-1">Anggaran disetujui penuh</div>
    </div>
  </div>

  <!-- Pengajuan Terbaru Table -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
      <div>
        <h2 class="text-lg font-bold text-slate-900">Pengajuan Terbaru Anda</h2>
        <p class="text-xs text-slate-500">Daftar pengajuan terkini yang diajukan oleh akun Anda</p>
      </div>
      <a href="{{ route('staff.riwayat') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
        Lihat Semua &rarr;
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-xs">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3">No. RAB</th>
            <th class="px-5 py-3">Judul Pengajuan</th>
            <th class="px-5 py-3">Estimasi Total</th>
            <th class="px-5 py-3 text-center">Status Alur</th>
            <th class="px-5 py-3 text-center">Tanggal</th>
            <th class="px-5 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($pengajuanTerbaru ?? [] as $item)
            <tr class="hover:bg-slate-50/70 transition-colors">
              <td class="px-5 py-3.5 font-mono font-semibold text-indigo-700">{{ $item->no_rab }}</td>
              <td class="px-5 py-3.5 text-slate-900 font-medium">{{ $item->judul_pengajuan }}</td>
              <td class="px-5 py-3.5 font-mono font-semibold text-slate-800">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
              <td class="px-5 py-3.5 text-center">
                  <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $item->status->badge() }}">
                    {{ $item->status->label() }}
                  </span>
              </td>
              <td class="px-5 py-3.5 text-center text-slate-500 font-mono">{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}</td>
              <td class="px-5 py-3.5 text-center">
                <a href="{{ route('staff.rab.show', $item->id_pengajuan) }}" 
                   class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200">
                  Detail & Track
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                Belum ada pengajuan RAB. Klik tombol "+ Buat Pengajuan RAB" di atas untuk memulai.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
