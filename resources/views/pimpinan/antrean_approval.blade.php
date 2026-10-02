@extends('layouts.app')

@section('title', 'Antrean Persetujuan Final (Pimpinan) - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('pimpinan.dashboard') }}" class="hover:text-slate-800">Dashboard Pimpinan</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Antrean Tahap 2 (Final)</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Antrean Persetujuan Final (Pimpinan)</h1>
      <p class="text-xs text-slate-500 mt-1">Daftar pengajuan berstatus <strong>ACC Finance</strong> yang siap diputuskan persetujuan akhirnya.</p>
    </div>
    <form method="GET" action="{{ route('pimpinan.antrean') }}" class="flex items-center gap-2">
      <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari No. RAB / Pemohon..." class="px-3.5 py-2 border border-slate-300 rounded-xl text-xs">
      <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Cari</button>
    </form>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[850px] text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3.5 text-left whitespace-nowrap">No. RAB</th>
            <th class="px-5 py-3.5 text-left whitespace-nowrap">Pemohon &amp; Divisi</th>
            <th class="px-5 py-3.5 text-left whitespace-nowrap">Judul Pengajuan</th>
            <th class="px-5 py-3.5 text-right whitespace-nowrap">Estimasi Biaya</th>
            <th class="px-5 py-3.5 text-center whitespace-nowrap">Status Tahap 1</th>
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
                  Diajukan: {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : '-' }}
                </div>
              </td>
              <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap tabular-nums">
                Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
              </td>
              <td class="px-5 py-3.5 text-center whitespace-nowrap">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                  <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                  ACC Finance
                </span>
              </td>
              <td class="px-5 py-3.5 text-center whitespace-nowrap">
                <a href="{{ route('pimpinan.show', $item->id_pengajuan) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition-colors shrink-0"
                   title="Buka dokumen untuk memberikan keputusan persetujuan final">
                  <i class="fa-solid fa-stamp text-[11px]"></i>
                  <span>Review &amp; Putuskan</span>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                Tidak ada antrean ACC Finance saat ini.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if(method_exists($pengajuanList, 'links'))
      <div class="p-4 border-t border-slate-100">
        {{ $pengajuanList->links() }}
      </div>
    @endif
  </div>
@endsection
