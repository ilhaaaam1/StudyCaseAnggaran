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
  <div class="bg-white border border-slate-200 rounded-xl shadow-xs overflow-hidden mb-8">
    <!-- Quick Filter Tabs -->
    <div class="p-3.5 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-center gap-1.5">
      @php
        $isSemua = empty($statusFilter) || $statusFilter === 'semua';
      @endphp
      <a href="{{ route('pengajuan.index', array_merge(request()->except(['status', 'page']), ['status' => ''])) }}"
         class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $isSemua ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
        Semua
      </a>

      @php
        $isDiajukan = in_array($statusFilter, ['diajukan', 'menunggu', \App\Enums\StatusPengajuan::MENUNGGU_FINANCE->value]);
      @endphp
      <a href="{{ route('pengajuan.index', array_merge(request()->except(['status', 'page']), ['status' => \App\Enums\StatusPengajuan::MENUNGGU_FINANCE->value])) }}"
         class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $isDiajukan ? 'bg-amber-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-amber-50/60 hover:text-amber-800' }}">
        <span class="w-1.5 h-1.5 rounded-full {{ $isDiajukan ? 'bg-white' : 'bg-amber-500' }}"></span>
        <span>Menunggu Review</span>
      </a>

      @php
        $isDisetujui = in_array($statusFilter, ['disetujui', \App\Enums\StatusPengajuan::SELESAI->value, \App\Enums\StatusPengajuan::PROSES_PENCAIRAN->value]);
      @endphp
      <a href="{{ route('pengajuan.index', array_merge(request()->except(['status', 'page']), ['status' => \App\Enums\StatusPengajuan::SELESAI->value])) }}"
         class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $isDisetujui ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50/60 hover:text-emerald-700' }}">
        <span class="w-1.5 h-1.5 rounded-full {{ $isDisetujui ? 'bg-white' : 'bg-emerald-500' }}"></span>
        <span>Disetujui</span>
      </a>

      @php
        $isRevisi = $statusFilter === \App\Enums\StatusPengajuan::REVISI->value || $statusFilter === 'revisi';
      @endphp
      <a href="{{ route('pengajuan.index', array_merge(request()->except(['status', 'page']), ['status' => \App\Enums\StatusPengajuan::REVISI->value])) }}"
         class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $isRevisi ? 'bg-amber-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-amber-50/60 hover:text-amber-800' }}">
        <span class="w-1.5 h-1.5 rounded-full {{ $isRevisi ? 'bg-white' : 'bg-amber-500' }}"></span>
        <span>Perlu Revisi</span>
      </a>

      @php
        $isDitolak = $statusFilter === \App\Enums\StatusPengajuan::DITOLAK->value || $statusFilter === 'ditolak';
      @endphp
      <a href="{{ route('pengajuan.index', array_merge(request()->except(['status', 'page']), ['status' => \App\Enums\StatusPengajuan::DITOLAK->value])) }}"
         class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $isDitolak ? 'bg-rose-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-rose-50/60 hover:text-rose-700' }}">
        <span class="w-1.5 h-1.5 rounded-full {{ $isDitolak ? 'bg-white' : 'bg-rose-500' }}"></span>
        <span>Ditolak</span>
      </a>
    </div>

    <div class="overflow-x-auto table-container">
      <table class="w-full min-w-[900px] text-left text-xs border-collapse">
        <thead class="text-[10px] text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-semibold">
          <tr>
            <th class="px-5 py-3.5 w-12 text-center whitespace-nowrap">NO.</th>
            <th class="px-5 py-3.5 whitespace-nowrap">NO. RAB</th>
            <th class="px-5 py-3.5 whitespace-nowrap">JUDUL PENGAJUAN</th>
            <th class="px-5 py-3.5 whitespace-nowrap">DIVISI</th>
            <th class="px-5 py-3.5 whitespace-nowrap">PENGAJU</th>
            <th class="px-5 py-3.5 whitespace-nowrap">TANGGAL</th>
            <th class="px-5 py-3.5 text-right whitespace-nowrap">ESTIMASI TOTAL</th>
            <th class="px-5 py-3.5 text-center whitespace-nowrap">PRIORITAS</th>
            <th class="px-5 py-3.5 text-center whitespace-nowrap">STATUS</th>
            <th class="px-5 py-3.5 text-center whitespace-nowrap">AKSI</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs">
          @forelse($pengajuanList as $idx => $item)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-3.5 text-center text-slate-400 font-mono whitespace-nowrap">
                {{ $pengajuanList->firstItem() + $idx }}
              </td>
              <td class="px-5 py-3.5 font-mono font-bold text-indigo-700 whitespace-nowrap">
                {{ $item->no_rab }}
              </td>
              <td class="px-5 py-3.5 text-slate-800 font-medium max-w-xs md:max-w-sm">
                <div class="truncate" title="{{ $item->judul_pengajuan }}">
                  {{ $item->judul_pengajuan }}
                </div>
              </td>
              <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                {{ $item->divisi->nama_divisi ?? '-' }}
              </td>
              <td class="px-5 py-3.5 text-slate-700 font-medium whitespace-nowrap">
                {{ $item->pengguna->nama_lengkap ?? '-' }}
              </td>
              <td class="px-5 py-3.5 text-slate-400 font-mono text-center whitespace-nowrap">
                {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}
              </td>
              <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-800 whitespace-nowrap tabular-nums">
                Rp {{ number_format((float)$item->estimasi_total, 0, ',', '.') }}
              </td>
              <td class="px-5 py-3.5 text-center whitespace-nowrap">
                @php
                  $priorityClasses = match(strtolower((string)$item->prioritas)) {
                    'tinggi' => 'bg-rose-50 text-rose-600 border-rose-100',
                    'sedang' => 'bg-amber-50 text-amber-600 border-amber-100',
                    'rendah' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                    default => 'bg-slate-50 text-slate-600 border-slate-100',
                  };
                @endphp
                <span class="inline-block px-2 py-0.5 text-[10px] font-semibold border rounded {{ $priorityClasses }}">
                  {{ ucfirst($item->prioritas ?? 'Normal') }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-center whitespace-nowrap">
                @php
                  $statusVal = is_object($item->status) ? $item->status->value : (string)$item->status;
                  $statusBadge = match(strtolower($statusVal)) {
                    'disetujui', 'selesai', 'acc', 'acc final' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                    'diajukan', 'pending', 'menunggu verifikasi finance', 'menunggu persetujuan pimpinan' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-200', 'dot' => 'bg-blue-500'],
                    'proses pencairan' => ['bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'dot' => 'bg-indigo-500'],
                    'revisi' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500'],
                    'ditolak' => ['bg' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500'],
                    default => ['bg' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400'],
                  };
                @endphp
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-medium border {{ $statusBadge['bg'] }} rounded-full">
                  <span class="w-1.5 h-1.5 rounded-full {{ $statusBadge['dot'] }}"></span>
                  {{ ucfirst($statusVal) }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-center whitespace-nowrap">
                <!-- Custom Primary Key id_pengajuan pada link route -->
                <a href="{{ route('pengajuan.show', $item->id_pengajuan) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 border border-slate-200/80 hover:border-indigo-200 shadow-2xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 active:scale-95 transition-all duration-150 shrink-0"
                   title="Lihat Detail Dokumen Pengajuan">
                  <i class="fa-regular fa-eye text-[12px] shrink-0"></i>
                  <span>Detail</span>
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
