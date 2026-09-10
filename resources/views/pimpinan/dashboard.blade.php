@extends('layouts.app')

@section('title', 'Dashboard Pimpinan - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Dashboard Pimpinan (Reviewer Final)</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
        Reviewer Final / Tahap 2 &bull; Role: Pimpinan
      </div>
      <h1 class="text-2xl font-bold text-slate-900">Dashboard Pimpinan</h1>
      <p class="text-sm text-slate-500 mt-1">
        Persetujuan akhir pengajuan RAB yang telah diverifikasi kelayakannya oleh tim Finance (ACC Finance).
      </p>
    </div>
    <a href="{{ route('pimpinan.antrean') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm transition-all shrink-0">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
      </svg>
      Antrean Menunggu Keputusan ({{ $totalAntreanAccFinance ?? 0 }})
    </a>
  </div>

  <!-- Metric Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-blue-50/70 border border-blue-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-blue-800 uppercase tracking-wider mb-1">ANTREAN FINAL (ACC FINANCE)</div>
      <div class="text-2xl font-bold text-blue-700 font-mono">{{ $totalAntreanAccFinance ?? 0 }}</div>
      <div class="text-xs text-blue-600 mt-1">Menunggu persetujuan Pimpinan</div>
    </div>

    <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider mb-1">ACC FINAL (DISETUJUI)</div>
      <div class="text-2xl font-bold text-emerald-700 font-mono">{{ $totalAccFinal ?? 0 }}</div>
      <div class="text-xs text-emerald-600 mt-1">Pengajuan sah &amp; terealisasi</div>
    </div>

    <div class="bg-rose-50/70 border border-rose-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-rose-800 uppercase tracking-wider mb-1">DITOLAK PIMPINAN</div>
      <div class="text-2xl font-bold text-rose-700 font-mono">{{ $totalDitolakPimpinan ?? 0 }}</div>
      <div class="text-xs text-rose-600 mt-1">Ditolak pada tahap final</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">TOTAL ANGGARAN ACC FINAL</div>
      <div class="text-2xl font-bold text-indigo-700 font-mono">Rp {{ number_format($totalAnggaranDisetujui ?? 0, 0, ',', '.') }}</div>
      <div class="text-xs text-slate-400 mt-1">Akumulasi anggaran disetujui</div>
    </div>
  </div>

  <!-- Antrean Terbaru -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
      <div>
        <h2 class="text-lg font-bold text-slate-900">Antrean Persetujuan Final Terkini</h2>
        <p class="text-xs text-slate-500">Berkas yang sudah lolos uji Finance dan memerlukan tanda tangan persetujuan Anda</p>
      </div>
      <a href="{{ route('pimpinan.antrean') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
        Buka Semua Antrean &rarr;
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3">No. RAB</th>
            <th class="px-5 py-3">Pemohon</th>
            <th class="px-5 py-3">Unit Kerja</th>
            <th class="px-5 py-3">Judul Pengajuan</th>
            <th class="px-5 py-3 text-right">Estimasi Anggaran</th>
            <th class="px-5 py-3 text-center">Keputusan Final</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($antreanTerbaru ?? [] as $item)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3.5 font-mono font-bold text-indigo-700">{{ $item->no_rab }}</td>
              <td class="px-5 py-3.5 font-medium text-slate-900">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</td>
              <td class="px-5 py-3.5 text-slate-600">{{ $item->divisi->nama_divisi ?? '-' }}</td>
              <td class="px-5 py-3.5 text-slate-800">{{ $item->judul_pengajuan }}</td>
              <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
              <td class="px-5 py-3.5 text-center">
                <a href="{{ route('pimpinan.show', $item->id_pengajuan) }}" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold">
                  Tinjau &amp; ACC Final
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                Tidak ada antrean ACC Finance yang menunggu review.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
