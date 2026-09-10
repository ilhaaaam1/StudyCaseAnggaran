@extends('layouts.app')

@section('title', 'Dashboard Finance - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Dashboard Finance (Tahap 1)</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
        Reviewer Anggaran Tahap 1 &bull; Role: Finance
      </div>
      <h1 class="text-2xl font-bold text-slate-900">Dashboard Finance</h1>
      <p class="text-sm text-slate-500 mt-1">
        Verifikasi ketersediaan pagu anggaran, validitas harga pasar, dan kelayakan item belanja sebelum diteruskan ke Pimpinan.
      </p>
    </div>
    <a href="{{ route('finance.antrean') }}"
       class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm transition-all shrink-0">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
      </svg>
      Lihat Antrean Pending ({{ $totalAntreanPending ?? 0 }})
    </a>
  </div>

  <!-- Metric Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-amber-50/70 border border-amber-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-amber-800 uppercase tracking-wider mb-1">ANTREAN REVIEW (PENDING)</div>
      <div class="text-2xl font-bold text-amber-700 font-mono">{{ $totalAntreanPending ?? 0 }}</div>
      <div class="text-xs text-amber-600 mt-1">Menunggu tindakan Finance</div>
    </div>

    <div class="bg-blue-50/70 border border-blue-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-blue-800 uppercase tracking-wider mb-1">ACC FINANCE</div>
      <div class="text-2xl font-bold text-blue-700 font-mono">{{ $totalAccFinance ?? 0 }}</div>
      <div class="text-xs text-blue-600 mt-1">Diteruskan ke Pimpinan</div>
    </div>

    <div class="bg-rose-50/70 border border-rose-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-rose-800 uppercase tracking-wider mb-1">DITOLAK FINANCE</div>
      <div class="text-2xl font-bold text-rose-700 font-mono">{{ $totalDitolakFinance ?? 0 }}</div>
      <div class="text-xs text-rose-600 mt-1">Ditolak pada tahap 1</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">TOTAL NOMINAL PENDING</div>
      <div class="text-2xl font-bold text-slate-800 font-mono">Rp {{ number_format($totalNominalPending ?? 0, 0, ',', '.') }}</div>
      <div class="text-xs text-slate-400 mt-1">Nilai antrean diverifikasi</div>
    </div>
  </div>

  <!-- Antrean Terbaru Table -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
      <div>
        <h2 class="text-lg font-bold text-slate-900">Antrean Pengajuan Pending Terbaru</h2>
        <p class="text-xs text-slate-500">Periksa dan berikan keputusan persetujuan Tahap 1</p>
      </div>
      <a href="{{ route('finance.antrean') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
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
            <th class="px-5 py-3 text-right">Estimasi Biaya</th>
            <th class="px-5 py-3 text-center">Aksi Review</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($antreanTerbaru ?? [] as $item)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3.5 font-mono font-semibold text-indigo-700">{{ $item->no_rab }}</td>
              <td class="px-5 py-3.5 font-medium text-slate-900">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</td>
              <td class="px-5 py-3.5 text-slate-600">{{ $item->divisi->nama_divisi ?? '-' }}</td>
              <td class="px-5 py-3.5 text-slate-800">{{ $item->judul_pengajuan }}</td>
              <td class="px-5 py-3.5 text-right font-mono font-semibold text-slate-800">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
              <td class="px-5 py-3.5 text-center">
                <a href="{{ route('finance.show', $item->id_pengajuan) }}" class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-semibold">
                  Review Tahap 1
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                Tidak ada antrean pending. Seluruh pengajuan telah diproses!
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
