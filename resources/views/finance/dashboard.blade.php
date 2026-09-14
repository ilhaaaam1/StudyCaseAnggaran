@extends('layouts.app')

@section('title', 'Dashboard Finance - SIRAB Kelompok-3')
@section('breadcrumb', 'Dashboard Finance (Tahap 1)')

@section('content')
  <!-- Alert Banner -->
  <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center justify-between mb-5 text-[13px] font-medium">
    <div class="flex items-center gap-2.5">
      <i class="fa-solid fa-circle-check text-[15px] text-green-500"></i>
      <span>Selamat datang kembali, {{ Auth::user()->nama_lengkap }}!</span>
    </div>
    <button type="button" onclick="this.parentElement.remove()" class="text-green-800 hover:text-green-900 cursor-pointer text-sm">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>

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
  <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="bg-white border border-slate-200 rounded-xl p-4.5 flex flex-col justify-between relative shadow-sm hover:shadow-md transition-shadow">
      <div>
        <div class="text-[11px] font-bold uppercase text-slate-500 tracking-wide mb-2">Antrean Review</div>
        <div class="text-2xl font-bold text-[#d97706] mb-1.5 font-mono">{{ $totalAntreanPending ?? 0 }}</div>
      </div>
      <div class="text-xs text-slate-500">Menunggu verifikasi Tahap 1</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-4.5 flex flex-col justify-between relative shadow-sm hover:shadow-md transition-shadow">
      <div>
        <div class="text-[11px] font-bold uppercase text-slate-500 tracking-wide mb-2">Menunggu Pimpinan</div>
        <div class="text-2xl font-bold text-blue-600 mb-1.5 font-mono">{{ $totalAccFinance ?? 0 }}</div>
      </div>
      <div class="text-xs text-slate-500">Diteruskan ke Pimpinan</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-4.5 flex flex-col justify-between relative shadow-sm hover:shadow-md transition-shadow">
      <div>
        <div class="text-[11px] font-bold uppercase text-slate-500 tracking-wide mb-2">Proses Pencairan</div>
        <div class="text-2xl font-bold text-slate-800 mb-1.5 font-mono">{{ \App\Models\PengajuanRab::where('status', \App\Enums\StatusPengajuan::PROSES_PENCAIRAN)->count() }}</div>
      </div>
      <a href="{{ route('finance.pencairan') }}" class="text-[12px] text-blue-600 font-semibold hover:underline flex items-center gap-2 mt-1">Lihat Antrean &rarr;</a>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-4.5 flex flex-col justify-between relative shadow-sm hover:shadow-md transition-shadow">
      <div>
        <div class="text-[11px] font-bold uppercase text-slate-500 tracking-wide mb-2">Revisi / Ditolak</div>
        <div class="text-2xl font-bold text-red-600 mb-1.5 font-mono">{{ $totalDitolakFinance ?? 0 }}</div>
      </div>
      <div class="text-xs text-slate-500">Dikembalikan ke Staff</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-4.5 flex flex-col justify-between relative shadow-sm hover:shadow-md transition-shadow">
      <div>
        <div class="text-[11px] font-bold uppercase text-slate-500 tracking-wide mb-2">Total Nominal Pending</div>
        <div class="text-lg font-bold text-slate-800 mb-1.5 font-mono">Rp {{ number_format($totalNominalPending ?? 0, 0, ',', '.') }}</div>
      </div>
      <div class="text-xs text-slate-500">Nilai antrean diverifikasi</div>
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
      <table class="w-full text-left border-collapse">
        <thead>
          <tr>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide">No. RAB</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide">Pemohon</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide">Unit Kerja</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide">Judul Pengajuan</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide text-right">Estimasi Biaya</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide text-center">Aksi Review</th>
          </tr>
        </thead>
        <tbody>
          @forelse($antreanTerbaru ?? [] as $item)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="text-[13px] font-medium text-slate-800 px-3 py-3.5 border-b border-slate-200 font-mono">{{ $item->no_rab }}</td>
              <td class="text-[13px] text-slate-800 px-3 py-3.5 border-b border-slate-200">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</td>
              <td class="text-[13px] text-slate-500 px-3 py-3.5 border-b border-slate-200">{{ $item->divisi->nama_divisi ?? '-' }}</td>
              <td class="text-[13px] text-slate-800 px-3 py-3.5 border-b border-slate-200">{{ $item->judul_pengajuan }}</td>
              <td class="text-[13px] text-slate-800 px-3 py-3.5 border-b border-slate-200 text-right font-semibold font-mono">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
              <td class="text-[13px] px-3 py-3.5 border-b border-slate-200 text-center">
                <a href="{{ route('finance.show', $item->id_pengajuan) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold shadow-sm inline-block transition-colors">
                  Review Tahap 1
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-[13px] text-slate-500 px-3 py-8 border-b border-slate-200 text-center">
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
      <table class="w-full text-left border-collapse">
        <thead>
          <tr>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide">No. RAB</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide">Pemohon</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide">Unit Kerja</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide">Judul Pengajuan</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide text-right">Estimasi Biaya</th>
            <th class="text-[11px] uppercase text-slate-500 font-bold px-3 py-2.5 border-b border-slate-200 tracking-wide text-center">Aksi Pencairan</th>
          </tr>
        </thead>
        <tbody>
          @forelse($antreanPencairanTerbaru ?? [] as $item)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="text-[13px] font-medium text-slate-800 px-3 py-3.5 border-b border-slate-200 font-mono">{{ $item->no_rab }}</td>
              <td class="text-[13px] text-slate-800 px-3 py-3.5 border-b border-slate-200">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</td>
              <td class="text-[13px] text-slate-500 px-3 py-3.5 border-b border-slate-200">{{ $item->divisi->nama_divisi ?? '-' }}</td>
              <td class="text-[13px] text-slate-800 px-3 py-3.5 border-b border-slate-200">{{ $item->judul_pengajuan }}</td>
              <td class="text-[13px] text-slate-800 px-3 py-3.5 border-b border-slate-200 text-right font-semibold font-mono">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
              <td class="text-[13px] px-3 py-3.5 border-b border-slate-200 text-center">
                <button onclick="document.getElementById('modal-pencairan-{{ $item->id_pengajuan }}').classList.remove('hidden')" class="px-3 py-1.5 bg-[#2b337c] hover:bg-[#1e255e] text-white rounded-md text-xs font-semibold shadow-sm transition-colors">
                  Upload Bukti
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
