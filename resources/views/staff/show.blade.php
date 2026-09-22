@extends('layouts.app')

@section('title', 'Detail & Alur Persetujuan RAB - SIRAB SDN Sidokare 3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('staff.riwayat') }}" class="hover:text-slate-800 transition-colors">Riwayat Pengajuan</a>
    <span>/</span>
    <span class="text-slate-800 font-semibold">Detail: {{ $pengajuan->no_rab }}</span>
  </div>

  <div class="max-w-4xl mx-auto space-y-6 mb-10">

    <!-- 5. KOTAK CATATAN REVISI (ALERT BANNER MENCOROK DI BAGIAN TERATAS JIKA STATUS REVISI) -->
    @if($pengajuan->status === \App\Enums\StatusPengajuan::REVISI)
      @php
        $catatanRevisi = $pengajuan->alurPersetujuan
          ->where('status_persetujuan', 'Revisi')
          ->sortByDesc('tanggal_proses')
          ->first()
          ?: $pengajuan->alurPersetujuan->whereNotNull('catatan')->sortByDesc('tanggal_proses')->first();
      @endphp
      <div class="bg-amber-50 border-2 border-amber-400 rounded-2xl p-5 shadow-sm space-y-3 ring-2 ring-amber-400/20 animate-fade-in">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-200 text-amber-900 flex items-center justify-center text-lg shrink-0 mt-0.5">
              <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
              <h3 class="text-sm font-bold text-amber-950 flex items-center gap-2">
                Perhatian: Berkas Pengajuan Memerlukan Perbaikan / Revisi
              </h3>
              <p class="text-xs text-amber-800 mt-0.5">
                Reviewer: <strong>{{ $catatanRevisi->reviewer->nama_lengkap ?? 'Tim Pemeriksa Anggaran' }}</strong> 
                ({{ ($catatanRevisi->level_persetujuan ?? 1) == 1 ? 'Bendahara BOS' : 'Kepala Sekolah' }}) 
                &bull; <span class="font-mono text-[11px]">{{ $catatanRevisi && $catatanRevisi->tanggal_proses ? \Carbon\Carbon::parse($catatanRevisi->tanggal_proses)->translatedFormat('d F Y, H:i') : now()->format('d M Y') }} WIB</span>
              </p>
            </div>
          </div>
          <a href="{{ route('staff.rab.edit', $pengajuan->id_pengajuan) }}"
             class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold inline-flex items-center gap-1.5 shadow-xs transition-all shrink-0">
            <i class="fa-solid fa-pen-to-square text-xs"></i>
            <span>Perbaiki Pengajuan Sekarang</span>
          </a>
        </div>

        <div class="bg-white/90 p-4 rounded-xl border border-amber-200 text-xs text-amber-950 space-y-1.5">
          <div class="font-bold text-[11px] text-amber-900 uppercase tracking-wider flex items-center gap-1.5">
            <i class="fa-solid fa-clipboard-list text-amber-700"></i> Poin Koreksi yang Wajib Diperbaiki:
          </div>
          <p class="text-xs leading-relaxed italic pl-3 border-l-2 border-amber-500 font-medium text-slate-800">
            &ldquo;{{ $catatanRevisi->catatan ?? 'Silakan lengkapi rincian dokumen belanja atau sesuaikan volume dan standar harga satuan dana BOS.' }}&rdquo;
          </p>
        </div>
      </div>
    @endif

    <!-- Header Dokumen RAB -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
          Rencana Anggaran Biaya (RAB) Sekolah
        </div>
        <h1 class="text-2xl font-bold text-slate-900 font-mono">{{ $pengajuan->no_rab }}</h1>
        <p class="text-sm font-semibold text-slate-700 mt-1">{{ $pengajuan->judul_pengajuan }}</p>
        <div class="mt-2.5 flex flex-wrap items-center gap-2">
          <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-semibold rounded-lg border border-slate-200 inline-flex items-center gap-1">
            <i class="fa-solid fa-school text-slate-500 text-[10px]"></i>
            {{ $pengajuan->divisi->nama_divisi ?? 'Unit Sekolah' }}
          </span>
          @if($pengajuan->kategori_anggaran)
            <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-[11px] font-semibold rounded-lg border border-indigo-100 inline-flex items-center gap-1">
              <i class="fa-solid fa-tag text-indigo-500 text-[10px]"></i>
              {{ $pengajuan->kategori_anggaran }}
            </span>
          @endif
        </div>
      </div>
      <div class="text-left sm:text-right shrink-0">
        <span class="text-[11px] text-slate-400 font-semibold block uppercase">Status Terkini:</span>
        <div class="mt-1">
          @if($pengajuan->status === \App\Enums\StatusPengajuan::MENUNGGU_FINANCE)
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 inline-flex items-center gap-1">
              <i class="fa-solid fa-clock text-[10px]"></i> Menunggu Verifikasi Bendahara
            </span>
          @elseif($pengajuan->status === \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN)
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200 inline-flex items-center gap-1">
              <i class="fa-solid fa-user-check text-[10px]"></i> Menunggu Persetujuan Kepsek
            </span>
          @elseif($pengajuan->status === \App\Enums\StatusPengajuan::REVISI)
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1">
              <i class="fa-solid fa-triangle-exclamation text-[10px]"></i> Perlu Revisi
            </span>
          @elseif($pengajuan->status === \App\Enums\StatusPengajuan::PROSES_PENCAIRAN)
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
              <i class="fa-solid fa-money-bill-transfer text-[10px]"></i> Proses Pencairan (BOS)
            </span>
          @elseif($pengajuan->status === \App\Enums\StatusPengajuan::SELESAI)
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 inline-flex items-center gap-1">
              <i class="fa-solid fa-circle-check text-[10px]"></i> Selesai (SPJ Lengkap)
            </span>
          @elseif($pengajuan->status === \App\Enums\StatusPengajuan::DITOLAK)
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200 inline-flex items-center gap-1">
              <i class="fa-solid fa-ban text-[10px]"></i> Ditolak
            </span>
          @else
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-300 inline-flex items-center gap-1">
              {{ $pengajuan->status->value ?? $pengajuan->status }}
            </span>
          @endif
        </div>

        @if(in_array($pengajuan->status, [\App\Enums\StatusPengajuan::MENUNGGU_FINANCE, \App\Enums\StatusPengajuan::DRAFT, \App\Enums\StatusPengajuan::REVISI]))
          <div class="mt-3">
            <a href="{{ route('staff.rab.edit', $pengajuan->id_pengajuan) }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-xl transition-colors shadow-xs">
              <i class="fa-solid fa-pen-to-square text-[11px]"></i>
              <span>Edit Pengajuan</span>
            </a>
          </div>
        @endif
      </div>
    </div>

    <!-- 5. MINI TRACKER ALUR PERSETUJUAN (VISUAL STEPPER 4 TAHAP SEKOLAH) -->
    @php
      $status = $pengajuan->status;
      
      // Step 1: Diajukan oleh Staf Pemohon (Selalu selesai jika data tersimpan)
      $s1Done = true;

      // Step 2: Verifikasi Bendahara BOS
      $s2Active = ($status === \App\Enums\StatusPengajuan::MENUNGGU_FINANCE);
      $s2Revision = ($status === \App\Enums\StatusPengajuan::REVISI);
      $s2Done = in_array($status, [
          \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN,
          \App\Enums\StatusPengajuan::PROSES_PENCAIRAN,
          \App\Enums\StatusPengajuan::SELESAI,
      ]);
      $s2Rejected = ($status === \App\Enums\StatusPengajuan::DITOLAK && $pengajuan->alurPersetujuan->where('level_persetujuan', 1)->isNotEmpty());

      // Step 3: Persetujuan Kepala Sekolah
      $s3Active = ($status === \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN);
      $s3Done = in_array($status, [
          \App\Enums\StatusPengajuan::PROSES_PENCAIRAN,
          \App\Enums\StatusPengajuan::SELESAI,
      ]);
      $s3Rejected = ($status === \App\Enums\StatusPengajuan::DITOLAK && $pengajuan->alurPersetujuan->where('level_persetujuan', 2)->isNotEmpty());

      // Step 4: Pencairan Dana & SPJ
      $s4Active = ($status === \App\Enums\StatusPengajuan::PROSES_PENCAIRAN);
      $s4Done = ($status === \App\Enums\StatusPengajuan::SELESAI);
    @endphp

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-5">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
          <i class="fa-solid fa-route text-indigo-600"></i>
          Alur Persetujuan &amp; Posisi Berkas Sekolah
        </h2>
        <span class="text-[11px] text-slate-400">4 Tahap Standar Operasional</span>
      </div>

      <!-- Stepper Container -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 relative">

        <!-- STEP 1: Diajukan -->
        <div class="p-4 rounded-xl border {{ $s1Done ? 'bg-emerald-50/70 border-emerald-200' : 'bg-slate-50 border-slate-200' }} flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="w-7 h-7 rounded-full bg-emerald-600 text-white font-bold text-xs flex items-center justify-center">
                <i class="fa-solid fa-check text-[11px]"></i>
              </span>
              <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-full">Selesai</span>
            </div>
            <h4 class="font-bold text-slate-900 text-xs">1. Berkas Diajukan</h4>
            <p class="text-[11px] text-slate-500 mt-1 leading-snug">Oleh Staf Pemohon Unit Kerja</p>
          </div>
          <div class="text-[10px] font-mono text-slate-400 mt-3 pt-2 border-t border-emerald-200/50">
            {{ $pengajuan->tanggal_pengajuan ? $pengajuan->tanggal_pengajuan->format('d/m/Y H:i') : '-' }}
          </div>
        </div>

        <!-- STEP 2: Verifikasi Bendahara BOS -->
        @php
          $bgS2 = $s2Done ? 'bg-emerald-50/70 border-emerald-200' : ($s2Active ? 'bg-blue-50/80 border-blue-300 ring-2 ring-blue-400/20' : ($s2Revision ? 'bg-amber-50 border-amber-300 ring-2 ring-amber-400/20' : ($s2Rejected ? 'bg-rose-50 border-rose-200' : 'bg-slate-50 border-slate-200 opacity-60')));
        @endphp
        <div class="p-4 rounded-xl border {{ $bgS2 }} flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center {{ $s2Done ? 'bg-emerald-600 text-white' : ($s2Active ? 'bg-blue-600 text-white animate-pulse' : ($s2Revision ? 'bg-amber-500 text-white' : ($s2Rejected ? 'bg-rose-600 text-white' : 'bg-slate-300 text-slate-600'))) }}">
                @if($s2Done)
                  <i class="fa-solid fa-check text-[11px]"></i>
                @elseif($s2Revision)
                  <i class="fa-solid fa-triangle-exclamation text-[11px]"></i>
                @elseif($s2Rejected)
                  <i class="fa-solid fa-xmark text-[11px]"></i>
                @else
                  2
                @endif
              </span>
              <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $s2Done ? 'text-emerald-700 bg-emerald-100/80' : ($s2Active ? 'text-blue-700 bg-blue-100/80' : ($s2Revision ? 'text-amber-800 bg-amber-200' : ($s2Rejected ? 'text-rose-700 bg-rose-100' : 'text-slate-500 bg-slate-200'))) }}">
                @if($s2Done)
                  Disetujui
                @elseif($s2Active)
                  Sedang Ditinjau
                @elseif($s2Revision)
                  Perlu Revisi
                @elseif($s2Rejected)
                  Ditolak
                @else
                  Menunggu
                @endif
              </span>
            </div>
            <h4 class="font-bold text-slate-900 text-xs">2. Bendahara BOS</h4>
            <p class="text-[11px] text-slate-500 mt-1 leading-snug">Verifikasi Standar Biaya &amp; RKAS</p>
          </div>
          <div class="text-[10px] font-mono text-slate-400 mt-3 pt-2 border-t border-slate-200/50">
            @php $log1 = $pengajuan->alurPersetujuan->where('level_persetujuan', 1)->first(); @endphp
            {{ $log1 && $log1->tanggal_proses ? \Carbon\Carbon::parse($log1->tanggal_proses)->format('d/m/Y H:i') : ($s2Active ? 'Antrean Berjalan' : '-') }}
          </div>
        </div>

        <!-- STEP 3: Persetujuan Kepala Sekolah -->
        @php
          $bgS3 = $s3Done ? 'bg-emerald-50/70 border-emerald-200' : ($s3Active ? 'bg-purple-50/80 border-purple-300 ring-2 ring-purple-400/20' : ($s3Rejected ? 'bg-rose-50 border-rose-200' : 'bg-slate-50 border-slate-200 opacity-60'));
        @endphp
        <div class="p-4 rounded-xl border {{ $bgS3 }} flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center {{ $s3Done ? 'bg-emerald-600 text-white' : ($s3Active ? 'bg-purple-600 text-white animate-pulse' : ($s3Rejected ? 'bg-rose-600 text-white' : 'bg-slate-300 text-slate-600')) }}">
                @if($s3Done)
                  <i class="fa-solid fa-check text-[11px]"></i>
                @elseif($s3Rejected)
                  <i class="fa-solid fa-xmark text-[11px]"></i>
                @else
                  3
                @endif
              </span>
              <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $s3Done ? 'text-emerald-700 bg-emerald-100/80' : ($s3Active ? 'text-purple-700 bg-purple-100/80' : ($s3Rejected ? 'text-rose-700 bg-rose-100' : 'text-slate-500 bg-slate-200')) }}">
                @if($s3Done)
                  Disetujui
                @elseif($s3Active)
                  Menunggu Kepsek
                @elseif($s3Rejected)
                  Ditolak
                @else
                  Menunggu
                @endif
              </span>
            </div>
            <h4 class="font-bold text-slate-900 text-xs">3. Kepala Sekolah</h4>
            <p class="text-[11px] text-slate-500 mt-1 leading-snug">Persetujuan Otorisasi Final</p>
          </div>
          <div class="text-[10px] font-mono text-slate-400 mt-3 pt-2 border-t border-slate-200/50">
            @php $log2 = $pengajuan->alurPersetujuan->where('level_persetujuan', 2)->first(); @endphp
            {{ $log2 && $log2->tanggal_proses ? \Carbon\Carbon::parse($log2->tanggal_proses)->format('d/m/Y H:i') : ($s3Active ? 'Menunggu Review Kepsek' : '-') }}
          </div>
        </div>

        <!-- STEP 4: Pencairan Dana & SPJ -->
        @php
          $bgS4 = $s4Done ? 'bg-emerald-50/70 border-emerald-200' : ($s4Active ? 'bg-emerald-50/60 border-emerald-300 ring-2 ring-emerald-400/20' : 'bg-slate-50 border-slate-200 opacity-60');
        @endphp
        <div class="p-4 rounded-xl border {{ $bgS4 }} flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="w-7 h-7 rounded-full font-bold text-xs flex items-center justify-center {{ $s4Done ? 'bg-emerald-600 text-white' : ($s4Active ? 'bg-emerald-600 text-white animate-pulse' : 'bg-slate-300 text-slate-600') }}">
                @if($s4Done)
                  <i class="fa-solid fa-check text-[11px]"></i>
                @elseif($s4Active)
                  <i class="fa-solid fa-money-bill-wave text-[11px]"></i>
                @else
                  4
                @endif
              </span>
              <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $s4Done ? 'text-emerald-700 bg-emerald-100/80' : ($s4Active ? 'text-emerald-800 bg-emerald-200' : 'text-slate-500 bg-slate-200') }}">
                @if($s4Done)
                  Selesai
                @elseif($s4Active)
                  Siap Dicairkan
                @else
                  Menunggu
                @endif
              </span>
            </div>
            <h4 class="font-bold text-slate-900 text-xs">4. Pencairan &amp; SPJ</h4>
            <p class="text-[11px] text-slate-500 mt-1 leading-snug">Pencairan Dana BOS &amp; Laporan</p>
          </div>
          <div class="text-[10px] font-mono text-slate-400 mt-3 pt-2 border-t border-slate-200/50">
            {{ $s4Done ? 'SPJ Diterima' : ($s4Active ? 'Bukti Bayar Siap' : '-') }}
          </div>
        </div>

      </div>
    </div>

    <!-- Informasi Pokok Operasional Sekolah & Rentang Waktu Pelaksanaan -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
          <i class="fa-solid fa-school text-indigo-600"></i>
          Informasi Operasional Sekolah &amp; Jadwal Pelaksanaan
        </h2>
        @if($pengajuan->durasi_hari)
          <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
            <i class="fa-solid fa-calendar-day mr-1"></i> {{ $pengajuan->durasi_hari }} Hari Pelaksanaan
          </span>
        @endif
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
          <span class="text-slate-400 font-semibold block text-[10px] uppercase">Unit Kerja / Penanggung Jawab</span>
          <span class="font-bold text-slate-800 text-sm mt-0.5 block">
            {{ $pengajuan->divisi->nama_divisi ?? '-' }}
          </span>
        </div>

        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
          <span class="text-slate-400 font-semibold block text-[10px] uppercase">Tahun Ajaran &amp; Semester</span>
          <span class="font-bold text-slate-800 text-sm mt-0.5 block">
            {{ $pengajuan->tahun_ajaran_semester ?? ($pengajuan->tahun_ajaran ? $pengajuan->tahun_ajaran . ' - ' . $pengajuan->semester : '-') }}
          </span>
        </div>

        <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
          <span class="text-slate-400 font-semibold block text-[10px] uppercase">Tahap Penyaluran BOS</span>
          <span class="font-bold text-indigo-700 text-sm mt-0.5 block">
            {{ $pengajuan->tahap_bos ?? '-' }}
          </span>
        </div>
      </div>

      <!-- Rentang Waktu Penggunaan / Jadwal Kegiatan -->
      <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm shadow-xs shrink-0">
            <i class="fa-regular fa-calendar-check"></i>
          </div>
          <div>
            <span class="text-indigo-900 font-bold block">Rentang Waktu Penggunaan / Kegiatan:</span>
            <span class="text-slate-700 font-medium font-mono text-xs">
              {{ $pengajuan->rentang_tanggal_formatted }}
            </span>
          </div>
        </div>
        <div class="text-[11px] text-slate-500 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
          Batas Acuan SPJ: <strong class="text-slate-700">{{ $pengajuan->tanggal_selesai ? $pengajuan->tanggal_selesai->format('d/m/Y') : '-' }}</strong>
        </div>
      </div>

      <!-- Latar Belakang & Urgensi -->
      @if($pengajuan->latar_belakang)
        <div class="pt-2">
          <span class="text-slate-400 font-semibold block text-[10px] uppercase mb-1">Latar Belakang &amp; Urgensi Kegiatan:</span>
          <p class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100 leading-relaxed">
            {{ $pengajuan->latar_belakang }}
          </p>
        </div>
      @endif
    </div>

    <!-- Rincian Item Belanja -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
      <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">
        Rincian Item Belanja
      </h2>
      <div class="overflow-x-auto">
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
    </div>

    <!-- Dokumen Pendukung -->
    @if($pengajuan->dokumenPendukung->isNotEmpty())
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">
          Dokumen Pendukung
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                  <a href="{{ $previewUrl }}" target="_blank" class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Lihat Penuh</a>
                  <a href="{{ $downloadUrl }}" download class="text-xs px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Unduh</a>
                </div>
              @elseif($isPdf)
                <div class="w-full h-48 mb-3 border border-slate-200 rounded-lg overflow-hidden bg-white">
                  <iframe src="{{ $previewUrl }}" class="w-full h-full" title="{{ $dokumen->nama_file }}"></iframe>
                </div>
                <p class="text-xs text-slate-600 font-medium truncate w-full text-center" title="{{ $dokumen->nama_file }}">{{ $dokumen->nama_file }}</p>
                <div class="mt-3 flex gap-2">
                  <a href="{{ $previewUrl }}" target="_blank" class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Buka Tab Baru</a>
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

    <!-- Bukti Pencairan (Jika sudah dicairkan Finance) -->
    @if($pengajuan->bukti_pencairan)
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">
          Bukti Pencairan Dana BOS (Finance)
        </h2>
        <div class="flex items-center gap-4 bg-emerald-50 p-4 rounded-xl border border-emerald-100">
          <div class="bg-emerald-100 p-3 rounded-full text-emerald-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </div>
          <div class="flex-1">
            <p class="text-sm font-semibold text-emerald-900">Dana telah dicairkan oleh Bendahara</p>
            <p class="text-xs text-emerald-700 mt-0.5">Bendahara BOS telah memvalidasi dan mengunggah bukti pencairan/transfer dana kegiatan.</p>
          </div>
          <a href="{{ asset('storage/' . $pengajuan->bukti_pencairan) }}" target="_blank"
             class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-colors flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Download Bukti
          </a>
        </div>
      </div>
    @endif

    <!-- Riwayat Detail Log Alur Persetujuan -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
      <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">
        Riwayat Catatan &amp; Log Persetujuan
      </h2>
      <div class="space-y-4">
        @forelse($pengajuan->alurPersetujuan as $log)
          <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 flex items-start justify-between">
            <div>
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-indigo-700">Tahap {{ $log->level_persetujuan }} ({{ $log->level_persetujuan == 1 ? 'Bendahara BOS' : 'Kepala Sekolah' }})</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold
                  {{ $log->status_persetujuan === 'ACC' ? 'bg-emerald-100 text-emerald-800' : ($log->status_persetujuan === 'Revisi' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                  {{ $log->status_persetujuan }}
                </span>
              </div>
              <p class="text-xs text-slate-600 mt-1">Reviewer: <span class="font-semibold">{{ $log->reviewer->nama_lengkap ?? 'Reviewer' }}</span></p>
              @if($log->catatan)
                <p class="text-xs text-slate-700 italic mt-1.5 bg-white p-2.5 rounded-lg border border-slate-200">
                  &ldquo;{{ $log->catatan }}&rdquo;
                </p>
              @endif
            </div>
            <div class="text-[11px] text-slate-400 font-mono">
              {{ $log->tanggal_proses ? \Carbon\Carbon::parse($log->tanggal_proses)->format('d/m/Y H:i') : '-' }}
            </div>
          </div>
        @empty
          <div class="text-center py-6 text-xs text-slate-400">
            Belum ada catatan persetujuan. Pengajuan sedang menunggu antrean pemeriksaan Tahap 1 oleh Bendahara BOS.
          </div>
        @endforelse
      </div>
    </div>

  </div>
@endsection
