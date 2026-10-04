@extends('layouts.app')

@section('title', 'Dashboard Finance - SIRAB Kelompok-3')
@section('breadcrumb', 'Dashboard Finance (Tahap 1)')

@section('content')
  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 gap-4">
    <div>
      <span class="text-[11px] uppercase text-[#2b337c] font-bold tracking-wide">Reviewer Anggaran Tahap 1 &bull; Role: Finance</span>
      <h2 class="text-[22px] text-slate-800 font-bold mt-0.5">Dashboard Finance</h2>
      <p class="text-[13px] text-slate-500 mt-0.5">
        Verifikasi ketersediaan pagu anggaran, validitas harga pasar, dan kelayakan item belanja sebelum diteruskan ke Pimpinan.
      </p>
    </div>
    <a href="{{ route('finance.antrean') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-lg text-[13px] font-semibold flex items-center gap-2 shadow-sm shrink-0 transition-colors">
      <i class="fa-solid fa-folder-open"></i> Lihat Antrean Pending ({{ $totalAntreanPending ?? 0 }})
    </a>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-5 mb-8">
    <!-- Card 1: Antrean Review -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
      <div>
        <div class="flex items-start justify-between gap-2 mb-2">
          <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Antrean Review</span>
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shrink-0">
            <i class="fa-solid fa-hourglass-start text-[15px]"></i>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
          {{ number_format($totalAntreanPending ?? 0) }}
        </div>
      </div>
      <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0"></span>
        <span>Menunggu verifikasi Tahap 1</span>
      </div>
    </div>

    <!-- Card 2: Menunggu Pimpinan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
      <div>
        <div class="flex items-start justify-between gap-2 mb-2">
          <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Menunggu Pimpinan</span>
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
            <i class="fa-solid fa-user-check text-[15px]"></i>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
          {{ number_format($totalAccFinance ?? 0) }}
        </div>
      </div>
      <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
        <span>Diteruskan ke Kepsek</span>
      </div>
    </div>

    <!-- Card 3: Proses Pencairan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
      <div>
        <div class="flex items-start justify-between gap-2 mb-2">
          <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Proses Pencairan</span>
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm shrink-0">
            <i class="fa-solid fa-money-bill-transfer text-[15px]"></i>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
          {{ number_format(\App\Models\PengajuanRab::where('status', \App\Enums\StatusPengajuan::PROSES_PENCAIRAN)->count()) }}
        </div>
      </div>
      <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
        <a href="{{ route('finance.pencairan') }}" class="text-xs text-indigo-600 font-semibold hover:underline inline-flex items-center gap-1">
          <span>Buka antrean pencairan</span>
          <i class="fa-solid fa-arrow-right text-[10px]"></i>
        </a>
      </div>
    </div>

    <!-- Card 4: Revisi / Ditolak -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
      <div>
        <div class="flex items-start justify-between gap-2 mb-2">
          <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Revisi / Ditolak</span>
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm shrink-0">
            <i class="fa-solid fa-triangle-exclamation text-[15px]"></i>
          </div>
        </div>
        <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
          {{ number_format($totalDitolakFinance ?? 0) }}
        </div>
      </div>
      <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
        <span>Dikembalikan ke Staf</span>
      </div>
    </div>

    <!-- Card 5: Total Nominal Pending -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
      <div>
        <div class="flex items-start justify-between gap-2 mb-2">
          <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Nominal Pending</span>
          <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shrink-0">
            <i class="fa-solid fa-sack-dollar text-[15px]"></i>
          </div>
        </div>
        <div class="text-xl sm:text-2xl xl:text-[20px] 2xl:text-2xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1" title="Rp {{ number_format($totalNominalPending ?? 0, 0, ',', '.') }}">
          Rp {{ number_format($totalNominalPending ?? 0, 0, ',', '.') }}
        </div>
      </div>
      <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
        <i class="fa-regular fa-clock text-slate-400 text-[11px] shrink-0"></i>
        <span>Nilai antrean diverifikasi</span>
      </div>
    </div>
  </div>

  <!-- Table Section -->
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
      <div>
        <h3 class="text-[15px] font-bold text-slate-800">Antrean Pengajuan Pending Terbaru</h3>
        <p class="text-xs text-slate-500 mt-0.5">Periksa dan berikan keputusan persetujuan Tahap 1</p>
      </div>
      <a href="{{ route('finance.antrean') }}" class="text-[13px] text-blue-600 font-semibold hover:underline">
        Buka Semua Antrean &rarr;
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full min-w-[800px] text-left border-collapse text-xs">
        <thead>
          <tr class="border-b border-slate-200 bg-slate-50/60">
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide whitespace-nowrap">No. RAB</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide whitespace-nowrap">Pemohon</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide whitespace-nowrap">Bidang / Bagian</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide whitespace-nowrap">Judul Pengajuan</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide text-right whitespace-nowrap">Estimasi Biaya</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide text-center whitespace-nowrap">Aksi Review</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($antreanTerbaru ?? [] as $item)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="font-medium text-slate-800 px-4 py-3.5 font-mono whitespace-nowrap">{{ $item->no_rab }}</td>
              <td class="text-slate-800 px-4 py-3.5 whitespace-nowrap font-medium">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</td>
              <td class="text-slate-500 px-4 py-3.5 whitespace-nowrap">{{ $item->divisi->nama_divisi ?? '-' }}</td>
              <td class="text-slate-800 px-4 py-3.5 max-w-xs md:max-w-sm truncate" title="{{ $item->judul_pengajuan }}">{{ $item->judul_pengajuan }}</td>
              <td class="text-slate-800 px-4 py-3.5 text-right font-bold font-mono whitespace-nowrap tabular-nums">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
              <td class="px-4 py-3.5 text-center whitespace-nowrap">
                <a href="{{ route('finance.show', $item->id_pengajuan) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 shadow-2xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 active:scale-95 transition-all duration-150 shrink-0"
                   title="Review & Verifikasi Anggaran">
                  <i class="fa-solid fa-clipboard-check text-[12px] shrink-0"></i>
                  <span>Review</span>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-slate-400 px-4 py-8 text-center">
                Tidak ada antrean pending. Seluruh pengajuan telah diproses!
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Antrean Pencairan Table -->
  <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm mt-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
      <div>
        <h3 class="text-[15px] font-bold text-slate-800">Antrean Pencairan Dana</h3>
        <p class="text-xs text-slate-500 mt-0.5">Unggah bukti transfer untuk pengajuan yang telah disetujui Pimpinan</p>
      </div>
      <a href="{{ route('finance.pencairan') }}" class="text-[13px] text-blue-600 font-semibold hover:underline">
        Buka Semua Antrean &rarr;
      </a>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full min-w-[800px] text-left border-collapse text-xs">
        <thead>
          <tr class="border-b border-slate-200 bg-slate-50/60">
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide whitespace-nowrap">No. RAB</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide whitespace-nowrap">Pemohon</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide whitespace-nowrap">Bidang / Bagian</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide whitespace-nowrap">Judul Pengajuan</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide text-right whitespace-nowrap">Estimasi Biaya</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-4 py-3 tracking-wide text-center whitespace-nowrap">Aksi Pencairan</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($antreanPencairanTerbaru ?? [] as $item)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="font-medium text-slate-800 px-4 py-3.5 font-mono whitespace-nowrap">{{ $item->no_rab }}</td>
              <td class="text-slate-800 px-4 py-3.5 whitespace-nowrap font-medium">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</td>
              <td class="text-slate-500 px-4 py-3.5 whitespace-nowrap">{{ $item->divisi->nama_divisi ?? '-' }}</td>
              <td class="text-slate-800 px-4 py-3.5 max-w-xs md:max-w-sm truncate" title="{{ $item->judul_pengajuan }}">{{ $item->judul_pengajuan }}</td>
              <td class="text-slate-800 px-4 py-3.5 text-right font-bold font-mono whitespace-nowrap tabular-nums">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
              <td class="px-4 py-3.5 text-center whitespace-nowrap">
                <button onclick="document.getElementById('modal-pencairan-{{ $item->id_pengajuan }}').classList.remove('hidden')"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-2xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 active:scale-95 transition-all duration-150 shrink-0 cursor-pointer"
                        title="Unggah Bukti Pencairan">
                  <i class="fa-solid fa-upload text-[11px] shrink-0"></i>
                  <span>Upload Bukti</span>
                </button>
              </td>
            </tr>

            <!-- Modal Upload Bukti -->
            <div id="modal-pencairan-{{ $item->id_pengajuan }}" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
              <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-xl">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                  <h3 class="font-bold text-slate-800 text-base">Upload Bukti Pencairan</h3>
                  <button onclick="document.getElementById('modal-pencairan-{{ $item->id_pengajuan }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 cursor-pointer">&times;</button>
                </div>
                <form action="{{ route('finance.upload_bukti', $item->id_pengajuan) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="p-6">
                    <p class="text-[13px] text-slate-600 mb-4">No RAB: <span class="font-bold text-slate-800">{{ $item->no_rab }}</span></p>
                    <label class="block text-[13px] font-medium text-slate-700 mb-2">Pilih File Bukti Transfer</label>
                    <input type="file" name="bukti_pencairan" accept=".pdf,.jpg,.jpeg,.png" required
                      class="block w-full text-[13px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                    <p class="mt-2 text-xs text-slate-500">Maksimal 5MB. Format: PDF, JPG, PNG.</p>
                  </div>
                  <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-pencairan-{{ $item->id_pengajuan }}').classList.add('hidden')" class="px-4 py-2 text-[13px] font-semibold text-slate-600 hover:text-slate-800 cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 text-[13px] font-semibold bg-[#2b337c] text-white rounded-lg hover:bg-[#1e255e] shadow-sm cursor-pointer">Simpan Bukti</button>
                  </div>
                </form>
              </div>
            </div>
          @empty
            <tr>
              <td colspan="6" class="text-[13px] text-slate-500 px-3 py-8 border-b border-slate-200 text-center">
                Tidak ada antrean pencairan dana.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
