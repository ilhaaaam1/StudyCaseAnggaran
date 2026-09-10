@extends('layouts.app')

@section('title', 'Antrean Persetujuan RAB - SIRAB')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6">
    SIRAB / <span class="text-slate-800 font-medium">Antrean Persetujuan RAB</span>
  </div>

  <!-- Page Header -->
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-900">Antrean Persetujuan RAB (Admin)</h1>
    <p class="text-sm text-slate-500 mt-1">
      Daftar usulan pengajuan RAB berstatus <span class="font-semibold text-amber-600 font-mono">diajukan</span> (pending) yang menunggu evaluasi dan persetujuan Admin
    </p>
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2 shadow-sm">
      <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
      </svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  <!-- Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-amber-50/60 border border-amber-200 rounded-lg p-4 shadow-sm">
      <div class="text-xs font-semibold text-amber-700 mb-1">
        Menunggu Persetujuan (Pending)
      </div>
      <div class="text-2xl font-bold text-amber-800 font-mono-num">{{ $pendingCount }}</div>
      <div class="text-[11px] text-amber-600 mt-1">Status: diajukan</div>
    </div>
    <div class="bg-emerald-50/60 border border-emerald-200 rounded-lg p-4 shadow-sm">
      <div class="text-xs font-semibold text-emerald-700 mb-1">
        Disetujui
      </div>
      <div class="text-2xl font-bold text-emerald-800 font-mono-num">{{ $approvedCount }}</div>
      <div class="text-[11px] text-emerald-600 mt-1">Status: disetujui</div>
    </div>
    <div class="bg-rose-50/60 border border-rose-200 rounded-lg p-4 shadow-sm">
      <div class="text-xs font-semibold text-rose-700 mb-1">
        Ditolak / Revisi
      </div>
      <div class="text-2xl font-bold text-rose-800 font-mono-num">{{ $rejectedCount }}</div>
      <div class="text-[11px] text-rose-600 mt-1">Status: ditolak / revisi</div>
    </div>
  </div>

  <!-- Data Table Antrean Persetujuan -->
  <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden mb-8">
    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/60">
      <h2 class="text-xs font-bold text-slate-700 tracking-wide uppercase">
        Dokumen Pengajuan Menunggu Review ({{ $pendingList->total() }})
      </h2>
      <span class="text-xs text-slate-500">Tabel: <code>pengajuan_rab</code> (status = 'diajukan')</span>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-left whitespace-nowrap">
        <thead class="text-[10px] text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-semibold">
          <tr>
            <th class="px-5 py-4 w-12 text-center">NO.</th>
            <th class="px-5 py-4">NO. RAB</th>
            <th class="px-5 py-4">JUDUL PENGAJUAN</th>
            <th class="px-5 py-4">DIVISI</th>
            <th class="px-5 py-4">PEMOHON</th>
            <th class="px-5 py-4">TANGGAL PENGAJUAN</th>
            <th class="px-5 py-4">ESTIMASI TOTAL</th>
            <th class="px-5 py-4">PRIORITAS</th>
            <th class="px-5 py-4 text-center">AKSI EVALUASI</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
          @forelse($pendingList as $idx => $item)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-4 text-center text-slate-400 text-xs">
                {{ $pendingList->firstItem() + $idx }}
              </td>
              <td class="px-5 py-4 font-mono font-bold text-slate-700 text-xs">
                {{ $item->no_rab }}
              </td>
              <td class="px-5 py-4 text-slate-800 font-medium max-w-xs truncate" title="{{ $item->judul_pengajuan }}">
                {{ $item->judul_pengajuan }}
              </td>
              <td class="px-5 py-4 text-slate-600 text-xs">
                {{ $item->divisi->nama_divisi ?? '-' }}
              </td>
              <td class="px-5 py-4 text-slate-600 text-xs">
                {{ $item->pengguna->nama_lengkap ?? '-' }}
              </td>
              <td class="px-5 py-4 text-slate-400 font-mono text-xs">
                {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d F Y H:i') : '-' }}
              </td>
              <td class="px-5 py-4 font-mono-num font-bold text-slate-900">
                Rp {{ number_format((float)$item->estimasi_total, 0, ',', '.') }}
              </td>
              <td class="px-5 py-4">
                @php
                  $priorityClasses = match($item->prioritas) {
                    'tinggi' => 'bg-rose-50 text-rose-600 border-rose-100',
                    'sedang' => 'bg-amber-50 text-amber-600 border-amber-100',
                    'rendah' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                    default => 'bg-slate-50 text-slate-600 border-slate-100',
                  };
                @endphp
                <span class="inline-block px-2 py-0.5 text-[10px] font-semibold border rounded {{ $priorityClasses }}">
                  {{ ucfirst($item->prioritas) }}
                </span>
              </td>
              <td class="px-5 py-4 text-center">
                <a href="{{ route('pengajuan.show', $item->id_pengajuan) }}"
                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-3 py-1.5 rounded shadow-sm transition-colors">
                  Evaluasi &rarr;
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-5 py-12 text-center text-slate-400 text-xs">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="font-medium text-slate-600">Semua usulan telah ditinjau</p>
                <p class="mt-1">Tidak ada pengajuan RAB yang sedang berstatus pending ('diajukan') saat ini.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination Footer -->
    <div class="px-6 py-4 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white text-xs">
      <div class="text-slate-500">
        Menampilkan {{ $pendingList->firstItem() ?? 0 }} - {{ $pendingList->lastItem() ?? 0 }} dari {{ $pendingList->total() }} dokumen pending
      </div>
      <div>
        {{ $pendingList->links() }}
      </div>
    </div>
  </div>
@endsection
