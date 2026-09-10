@extends('layouts.app')

@section('title', 'Daftar & Antrean Persetujuan RAB - Administrator')

@section('content')
<div class="space-y-6">
  <!-- Header Card -->
  <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm gap-4">
    <div>
      <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
        Administrator &bull; Verifikasi Anggaran
      </div>
      <h1 class="text-2xl font-bold text-slate-800">Antrean Persetujuan RAB</h1>
      <p class="text-sm text-slate-500 mt-0.5">
        Daftar usulan RAB lengkap beserta rincian item dan dokumen pendukung siap ditinjau.
      </p>
    </div>
    <div class="flex items-center gap-3 shrink-0">
      <a href="{{ route('admin.dashboard') }}" 
         class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 transition-colors shadow-sm">
        &larr; Dashboard Admin
      </a>
      <a href="{{ route('admin.laporan') }}" 
         class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 border border-indigo-100 hover:bg-indigo-100 transition-colors shadow-sm">
        Rekap Laporan Final
      </a>
    </div>
  </div>

  <!-- Filter Tabs & Search Bar -->
  <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <!-- Status Tabs -->
    <div class="flex flex-wrap items-center gap-2">
      <a href="{{ route('admin.approval.list') }}" 
         class="px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition-all {{ empty($statusFilter) ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
        Semua Berkas
        <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ empty($statusFilter) ? 'bg-indigo-500 text-white' : 'bg-slate-200 text-slate-700' }}">{{ $countAll ?? 0 }}</span>
      </a>

      <a href="{{ route('admin.approval.list', ['status' => 'Pending', 'q' => $search]) }}" 
         class="px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition-all {{ $statusFilter === 'Pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
        Menunggu Review
        <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'Pending' ? 'bg-amber-600 text-white' : 'bg-amber-100 text-amber-800' }}">{{ $countPending ?? 0 }}</span>
      </a>

      <a href="{{ route('admin.approval.list', ['status' => 'ACC', 'q' => $search]) }}" 
         class="px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition-all {{ $statusFilter === 'ACC' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
        Disetujui (ACC)
        <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'ACC' ? 'bg-emerald-700 text-white' : 'bg-emerald-100 text-emerald-800' }}">{{ $countAcc ?? 0 }}</span>
      </a>

      <a href="{{ route('admin.approval.list', ['status' => 'Ditolak', 'q' => $search]) }}" 
         class="px-3.5 py-2 rounded-xl text-xs font-semibold flex items-center gap-2 transition-all {{ $statusFilter === 'Ditolak' ? 'bg-rose-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
        Ditolak
        <span class="px-1.5 py-0.5 rounded-full text-[10px] {{ $statusFilter === 'Ditolak' ? 'bg-rose-700 text-white' : 'bg-rose-100 text-rose-800' }}">{{ $countDitolak ?? 0 }}</span>
      </a>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.approval.list') }}" class="flex items-center gap-2">
      @if($statusFilter)
        <input type="hidden" name="status" value="{{ $statusFilter }}">
      @endif
      <div class="relative">
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari No. RAB, judul, pemohon..."
               class="w-56 sm:w-64 pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-300 focus:border-indigo-500 bg-slate-50 text-slate-700">
        <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>
      <button type="submit" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold transition-colors">
        Cari
      </button>
      @if($search)
        <a href="{{ route('admin.approval.list', ['status' => $statusFilter]) }}" 
           class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs transition-colors" title="Reset Pencarian">
          ✕
        </a>
      @endif
    </form>
  </div>

  <div class="space-y-4">
    @forelse($pengajuanList as $item)
      <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4 hover:shadow-md transition-shadow">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-2">
          <div>
            <span class="font-mono text-xs font-bold text-indigo-600">{{ $item->no_rab }}</span>
            <h2 class="text-lg font-bold text-slate-800">{{ $item->judul_pengajuan }}</h2>
            <div class="text-xs text-slate-500">
              Pemohon: <strong class="text-slate-700">{{ $item->pengguna->nama_lengkap ?? 'N/A' }}</strong> &bull; Divisi: {{ $item->divisi->nama_divisi ?? '-' }}
            </div>
          </div>
          <div class="text-right">
            <div class="text-xs text-slate-400 font-medium">Total Anggaran</div>
            <div class="text-lg font-bold text-indigo-900 font-mono">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</div>
            <div class="mt-1">
              @if($item->status === 'ACC')
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">ACC (Disetujui)</span>
              @elseif($item->status === 'Ditolak')
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Ditolak</span>
              @else
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Pending</span>
              @endif
            </div>
          </div>
        </div>

        <!-- Rincian Item Ringkas -->
        <div class="text-xs">
          <div class="font-semibold text-slate-700 mb-1.5">Rincian Item ({{ $item->rincian_item->count() }} item):</div>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
            @foreach($item->rincian_item as $rincian)
              <div class="bg-slate-50 p-2 rounded-lg border border-slate-100 text-slate-700">
                <div class="font-medium truncate">{{ $rincian->uraian_barang }}</div>
                <div class="text-[10px] text-slate-400">{{ $rincian->volume }} {{ $rincian->satuan }} &bull; Rp {{ number_format((float) $rincian->total_harga, 0, ',', '.') }}</div>
              </div>
            @endforeach
          </div>
        </div>

        <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
          <div class="text-slate-400">
            Lampiran: {{ $item->dokumen_pendukung->count() }} dokumen terunggah
          </div>
          <a href="{{ route('admin.pengajuan.show', $item->id_pengajuan) }}" 
             class="px-4 py-1.5 {{ $item->status === 'Pending' ? 'bg-indigo-600 hover:bg-indigo-700' : ($item->status === 'ACC' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-slate-700 hover:bg-slate-800') }} text-white font-semibold rounded-lg transition-colors shadow-sm">
            {{ $item->status === 'Pending' ? 'Proses Persetujuan »' : 'Lihat Detail & Riwayat »' }}
          </a>
        </div>
      </div>
    @empty
      <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center text-slate-400 text-sm">
        <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <div class="font-medium text-slate-600">Tidak ada data pengajuan dalam kriteria ini</div>
        <p class="text-xs text-slate-400 mt-1">Belum ada pengajuan RAB yang cocok dengan filter antrean saat ini.</p>
      </div>
    @endforelse
  </div>
</div>
@endsection
