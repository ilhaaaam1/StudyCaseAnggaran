@extends('layouts.app')

@section('title', 'Laporan - SIRAB')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6">
    SIRAB / <span class="text-slate-800 font-medium">Laporan</span>
  </div>

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Laporan Anggaran</h1>
      <p class="text-sm text-slate-500 mt-1">
        Rekapitulasi dan analisis realisasi anggaran tahun {{ $year }}
      </p>
    </div>
    <div class="flex items-center gap-3">
      <form method="GET" action="{{ route('laporan.index') }}" class="bg-white border border-slate-200 rounded-md flex items-center shadow-sm">
        <select
          name="year"
          onchange="this.form.submit()"
          class="bg-transparent text-sm text-slate-700 py-2 pl-4 pr-8 cursor-pointer focus:outline-none"
        >
          @for($y = (int)date('Y'); $y >= (int)date('Y') - 3; $y--)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
          @endfor
        </select>
      </form>
      <button
        onclick="window.print()"
        class="bg-[#1e293b] hover:bg-slate-800 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm flex items-center gap-1.5"
      >
        <span>Cetak / Export</span> &darr;
      </button>
    </div>
  </div>

  <!-- 4 Stats Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="w-6 h-6 mb-3 text-indigo-500 text-lg">📊</div>
      <div class="text-xs text-slate-500 mb-1">Total Pengajuan {{ $year }}</div>
      <div class="text-lg font-bold font-mono-num text-slate-800 mb-1">
        Rp {{ number_format($totalAmount, 0, ',', '.') }}
      </div>
      <div class="text-[10px] text-slate-400">{{ $totalCount }} dokumen RAB</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="w-6 h-6 mb-3 text-emerald-500 text-lg">✓</div>
      <div class="text-xs text-slate-500 mb-1">Total Disetujui</div>
      <div class="text-lg font-bold font-mono-num text-emerald-700 mb-1">
        Rp {{ number_format($approvedAmount, 0, ',', '.') }}
      </div>
      <div class="text-[10px] text-slate-400">{{ $approvalPercentage }}% dari total anggaran</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="w-6 h-6 mb-3 text-slate-400 text-lg">≈</div>
      <div class="text-xs text-slate-500 mb-1">Rata-rata per RAB</div>
      <div class="text-lg font-bold font-mono-num text-slate-800 mb-1">
        Rp {{ number_format($averageAmount, 0, ',', '.') }}
      </div>
      <div class="text-[10px] text-slate-400">Rata-rata seluruh divisi</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
      <div class="w-6 h-6 mb-3 text-amber-500 text-lg">%</div>
      <div class="text-xs text-slate-500 mb-1">Tingkat Persetujuan</div>
      <div class="text-lg font-bold font-mono-num text-amber-700 mb-1">
        {{ $approvalRate }}%
      </div>
      <div class="text-[10px] text-slate-400">{{ $approvedCount }} dari {{ $processedCount }} dokumen diproses</div>
    </div>
  </div>

  <!-- Realisasi per Divisi Table -->
  <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
      <div>
        <h2 class="text-sm font-semibold text-slate-800">
          Realisasi Anggaran per Divisi
        </h2>
        <p class="text-xs text-slate-400 mt-0.5">
          Perbandingan total alokasi dan anggaran yang telah disetujui
        </p>
      </div>
    </div>
    <div class="overflow-x-auto table-container">
      <table class="w-full text-left text-xs whitespace-nowrap">
        <thead class="text-[10px] text-slate-400 uppercase bg-slate-50 border-b border-slate-200">
          <tr>
            <th class="px-5 py-3.5 font-semibold">DIVISI</th>
            <th class="px-5 py-3.5 font-semibold text-center">JUMLAH DOKUMEN</th>
            <th class="px-5 py-3.5 font-semibold">TOTAL PENGAJUAN</th>
            <th class="px-5 py-3.5 font-semibold">DISETUJUI</th>
            <th class="px-5 py-3.5 font-semibold">SISA / PENDING</th>
            <th class="px-5 py-3.5 font-semibold w-40">PERSENTASE (%)</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-mono-num">
          @forelse($divisionReports as $item)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-3.5 font-sans font-medium text-slate-800">{{ $item['division'] }}</td>
              <td class="px-5 py-3.5 text-center text-slate-600">{{ $item['count'] }}</td>
              <td class="px-5 py-3.5 font-semibold text-slate-800">Rp {{ number_format($item['total_amount'], 0, ',', '.') }}</td>
              <td class="px-5 py-3.5 text-emerald-600 font-semibold">Rp {{ number_format($item['approved_amount'], 0, ',', '.') }}</td>
              <td class="px-5 py-3.5 text-slate-500">Rp {{ number_format($item['remaining_amount'], 0, ',', '.') }}</td>
              <td class="px-5 py-3.5 font-sans">
                <div class="flex items-center gap-2">
                  <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ min(100, $item['percentage']) }}%"></div>
                  </div>
                  <span class="text-[10px] w-8 text-right font-semibold text-slate-700">{{ $item['percentage'] }}%</span>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-6 text-center text-slate-400 font-sans">
                Belum ada data laporan divisi untuk tahun {{ $year }}.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Detail Rekapitulasi RAB Keseluruhan -->
  <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100">
      <h2 class="text-sm font-semibold text-slate-800">
        Daftar Rekapitulasi Seluruh Pengajuan RAB
      </h2>
      <p class="text-xs text-slate-400 mt-0.5">
        Riwayat status dan tanggal persetujuan dokumen anggaran
      </p>
    </div>
    <div class="overflow-x-auto table-container">
      <table class="w-full text-left text-xs whitespace-nowrap">
        <thead class="text-[10px] text-slate-400 uppercase bg-slate-50 border-b border-slate-200">
          <tr>
            <th class="px-5 py-3.5 font-semibold w-12 text-center">NO.</th>
            <th class="px-5 py-3.5 font-semibold">KODE RAB</th>
            <th class="px-5 py-3.5 font-semibold">JUDUL PENGAJUAN</th>
            <th class="px-5 py-3.5 font-semibold">DIVISI</th>
            <th class="px-5 py-3.5 font-semibold">TOTAL ANGGARAN</th>
            <th class="px-5 py-3.5 font-semibold">STATUS</th>
            <th class="px-5 py-3.5 font-semibold">TGL PENGAJUAN</th>
            <th class="px-5 py-3.5 font-semibold">TGL PERSETUJUAN</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-mono-num">
          @forelse($rabs as $idx => $rab)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-3.5 text-center text-slate-400 font-sans">{{ $idx + 1 }}</td>
              <td class="px-5 py-3.5 font-medium text-slate-700">{{ $rab->code }}</td>
              <td class="px-5 py-3.5 font-sans font-medium text-slate-800 max-w-xs truncate" title="{{ $rab->title }}">
                {{ $rab->title }}
              </td>
              <td class="px-5 py-3.5 font-sans text-slate-600">{{ $rab->division }}</td>
              <td class="px-5 py-3.5 font-semibold text-slate-800">
                Rp {{ number_format((float)$rab->total_amount, 0, ',', '.') }}
              </td>
              <td class="px-5 py-3.5 font-sans">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-medium {{ $rab->status->badgeClasses() }} rounded-full">
                  <span class="w-1.5 h-1.5 rounded-full {{ $rab->status->dotClasses() }}"></span>
                  {{ $rab->status->label() }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-slate-500 font-mono text-[11px]">
                {{ $rab->created_at->format('Y-m-d') }}
              </td>
              <td class="px-5 py-3.5 text-slate-500 font-mono text-[11px]">
                {{ $rab->approved_at?->format('Y-m-d') ?? '-' }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-5 py-6 text-center text-slate-400 font-sans">
                Belum ada data dokumen RAB di tahun {{ $year }}.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
