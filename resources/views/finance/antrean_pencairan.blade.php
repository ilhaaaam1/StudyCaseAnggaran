@extends('layouts.app')

@section('title', 'Antrean Persetujuan Finance (Tahap 1) - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('finance.dashboard') }}" class="hover:text-slate-800">Dashboard Finance</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Antrean Pencairan Dana</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Antrean Pencairan Dana</h1>
      <p class="text-xs text-slate-500 mt-1">Daftar pengajuan RAB yang telah disetujui Pimpinan dan menunggu proses pencairan oleh Finance.</p>
    </div>
    <form method="GET" action="{{ route('finance.pencairan') }}" class="flex items-center gap-2">
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
            <th class="px-5 py-3.5">Pemohon &amp; Unit Kerja</th>
            <th class="px-5 py-3.5">Kegiatan &amp; Rentang Waktu</th>
            <th class="px-5 py-3.5">Pos Anggaran</th>
            <th class="px-5 py-3.5 text-right">Estimasi Total</th>
            <th class="px-5 py-3.5 text-center">Tanggal Diajukan</th>
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
                <div class="text-[10px] text-slate-500 font-medium">{{ $item->divisi->nama_divisi ?? '-' }}</div>
              </td>
              <td class="px-5 py-3.5 text-slate-800">
                <div class="font-medium text-slate-900">{{ $item->judul_pengajuan }}</div>
                <div class="text-[10px] text-indigo-600 mt-0.5 flex items-center gap-1">
                  <i class="fa-regular fa-calendar-days text-[10px]"></i>
                  <span>{{ $item->rentang_tanggal_formatted }}</span>
                  @if($item->durasi_hari)
                    <span class="text-slate-400">({{ $item->durasi_hari }} hr)</span>
                  @endif
                </div>
              </td>
              <td class="px-5 py-3.5">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                  {{ $item->kategori_anggaran }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900">
                Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
              </td>
              <td class="px-5 py-3.5 text-center font-mono text-slate-500">
                {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}
              </td>
              <td class="px-5 py-3.5 text-center">
                <a href="{{ route('finance.show', $item->id_pengajuan) }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-sm">
                  Upload Bukti
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-5 py-10 text-center text-slate-400">
                Tidak ada antrean pencairan.
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
