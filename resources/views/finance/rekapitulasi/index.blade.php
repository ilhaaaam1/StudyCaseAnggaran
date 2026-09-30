{{-- PRESENTASI: Extend Layout Utama --}}
{{-- Penambahan tag extends untuk memastikan halaman dimuat dalam struktur template utuh (mengatasi blank page) --}}
@extends('layouts.app')

@section('title', 'Rekapitulasi Laporan RAB - SIRAB')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('finance.dashboard') }}" class="hover:text-slate-800">Dashboard Finance</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Rekapitulasi Laporan</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Rekapitulasi Laporan</h1>
      <p class="text-xs text-slate-500 mt-1">Cari, saring, dan cetak dokumen rekapitulasi pengajuan RAB di instansi.</p>
    </div>
  </div>

  <!-- Filter Card -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-6">
    <form method="GET" action="{{ route('finance.rekapitulasi.index') }}">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        <div>
          <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1.5">Tanggal Mulai</label>
          <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-[13px] px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1.5">Tanggal Akhir</label>
          <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-[13px] px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1.5">Status Akhir</label>
          <select name="status" class="w-full text-[13px] px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Semua Status</option>
            @foreach(\App\Enums\StatusPengajuan::cases() as $st)
              <option value="{{ $st->value }}" {{ request('status') == $st->value ? 'selected' : '' }}>{{ $st->label() }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1.5">Kategori Anggaran</label>
          <select name="kategori" class="w-full text-[13px] px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">Semua Kategori</option>
            @foreach($kategoriList as $kat)
              <option value="{{ $kat->nama_kategori }}" {{ request('kategori') == $kat->nama_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex gap-2">
          <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-[13px] font-semibold transition-colors flex items-center justify-center gap-2">
            <i class="fa-solid fa-filter"></i> Filter
          </button>
          <a href="{{ route('finance.rekapitulasi.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-[13px] font-semibold transition-colors flex items-center justify-center" title="Reset Filter">
            <i class="fa-solid fa-rotate-left"></i>
          </a>
        </div>
      </div>
    </form>
  </div>

  <!-- Data Table & Export Buttons -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <h3 class="text-[15px] font-bold text-slate-800">Data Hasil Penyaringan</h3>
      <div class="flex items-center gap-3">
        {{-- PRESENTASI: Tombol Export dengan parameter request->query() agar sesuai filter --}}
        <a href="{{ route('finance.rekapitulasi.pdf', request()->query()) }}" target="_blank" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-colors">
          <i class="fa-solid fa-file-pdf"></i> Cetak PDF
        </a>
        <button type="button" onclick="alert('Fitur Export Excel sedang dikembangkan.')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-xs font-semibold flex items-center gap-2 transition-colors">
          <i class="fa-solid fa-file-excel"></i> Export Excel
        </button>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3.5 w-10 text-center">No</th>
            <th class="px-5 py-3.5">Tanggal Diajukan</th>
            <th class="px-5 py-3.5">No RAB</th>
            <th class="px-5 py-3.5">Pemohon & Bidang</th>
            <th class="px-5 py-3.5">Kategori</th>
            <th class="px-5 py-3.5 text-right">Total Anggaran</th>
            <th class="px-5 py-3.5 text-center">Status Akhir</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          {{-- PRESENTASI: Logika Perulangan & Kalkulasi Total --}}
          {{-- Melakukan loop pada data RAB yang telah difilter dan menjumlahkan pagu nominal ke variabel $totalKeseluruhan --}}
          @php $totalKeseluruhan = 0; @endphp
          @forelse($rekapList as $idx => $item)
            @php $totalKeseluruhan += (float) $item->estimasi_total; @endphp
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="px-5 py-3.5 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
              <td class="px-5 py-3.5 font-mono text-slate-600">{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y') : '-' }}</td>
              <td class="px-5 py-3.5 font-mono font-bold text-indigo-700">{{ $item->no_rab }}</td>
              <td class="px-5 py-3.5">
                <div class="font-semibold text-slate-800">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</div>
                <div class="text-[10px] text-slate-500 font-medium">{{ $item->divisi->nama_divisi ?? '-' }}</div>
              </td>
              <td class="px-5 py-3.5">
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                  {{ $item->kategori_anggaran ?: 'Tanpa Kategori' }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-800">
                Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
              </td>
              <td class="px-5 py-3.5 text-center">
                <span class="inline-block whitespace-nowrap px-2.5 py-1 rounded-full text-[10px] font-bold {{ $item->status->badge() }}">
                  {{ $item->status->label() }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                Tidak ada data RAB yang sesuai dengan filter pencarian Anda.
              </td>
            </tr>
          @endforelse
        </tbody>
        @if($rekapList->count() > 0)
          <tfoot class="bg-indigo-50/50 border-t-2 border-indigo-100">
            <tr>
              <td colspan="5" class="px-5 py-4 text-right font-bold text-slate-700 uppercase tracking-wide">
                Total Keseluruhan
              </td>
              <td class="px-5 py-4 text-right font-mono font-extrabold text-indigo-700 text-sm">
                Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}
              </td>
              <td></td>
            </tr>
          </tfoot>
        @endif
      </table>
    </div>
  </div>
@endsection