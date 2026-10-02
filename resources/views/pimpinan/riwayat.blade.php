@extends('layouts.app')

@section('title', 'Riwayat Persetujuan Final - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('pimpinan.dashboard') }}" class="hover:text-slate-800">Dashboard Pimpinan</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Riwayat Persetujuan Final</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Riwayat Persetujuan Final (Pimpinan)</h1>
      <p class="text-xs text-slate-500 mt-1">Daftar pengajuan yang telah memiliki keputusan final (ACC Final / Ditolak Pimpinan).</p>
    </div>
    <form method="GET" action="{{ route('pimpinan.riwayat') }}" class="flex items-center gap-2">
      <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari No. RAB / Judul..." class="px-3.5 py-2 border border-slate-300 rounded-xl text-xs">
      <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Cari</button>
    </form>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <!-- Quick Filter Tabs -->
    <div class="p-3.5 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-center gap-1.5">
      @php
        $isSemua = empty($statusFilter) || $statusFilter === 'semua';
      @endphp
      <a href="{{ route('pimpinan.riwayat', array_merge(request()->except(['status', 'page']), ['status' => ''])) }}" 
         class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $isSemua ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
        Semua
      </a>

      @php
        $isPencairan = $statusFilter === \App\Enums\StatusPengajuan::PROSES_PENCAIRAN->value;
      @endphp
      <a href="{{ route('pimpinan.riwayat', array_merge(request()->except(['status', 'page']), ['status' => \App\Enums\StatusPengajuan::PROSES_PENCAIRAN->value])) }}" 
         class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $isPencairan ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-indigo-50/60 hover:text-indigo-700' }}">
        <span class="w-1.5 h-1.5 rounded-full {{ $isPencairan ? 'bg-white' : 'bg-indigo-500' }}"></span>
        <span>Pencairan</span>
      </a>

      @php
        $isSelesai = $statusFilter === \App\Enums\StatusPengajuan::SELESAI->value;
      @endphp
      <a href="{{ route('pimpinan.riwayat', array_merge(request()->except(['status', 'page']), ['status' => \App\Enums\StatusPengajuan::SELESAI->value])) }}" 
         class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $isSelesai ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50/60 hover:text-emerald-700' }}">
        <span class="w-1.5 h-1.5 rounded-full {{ $isSelesai ? 'bg-white' : 'bg-emerald-500' }}"></span>
        <span>Selesai</span>
      </a>

      @php
        $isDitolak = $statusFilter === \App\Enums\StatusPengajuan::DITOLAK->value;
      @endphp
      <a href="{{ route('pimpinan.riwayat', array_merge(request()->except(['status', 'page']), ['status' => \App\Enums\StatusPengajuan::DITOLAK->value])) }}" 
         class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 {{ $isDitolak ? 'bg-rose-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-rose-50/60 hover:text-rose-700' }}">
        <span class="w-1.5 h-1.5 rounded-full {{ $isDitolak ? 'bg-white' : 'bg-rose-500' }}"></span>
        <span>Ditolak</span>
      </a>
    </div>

    <!-- Responsive Table Container -->
    <div class="overflow-x-auto">
      <table class="w-full min-w-[800px] text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3.5 whitespace-nowrap">No. RAB</th>
            <th class="px-5 py-3.5 whitespace-nowrap">Pemohon &amp; Divisi</th>
            <th class="px-5 py-3.5 whitespace-nowrap">Judul Pengajuan</th>
            <th class="px-5 py-3.5 text-right whitespace-nowrap">Estimasi Biaya</th>
            <th class="px-5 py-3.5 text-center whitespace-nowrap">Status Akhir</th>
            <th class="px-5 py-3.5 text-center whitespace-nowrap">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($pengajuanList ?? [] as $item)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-3.5 font-mono font-bold text-indigo-700 whitespace-nowrap">{{ $item->no_rab }}</td>
              <td class="px-5 py-3.5 whitespace-nowrap">
                <div class="font-semibold text-slate-800">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</div>
                <div class="text-[10px] text-slate-500 font-medium">{{ $item->divisi->nama_divisi ?? '-' }}</div>
              </td>
              <td class="px-5 py-3.5 text-slate-800 max-w-xs md:max-w-sm">
                <div class="font-medium text-slate-900 truncate" title="{{ $item->judul_pengajuan }}">{{ $item->judul_pengajuan }}</div>
                <div class="text-[10px] text-slate-400 mt-0.5 whitespace-nowrap">
                  {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : '-' }}
                </div>
              </td>
              <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap tabular-nums">
                Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
              </td>
              <td class="px-5 py-3.5 text-center whitespace-nowrap">
                <span class="inline-block whitespace-nowrap px-2.5 py-1 rounded-full text-[10px] font-bold 
                  {{ $item->status === \App\Enums\StatusPengajuan::SELESAI || $item->status === \App\Enums\StatusPengajuan::PROSES_PENCAIRAN ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                  {{ $item->status }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-center whitespace-nowrap">
                <a href="{{ route('pimpinan.show', $item->id_pengajuan) }}" 
                   class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition-colors shrink-0"
                   title="Lihat Detail & Keputusan">
                  <i class="fa-regular fa-eye text-[11px]"></i>
                  <span>Detail</span>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada pengajuan yang cocok dengan filter ini.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
