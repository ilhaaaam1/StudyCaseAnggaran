@extends('layouts.app')

@section('title', 'Riwayat Verifikasi Finance - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('finance.dashboard') }}" class="hover:text-slate-800">Dashboard Finance</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Riwayat Verifikasi Finance</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Riwayat Verifikasi Finance</h1>
      <p class="text-xs text-slate-500 mt-1">Daftar seluruh berkas RAB yang telah melewati peninjauan tahap 1 oleh tim Finance.</p>
    </div>
    <form method="GET" action="{{ route('finance.riwayat') }}" class="flex items-center gap-2">
      <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari No. RAB / Judul..." class="px-3.5 py-2 border border-slate-300 rounded-xl text-xs">
      <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Cari</button>
    </form>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-100 flex items-center gap-2">
      <a href="{{ route('finance.riwayat') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ empty($statusFilter) ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600' }}">Semua</a>
      <a href="{{ route('finance.riwayat', ['status' => 'ACC Finance']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $statusFilter === 'ACC Finance' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-800' }}">ACC Finance</a>
      <a href="{{ route('finance.riwayat', ['status' => 'ACC Final']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $statusFilter === 'ACC Final' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-800' }}">ACC Final</a>
      <a href="{{ route('finance.riwayat', ['status' => 'Ditolak Finance']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $statusFilter === 'Ditolak Finance' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-800' }}">Ditolak Finance</a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3.5">No. RAB</th>
            <th class="px-5 py-3.5">Pemohon</th>
            <th class="px-5 py-3.5">Judul Pengajuan</th>
            <th class="px-5 py-3.5 text-right">Estimasi Biaya</th>
            <th class="px-5 py-3.5 text-center">Status Alur</th>
            <th class="px-5 py-3.5 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($pengajuanList ?? [] as $item)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3.5 font-mono font-bold text-indigo-700">{{ $item->no_rab }}</td>
              <td class="px-5 py-3.5 text-slate-800 font-medium">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</td>
              <td class="px-5 py-3.5 text-slate-800">{{ $item->judul_pengajuan }}</td>
              <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
              <td class="px-5 py-3.5 text-center">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold 
                  {{ $item->status === 'ACC Final' ? 'bg-emerald-100 text-emerald-800' : ($item->status === 'ACC Finance' ? 'bg-blue-100 text-blue-800' : 'bg-rose-100 text-rose-800') }}">
                  {{ $item->status }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-center">
                <a href="{{ route('finance.show', $item->id_pengajuan) }}" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold">
                  Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-8 text-center text-slate-400">Belum ada riwayat verifikasi.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
