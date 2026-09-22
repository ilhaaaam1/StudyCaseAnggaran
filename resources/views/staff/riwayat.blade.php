@extends('layouts.app')

@section('title', 'Riwayat Pengajuan RAB Saya - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('staff.dashboard') }}" class="hover:text-slate-800">Dashboard Staf</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Riwayat Pengajuan</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Riwayat Pengajuan RAB Saya</h1>
      <p class="text-xs text-slate-500 mt-1">Lacak status verifikasi alur persetujuan multi-level (Finance &amp; Pimpinan).</p>
    </div>
    <a href="{{ route('staff.rab.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold">
      + Buat Pengajuan Baru
    </a>
  </div>

  <!-- Filter & Table -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
      {{-- PRESENTASI: Menambahkan filter Revisi & Ditolak serta sinkronisasi warna state aktif/non-aktif --}}
      <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('staff.riwayat') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ empty($statusFilter) || $statusFilter === 'semua' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">Semua</a>
        <a href="{{ route('staff.riwayat', ['status' => \App\Enums\StatusPengajuan::MENUNGGU_FINANCE]) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $statusFilter === \App\Enums\StatusPengajuan::MENUNGGU_FINANCE ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700 hover:bg-blue-200' }}">Menunggu Finance</a>
        <a href="{{ route('staff.riwayat', ['status' => \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN]) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $statusFilter === \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN ? 'bg-indigo-600 text-white' : 'bg-indigo-100 text-indigo-700 hover:bg-indigo-200' }}">Menunggu Pimpinan</a>
        <a href="{{ route('staff.riwayat', ['status' => \App\Enums\StatusPengajuan::REVISI]) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $statusFilter === \App\Enums\StatusPengajuan::REVISI ? 'bg-orange-500 text-white' : 'bg-orange-100 text-orange-700 hover:bg-orange-200' }}">Revisi</a>
        <a href="{{ route('staff.riwayat', ['status' => \App\Enums\StatusPengajuan::DITOLAK]) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $statusFilter === \App\Enums\StatusPengajuan::DITOLAK ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200' }}">Ditolak</a>
        <a href="{{ route('staff.riwayat', ['status' => \App\Enums\StatusPengajuan::SELESAI]) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $statusFilter === \App\Enums\StatusPengajuan::SELESAI ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">Selesai</a>
      </div>
      <form method="GET" action="{{ route('staff.riwayat') }}" class="flex items-center gap-2">
        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari No. RAB / Judul..." class="px-3 py-1.5 border border-slate-300 rounded-xl text-xs">
        <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Cari</button>
      </form>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3">No. RAB</th>
            <th class="px-5 py-3">Judul Pengajuan</th>
            <th class="px-5 py-3">Estimasi Total</th>
            <th class="px-5 py-3 text-center">Status Alur</th>
            <th class="px-5 py-3 text-center">Tanggal Diajukan</th>
            <th class="px-5 py-3 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($pengajuanList ?? [] as $rab)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3.5 font-mono font-semibold text-indigo-700">{{ $rab->no_rab }}</td>
              <td class="px-5 py-3.5 font-medium text-slate-900">{{ $rab->judul_pengajuan }}</td>
              <td class="px-5 py-3.5 font-mono font-semibold text-slate-800">Rp {{ number_format((float) $rab->estimasi_total, 0, ',', '.') }}</td>
              <td class="px-5 py-3.5 text-center">
                {{-- PRESENTASI: Menyesuaikan warna badge dengan Dashboard (Konsistensi UI/UX) --}}
                @if($rab->status === \App\Enums\StatusPengajuan::MENUNGGU_FINANCE)
                  {{-- PRESENTASI: Menunggu Review menggunakan nuansa Biru (Blue) --}}
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">
                    Menunggu Review Finance
                  </span>
                @elseif($rab->status === \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN)
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">
                    Menunggu Pimpinan
                  </span>
                @elseif($rab->status === \App\Enums\StatusPengajuan::PROSES_PENCAIRAN)
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 border border-purple-200">
                    Proses Pencairan
                  </span>
                @elseif($rab->status === \App\Enums\StatusPengajuan::SELESAI)
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                    Selesai
                  </span>
                @elseif($rab->status === \App\Enums\StatusPengajuan::REVISI)
                  {{-- PRESENTASI: Memisahkan warna Revisi menjadi Orange --}}
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700 border border-orange-200">
                    Revisi
                  </span>
                @elseif($rab->status === \App\Enums\StatusPengajuan::DITOLAK)
                  {{-- PRESENTASI: Memisahkan warna Ditolak menjadi Merah (Red) --}}
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700 border border-red-200">
                    Ditolak Permanen
                  </span>
                @else
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ $rab->status }}
                  </span>
                @endif
              </td>
              <td class="px-5 py-3.5 text-center font-mono text-slate-500">{{ $rab->tanggal_pengajuan ? $rab->tanggal_pengajuan->format('d/m/Y') : '-' }}</td>
              <td class="px-5 py-3.5 text-center">
                <a href="{{ route('staff.rab.show', $rab->id_pengajuan) }}" class="px-3 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg text-xs font-semibold">
                  Detail &amp; Log
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-8 text-center text-slate-400">Tidak ada pengajuan ditemukan.</td>
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
