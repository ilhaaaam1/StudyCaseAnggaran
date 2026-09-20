@extends('layouts.app')

@section('title', 'Detail Pengajuan ' . $pengajuan->no_rab . ' - SIRAB')

@section('content')
  @if(session('success'))
    <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2 shadow-sm">
      <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
      </svg>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6">
    SIRAB / <a href="{{ route('pengajuan.index') }}" class="hover:underline">Pengajuan</a> / <span class="text-slate-800 font-medium">{{ $pengajuan->no_rab }}</span>
  </div>

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
    <div>
      <div class="flex items-center gap-3 mb-1">
        <span class="text-xs font-mono font-bold text-slate-700 bg-slate-100 px-2.5 py-0.5 rounded border border-slate-200">
          {{ $pengajuan->no_rab }}
        </span>
        @php
          $statusBadge = match($pengajuan->status) {
            'disetujui' => ['bg' => 'bg-emerald-50 text-emerald-600', 'dot' => 'bg-emerald-400'],
            'diajukan' => ['bg' => 'bg-amber-50 text-amber-600', 'dot' => 'bg-amber-400'],
            'revisi' => ['bg' => 'bg-orange-50 text-orange-600', 'dot' => 'bg-orange-400'],
            'ditolak' => ['bg' => 'bg-rose-50 text-rose-600', 'dot' => 'bg-rose-400'],
            default => ['bg' => 'bg-slate-100 text-slate-600', 'dot' => 'bg-slate-400'],
          };
        @endphp
        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold {{ $statusBadge['bg'] }} rounded-full">
          <span class="w-1.5 h-1.5 rounded-full {{ $statusBadge['dot'] }}"></span>
          {{ ucfirst($pengajuan->status) }}
        </span>
      </div>
      <h1 class="text-2xl font-bold text-slate-900">
        {{ $pengajuan->judul_pengajuan }}
      </h1>
      <p class="text-xs text-slate-500 mt-1">
        Diajukan oleh: <span class="font-medium text-slate-700">{{ $pengajuan->pengguna->nama_lengkap ?? '-' }}</span> ({{ $pengajuan->divisi->nama_divisi ?? '-' }}) · Tanggal: {{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('d F Y H:i') : '-' }}
      </p>
    </div>
    <div class="text-right">
      <div class="text-xs text-slate-400 mb-0.5">Estimasi Total Anggaran</div>
      <div class="text-2xl font-bold text-slate-900 font-mono-num text-emerald-600">
        Rp {{ number_format((float)$pengajuan->estimasi_total, 0, ',', '.') }}
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
    <!-- LEFT COLUMN -->
    <div class="lg:col-span-2 space-y-6">
      <!-- Info Detail Card -->
      <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5 space-y-4">
        <h2 class="text-xs font-bold text-slate-700 tracking-wide uppercase border-b border-slate-100 pb-2">
          Latar Belakang & Kebutuhan
        </h2>
        <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
          {{ $pengajuan->latar_belakang }}
        </p>
      </div>

      <!-- Rincian Item Belanja -->
      <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="bg-[#f8fafc] px-5 py-3 border-b border-slate-200 flex justify-between items-center">
          <h2 class="text-xs font-bold text-slate-700 tracking-wide uppercase">
            Rincian Item Anggaran Biaya ({{ $pengajuan->rincianItem->count() }} item)
          </h2>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs whitespace-nowrap">
            <thead class="text-[10px] text-slate-400 uppercase bg-slate-50 border-b border-slate-200">
              <tr>
                <th class="px-4 py-3 text-center w-12">No.</th>
                <th class="px-4 py-3">Uraian Kegiatan / Barang</th>
                <th class="px-4 py-3 text-center w-24">Satuan</th>
                <th class="px-4 py-3 text-center w-20">Volume</th>
                <th class="px-4 py-3 text-right w-36">Harga Satuan (Rp)</th>
                <th class="px-4 py-3 text-right w-36">Total Harga (Rp)</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-mono-num">
              @foreach($pengajuan->rincianItem as $idx => $item)
                <tr class="hover:bg-slate-50">
                  <td class="px-4 py-3 text-center text-slate-400 font-sans">{{ $idx + 1 }}</td>
                  <td class="px-4 py-3 font-sans font-medium text-slate-800">{{ $item->uraian_barang }}</td>
                  <td class="px-4 py-3 text-center text-slate-600 font-sans">{{ $item->satuan }}</td>
                  <td class="px-4 py-3 text-center text-slate-700">{{ $item->volume }}</td>
                  <td class="px-4 py-3 text-right text-slate-700">Rp {{ number_format((float)$item->harga_satuan, 0, ',', '.') }}</td>
                  <td class="px-4 py-3 text-right font-semibold text-slate-900">Rp {{ number_format((float)$item->total_harga, 0, ',', '.') }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot class="bg-[#f8fafc] border-t-2 border-slate-200 font-mono-num">
              <tr>
                <td colspan="5" class="px-4 py-3 font-sans font-bold text-right text-slate-800">
                  TOTAL KESELURUHAN
                </td>
                <td class="px-4 py-3 text-right font-bold text-emerald-600 text-sm">
                  Rp {{ number_format((float)$pengajuan->estimasi_total, 0, ',', '.') }}
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- Dokumen Pendukung -->
      @if($pengajuan->dokumenPendukung->isNotEmpty())
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5">
          <h2 class="text-xs font-bold text-slate-700 tracking-wide uppercase border-b border-slate-100 pb-2 mb-3">
            Dokumen Lampiran
          </h2>
          <div class="grid grid-cols-1 gap-3">
            @foreach($pengajuan->dokumenPendukung as $dokumen)
              @php
                $extension = pathinfo($dokumen->path_file, PATHINFO_EXTENSION);
                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
                $isPdf = strtolower($extension) === 'pdf';
                $previewUrl = asset('storage/' . $dokumen->path_file);
                $downloadUrl = asset('storage/' . $dokumen->path_file);
              @endphp
              <div class="border border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center bg-slate-50 relative group">
                @if($isImage)
                  <img src="{{ $previewUrl }}" alt="{{ $dokumen->nama_file }}" class="max-h-48 object-contain rounded-lg mb-3 shadow-sm border border-slate-200" />
                  <p class="text-xs text-slate-600 font-medium truncate w-full text-center" title="{{ $dokumen->nama_file }}">{{ $dokumen->nama_file }}</p>
                  <div class="mt-3 flex gap-2">
                    <a href="{{ $previewUrl }}" target="_blank" class="text-xs px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Lihat Penuh</a>
                    <a href="{{ $downloadUrl }}" download class="text-xs px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Unduh</a>
                  </div>
                @elseif($isPdf)
                  <div class="w-full h-48 mb-3 border border-slate-200 rounded-lg overflow-hidden bg-white">
                    <iframe src="{{ $previewUrl }}" class="w-full h-full" title="{{ $dokumen->nama_file }}"></iframe>
                  </div>
                  <p class="text-xs text-slate-600 font-medium truncate w-full text-center" title="{{ $dokumen->nama_file }}">{{ $dokumen->nama_file }}</p>
                  <div class="mt-3 flex gap-2">
                    <a href="{{ $previewUrl }}" target="_blank" class="text-xs px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Buka Tab Baru</a>
                    <a href="{{ $downloadUrl }}" download class="text-xs px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Unduh PDF</a>
                  </div>
                @else
                  <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                  </div>
                  <p class="text-xs text-slate-600 font-medium truncate w-full text-center" title="{{ $dokumen->nama_file }}">{{ $dokumen->nama_file }}</p>
                  <div class="mt-3">
                    <a href="{{ $downloadUrl }}" download class="text-xs px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Unduh File</a>
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>

    <!-- RIGHT COLUMN -->
    <div class="lg:col-span-1 space-y-6">
      <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5 space-y-4">
        <h2 class="text-xs font-bold text-slate-700 tracking-wide uppercase border-b border-slate-100 pb-2">
          Informasi Metadata
        </h2>
        <div class="space-y-3 text-xs">
          <div class="flex justify-between">
            <span class="text-slate-400">ID Pengajuan (PK):</span>
            <span class="font-mono font-bold text-slate-700">#{{ $pengajuan->id_pengajuan }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Nomor Dokumen:</span>
            <span class="font-mono font-medium text-slate-700">{{ $pengajuan->no_rab }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Divisi Pemohon:</span>
            <span class="font-medium text-slate-700">{{ $pengajuan->divisi->nama_divisi ?? '-' }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Periode:</span>
            <span class="font-medium text-slate-700">{{ $pengajuan->periode_penggunaan }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Tingkat Prioritas:</span>
            <span class="font-medium capitalize text-slate-700">{{ $pengajuan->prioritas }}</span>
          </div>
        </div>
        <div class="pt-2 border-t border-slate-100">
          <a href="{{ route('pengajuan.index') }}" class="block w-full text-center bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium py-2 rounded transition-colors">
            &larr; Kembali ke Daftar
          </a>
        </div>
      </div>

      <!-- Form Persetujuan & Evaluasi Admin (Workflow Logic) -->
      @if($pengajuan->status === 'diajukan')
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5 space-y-4">
          <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <h2 class="text-xs font-bold text-slate-700 tracking-wide uppercase">
              Aksi Persetujuan (Admin)
            </h2>
            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
              <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
              Menunggu Review
            </span>
          </div>

          <form method="POST" action="{{ route('pengajuan.approval', $pengajuan->id_pengajuan) }}" class="space-y-3">
            @csrf
            @method('PUT')

            <div>
              <label for="catatan" class="block text-[11px] font-medium text-slate-600 mb-1">
                Catatan / Justifikasi Evaluasi:
              </label>
              <textarea
                id="catatan"
                name="catatan"
                rows="3"
                placeholder="Tuliskan alasan persetujuan, penolakan, atau arahan revisi..."
                class="w-full border border-slate-200 rounded-lg p-2.5 text-xs text-slate-700 placeholder-slate-400 focus:outline-none focus:border-indigo-500 transition-colors"
              >{{ old('catatan') }}</textarea>
              @error('catatan')
                <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div class="space-y-2 pt-1">
              <button
                type="submit"
                name="status_persetujuan"
                value="disetujui"
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs py-2.5 px-3 rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-sm"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Setujui Pengajuan
              </button>

              <button
                type="submit"
                name="status_persetujuan"
                value="ditolak"
                class="w-full bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs py-2.5 px-3 rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-sm"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Tolak Pengajuan
              </button>

              <button
                type="submit"
                name="status_persetujuan"
                value="revisi"
                class="w-full bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 font-semibold text-xs py-2 px-3 rounded-lg transition-colors flex items-center justify-center gap-1.5"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Minta Revisi
              </button>
            </div>
          </form>
        </div>
      @endif

      <!-- Riwayat Persetujuan (Child Table: alur_persetujuan) -->
      <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-5 space-y-3">
        <h2 class="text-xs font-bold text-slate-700 tracking-wide uppercase border-b border-slate-100 pb-2 flex items-center justify-between">
          <span>Riwayat Persetujuan</span>
          <span class="text-[10px] text-slate-400 font-normal">Tabel: alur_persetujuan</span>
        </h2>

        @forelse($pengajuan->alurPersetujuan as $log)
          <div class="p-3 rounded-lg border text-xs {{ $log->status_persetujuan === 'disetujui' ? 'bg-emerald-50/60 border-emerald-200' : ($log->status_persetujuan === 'ditolak' ? 'bg-rose-50/60 border-rose-200' : 'bg-amber-50/60 border-amber-200') }}">
            <div class="flex items-center justify-between font-semibold mb-1">
              <span class="text-slate-800">{{ $log->reviewer->nama_lengkap ?? 'Reviewer Admin' }}</span>
              <span class="uppercase text-[10px] px-2 py-0.5 rounded font-bold {{ $log->status_persetujuan === 'disetujui' ? 'bg-emerald-100 text-emerald-700' : ($log->status_persetujuan === 'ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                {{ $log->status_persetujuan }}
              </span>
            </div>
            @if($log->catatan)
              <p class="text-slate-700 italic my-1.5 text-[11px] bg-white/70 p-2 rounded border border-slate-100">
                &ldquo;{{ $log->catatan }}&rdquo;
              </p>
            @endif
            <div class="flex justify-between items-center text-[10px] text-slate-400 mt-2 pt-1 border-t border-slate-100/60 font-mono">
              <span>Level: {{ $log->level_persetujuan }}</span>
              <span>{{ $log->tanggal_proses ? $log->tanggal_proses->format('d M Y H:i') : '-' }}</span>
            </div>
          </div>
        @empty
          <div class="text-center py-4 text-xs text-slate-400">
            <p>Belum ada riwayat persetujuan untuk pengajuan ini.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection
