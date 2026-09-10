@extends('layouts.app')

@section('title', 'Detail RAB '.$pengajuan->no_rab.' - SIRAB Kelompok-3')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
  <!-- Top Navigation & Action -->
  <div class="flex items-center justify-between">
    <a href="{{ route('user.dashboard') }}" 
       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 hover:bg-slate-50 transition-colors shadow-sm">
      &larr; Kembali ke Dashboard
    </a>

    <!-- Status Badge -->
    <div>
      @if($pengajuan->status === 'ACC')
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Disetujui (ACC)
        </span>
      @elseif($pengajuan->status === 'Ditolak')
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
          <span class="w-2 h-2 rounded-full bg-rose-500"></span> Pengajuan Ditolak
        </span>
      @else
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
          <span class="w-2 h-2 rounded-full bg-amber-500"></span> Menunggu Persetujuan (Pending)
        </span>
      @endif
    </div>
  </div>

  <!-- Header Card: Info Pengajuan -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 pb-4 border-b border-slate-100">
      <div>
        <div class="font-mono text-xs font-bold text-indigo-600 uppercase tracking-wider">
          {{ $pengajuan->no_rab }}
        </div>
        <h1 class="text-2xl font-bold text-slate-800 mt-1">
          {{ $pengajuan->judul_pengajuan }}
        </h1>
        <div class="text-xs text-slate-500 mt-1">
          Diajukan pada {{ $pengajuan->tanggal_pengajuan->format('d F Y, H:i') }} WIB &bull; 
          Oleh <strong class="text-slate-700">{{ $pengajuan->pengguna->nama_lengkap }}</strong> ({{ $pengajuan->pengguna->jabatan }})
        </div>
      </div>
      <div class="text-right">
        <div class="text-xs text-slate-400 uppercase font-semibold">Total Nilai Anggaran</div>
        <div class="text-2xl font-bold text-indigo-900 font-mono-num mt-0.5">
          Rp {{ number_format((float) $pengajuan->estimasi_total, 0, ',', '.') }}
        </div>
      </div>
    </div>

    <!-- Metadata Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs pt-1">
      <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
        <div class="text-slate-400 font-semibold uppercase">Divisi</div>
        <div class="text-slate-800 font-bold mt-1">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</div>
      </div>
      <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
        <div class="text-slate-400 font-semibold uppercase">Periode Penggunaan</div>
        <div class="text-slate-800 font-bold mt-1">{{ $pengajuan->periode_penggunaan }}</div>
      </div>
      <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
        <div class="text-slate-400 font-semibold uppercase">Prioritas</div>
        <div class="mt-1">
          @if($pengajuan->prioritas === 'Tinggi')
            <span class="font-bold text-rose-600">Tinggi (Mendesak)</span>
          @elseif($pengajuan->prioritas === 'Sedang')
            <span class="font-bold text-amber-600">Sedang</span>
          @else
            <span class="font-bold text-blue-600">Rendah</span>
          @endif
        </div>
      </div>
      <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
        <div class="text-slate-400 font-semibold uppercase">Status Saat Ini</div>
        <div class="text-slate-800 font-bold mt-1">{{ $pengajuan->status }}</div>
      </div>
    </div>

    <!-- Latar Belakang -->
    <div class="pt-2">
      <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Latar Belakang & Urgensi Kebutuhan</div>
      <div class="p-4 bg-slate-50 rounded-xl text-slate-700 text-sm leading-relaxed border border-slate-100">
        {{ $pengajuan->latar_belakang }}
      </div>
    </div>
  </div>

  <!-- Rincian Item Belanja -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
      <h2 class="text-base font-bold text-slate-800">Daftar Rincian Item Anggaran</h2>
      <span class="text-xs text-slate-400">{{ $pengajuan->rincianItem->count() }} item belanja tercatat</span>
    </div>

    <div class="table-container overflow-x-auto">
      <table class="w-full text-left border-collapse text-sm">
        <thead>
          <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
            <th class="py-3 px-4 w-10 text-center">#</th>
            <th class="py-3 px-4">Uraian Barang / Jasa</th>
            <th class="py-3 px-4 w-28">Satuan</th>
            <th class="py-3 px-4 w-24 text-right">Volume</th>
            <th class="py-3 px-4 w-44 text-right">Harga Satuan (Rp)</th>
            <th class="py-3 px-4 w-48 text-right">Total Harga (Rp)</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-700">
          @forelse($pengajuan->rincianItem as $idx => $item)
            <tr class="hover:bg-slate-50/60">
              <td class="py-3 px-4 text-center font-mono text-xs text-slate-400">{{ $idx + 1 }}</td>
              <td class="py-3 px-4 font-medium text-slate-800">{{ $item->uraian_barang }}</td>
              <td class="py-3 px-4 text-xs text-slate-600">{{ $item->satuan }}</td>
              <td class="py-3 px-4 text-right font-mono-num">{{ number_format($item->volume) }}</td>
              <td class="py-3 px-4 text-right font-mono-num">Rp {{ number_format((float) $item->harga_satuan, 0, ',', '.') }}</td>
              <td class="py-3 px-4 text-right font-mono-num font-semibold text-slate-900">
                Rp {{ number_format((float) $item->total_harga, 0, ',', '.') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-6 text-center text-slate-400 text-xs">Tidak ada rincian item.</td>
            </tr>
          @endforelse
        </tbody>
        <tfoot>
          <tr class="bg-slate-50 font-bold border-t-2 border-slate-300 text-slate-800">
            <td colspan="5" class="py-3.5 px-4 text-right uppercase text-xs tracking-wider">
              Total Rencana Anggaran:
            </td>
            <td class="py-3.5 px-4 text-right text-base font-mono-num text-indigo-700">
              Rp {{ number_format((float) $pengajuan->estimasi_total, 0, ',', '.') }}
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <!-- Dokumen Pendukung Card -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
    <h2 class="text-base font-bold text-slate-800">Dokumen Pendukung</h2>

    @if($pengajuan->dokumenPendukung->count() > 0)
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        @foreach($pengajuan->dokumenPendukung as $dokumen)
          <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-indigo-50/40 hover:border-indigo-200 transition-colors">
            <div class="flex items-center gap-3 overflow-hidden">
              <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs shrink-0">
                {{ $dokumen->tipe_dokumen }}
              </div>
              <div class="overflow-hidden">
                <div class="text-xs font-semibold text-slate-800 truncate" title="{{ $dokumen->nama_file }}">
                  {{ $dokumen->nama_file }}
                </div>
                <div class="text-[10px] text-slate-400">
                  Diunggah: {{ $dokumen->waktu_unggah->format('d M Y, H:i') }} WIB
                </div>
              </div>
            </div>
            <a href="{{ route('dokumen.download', $dokumen->id_dokumen) }}" 
               class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors shrink-0">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              Unduh
            </a>
          </div>
        @endforeach
      </div>
    @else
      <div class="text-xs text-slate-400 italic py-2">
        Tidak ada berkas dokumen pendukung yang dilampirkan pada pengajuan ini.
      </div>
    @endif
  </div>

  <!-- Riwayat Alur Persetujuan (Log History) -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
      <h2 class="text-base font-bold text-slate-800">Riwayat & Alur Persetujuan (Approval Timeline)</h2>
      <span class="text-xs text-slate-400 font-medium">Tercatat di sistem</span>
    </div>

    @if($pengajuan->alurPersetujuan->count() > 0)
      <div class="space-y-4">
        @foreach($pengajuan->alurPersetujuan as $alur)
          <div class="p-4 rounded-xl border {{ $alur->status_persetujuan === 'ACC' ? 'border-emerald-200 bg-emerald-50/30' : 'border-rose-200 bg-rose-50/30' }}">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                @if($alur->status_persetujuan === 'ACC')
                  <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800">
                    DISETUJUI (ACC)
                  </span>
                @else
                  <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-100 text-rose-800">
                    DITOLAK
                  </span>
                @endif
                <span class="text-xs font-semibold text-slate-700">Level Persetujuan #{{ $alur->level_persetujuan }}</span>
              </div>
              <div class="text-[11px] text-slate-400 font-mono">
                {{ $alur->tanggal_proses ? $alur->tanggal_proses->format('d M Y, H:i') . ' WIB' : '-' }}
              </div>
            </div>

            <div class="mt-2 text-xs text-slate-600">
              Reviewer: <strong>{{ $alur->reviewer->nama_lengkap ?? 'Administrator' }}</strong> ({{ $alur->reviewer->jabatan ?? 'Reviewer' }})
            </div>

            @if($alur->catatan)
              <div class="mt-2 pt-2 border-t border-slate-200/60 text-xs text-slate-700">
                <span class="font-semibold text-slate-600">Catatan Reviewer:</span> {{ $alur->catatan }}
              </div>
            @endif
          </div>
        @endforeach
      </div>
    @else
      <div class="p-4 rounded-xl border border-amber-200 bg-amber-50/40 text-amber-800 text-xs flex items-center gap-3">
        <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
          <strong>Menunggu Review Administrator:</strong> Pengajuan ini belum diproses oleh reviewer. Status keputusan (ACC / Tolak) akan muncul di sini setelah diperiksa.
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
