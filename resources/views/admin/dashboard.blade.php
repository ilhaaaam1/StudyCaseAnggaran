@extends('layouts.app')

@section('title', 'Dashboard & Antrean RAB - SIRAB Kelompok-3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Dashboard</span>
  </div>

  <!-- Page Header (Figma style) -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
        Panel Administrator &bull; Reviewer Anggaran
      </div>
      <h1 class="text-2xl font-bold text-slate-900">Dashboard &amp; Antrean Pengajuan RAB</h1>
      <p class="text-sm text-slate-500 mt-1">
        Periode: {{ date('F Y') }} &bull; Tahun Anggaran {{ date('Y') }} &bull; Halo, <span class="font-semibold text-slate-700">{{ Auth::user()->nama_lengkap }}</span> ({{ Auth::user()->jabatan }})
      </p>
    </div>
    <div class="flex items-center gap-3 shrink-0">
      <a href="{{ route('admin.rab.index') }}" 
         class="bg-[#1e293b] hover:bg-slate-800 text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm transition-all">
        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Buka Antrean Persetujuan
      </a>
      <a href="{{ route('admin.laporan') }}" 
         class="bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-sm">
        Laporan Final
      </a>
    </div>
  </div>

  <!-- Stats Grid (Figma dashboard.html 4 Cards) -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Card 1: TOTAL PENGAJUAN -->
    <div class="bg-white border border-indigo-100 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
      <div class="text-xs font-bold text-slate-500 tracking-wider uppercase mb-2">
        TOTAL PENGAJUAN
      </div>
      <div class="text-2xl font-bold text-slate-800 font-mono mb-1">
        Rp {{ number_format($totalNominalPengajuan ?? 0, 0, ',', '.') }}
      </div>
      <div class="text-xs text-slate-400">
        {{ $totalPengajuan ?? 0 }} dokumen masuk
      </div>
    </div>

    <!-- Card 2: DISETUJUI (ACC) -->
    <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
      <div class="text-xs font-bold text-emerald-700 tracking-wider uppercase mb-2">
        DISETUJUI (ACC)
      </div>
      <div class="text-2xl font-bold text-emerald-700 font-mono mb-1">
        Rp {{ number_format($totalAnggaranAcc ?? 0, 0, ',', '.') }}
      </div>
      <div class="text-xs text-emerald-600">
        {{ $totalAcc ?? 0 }} dokumen disetujui
      </div>
    </div>

    <!-- Card 3: MENUNGGU REVIEW (Pending) -->
    <div class="bg-amber-50/70 border border-amber-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
      <div class="text-xs font-bold text-amber-700 tracking-wider uppercase mb-2">
        MENUNGGU REVIEW
      </div>
      <div class="text-2xl font-bold text-amber-700 font-mono mb-1">
        {{ $totalPending ?? 0 }} Dokumen
      </div>
      <div class="text-xs text-amber-600">
        Antrean butuh evaluasi segera
      </div>
    </div>

    <!-- Card 4: DITOLAK / REVISI -->
    <div class="bg-rose-50/70 border border-rose-200 rounded-xl p-5 shadow-sm hover:shadow transition-shadow">
      <div class="text-xs font-bold text-rose-700 tracking-wider uppercase mb-2">
        DITOLAK / REVISI
      </div>
      <div class="text-2xl font-bold text-rose-700 font-mono mb-1">
        {{ $totalDitolak ?? 0 }} Dokumen
      </div>
      <div class="text-xs text-rose-600">
        Pengajuan yang tidak disetujui
      </div>
    </div>
  </div>

  @if($totalPending > 0)
    <!-- Action Required Alert Box (Figma dashboard.html) -->
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 mb-8 shadow-sm">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h3 class="text-amber-900 font-bold text-sm flex items-center gap-2">
            <span>⚡</span> Perlu Persetujuan Segera ({{ $totalPending }} Pengajuan Pending)
          </h3>
          <p class="text-xs text-amber-700 mt-1">
            Terdapat dokumen pengajuan RAB yang sedang menunggu keputusan persetujuan dari Direktur Keuangan / Reviewer.
          </p>
        </div>
        <a href="{{ route('admin.rab.index', ['status' => 'Pending']) }}" 
           class="inline-flex items-center gap-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-4 py-2 rounded-lg shadow-sm transition-all shrink-0">
          Proses Antrean Sekarang &rarr;
        </a>
      </div>
    </div>
  @endif

  <!-- Progress Bars Section (Figma layout: Distribusi Status & Realisasi) -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Distribusi Status -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
      <h2 class="font-semibold text-slate-800 text-sm mb-5">Distribusi Status Seluruh Pengajuan</h2>
      @php
        $totalAllAdmin = max(1, $totalPengajuan ?? 1);
        $pctAdminAcc = round((($totalAcc ?? 0) / $totalAllAdmin) * 100);
        $pctAdminPending = round((($totalPending ?? 0) / $totalAllAdmin) * 100);
        $pctAdminDitolak = round((($totalDitolak ?? 0) / $totalAllAdmin) * 100);
      @endphp
      <div class="space-y-4 text-xs">
        <div>
          <div class="flex justify-between mb-1.5">
            <span class="font-medium text-emerald-700">Disetujui (ACC)</span>
            <span class="text-slate-500 font-mono">{{ $totalAcc ?? 0 }} dok ({{ $pctAdminAcc }}%)</span>
          </div>
          <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
            <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $pctAdminAcc }}%"></div>
          </div>
        </div>
        <div>
          <div class="flex justify-between mb-1.5">
            <span class="font-medium text-amber-600">Menunggu Review (Pending)</span>
            <span class="text-slate-500 font-mono">{{ $totalPending ?? 0 }} dok ({{ $pctAdminPending }}%)</span>
          </div>
          <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
            <div class="bg-amber-400 h-2 rounded-full" style="width: {{ $pctAdminPending }}%"></div>
          </div>
        </div>
        <div>
          <div class="flex justify-between mb-1.5">
            <span class="font-medium text-rose-600">Ditolak</span>
            <span class="text-slate-500 font-mono">{{ $totalDitolak ?? 0 }} dok ({{ $pctAdminDitolak }}%)</span>
          </div>
          <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
            <div class="bg-rose-500 h-2 rounded-full" style="width: {{ $pctAdminDitolak }}%"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Ringkasan Anggaran Disetujui -->
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 flex flex-col justify-between">
      <div>
        <div class="mb-4">
          <h2 class="font-semibold text-slate-800 text-sm">Alokasi &amp; Realisasi Anggaran</h2>
          <p class="text-xs text-slate-400 mt-0.5">Tahun Anggaran {{ date('Y') }}</p>
        </div>
        <div class="space-y-3 text-xs">
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Total Nominal Disetujui (ACC):</span>
            <span class="font-bold text-emerald-700 font-mono-num">
              Rp {{ number_format($totalAnggaranAcc ?? 0, 0, ',', '.') }}
            </span>
          </div>
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Jumlah Berkas ACC:</span>
            <span class="font-semibold text-slate-800">{{ $totalAcc ?? 0 }} berkas</span>
          </div>
          <div class="flex justify-between py-1.5 border-b border-slate-100">
            <span class="text-slate-500">Tingkat Persetujuan:</span>
            <span class="font-semibold text-indigo-700 font-mono">{{ $pctAdminAcc }}%</span>
          </div>
        </div>
      </div>
      <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
        <span class="text-slate-400">Status Database:</span>
        <span class="font-semibold text-emerald-700 flex items-center gap-1.5">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Terverifikasi Sinkron
        </span>
      </div>
    </div>
  </div>

  <!-- Table Card: Pengajuan Masuk Terbaru -->
  <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h2 class="font-semibold text-slate-800 text-sm sm:text-base">Antrean Pengajuan RAB Terbaru</h2>
        <p class="text-xs text-slate-400 mt-0.5">Daftar berkas pengajuan yang diajukan oleh pemohon/staf</p>
      </div>

      <!-- Filter & Search Controls -->
      <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-wrap items-center gap-2.5">
        <div class="relative">
          <input type="text" name="q" value="{{ $search }}" placeholder="Cari No. RAB, judul, pemohon..."
                 class="w-48 sm:w-64 pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-200 focus:border-indigo-500 bg-slate-50 text-slate-700">
          <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>

        <select name="status" onchange="this.form.submit()"
                class="border border-slate-200 bg-slate-50 text-xs rounded-lg px-2.5 py-1.5 text-slate-700 cursor-pointer focus:border-indigo-500">
          <option value="">Semua Status</option>
          <option value="Pending" {{ $statusFilter === 'Pending' ? 'selected' : '' }}>Pending</option>
          <option value="ACC" {{ $statusFilter === 'ACC' ? 'selected' : '' }}>Disetujui (ACC)</option>
          <option value="Ditolak" {{ $statusFilter === 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>

        @if($search || $statusFilter)
          <a href="{{ route('admin.dashboard') }}" 
             class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs transition-colors" title="Reset Filter">
            ✕ Reset
          </a>
        @endif
      </form>
    </div>

    <div class="overflow-x-auto table-container">
      <table class="w-full text-left text-sm whitespace-nowrap">
        <thead class="text-[10px] text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-semibold">
          <tr>
            <th class="px-5 py-3.5">NO. RAB</th>
            <th class="px-5 py-3.5">JUDUL PENGAJUAN</th>
            <th class="px-5 py-3.5">PEMOHON</th>
            <th class="px-5 py-3.5">DIVISI</th>
            <th class="px-5 py-3.5">PRIORITAS</th>
            <th class="px-5 py-3.5 text-right">TOTAL ANGGARAN</th>
            <th class="px-5 py-3.5 text-center">STATUS</th>
            <th class="px-5 py-3.5 text-center">TINDAKAN</th>
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
                  {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }} &bull; Periode: {{ $item->periode_penggunaan }}
                </div>
              </td>
              <td class="px-5 py-4">
                <div class="font-semibold text-slate-800">{{ $item->pengguna->nama_lengkap ?? 'N/A' }}</div>
                <div class="text-[10px] text-slate-400">{{ $item->pengguna->jabatan ?? '-' }}</div>
              </td>
              <td class="px-5 py-4 text-slate-600">
                {{ $item->divisi->nama_divisi ?? '-' }}
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
                <a href="{{ route('admin.pengajuan.show', $item->id_pengajuan) }}" 
                   class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#1e293b] hover:bg-slate-800 text-white font-medium rounded-lg text-xs transition-colors shadow-sm cursor-pointer">
                  Review RAB &raquo;
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-5 py-12 text-center text-slate-400">
                <svg class="mx-auto h-10 w-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div class="text-sm font-medium text-slate-600">Tidak ada data pengajuan</div>
                <p class="text-xs text-slate-400 mt-1">Belum ada pengajuan RAB yang cocok dengan kriteria filter.</p>
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
