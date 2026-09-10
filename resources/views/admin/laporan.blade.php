@extends('layouts.app')

@section('title', 'Laporan Anggaran - SIRAB Kelompok-3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-800">Dashboard</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Laporan Anggaran</span>
  </div>

  <!-- Page Header (Figma laporan.html) -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
        Laporan Anggaran &bull; Rekapitulasi Realisasi &amp; Persetujuan RAB
      </div>
      <h1 class="text-2xl font-bold text-slate-900">Rekapitulasi Realisasi &amp; Persetujuan RAB</h1>
      <p class="text-sm text-slate-500 mt-1">
        Rekap &amp; analisis penggunaan anggaran {{ date('Y') }} &bull; Riwayat seluruh berkas yang telah berstatus final (ACC / Ditolak).
      </p>
    </div>
    <div class="flex items-center gap-3 shrink-0">
      <div class="bg-white border border-slate-200 rounded-xl flex items-center shadow-sm">
        <select class="bg-transparent text-xs sm:text-sm text-slate-700 py-2.5 pl-4 pr-8 rounded-xl appearance-none cursor-pointer focus:outline-none">
          <option>Tahun {{ date('Y') }}</option>
          <option>Tahun {{ date('Y') - 1 }}</option>
        </select>
      </div>
      <button onclick="window.print()" 
              class="bg-[#1e293b] hover:bg-slate-800 text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm transition-all cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Export Laporan
      </button>
    </div>
  </div>

  <!-- 4 Stats Cards (Figma laporan.html) -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Card 1 -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="w-6 h-6 mb-2 text-indigo-500">📊</div>
      <div class="text-xs text-slate-500 mb-1">Total Pengajuan Final</div>
      <div class="text-lg font-bold font-mono-num text-slate-800 mb-1">
        {{ $totalPengajuan ?? 0 }} Berkas
      </div>
      <div class="text-[10px] text-slate-400">Tahun Anggaran {{ date('Y') }}</div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="w-6 h-6 mb-2 text-emerald-500">✓</div>
      <div class="text-xs text-slate-500 mb-1">Total Nilai Disetujui (ACC)</div>
      <div class="text-lg font-bold font-mono-num text-emerald-700 mb-1">
        Rp {{ number_format($totalNominalAcc ?? 0, 0, ',', '.') }}
      </div>
      <div class="text-[10px] text-emerald-600">Realisasi alokasi dana</div>
    </div>

    <!-- Card 3 -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="w-6 h-6 mb-2 text-rose-500">✕</div>
      <div class="text-xs text-slate-500 mb-1">Total Nilai Ditolak</div>
      <div class="text-lg font-bold font-mono-num text-rose-700 mb-1">
        Rp {{ number_format($totalNominalDitolak ?? 0, 0, ',', '.') }}
      </div>
      <div class="text-[10px] text-rose-500">Anggaran yang diefisiensi</div>
    </div>

    <!-- Card 4 -->
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="w-6 h-6 mb-2 text-amber-500">≈</div>
      <div class="text-xs text-slate-500 mb-1">Rata-rata per Pengajuan</div>
      <div class="text-lg font-bold font-mono-num text-slate-800 mb-1">
        @php
          $avg = ($totalPengajuan > 0) ? (($totalNominalAcc + $totalNominalDitolak) / $totalPengajuan) : 0;
        @endphp
        Rp {{ number_format($avg, 0, ',', '.') }}
      </div>
      <div class="text-[10px] text-slate-400">Seluruh unit kerja</div>
    </div>
  </div>

  <!-- Filter Controls Section -->
  <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 mb-6">
    <form method="GET" action="{{ route('admin.laporan') }}" class="flex flex-col sm:flex-row items-center justify-between gap-4">
      <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
        <!-- Filter Divisi -->
        <div class="flex items-center gap-2">
          <label for="id_divisi" class="text-xs font-semibold text-slate-600">Divisi:</label>
          <select name="id_divisi" 
                  id="id_divisi"
                  onchange="this.form.submit()" 
                  class="border border-slate-300 bg-white text-xs rounded-lg px-3 py-1.5 text-slate-700 cursor-pointer focus:border-indigo-500">
            <option value="">-- Semua Divisi --</option>
            @foreach($divisiList as $divisi)
              <option value="{{ $divisi->id_divisi }}" {{ $divisiFilter == $divisi->id_divisi ? 'selected' : '' }}>
                {{ $divisi->nama_divisi }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Filter Status -->
        <div class="flex items-center gap-2">
          <label for="status" class="text-xs font-semibold text-slate-600">Status:</label>
          <select name="status" 
                  id="status"
                  onchange="this.form.submit()" 
                  class="border border-slate-300 bg-white text-xs rounded-lg px-3 py-1.5 text-slate-700 cursor-pointer focus:border-indigo-500">
            <option value="">-- Semua Status --</option>
            <option value="ACC" {{ $statusFilter === 'ACC' ? 'selected' : '' }}>Hanya ACC (Disetujui)</option>
            <option value="Ditolak" {{ $statusFilter === 'Ditolak' ? 'selected' : '' }}>Hanya Ditolak</option>
            <option value="Pending" {{ $statusFilter === 'Pending' ? 'selected' : '' }}>Pending (Menunggu Keputusan)</option>
          </select>
        </div>

        @if($divisiFilter || $statusFilter)
          <a href="{{ route('admin.laporan') }}" 
             class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg text-xs font-semibold transition-colors" title="Reset Filter">
            ✕ Reset
          </a>
        @endif
      </div>

      <div class="text-xs text-slate-500 font-mono">
        Ditemukan {{ $laporanList->count() }} berkas
      </div>
    </form>
  </div>

  <!-- Table Laporan Histori Final -->
  <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
      <h2 class="font-semibold text-slate-800 text-sm sm:text-base">Tabel Rekapitulasi Berkas Final</h2>
      <span class="text-xs text-slate-400">Data telah ditandatangani / diputuskan oleh Reviewer</span>
    </div>

    <div class="overflow-x-auto table-container">
      <table class="w-full text-left whitespace-nowrap text-sm">
        <thead class="text-[10px] text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-semibold">
          <tr>
            <th class="px-5 py-3.5">NO. RAB</th>
            <th class="px-5 py-3.5">TANGGAL</th>
            <th class="px-5 py-3.5">JUDUL PENGAJUAN</th>
            <th class="px-5 py-3.5">DIVISI</th>
            <th class="px-5 py-3.5">PEMOHON</th>
            <th class="px-5 py-3.5 text-right">TOTAL ANGGARAN</th>
            <th class="px-5 py-3.5 text-center">STATUS FINAL</th>
            <th class="px-5 py-3.5">REVIEWER &amp; CATATAN</th>
            <th class="px-5 py-3.5 text-center">BERKAS</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs">
          @forelse($laporanList as $item)
            @php
              $approval = $item->alurPersetujuan->sortByDesc('tanggal_proses')->first();
              $dokumen = $item->dokumenPendukung->first();
            @endphp
            <tr class="hover:bg-slate-50/80 transition-colors">
              <td class="px-5 py-4 font-mono font-medium text-slate-700">
                {{ $item->no_rab }}
              </td>
              <td class="px-5 py-4 font-mono text-slate-400 text-[11px]">
                {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('Y-m-d') : '-' }}
              </td>
              <td class="px-5 py-4">
                <div class="font-medium text-slate-900 max-w-xs truncate" title="{{ $item->judul_pengajuan }}">
                  {{ $item->judul_pengajuan }}
                </div>
                <div class="text-[10px] text-slate-400 mt-0.5">
                  Periode: {{ $item->periode_penggunaan }}
                </div>
              </td>
              <td class="px-5 py-4 text-slate-600">
                {{ $item->divisi->nama_divisi ?? '-' }}
              </td>
              <td class="px-5 py-4">
                <div class="font-semibold text-slate-800">{{ $item->pengguna->nama_lengkap ?? 'N/A' }}</div>
                <div class="text-[10px] text-slate-400">{{ $item->pengguna->jabatan ?? '-' }}</div>
              </td>
              <td class="px-5 py-4 text-right font-mono-num font-semibold text-slate-800">
                Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
              </td>
              <td class="px-5 py-4 text-center">
                @if($item->status === 'ACC')
                  <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui (ACC)
                  </span>
                @elseif($item->status === 'Ditolak')
                  <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-rose-100 text-rose-800 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 text-[10px] font-bold bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full">
                    {{ $item->status }}
                  </span>
                @endif
              </td>
              <td class="px-5 py-4 max-w-xs">
                @if($approval && $approval->catatan)
                  <div class="text-[11px] text-slate-700 bg-slate-50 p-2 rounded border border-slate-200 line-clamp-2" title="{{ $approval->catatan }}">
                    <span class="font-semibold text-slate-800">{{ $approval->reviewer->nama_lengkap ?? 'Reviewer' }}:</span>
                    "{{ $approval->catatan }}"
                  </div>
                @else
                  <span class="text-slate-400 italic text-[11px]">- Tidak ada catatan -</span>
                @endif
              </td>
              <td class="px-5 py-4 text-center">
                @if($dokumen)
                  <a href="{{ route('dokumen.download', $dokumen->id_dokumen) }}" 
                     class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 text-xs font-semibold"
                     title="Unduh {{ $dokumen->nama_file }}">
                    <span>📎</span>
                    <span class="text-[10px] font-mono">{{ $dokumen->tipe_dokumen ?? 'FILE' }}</span>
                  </a>
                @else
                  <span class="text-slate-300">-</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-5 py-12 text-center text-slate-400">
                <svg class="mx-auto h-10 w-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div class="text-sm font-medium text-slate-600">Tidak ada data laporan final</div>
                <p class="text-xs text-slate-400 mt-1">Belum ada pengajuan dengan status final sesuai kriteria filter saat ini.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
