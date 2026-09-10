@extends('layouts.app')

@section('title', 'Histori Pengajuan RAB Saya - SIRAB Kelompok-3')

@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <h1 class="text-2xl font-bold text-slate-800">Histori Pengajuan Anggaran</h1>
      <p class="text-sm text-slate-500 mt-0.5">
        Rekapitulasi seluruh usulan anggaran yang pernah Anda ajukan di sistem.
      </p>
    </div>
    <a href="{{ route('user.dashboard') }}" 
       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 transition-colors">
      &larr; Kembali ke Dashboard
    </a>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="table-container overflow-x-auto">
      <table class="w-full text-left border-collapse text-sm">
        <thead>
          <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-500">
            <th class="py-3 px-4">No. RAB</th>
            <th class="py-3 px-4">Judul Pengajuan</th>
            <th class="py-3 px-4">Divisi</th>
            <th class="py-3 px-4">Periode</th>
            <th class="py-3 px-4 text-right">Estimasi Total</th>
            <th class="py-3 px-4 text-center">Status</th>
            <th class="py-3 px-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-700">
          @forelse($historiList as $item)
            <tr class="hover:bg-slate-50/80">
              <td class="py-3 px-4 font-mono font-bold">{{ $item->no_rab }}</td>
              <td class="py-3 px-4">{{ $item->judul_pengajuan }}</td>
              <td class="py-3 px-4">{{ $item->divisi->nama_divisi ?? '-' }}</td>
              <td class="py-3 px-4">{{ $item->periode_penggunaan }}</td>
              <td class="py-3 px-4 text-right font-mono font-semibold">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
              <td class="py-3 px-4 text-center">
                @if($item->status === 'ACC')
                  <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">ACC</span>
                @elseif($item->status === 'Ditolak')
                  <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">Ditolak</span>
                @else
                  <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Pending</span>
                @endif
              </td>
              <td class="py-3 px-4 text-center">
                <a href="{{ route('user.rab.show', $item->id_pengajuan) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs">
                  Detail &raquo;
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="py-8 text-center text-slate-400">Belum ada histori pengajuan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
