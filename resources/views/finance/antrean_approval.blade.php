@extends('layouts.app')

@section('title', 'Antrean Persetujuan Finance (Tahap 1) - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('finance.dashboard') }}" class="hover:text-slate-800">Dashboard Finance</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Antrean Verifikasi (Tahap 1)</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Antrean Verifikasi Anggaran (Tahap 1)</h1>
      <p class="text-xs text-slate-500 mt-1">Daftar berkas pengajuan RAB berstatus <strong>Pending</strong> yang menunggu validasi Finance.</p>
    </div>
    <form method="GET" action="{{ route('finance.antrean') }}" class="flex items-center gap-2">
      <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari No. RAB / Pemohon..." class="px-3.5 py-2 border border-slate-300 rounded-xl text-xs">
      <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Cari</button>
    </form>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full min-w-[850px] text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3.5 w-10 text-center whitespace-nowrap">#</th>
            <th class="px-5 py-3.5 whitespace-nowrap">No. RAB</th>
            <th class="px-5 py-3.5 whitespace-nowrap">Pemohon &amp; Bidang / Bagian</th>
            <th class="px-5 py-3.5 whitespace-nowrap">Kegiatan &amp; Rentang Waktu</th>
            <th class="px-5 py-3.5 whitespace-nowrap">Pos Anggaran</th>
            <th class="px-5 py-3.5 text-right whitespace-nowrap">Estimasi Total</th>
            <th class="px-5 py-3.5 text-center whitespace-nowrap">Tanggal Diajukan</th>
            <th class="px-5 py-3.5 text-center whitespace-nowrap">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($pengajuanList ?? [] as $idx => $item)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-3.5 text-center text-slate-400 font-mono whitespace-nowrap">{{ $idx + 1 }}</td>
              <td class="px-5 py-3.5 font-mono font-bold text-indigo-700 whitespace-nowrap">{{ $item->no_rab }}</td>
              <td class="px-5 py-3.5 whitespace-nowrap">
                <div class="font-semibold text-slate-900">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</div>
                <div class="text-[10px] text-slate-500 font-medium">{{ $item->divisi->nama_divisi ?? '-' }}</div>
              </td>
              <td class="px-5 py-3.5 text-slate-800 max-w-xs md:max-w-sm">
                <div class="font-medium text-slate-900 truncate" title="{{ $item->judul_pengajuan }}">{{ $item->judul_pengajuan }}</div>
                <div class="text-[10px] text-indigo-600 mt-0.5 flex items-center gap-1 whitespace-nowrap">
                  <i class="fa-regular fa-calendar-days text-[10px]"></i>
                  <span>{{ $item->rentang_tanggal_formatted }}</span>
                  @if($item->durasi_hari)
                    <span class="text-slate-400">({{ $item->durasi_hari }} hr)</span>
                  @endif
                </div>
              </td>
              <td class="px-5 py-3.5 whitespace-nowrap">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                  {{ $item->kategori_anggaran }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap tabular-nums">
                Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
              </td>
              <td class="px-5 py-3.5 text-center font-mono text-slate-500 whitespace-nowrap">
                {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}
              </td>
              <td class="px-5 py-3.5 text-center whitespace-nowrap">
                <a href="{{ route('finance.show', $item->id_pengajuan) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-semibold shadow-xs transition-colors shrink-0"
                   title="Buka untuk verifikasi kelayakan anggaran tahap 1">
                  <i class="fa-solid fa-clipboard-check text-[11px]"></i>
                  <span>Review &amp; Verifikasi</span>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-5 py-10 text-center text-slate-400">
                Tidak ada antrean pending. Semua pengajuan telah diverifikasi!
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
