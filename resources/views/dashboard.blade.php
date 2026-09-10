@extends('layouts.app')

@section('title', 'Dashboard Anggaran - SIRAB')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6">
    SIRAB / <span class="text-slate-800 font-medium">Dashboard</span>
  </div>

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Dashboard Anggaran</h1>
      <p class="text-sm text-slate-500 mt-1">
        Periode: {{ now()->translatedFormat('F Y') }} · Tahun Anggaran {{ now()->format('Y') }}
      </p>
    </div>
    <a href="{{ route('pengajuan.index') }}"
       class="bg-[#1e293b] hover:bg-slate-800 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 transition-colors shadow-sm">
      + Buat Pengajuan
    </a>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <!-- Card 1: Total Pengajuan -->
    <div class="bg-white border border-indigo-100 rounded-lg p-5 shadow-sm">
      <div class="text-xs font-semibold text-slate-500 mb-2">
        TOTAL PENGAJUAN
      </div>
      <div class="text-2xl font-bold text-slate-800 font-mono mb-1">
        Rp {{ number_format($totalAmount, 0, ',', '.') }}
      </div>
      <div class="text-xs text-slate-400">{{ $totalCount }} dokumen</div>
    </div>
    <!-- Card 2: Disetujui -->
    <div class="bg-emerald-50 border border-emerald-100 rounded-lg p-5 shadow-sm">
      <div class="text-xs font-semibold text-emerald-600 mb-2">
        DISETUJUI
      </div>
      <div class="text-2xl font-bold text-emerald-700 font-mono mb-1">
        Rp {{ number_format($approvedAmount, 0, ',', '.') }}
      </div>
      <div class="text-xs text-emerald-500">{{ $approvedCount }} dokumen</div>
    </div>
    <!-- Card 3: Menunggu Review -->
    <div class="bg-amber-50 border border-amber-100 rounded-lg p-5 shadow-sm">
      <div class="text-xs font-semibold text-amber-600 mb-2">
        MENUNGGU REVIEW
      </div>
      <div class="text-2xl font-bold text-amber-700 font-mono mb-1">
        Rp {{ number_format($pendingAmount, 0, ',', '.') }}
      </div>
      <div class="text-xs text-amber-500">{{ $pendingCount }} dokumen</div>
    </div>
    <!-- Card 4: Ditolak / Revisi -->
    <div class="bg-rose-50 border border-rose-100 rounded-lg p-5 shadow-sm">
      <div class="text-xs font-semibold text-rose-600 mb-2">
        DITOLAK / REVISI
      </div>
      <div class="text-2xl font-bold text-rose-700 font-mono mb-1">
        Rp {{ number_format($rejectedAmount, 0, ',', '.') }}
      </div>
      <div class="text-xs text-rose-500">{{ $rejectedCount }} dokumen</div>
    </div>
  </div>

  <!-- Pengajuan Terbaru -->
  <div class="bg-white border border-slate-200 rounded-lg shadow-sm mb-8">
    <div class="flex justify-between items-center p-5 border-b border-slate-100">
      <h2 class="font-semibold text-slate-800">Pengajuan Terbaru</h2>
      <a href="{{ route('rab.index') }}" class="text-sm text-slate-500 hover:text-indigo-600 flex items-center gap-1 transition-colors">
        Lihat semua &rarr;
      </a>
    </div>
    <div class="divide-y divide-slate-100">
      @forelse($recentRabs as $rab)
        <div class="p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 hover:bg-slate-50 transition-colors">
          <div>
            <div class="flex items-center gap-3 mb-1">
              <span class="text-xs font-mono font-medium text-slate-600">{{ $rab->code }}</span>
              <span class="text-[10px] font-semibold {{ $rab->priority->badgeClasses() }} px-2 py-0.5 rounded">
                {{ $rab->priority->label() }}
              </span>
            </div>
            <div class="text-sm font-medium text-slate-800">
              {{ $rab->title }}
            </div>
            <div class="text-xs text-slate-400 mt-1">
              {{ $rab->division }} · {{ $rab->created_at->format('Y-m-d') }} · Pengaju: {{ $rab->user->name ?? '-' }}
            </div>
          </div>
          <div class="text-left sm:text-right">
            <div class="font-mono text-sm font-semibold text-slate-800 mb-1">
              Rp {{ number_format((float)$rab->total_amount, 0, ',', '.') }}
            </div>
            <span class="inline-flex items-center gap-1 text-[10px] font-medium {{ $rab->status->badgeClasses() }} px-2.5 py-0.5 rounded-full">
              <span class="w-1.5 h-1.5 rounded-full {{ $rab->status->dotClasses() }}"></span>
              {{ $rab->status->label() }}
            </span>
          </div>
        </div>
      @empty
        <div class="p-8 text-center text-xs text-slate-400">
          Belum ada data pengajuan anggaran.
        </div>
      @endforelse
    </div>
  </div>

  <!-- Ringkasan per Divisi / Unit Kerja -->
  <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden mb-8">
    <div class="p-5 border-b border-slate-100 flex justify-between items-center">
      <div>
        <h2 class="font-semibold text-slate-800">Ringkasan per Divisi / Unit Kerja</h2>
        <p class="text-xs text-slate-400 mt-0.5">Alokasi dan realisasi anggaran per divisi</p>
      </div>
      <a href="{{ route('laporan.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
        Buka Laporan Penuh &rarr;
      </a>
    </div>
    <div class="overflow-x-auto table-container">
      <table class="w-full text-left text-xs whitespace-nowrap">
        <thead class="text-[10px] text-slate-400 uppercase bg-slate-50 border-b border-slate-200">
          <tr>
            <th class="px-5 py-3 font-semibold">Divisi</th>
            <th class="px-5 py-3 font-semibold text-center">Jumlah RAB</th>
            <th class="px-5 py-3 font-semibold">Total Anggaran</th>
            <th class="px-5 py-3 font-semibold">Disetujui</th>
            <th class="px-5 py-3 font-semibold">Menunggu</th>
            <th class="px-5 py-3 font-semibold w-40">Realisasi (%)</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 font-mono-num">
          @forelse($divisionStats as $stat)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-3 font-sans font-medium text-slate-800">{{ $stat['division'] }}</td>
              <td class="px-5 py-3 text-center text-slate-600">{{ $stat['count'] }}</td>
              <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($stat['total_amount'], 0, ',', '.') }}</td>
              <td class="px-5 py-3 {{ $stat['approved_amount'] > 0 ? 'text-emerald-600 font-medium' : 'text-slate-400' }}">
                {{ $stat['approved_amount'] > 0 ? 'Rp ' . number_format($stat['approved_amount'], 0, ',', '.') : '-' }}
              </td>
              <td class="px-5 py-3 {{ $stat['pending_amount'] > 0 ? 'text-amber-600 font-medium' : 'text-slate-400' }}">
                {{ $stat['pending_amount'] > 0 ? 'Rp ' . number_format($stat['pending_amount'], 0, ',', '.') : '-' }}
              </td>
              <td class="px-5 py-3 font-sans">
                <div class="flex items-center gap-2">
                  <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ min(100, $stat['percentage']) }}%"></div>
                  </div>
                  <span class="text-[10px] w-8 text-right font-medium text-slate-600">{{ $stat['percentage'] }}%</span>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-6 text-center text-slate-400 font-sans">
                Belum ada data alokasi divisi.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
