@extends('layouts.app')

@section('title', 'Dashboard Pemohon RAB - SIRAB Kelompok-3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Dashboard Pemohon RAB</span>
  </div>

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Dashboard Pemohon RAB</h1>
      <p class="text-sm text-slate-500 mt-1">
        Halo, <span class="font-semibold text-slate-700">{{ Auth::user()->nama_lengkap }}</span> ({{ Auth::user()->jabatan ?? 'Staf' }} - {{ Auth::user()->divisi->nama_divisi ?? 'Umum' }}). Periode: {{ date('F Y') }} &bull; Tahun Anggaran {{ date('Y') }}
      </p>
    </div>
    <a href="{{ route('user.rab.create') }}"
       class="bg-[#1e293b] hover:bg-slate-800 text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm transition-all shrink-0">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
      </svg>
      Buat Pengajuan RAB Baru
    </a>
  </div>

  <!-- Stats Grid (Figma Cards) -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Card 1: Total Pengajuan -->
    <div class="bg-white border border-indigo-100 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
      <div class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-2">
        TOTAL PENGAJUAN
      </div>
      <div class="text-2xl font-bold text-slate-800 font-mono mb-1">
        Rp {{ number_format($totalAnggaran ?? 0, 0, ',', '.') }}
      </div>
      <div class="text-xs text-slate-400">
        {{ $totalPengajuan ?? 0 }} dokumen diajukan
      </div>
    </div>

    <!-- Card 2: Disetujui (ACC) -->
    <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
      <div class="text-xs font-bold text-emerald-700 tracking-wider uppercase mb-2">
        DISETUJUI (ACC)
      </div>
      <div class="text-2xl font-bold text-emerald-700 font-mono mb-1">
        {{ $totalAcc ?? 0 }}
      </div>
      <div class="text-xs text-emerald-600">
        Disetujui oleh Reviewer / Direktur
      </div>
    </div>

    <!-- Card 3: Menunggu Review (Pending) -->
    <div class="bg-amber-50/70 border border-amber-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
      <div class="text-xs font-bold text-amber-700 tracking-wider uppercase mb-2">
        MENUNGGU REVIEW
      </div>
      <div class="text-2xl font-bold text-amber-700 font-mono mb-1">
        {{ $totalPending ?? 0 }}
      </div>
      <div class="text-xs text-amber-600">
        Dalam antrean persetujuan
      </div>
    </div>

    <!-- Card 4: Ditolak -->
    <div class="bg-rose-50/70 border border-rose-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
      <div class="text-xs font-bold text-rose-700 tracking-wider uppercase mb-2">
        DITOLAK / REVISI
      </div>
      <div class="text-2xl font-bold text-rose-700 font-mono mb-1">
        {{ $totalDitolak ?? 0 }}
      </div>
      <div class="text-xs text-rose-600">
        Perlu evaluasi atau penyesuaian
      </div>
    </div>
  </div>

  <!-- Progress & Distribution Section (Figma layout) -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Distribusi Status -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 sm:p-6">
      <h2 class="font-semibold text-slate-800 text-sm mb-4 flex items-center justify-between">
        <span>Distribusi Status Pengajuan</span>
        <span class="text-xs font-normal text-slate-400">Total: {{ $totalPengajuan ?? 0 }} Dokumen</span>
      </h2>
      @php
        $totalAll = max(1, $totalPengajuan ?? 1);
        $pctAcc = round((($totalAcc ?? 0) / $totalAll) * 100);
        $pctPending = round((($totalPending ?? 0) / $totalAll) * 100);
        $pctDitolak = round((($totalDitolak ?? 0) / $totalAll) * 100);
      @endphp
      <div class="space-y-3.5 text-xs">
        <div>
          <div class="flex justify-between mb-1">
            <span class="font-medium text-emerald-700">Disetujui (ACC)</span>
            <span class="text-slate-500 font-mono">{{ $totalAcc ?? 0 }} dok ({{ $pctAcc }}%)</span>
          </div>
          <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
            <div class="bg-emerald-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pctAcc }}%"></div>
          </div>
        </div>

        <div>
          <div class="flex justify-between mb-1">
            <span class="font-medium text-amber-600">Menunggu Review (Pending)</span>
            <span class="text-slate-500 font-mono">{{ $totalPending ?? 0 }} dok ({{ $pctPending }}%)</span>
          </div>
          <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
            <div class="bg-amber-400 h-2 rounded-full transition-all duration-500" style="width: {{ $pctPending }}%"></div>
          </div>
        </div>

        <div>
          <div class="flex justify-between mb-1">
            <span class="font-medium text-rose-600">Ditolak</span>
            <span class="text-slate-500 font-mono">{{ $totalDitolak ?? 0 }} dok ({{ $pctDitolak }}%)</span>
          </div>
          <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
            <div class="bg-rose-500 h-2 rounded-full transition-all duration-500" style="width: {{ $pctDitolak }}%"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Ringkasan Unit Kerja & Status Akun -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 sm:p-6 flex flex-col justify-between">
      <div>
        <h2 class="font-semibold text-slate-800 text-sm mb-4">Informasi Pemohon &amp; Divisi</h2>
        <div class="space-y-3 text-xs">
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Nama Lengkap:</span>
            <span class="font-semibold text-slate-800">{{ Auth::user()->nama_lengkap }}</span>
          </div>
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Jabatan:</span>
            <span class="font-medium text-slate-700">{{ Auth::user()->jabatan ?? 'Staf' }}</span>
          </div>
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Divisi / Unit:</span>
            <span class="font-semibold text-indigo-700">{{ Auth::user()->divisi->nama_divisi ?? 'Umum' }}</span>
          </div>
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Email:</span>
            <span class="font-mono text-slate-600">{{ Auth::user()->email }}</span>
          </div>
        </div>
      </div>
      <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
        <span class="text-slate-400">Status Otorisasi:</span>
        <span class="inline-flex items-center gap-1.5 font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-full">
          <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span> Pemohon Aktif
        </span>
      </div>
    </div>
  </div>

  <!-- Pengajuan Terbaru Section (Figma design) -->
  <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="flex justify-between items-center p-5 border-b border-slate-100">
      <div>
        <h2 class="font-semibold text-slate-800 text-sm sm:text-base">Daftar Pengajuan RAB Anda</h2>
        <p class="text-xs text-slate-400 mt-0.5">Seluruh berkas pengajuan anggaran yang Anda buat</p>
      </div>
      <a href="{{ route('user.laporan') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-1">
        Lihat Laporan Lengkap &rarr;
      </a>
    </div>

    <div class="overflow-x-auto table-container">
      <table class="w-full text-left text-sm whitespace-nowrap">
        <thead class="text-[10px] text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-semibold">
          <tr>
            <th class="px-5 py-3.5">NO. RAB</th>
            <th class="px-5 py-3.5">JUDUL PENGAJUAN</th>
            <th class="px-5 py-3.5">DIVISI</th>
            <th class="px-5 py-3.5">PERIODE</th>
            <th class="px-5 py-3.5">PRIORITAS</th>
            <th class="px-5 py-3.5 text-right">TOTAL ANGGARAN</th>
            <th class="px-5 py-3.5 text-center">STATUS</th>
            <th class="px-5 py-3.5 text-center">AKSI</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs">
          @forelse($pengajuanList as $item)
            <tr class="hover:bg-slate-50/80 transition-colors">
              <td class="px-5 py-4 font-mono font-medium text-slate-700">
                {{ $item->no_rab }}
              </td>
              <td class="px-5 py-4">
                <div class="font-medium text-slate-900 max-w-xs truncate" title="{{ $item->judul_pengajuan }}">
                  {{ $item->judul_pengajuan }}
                </div>
                <div class="text-[10px] text-slate-400 mt-0.5">
                  Diajukan: {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}
                </div>
              </td>
              <td class="px-5 py-4 text-slate-600">
                {{ $item->divisi->nama_divisi ?? '-' }}
              </td>
              <td class="px-5 py-4 text-slate-600">
                {{ $item->periode_penggunaan }}
              </td>
              <td class="px-5 py-4">
                @if($item->prioritas === 'Tinggi')
                  <span class="inline-block px-2 py-0.5 text-[10px] font-semibold bg-rose-50 text-rose-600 border border-rose-100 rounded">
                    Tinggi
                  </span>
                @elseif($item->prioritas === 'Sedang')
                  <span class="inline-block px-2 py-0.5 text-[10px] font-semibold bg-amber-50 text-amber-600 border border-amber-100 rounded">
                    Sedang
                  </span>
                @else
                  <span class="inline-block px-2 py-0.5 text-[10px] font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 rounded">
                    Rendah
                  </span>
                @endif
              </td>
              <td class="px-5 py-4 text-right font-mono-num font-semibold text-slate-800">
                Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
              </td>
              <td class="px-5 py-4 text-center">
                @if($item->status === 'ACC')
                  <span class="inline-flex items-center gap-1 text-[10px] font-medium bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                  </span>
                @elseif($item->status === 'Ditolak')
                  <span class="inline-flex items-center gap-1 text-[10px] font-medium bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 text-[10px] font-medium bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                  </span>
                @endif
              </td>
              <td class="px-5 py-4 text-center">
                <a href="{{ route('user.rab.show', $item->id_pengajuan) }}" 
                   class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 font-medium rounded-lg text-xs transition-colors">
                  Detail &raquo;
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                <svg class="mx-auto h-10 w-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div class="text-sm font-medium text-slate-600">Belum ada pengajuan RAB</div>
                <p class="text-xs text-slate-400 mt-1">Mulai buat pengajuan anggaran baru dengan menekan tombol di atas.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($pengajuanList->hasPages())
      <div class="px-6 py-4 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white text-xs">
        <div class="text-slate-500">
          Menampilkan {{ $pengajuanList->firstItem() ?? 0 }} - {{ $pengajuanList->lastItem() ?? 0 }} dari {{ $pengajuanList->total() }} dokumen
        </div>
        <div>
          {{ $pengajuanList->links() }}
        </div>
      </div>
    @endif
  </div>
@endsection
