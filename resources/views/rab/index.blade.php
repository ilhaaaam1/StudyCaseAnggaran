@extends('layouts.app')

@section('title', 'Daftar RAB - SIRAB')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6">
    SIRAB / <span class="text-slate-800 font-medium">Daftar RAB</span>
  </div>

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">
        Daftar Pengajuan RAB
      </h1>
      <p class="text-sm text-slate-500 mt-1">{{ $rabs->total() }} dokumen ditemukan</p>
    </div>
    <a href="{{ route('pengajuan.index') }}"
       class="bg-[#1e293b] hover:bg-slate-800 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2 shadow-sm transition-colors">
      + Buat Pengajuan
    </a>
  </div>

  <!-- Filter & Search Section -->
  <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4 mb-6">
    <div class="flex flex-col lg:flex-row justify-between gap-4">
      <!-- Left: Search and Status Filters -->
      <div class="flex flex-col sm:flex-row flex-1 gap-4 items-start sm:items-center">
        <!-- Search Form -->
        <form method="GET" action="{{ route('rab.index') }}" class="w-full sm:w-64 shrink-0 flex items-center">
          @if($status)
            <input type="hidden" name="status" value="{{ $status }}">
          @endif
          <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Cari nomor, judul, divisi..."
            class="w-full border border-slate-300 rounded-md px-3 py-1.5 text-sm text-slate-800 placeholder-slate-400 focus:outline-none"
          />
        </form>

        <!-- Status Filter Group -->
        <div class="flex flex-wrap items-center gap-2">
          <a href="{{ route('rab.index', array_filter(['search' => $search, 'sort' => $sort])) }}"
             class="{{ empty($status) || $status === 'all' ? 'bg-[#1e293b] text-white border-[#1e293b]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }} border text-xs font-medium px-3.5 py-1.5 rounded-md transition-colors">
            Semua
          </a>
          <a href="{{ route('rab.index', array_filter(['status' => 'draft', 'search' => $search, 'sort' => $sort])) }}"
             class="{{ $status === 'draft' ? 'bg-[#1e293b] text-white border-[#1e293b]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }} border text-xs font-medium px-3.5 py-1.5 rounded-md transition-colors">
            Draft
          </a>
          <a href="{{ route('rab.index', array_filter(['status' => 'diajukan', 'search' => $search, 'sort' => $sort])) }}"
             class="{{ $status === 'diajukan' ? 'bg-[#1e293b] text-white border-[#1e293b]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }} border text-xs font-medium px-3.5 py-1.5 rounded-md transition-colors">
            Diajukan
          </a>
          <a href="{{ route('rab.index', array_filter(['status' => 'disetujui', 'search' => $search, 'sort' => $sort])) }}"
             class="{{ $status === 'disetujui' ? 'bg-[#1e293b] text-white border-[#1e293b]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }} border text-xs font-medium px-3.5 py-1.5 rounded-md transition-colors">
            Disetujui
          </a>
          <a href="{{ route('rab.index', array_filter(['status' => 'ditolak', 'search' => $search, 'sort' => $sort])) }}"
             class="{{ $status === 'ditolak' ? 'bg-[#1e293b] text-white border-[#1e293b]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }} border text-xs font-medium px-3.5 py-1.5 rounded-md transition-colors">
            Ditolak
          </a>
          <a href="{{ route('rab.index', array_filter(['status' => 'revisi', 'search' => $search, 'sort' => $sort])) }}"
             class="{{ $status === 'revisi' ? 'bg-[#1e293b] text-white border-[#1e293b]' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' }} border text-xs font-medium px-3.5 py-1.5 rounded-md transition-colors">
            Perlu Revisi
          </a>
        </div>
      </div>

      <!-- Right: Sort -->
      <form method="GET" action="{{ route('rab.index') }}" class="flex items-center gap-3 justify-end shrink-0 pt-4 lg:pt-0 border-t lg:border-t-0 border-slate-100">
        @if($status)
          <input type="hidden" name="status" value="{{ $status }}">
        @endif
        @if($search)
          <input type="hidden" name="search" value="{{ $search }}">
        @endif
        <select
          name="sort"
          onchange="this.form.submit()"
          class="border border-slate-300 bg-white text-slate-700 text-sm rounded-md px-3 py-1.5 pr-8 cursor-pointer focus:outline-none"
        >
          <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru</option>
          <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
        </select>
      </form>
    </div>
  </div>

  <!-- Data Table Section -->
  <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto table-container">
      <table class="w-full text-left whitespace-nowrap">
        <thead class="text-[10px] text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-semibold">
          <tr>
            <th class="px-5 py-4 w-10">
              <input
                type="checkbox"
                class="w-4 h-4 rounded border-slate-300 accent-[#1e293b] cursor-pointer"
              />
            </th>
            <th class="px-5 py-4">NO. RAB</th>
            <th class="px-5 py-4">JUDUL PENGAJUAN</th>
            <th class="px-5 py-4">DIVISI</th>
            <th class="px-5 py-4">PENGAJU</th>
            <th class="px-5 py-4">TANGGAL</th>
            <th class="px-5 py-4">TOTAL ANGGARAN</th>
            <th class="px-5 py-4">PRIORITAS</th>
            <th class="px-5 py-4">STATUS</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
          @forelse($rabs as $rab)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-4">
                <input
                  type="checkbox"
                  class="w-4 h-4 rounded border-slate-300 accent-[#1e293b] cursor-pointer"
                />
              </td>
              <td class="px-5 py-4 font-mono font-medium text-slate-700 text-xs">
                {{ $rab->code }}
              </td>
              <td class="px-5 py-4 text-slate-800 font-medium max-w-xs truncate" title="{{ $rab->title }}">
                {{ $rab->title }}
              </td>
              <td class="px-5 py-4 text-slate-600 text-xs">{{ $rab->division }}</td>
              <td class="px-5 py-4 text-slate-600 text-xs">{{ $rab->user->name ?? '-' }}</td>
              <td class="px-5 py-4 text-slate-400 font-mono text-xs">
                {{ $rab->created_at->format('Y-m-d') }}
              </td>
              <td class="px-5 py-4 font-mono-num font-semibold text-slate-800">
                Rp {{ number_format((float)$rab->total_amount, 0, ',', '.') }}
              </td>
              <td class="px-5 py-4">
                <span class="inline-block px-2 py-0.5 text-[10px] font-semibold {{ $rab->priority->badgeClasses() }} rounded">
                  {{ $rab->priority->label() }}
                </span>
              </td>
              <td class="px-5 py-4">
                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[10px] font-medium {{ $rab->status->badgeClasses() }} rounded-full">
                  <span class="w-1.5 h-1.5 rounded-full {{ $rab->status->dotClasses() }}"></span>
                  {{ $rab->status->label() }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-5 py-8 text-center text-slate-400 text-xs">
                Tidak ada data RAB yang cocok dengan kriteria pencarian/filter.
              </td>
            </tr>
          @endforelse
        </tbody>
        <tfoot class="bg-[#f8fafc] border-t border-slate-200">
          <tr>
            <td colspan="6" class="px-5 py-4 text-center text-xs font-bold text-slate-800">
              GRAND TOTAL (HASIL FILTER)
            </td>
            <td class="px-5 py-4 font-bold text-slate-900 font-mono-num">
              <div class="text-[10px] text-slate-500 font-sans font-normal">
                Rp
              </div>
              {{ number_format($grandTotal, 0, ',', '.') }}
            </td>
            <td colspan="2"></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Pagination Footer -->
    <div class="px-6 py-4 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white text-xs">
      <div class="text-slate-500">
        Menampilkan {{ $rabs->firstItem() ?? 0 }} - {{ $rabs->lastItem() ?? 0 }} dari {{ $rabs->total() }} dokumen
      </div>
      <div>
        {{ $rabs->links() }}
      </div>
    </div>
  </div>
@endsection
