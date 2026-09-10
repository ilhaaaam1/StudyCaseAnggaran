@extends('layouts.app')

@section('title', 'Detail & Alur Persetujuan RAB - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('staff.riwayat') }}" class="hover:text-slate-800">Riwayat Pengajuan</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Detail: {{ $pengajuan->no_rab }}</span>
  </div>

  <div class="max-w-4xl mx-auto space-y-6 mb-10">
    <!-- Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
      <div>
        <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Detail Pengajuan RAB</div>
        <h1 class="text-2xl font-bold text-slate-900 font-mono">{{ $pengajuan->no_rab }}</h1>
        <p class="text-sm font-semibold text-slate-700 mt-1">{{ $pengajuan->judul_pengajuan }}</p>
      </div>
      <div class="text-right">
        <span class="text-xs text-slate-400 block">Status Saat Ini:</span>
        <span class="px-3 py-1 rounded-full text-xs font-bold inline-block mt-1
          {{ $pengajuan->status === 'ACC Final' ? 'bg-emerald-100 text-emerald-800' : ($pengajuan->status === 'ACC Finance' ? 'bg-blue-100 text-blue-800' : ($pengajuan->status === 'Pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800')) }}">
          {{ $pengajuan->status }}
        </span>
      </div>
    </div>

    <!-- Rincian Item Belanja -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
      <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">
        Rincian Item Belanja
      </h2>
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
          <tr>
            <th class="px-4 py-2.5 w-10 text-center">#</th>
            <th class="px-4 py-2.5">Uraian</th>
            <th class="px-4 py-2.5 text-center">Satuan</th>
            <th class="px-4 py-2.5 text-center">Volume</th>
            <th class="px-4 py-2.5 text-right">Harga Satuan</th>
            <th class="px-4 py-2.5 text-right">Subtotal</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @foreach($pengajuan->rincianItem as $idx => $item)
            <tr>
              <td class="px-4 py-3 text-center text-slate-400">{{ $idx + 1 }}</td>
              <td class="px-4 py-3 font-medium text-slate-900">{{ $item->uraian_barang }}</td>
              <td class="px-4 py-3 text-center">{{ $item->satuan }}</td>
              <td class="px-4 py-3 text-center font-mono">{{ $item->volume }}</td>
              <td class="px-4 py-3 text-right font-mono">Rp {{ number_format((float) $item->harga_satuan, 0, ',', '.') }}</td>
              <td class="px-4 py-3 text-right font-mono font-semibold text-slate-800">Rp {{ number_format((float) $item->total_harga, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
        <tfoot class="bg-slate-50 font-bold border-t border-slate-200">
          <tr>
            <td colspan="5" class="px-4 py-3 text-right text-xs uppercase text-slate-700">Total Anggaran:</td>
            <td class="px-4 py-3 text-right font-mono text-sm text-indigo-700">Rp {{ number_format((float) $pengajuan->estimasi_total, 0, ',', '.') }}</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Tracking Alur Persetujuan (Level 1 Finance & Level 2 Pimpinan) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
      <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">
        Log Alur Persetujuan Bertingkat
      </h2>
      <div class="space-y-4">
        @forelse($pengajuan->alurPersetujuan as $log)
          <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 flex items-start justify-between">
            <div>
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-indigo-700">Tahap {{ $log->level_persetujuan }} ({{ $log->level_persetujuan == 1 ? 'Finance' : 'Pimpinan' }})</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $log->status_persetujuan === 'ACC' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                  {{ $log->status_persetujuan }}
                </span>
              </div>
              <p class="text-xs text-slate-600 mt-1">Reviewer: <span class="font-semibold">{{ $log->reviewer->nama_lengkap ?? 'Reviewer' }}</span></p>
              @if($log->catatan)
                <p class="text-xs text-slate-500 italic mt-1">&ldquo;{{ $log->catatan }}&rdquo;</p>
              @endif
            </div>
            <div class="text-[11px] text-slate-400 font-mono">
              {{ $log->tanggal_proses ? \Carbon\Carbon::parse($log->tanggal_proses)->format('d/m/Y H:i') : '-' }}
            </div>
          </div>
        @empty
          <div class="text-center py-6 text-xs text-slate-400">
            Belum ada catatan persetujuan. Pengajuan sedang menunggu giliran review Tahap 1 oleh Finance.
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection
