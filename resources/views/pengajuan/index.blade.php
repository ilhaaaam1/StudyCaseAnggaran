@extends('layouts.app')

@section('title', 'Daftar Pengajuan RAB - SIRAB')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6">
    SIRAB / <span class="text-slate-800 font-medium">Pengajuan RAB</span>
  </div>

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">
        Daftar Pengajuan Anggaran (RAB)
      </h1>
      <p class="text-sm text-slate-500 mt-1">
        Kelola dan pantau seluruh usulan rencana anggaran biaya dari berbagai divisi
      </p>
    </div>
    <div class="flex items-center gap-3">
      <a href="{{ route('pengajuan.persetujuan.index') }}"
         class="bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 px-3.5 py-2 rounded-md text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-sm">
        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
        Antrean Persetujuan (Admin)
      </a>
      <a href="{{ route('pengajuan.create') }}"
         class="bg-[#1e293b] hover:bg-slate-800 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
        + Buat Pengajuan Baru
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2 shadow-sm">
      <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
      </svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  <!-- Data Table Section -->
  <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto table-container">
      <table class="w-full text-left whitespace-nowrap">
        <thead class="text-[10px] text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-semibold">
          <tr>
            <th class="px-5 py-4 w-12 text-center">NO.</th>
            <th class="px-5 py-4">NO. RAB</th>
            <th class="px-5 py-4">JUDUL PENGAJUAN</th>
            <th class="px-5 py-4">DIVISI</th>
            <th class="px-5 py-4">PENGAJU</th>
            <th class="px-5 py-4">TANGGAL</th>
            <th class="px-5 py-4">ESTIMASI TOTAL</th>
            <th class="px-5 py-4">PRIORITAS</th>
            <th class="px-5 py-4">STATUS</th>
            <th class="px-5 py-4 text-center">AKSI</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
          @forelse($pengajuanList as $idx => $item)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-4 text-center text-slate-400 text-xs">
                {{ $pengajuanList->firstItem() + $idx }}
              </td>
              <td class="px-5 py-4 font-mono font-medium text-slate-700 text-xs">
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
                {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('Y-m-d') : '-' }}
              </td>
              <td class="px-5 py-4 font-mono-num font-semibold text-slate-800">
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
              <td class="px-5 py-4">
                @php
                  $statusBadge = match($item->status) {
                    'disetujui' => ['bg' => 'bg-emerald-50 text-emerald-600', 'dot' => 'bg-emerald-400'],
                    'diajukan' => ['bg' => 'bg-amber-50 text-amber-600', 'dot' => 'bg-amber-400'],
                    'revisi' => ['bg' => 'bg-orange-50 text-orange-600', 'dot' => 'bg-orange-400'],
                    'ditolak' => ['bg' => 'bg-rose-50 text-rose-600', 'dot' => 'bg-rose-400'],
                    default => ['bg' => 'bg-slate-100 text-slate-600', 'dot' => 'bg-slate-400'],
                  };
                @endphp
                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-medium {{ $statusBadge['bg'] }} rounded-full">
                  <span class="w-1.5 h-1.5 rounded-full {{ $statusBadge['dot'] }}"></span>
                  {{ ucfirst($item->status) }}
                </span>
              </td>
              <td class="px-5 py-4 text-center">
                <!-- Custom Primary Key id_pengajuan pada link route -->
                <a href="{{ route('pengajuan.show', $item->id_pengajuan) }}"
                   class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-2.5 py-1 rounded transition-colors">
                  Detail &rarr;
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="10" class="px-5 py-8 text-center text-slate-400 text-xs">
                Belum ada data pengajuan RAB. Klik tombol "+ Buat Pengajuan Baru" di atas.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination Footer -->
    <div class="px-6 py-4 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white text-xs">
      <div class="text-slate-500">
        Menampilkan {{ $pengajuanList->firstItem() ?? 0 }} - {{ $pengajuanList->lastItem() ?? 0 }} dari {{ $pengajuanList->total() }} dokumen
      </div>
      <div>
        {{ $pengajuanList->links() }}
      </div>
    </div>
  </div>
@endsection
