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
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3.5 w-10 text-center">#</th>
            <th class="px-5 py-3.5">No. RAB</th>
            <th class="px-5 py-3.5">Pemohon &amp; Bidang</th>
            <th class="px-5 py-3.5">Judul Pengajuan</th>
            <th class="px-5 py-3.5 text-right">Estimasi Biaya</th>
            <th class="px-5 py-3.5 text-center">Status Tahap 1</th>
            <th class="px-5 py-3.5 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($pengajuanList ?? [] as $idx => $item)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3.5 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
              <td class="px-5 py-3.5 font-mono font-bold text-indigo-700">{{ $item->no_rab }}</td>
              <td class="px-5 py-3.5">
                <div class="font-semibold text-slate-900">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</div>
                <div class="text-[10px] text-slate-400">{{ $item->divisi->nama_divisi ?? '-' }}</div>
              </td>
              <td class="px-5 py-3.5 text-slate-800">{{ $item->judul_pengajuan }}</td>
              <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
              <td class="px-5 py-3.5 text-center">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">
                  ACC Finance
                </span>
              </td>
              <td class="px-5 py-3.5 text-center">
                <a href="{{ route('pimpinan.show', $item->id_pengajuan) }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-sm">
                  Keputusan Final &rarr;
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-10 text-center text-slate-400">
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
