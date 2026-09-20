@extends('layouts.app')

@section('title', 'Daftar & Persetujuan RAB - SIRAB Kelompok-3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-800">Dashboard</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Antrean &amp; Persetujuan RAB</span>
  </div>

  <!-- Page Header -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
        Verifikasi &amp; Persetujuan Gabungan
      </div>
      <h1 class="text-2xl font-bold text-slate-900">Daftar Pengajuan RAB Masuk</h1>
      <p class="text-sm text-slate-500 mt-1">
        Tinjau rincian belanja, unduh dokumen lampiran, dan berikan keputusan persetujuan secara langsung.
      </p>
    </div>
    <div class="flex items-center gap-2 font-mono text-xs text-slate-500 bg-slate-100 px-3.5 py-2 rounded-xl">
      <span>Antrean Pending:</span>
      <span class="font-bold text-amber-600 font-mono-num">{{ $totalPending ?? 0 }}</span>
    </div>
  </div>

  <!-- Filter & Search Section (Figma daftarRAB.html) -->
  <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 mb-6">
    <div class="flex flex-col lg:flex-row justify-between gap-4">
      <!-- Left: Search and Status Filters -->
      <div class="flex flex-col sm:flex-row flex-1 gap-3 items-start sm:items-center">
        <!-- Search Input Form -->
        <form method="GET" action="{{ route('admin.rab.index') }}" class="w-full sm:w-72 shrink-0 flex items-center">
          @if($statusFilter)
            <input type="hidden" name="status" value="{{ $statusFilter }}">
          @endif
          <div class="relative w-full">
            <input type="text"
                   name="q"
                   value="{{ $search }}"
                   placeholder="Cari nomor, judul, pemohon..."
                   class="w-full border border-slate-300 rounded-lg pl-9 pr-3.5 py-1.5 text-xs text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"/>
            <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
        </form>

        <!-- Status Filter Group -->
        <div class="flex flex-wrap items-center gap-1.5">
          <a href="{{ route('admin.rab.index', array_filter(['q' => $search])) }}"
             class="{{ empty($statusFilter) ? 'bg-[#1e293b] text-white' : 'bg-white text-slate-600 border border-slate-300 hover:bg-slate-50' }} text-xs font-semibold px-3.5 py-1.5 rounded-lg transition-colors">
            Semua ({{ $totalPengajuan ?? 0 }})
          </a>
          <a href="{{ route('admin.rab.index', array_filter(['status' => 'Pending', 'q' => $search])) }}"
             class="{{ $statusFilter === 'Pending' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-amber-700 border border-slate-300 hover:bg-amber-50' }} text-xs font-semibold px-3.5 py-1.5 rounded-lg transition-colors">
            Pending ({{ $totalPending ?? 0 }})
          </a>
          <a href="{{ route('admin.rab.index', array_filter(['status' => 'ACC', 'q' => $search])) }}"
             class="{{ $statusFilter === 'ACC' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-emerald-700 border border-slate-300 hover:bg-emerald-50' }} text-xs font-semibold px-3.5 py-1.5 rounded-lg transition-colors">
            Disetujui ACC ({{ $totalAcc ?? 0 }})
          </a>
          <a href="{{ route('admin.rab.index', array_filter(['status' => 'Ditolak', 'q' => $search])) }}"
             class="{{ $statusFilter === 'Ditolak' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-rose-700 border border-slate-300 hover:bg-rose-50' }} text-xs font-semibold px-3.5 py-1.5 rounded-lg transition-colors">
            Ditolak ({{ $totalDitolak ?? 0 }})
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Data Table: Antrean RAB Gabungan -->
  <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="overflow-x-auto table-container">
      <table class="w-full text-left whitespace-nowrap text-sm">
        <thead class="text-[10px] text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-semibold">
          <tr>
            <th class="px-5 py-3.5">NO. RAB</th>
            <th class="px-5 py-3.5">TANGGAL</th>
            <th class="px-5 py-3.5">JUDUL PENGAJUAN</th>
            <th class="px-5 py-3.5">DIVISI</th>
            <th class="px-5 py-3.5">PEMOHON</th>
            <th class="px-5 py-3.5">PRIORITAS</th>
            <th class="px-5 py-3.5 text-right">TOTAL ANGGARAN</th>
            <th class="px-5 py-3.5 text-center">STATUS</th>
            <th class="px-5 py-3.5 text-center">TINDAKAN REVIEW</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-xs">
          @forelse($pengajuanList as $item)
            <tr class="hover:bg-slate-50/80 transition-colors">
              <td class="px-5 py-4 font-mono font-medium text-slate-700">
                {{ $item->no_rab }}
              </td>
              <td class="px-5 py-4 font-mono text-slate-400 text-[11px]">
                {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('Y-m-d') : '-' }}
              </td>
              <td class="px-5 py-4">
                <div class="font-medium text-slate-900 max-w-xs truncate" title="{{ $item->judul_pengajuan }}">
                  {{ $item->judul_pengajuan }}
                </div>
                <div class="text-[10px] text-slate-400 mt-0.5">
                  Periode: {{ $item->periode_penggunaan }}
                </div>
              </td>
              <td class="px-5 py-4 text-slate-600">
                {{ $item->divisi->nama_divisi ?? '-' }}
              </td>
              <td class="px-5 py-4">
                <div class="font-semibold text-slate-800">{{ $item->pengguna->nama_lengkap ?? 'N/A' }}</div>
                <div class="text-[10px] text-slate-400">{{ $item->pengguna->jabatan ?? '-' }}</div>
              </td>
              <td class="px-5 py-4">
                @if($item->prioritas === 'Tinggi')
                  <span class="inline-block px-2 py-0.5 text-[10px] font-semibold bg-rose-50 text-rose-600 border border-rose-100 rounded">
                    Tinggi
                  </span>
                @elseif($item->prioritas === 'Sedang')
                  <span class="inline-block px-2 py-0.5 text-[10px] font-semibold bg-amber-50 text-amber-600 border border-amber-100 rounded">
                    Sedang
                  </span>
                @else
                  <span class="inline-block px-2 py-0.5 text-[10px] font-semibold bg-indigo-50 text-indigo-600 border border-indigo-100 rounded">
                    Rendah
                  </span>
                @endif
              </td>
              <td class="px-5 py-4 text-right font-mono-num font-semibold text-slate-800">
                Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
              </td>
              <td class="px-5 py-4 text-center">
                @if($item->status === 'ACC')
                  <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                  </span>
                @elseif($item->status === 'Ditolak')
                  <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-rose-100 text-rose-800 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                  </span>
                @else
                  <span class="inline-flex items-center gap-1 text-[10px] font-semibold bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                  </span>
                @endif
              </td>
              <td class="px-5 py-4 text-center">
                <button type="button" 
                        onclick="openApprovalModal({{ $item->id_pengajuan }})"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#1e293b] hover:bg-slate-800 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors cursor-pointer">
                  <span>⚡</span> Review &amp; Putuskan
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-5 py-12 text-center text-slate-400">
                <svg class="mx-auto h-10 w-10 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div class="text-sm font-medium text-slate-600">Tidak ada data antrean pengajuan</div>
                <p class="text-xs text-slate-400 mt-1">Semua berkas pengajuan telah diproses atau tidak cocok dengan filter.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($pengajuanList->hasPages())
      <div class="px-6 py-4 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white text-xs">
        <div class="text-slate-500">
          Menampilkan {{ $pengajuanList->firstItem() ?? 0 }} - {{ $pengajuanList->lastItem() ?? 0 }} dari {{ $pengajuanList->total() }} dokumen
        </div>
        <div>
          {{ $pengajuanList->links() }}
        </div>
      </div>
    @endif
  </div>

  <!-- ACTION / DETAIL MODAL POPUP (Figma persetujuan.html) -->
  @foreach($pengajuanList as $item)
    @php
      $dokumen = $item->dokumenPendukung->first();
      $latestReview = $item->alurPersetujuan->sortByDesc('tanggal_proses')->first();
    @endphp
    <div id="modalApproval{{ $item->id_pengajuan }}" 
         class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-sm overflow-y-auto">
      
      <div class="bg-white rounded-2xl max-w-4xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden transform transition-all">
        
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between shrink-0">
          <div class="flex items-center gap-3">
            <span class="font-mono font-bold text-xs bg-indigo-100 text-indigo-800 px-2.5 py-1 rounded">
              {{ $item->no_rab }}
            </span>
            <h3 class="font-bold text-slate-800 text-base">
              Detail &amp; Persetujuan Pengajuan RAB
            </h3>
          </div>
          <button type="button" 
                  onclick="closeApprovalModal({{ $item->id_pengajuan }})" 
                  class="text-slate-400 hover:text-slate-700 text-lg font-bold p-1">
            ✕
          </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-6 overflow-y-auto space-y-6 text-xs">
          
          <!-- Informasi Pokok Header Card -->
          <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-200 pb-3">
              <div>
                <h4 class="text-sm font-bold text-slate-900">{{ $item->judul_pengajuan }}</h4>
                <div class="text-slate-500 mt-0.5">
                  Pemohon: <span class="font-semibold text-slate-700">{{ $item->pengguna->nama_lengkap ?? '-' }}</span> ({{ $item->pengguna->jabatan ?? '-' }} - {{ $item->divisi->nama_divisi ?? '-' }})
                </div>
              </div>
              <div class="text-right">
                <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Total Anggaran</div>
                <div class="text-lg font-bold text-slate-900 font-mono-num">
                  Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
                </div>
              </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-[11px] pt-1">
              <div>
                <span class="text-slate-400 block">Tanggal Diajukan:</span>
                <span class="font-mono font-medium text-slate-700">{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d M Y, H:i') : '-' }} WIB</span>
              </div>
              <div>
                <span class="text-slate-400 block">Periode Anggaran:</span>
                <span class="font-medium text-slate-700">{{ $item->periode_penggunaan }}</span>
              </div>
              <div>
                <span class="text-slate-400 block">Tingkat Prioritas:</span>
                <span class="font-semibold {{ $item->prioritas === 'Tinggi' ? 'text-rose-600' : ($item->prioritas === 'Sedang' ? 'text-amber-600' : 'text-indigo-600') }}">
                  {{ $item->prioritas }}
                </span>
              </div>
              <div>
                <span class="text-slate-400 block">Status Saat Ini:</span>
                <span class="font-semibold {{ $item->status === 'ACC' ? 'text-emerald-700' : ($item->status === 'Ditolak' ? 'text-rose-700' : 'text-amber-700') }}">
                  {{ $item->status }}
                </span>
              </div>
            </div>

            <!-- Justifikasi / Latar Belakang -->
            <div class="pt-2">
              <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider mb-1">Latar Belakang &amp; Justifikasi:</span>
              <p class="text-slate-700 bg-white p-3 rounded-lg border border-slate-200 leading-relaxed">
                {{ $item->latar_belakang ?? 'Tidak ada catatan justifikasi.' }}
              </p>
            </div>
          </div>

          <!-- Rincian Item Belanja Table (Figma Style) -->
          <div>
            <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2 flex items-center justify-between">
              <span>Rincian Item Anggaran Biaya ({{ $item->rincianItem->count() }} item)</span>
              <span class="text-[10px] font-normal text-slate-400">Total Harga = Volume x Harga Satuan</span>
            </h4>
            <div class="border border-slate-200 rounded-xl overflow-hidden table-container">
              <table class="w-full text-left whitespace-nowrap text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold text-[10px]">
                  <tr>
                    <th class="px-4 py-2.5 w-8 text-center">No.</th>
                    <th class="px-4 py-2.5">Uraian Kegiatan / Barang</th>
                    <th class="px-4 py-2.5 w-20">Satuan</th>
                    <th class="px-4 py-2.5 w-16 text-right">Vol</th>
                    <th class="px-4 py-2.5 w-32 text-right">Harga Satuan (Rp)</th>
                    <th class="px-4 py-2.5 w-36 text-right">Subtotal (Rp)</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @forelse($item->rincianItem as $idx => $rincian)
                    <tr class="hover:bg-slate-50/50">
                      <td class="px-4 py-2 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
                      <td class="px-4 py-2 font-medium text-slate-800">{{ $rincian->uraian_barang }}</td>
                      <td class="px-4 py-2 text-slate-600">{{ $rincian->satuan }}</td>
                      <td class="px-4 py-2 text-right font-mono">{{ $rincian->volume }}</td>
                      <td class="px-4 py-2 text-right font-mono-num text-slate-700">
                        Rp {{ number_format((float) $rincian->harga_satuan, 0, ',', '.') }}
                      </td>
                      <td class="px-4 py-2 text-right font-mono-num font-semibold text-slate-900">
                        Rp {{ number_format((float) $rincian->total_harga, 0, ',', '.') }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="px-4 py-4 text-center text-slate-400">Tidak ada rincian item belanja.</td>
                    </tr>
                  @endforelse
                </tbody>
                <tfoot class="bg-slate-50 border-t-2 border-slate-200 font-bold">
                  <tr>
                    <td colspan="5" class="px-4 py-2.5 text-right text-slate-700">TOTAL KESELURUHAN:</td>
                    <td class="px-4 py-2.5 text-right font-mono-num text-indigo-700 text-sm">
                      Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>

          <!-- Dokumen Pendukung Fisik & Unduh File -->
          <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
            <h4 class="font-bold text-slate-800 text-xs uppercase tracking-wider mb-2">Lampiran Dokumen Pendukung</h4>
            @if($dokumen)
              <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-slate-200">
                <div class="flex items-center gap-2.5 truncate">
                  <span class="text-indigo-600 text-base">📎</span>
                  <div class="truncate">
                    <div class="font-semibold text-slate-800 text-xs truncate">{{ $dokumen->nama_file }}</div>
                    <div class="text-[10px] text-slate-400">Format: {{ $dokumen->tipe_dokumen ?? 'BERKAS' }} &bull; Diunggah: {{ $dokumen->waktu_unggah ? $dokumen->waktu_unggah->format('d/m/Y H:i') : '-' }}</div>
                  </div>
                </div>
                <a href="{{ asset('storage/' . $dokumen->path_file) }}" download
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold rounded-lg text-xs transition-colors shrink-0">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                  </svg>
                  Unduh Dokumen
                </a>
              </div>
            @else
              <div class="text-slate-400 italic text-xs">
                Tidak ada dokumen fisik yang dilampirkan pada pengajuan ini.
              </div>
            @endif
          </div>

          <!-- FORM PERSETUJUAN LANGSUNG (ACC / TOLAK + CATATAN REVIEWER) -->
          <div class="p-5 bg-white border-2 border-indigo-200 rounded-xl shadow-sm space-y-4">
            <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span>
              Formulir Keputusan Persetujuan (Reviewer)
            </h4>

            @if($latestReview)
              <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-xs">
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Catatan Keputusan Terakhir:</span>
                <p class="text-slate-800 font-medium mt-0.5">"{{ $latestReview->catatan ?? '-' }}"</p>
                <div class="text-[10px] text-slate-400 mt-1">Oleh: {{ $latestReview->reviewer->nama_lengkap ?? 'Reviewer' }} (Status: {{ $latestReview->status_persetujuan }})</div>
              </div>
            @endif

            <form action="{{ route('admin.pengajuan.approve', $item->id_pengajuan) }}" method="POST" class="space-y-4">
              @csrf

              <div>
                <label for="catatan_{{ $item->id_pengajuan }}" class="block text-xs font-semibold text-slate-700 mb-1.5">
                  Catatan Reviewer / Alasan Penolakan:
                </label>
                <textarea name="catatan" 
                          id="catatan_{{ $item->id_pengajuan }}" 
                          rows="3"
                          placeholder="Tambahkan catatan persetujuan, instruksi realisasi belanja, atau alasan jika pengajuan ditolak..."
                          class="w-full border border-slate-300 rounded-lg p-3 text-xs text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50/40"></textarea>
              </div>

              <!-- Approval Action Buttons (Figma persetujuan.html) -->
              <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2 border-t border-slate-100">
                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                  <!-- Tombol ACC / Setujui -->
                  <button type="submit" 
                          name="status" 
                          value="ACC"
                          class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    ✓ Setujui Pengajuan (ACC)
                  </button>

                  <!-- Tombol Tolak -->
                  <button type="submit" 
                          name="status" 
                          value="Ditolak"
                          onclick="return confirm('Apakah Anda yakin ingin menolak pengajuan RAB ini?');"
                          class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    ✕ Tolak Pengajuan
                  </button>
                </div>

                <button type="button" 
                        onclick="closeApprovalModal({{ $item->id_pengajuan }})"
                        class="w-full sm:w-auto px-4 py-2 border border-slate-300 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                  Tutup
                </button>
              </div>
            </form>
          </div>

        </div>

      </div>
    </div>
  @endforeach
@endsection

@push('scripts')
<script>
  function openApprovalModal(id) {
    const modal = document.getElementById('modalApproval' + id);
    if (modal) {
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }
  }

  function closeApprovalModal(id) {
    const modal = document.getElementById('modalApproval' + id);
    if (modal) {
      modal.classList.add('hidden');
      document.body.style.overflow = 'auto';
    }
  }

  // Close modal when clicking outside dialog
  window.addEventListener('click', function(event) {
    const openModals = document.querySelectorAll('[id^="modalApproval"]:not(.hidden)');
    openModals.forEach(modal => {
      if (event.target === modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
      }
    });
  });
</script>
@endpush
