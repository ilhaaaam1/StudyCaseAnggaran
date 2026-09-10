@extends('layouts.app')

@section('title', 'Review RAB '.$pengajuan->no_rab.' - Administrator')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
  <!-- Top Bar -->
  <div class="flex items-center justify-between">
    <a href="{{ route('admin.dashboard') }}" 
       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 hover:bg-slate-50 transition-colors shadow-sm">
      &larr; Kembali ke Daftar Antrean
    </a>

    <!-- Current Status Badge -->
    <div>
      @if($pengajuan->status === 'ACC')
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
          <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Status Saat Ini: DISETUJUI (ACC)
        </span>
      @elseif($pengajuan->status === 'Ditolak')
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
          <span class="w-2 h-2 rounded-full bg-rose-500"></span> Status Saat Ini: DITOLAK
        </span>
      @else
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
          <span class="w-2 h-2 rounded-full bg-amber-500"></span> Status Saat Ini: PENDING (Menunggu Keputusan)
        </span>
      @endif
    </div>
  </div>

  <!-- Main Info Card -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 pb-4 border-b border-slate-100">
      <div>
        <div class="font-mono text-xs font-bold text-indigo-600 uppercase tracking-wider">
          {{ $pengajuan->no_rab }}
        </div>
        <h1 class="text-2xl font-bold text-slate-800 mt-1">
          {{ $pengajuan->judul_pengajuan }}
        </h1>
        <div class="text-xs text-slate-500 mt-1">
          Diajukan oleh: <strong class="text-slate-700">{{ $pengajuan->pengguna?->nama_lengkap ?? 'Pemohon' }}</strong> 
          ({{ $pengajuan->pengguna?->jabatan ?? '-' }} &bull; {{ $pengajuan->pengguna?->email ?? '-' }})
        </div>
      </div>
      <div class="text-right">
        <div class="text-xs text-slate-400 uppercase font-semibold">Total Nilai Usulan Anggaran</div>
        <div class="text-2xl font-bold text-indigo-900 font-mono-num mt-0.5">
          Rp {{ number_format((float) $pengajuan->estimasi_total, 0, ',', '.') }}
        </div>
      </div>
    </div>

    <!-- Metadata Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
      <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
        <div class="text-slate-400 font-semibold uppercase">Divisi Pemohon</div>
        <div class="text-slate-800 font-bold mt-1">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</div>
      </div>
      <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
        <div class="text-slate-400 font-semibold uppercase">Periode Penggunaan</div>
        <div class="text-slate-800 font-bold mt-1">{{ $pengajuan->periode_penggunaan }}</div>
      </div>
      <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
        <div class="text-slate-400 font-semibold uppercase">Tingkat Prioritas</div>
        <div class="mt-1">
          @if($pengajuan->prioritas === 'Tinggi')
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">Tinggi (Mendesak)</span>
          @elseif($pengajuan->prioritas === 'Sedang')
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Sedang</span>
          @else
            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800">Rendah</span>
          @endif
        </div>
      </div>
      <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
        <div class="text-slate-400 font-semibold uppercase">Waktu Pengajuan</div>
        <div class="text-slate-800 font-bold mt-1">{{ $pengajuan->tanggal_pengajuan->format('d/m/Y H:i') }} WIB</div>
      </div>
    </div>

    <!-- Latar Belakang -->
    <div>
      <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Latar Belakang & Urgensi Kebutuhan</div>
      <div class="p-4 bg-slate-50 rounded-xl text-slate-700 text-sm leading-relaxed border border-slate-100">
        {{ $pengajuan->latar_belakang }}
      </div>
    </div>
  </div>

  <!-- Rincian Item Belanja -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
      <h2 class="text-base font-bold text-slate-800">Rincian Item Belanja yang Diajukan</h2>
      <span class="text-xs text-slate-400">{{ $pengajuan->rincianItem->count() }} item</span>
    </div>

    <div class="table-container overflow-x-auto">
      <table class="w-full text-left border-collapse text-sm">
        <thead>
          <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
            <th class="py-3 px-4 w-10 text-center">#</th>
            <th class="py-3 px-4">Uraian Barang / Kegiatan</th>
            <th class="py-3 px-4 w-28">Satuan</th>
            <th class="py-3 px-4 w-24 text-right">Volume</th>
            <th class="py-3 px-4 w-44 text-right">Harga Satuan (Rp)</th>
            <th class="py-3 px-4 w-48 text-right">Subtotal (Rp)</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-700">
          @foreach($pengajuan->rincianItem as $idx => $item)
            <tr class="hover:bg-slate-50/60">
              <td class="py-3 px-4 text-center font-mono text-xs text-slate-400">{{ $idx + 1 }}</td>
              <td class="py-3 px-4 font-medium text-slate-800">{{ $item->uraian_barang }}</td>
              <td class="py-3 px-4 text-xs text-slate-600">{{ $item->satuan }}</td>
              <td class="py-3 px-4 text-right font-mono-num">{{ number_format($item->volume) }}</td>
              <td class="py-3 px-4 text-right font-mono-num">Rp {{ number_format((float) $item->harga_satuan, 0, ',', '.') }}</td>
              <td class="py-3 px-4 text-right font-mono-num font-bold text-slate-900">
                Rp {{ number_format((float) $item->total_harga, 0, ',', '.') }}
              </td>
            </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr class="bg-slate-50 font-bold border-t-2 border-slate-300 text-slate-800">
            <td colspan="5" class="py-3.5 px-4 text-right uppercase text-xs tracking-wider">
              Total Rencana Anggaran Biaya:
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
               class="inline-flex items-center gap-1 px-3.5 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors shrink-0 cursor-pointer">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
              </svg>
              Unduh File
            </a>
          </div>
        @endforeach
      </div>
    @else
      <div class="text-xs text-slate-400 italic py-2">
        Tidak ada berkas dokumen pendukung yang dilampirkan oleh pemohon.
      </div>
    @endif
  </div>

  <!-- Riwayat Persetujuan Sebelumnya -->
  @if($pengajuan->alurPersetujuan->count() > 0)
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-3">
      <h2 class="text-base font-bold text-slate-800">Riwayat Catatan Persetujuan Sebelumnya</h2>
      <div class="space-y-3">
        @foreach($pengajuan->alurPersetujuan as $alur)
          <div class="p-4 rounded-xl border {{ $alur->status_persetujuan === 'ACC' ? 'border-emerald-200 bg-emerald-50/25' : 'border-rose-200 bg-rose-50/25' }}">
            <div class="flex items-center justify-between text-xs">
              <div>
                Keputusan: <strong class="{{ $alur->status_persetujuan === 'ACC' ? 'text-emerald-700' : 'text-rose-700' }}">{{ $alur->status_persetujuan }}</strong>
                &bull; Reviewer: <strong>{{ $alur->reviewer->nama_lengkap ?? 'Administrator' }}</strong>
              </div>
              <div class="text-slate-400 font-mono">
                {{ $alur->tanggal_proses ? $alur->tanggal_proses->format('d M Y, H:i') . ' WIB' : '-' }}
              </div>
            </div>
            @if($alur->catatan)
              <div class="mt-2 text-xs text-slate-700 pt-2 border-t border-slate-200/50">
                {{ $alur->catatan }}
              </div>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  @endif

  <!-- FORM APPROVAL (ACC / TOLAK) -->
  <div class="bg-white p-6 rounded-2xl border-2 border-indigo-200 shadow-md space-y-4">
    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
      <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
        &check;
      </div>
      <div>
        <h2 class="text-base font-bold text-slate-800">Formulir Keputusan Persetujuan (Approval)</h2>
        <p class="text-xs text-slate-500">Tentukan status verifikasi dan berikan catatan resmi untuk pemohon anggaran.</p>
      </div>
    </div>

    <form action="{{ route('admin.pengajuan.approve', $pengajuan->id_pengajuan) }}" method="POST" class="space-y-5">
      @csrf

      <!-- Pilihan Keputusan: ACC atau Ditolak -->
      <div>
        <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
          Keputusan Administrator <span class="text-rose-500">*</span>
        </label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <label class="relative flex items-center gap-3 p-4 rounded-xl border-2 border-slate-200 hover:border-emerald-300 bg-slate-50/50 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/40 cursor-pointer transition-all">
            <input type="radio" name="status" value="ACC" required class="text-emerald-600 focus:ring-emerald-500 h-4 w-4"
                   {{ old('status', $pengajuan->status === 'ACC' ? 'ACC' : '') === 'ACC' ? 'checked' : '' }}>
            <div>
              <div class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Setujui (ACC)
              </div>
              <div class="text-xs text-slate-500 mt-0.5">Pengajuan disetujui sesuai spesifikasi dan pagu anggaran.</div>
            </div>
          </label>

          <label class="relative flex items-center gap-3 p-4 rounded-xl border-2 border-slate-200 hover:border-rose-300 bg-slate-50/50 has-[:checked]:border-rose-600 has-[:checked]:bg-rose-50/40 cursor-pointer transition-all">
            <input type="radio" name="status" value="Ditolak" required class="text-rose-600 focus:ring-rose-500 h-4 w-4"
                   {{ old('status', $pengajuan->status === 'Ditolak' ? 'Ditolak' : '') === 'Ditolak' ? 'checked' : '' }}>
            <div>
              <div class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Tolak Pengajuan
              </div>
              <div class="text-xs text-slate-500 mt-0.5">Pengajuan ditolak dengan alasan penolakan pada catatan.</div>
            </div>
          </label>
        </div>
      </div>

      <!-- Catatan Administrator -->
      <div>
        <label for="catatan" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
          Catatan / Pertimbangan Verifikasi
        </label>
        <textarea name="catatan" id="catatan" rows="3"
                  placeholder="Tuliskan catatan rekomendasi, justifikasi persetujuan, atau alasan penolakan untuk pemohon..."
                  class="w-full px-3.5 py-2.5 rounded-lg border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">{{ old('catatan') }}</textarea>
      </div>

      <!-- Submit Button -->
      <div class="flex items-center justify-end gap-3 pt-2">
        <a href="{{ route('admin.dashboard') }}" 
           class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors">
          Batal
        </a>
        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyimpan keputusan persetujuan untuk pengajuan ini?')"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-md shadow-indigo-100 transition-all cursor-pointer">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          Simpan Keputusan Persetujuan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
