@extends('layouts.app')

@section('title', 'Verifikasi Anggaran Tahap 1 - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('finance.antrean') }}" class="hover:text-slate-800">Antrean Tahap 1</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Verifikasi: {{ $pengajuan->no_rab }}</span>
  </div>

  <div class="max-w-4xl mx-auto space-y-6 mb-10">
    <!-- Header Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
      <div>
        <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Verifikasi Kelayakan Finansial (Tahap 1)</div>
        <h1 class="text-2xl font-bold text-slate-900 font-mono mt-0.5">{{ $pengajuan->no_rab }}</h1>
        <p class="text-sm font-semibold text-slate-700 mt-1">{{ $pengajuan->judul_pengajuan }}</p>
        <div class="mt-2 flex items-center gap-2">
            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg border border-slate-200">
                <i class="fa-solid fa-tag mr-1"></i> {{ $pengajuan->kategori_anggaran ?? 'Tanpa Kategori' }}
            </span>
        </div>
        <div class="mt-2 inline-block px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pengajuan->status === \App\Enums\StatusPengajuan::SELESAI || $pengajuan->status === \App\Enums\StatusPengajuan::PROSES_PENCAIRAN ? 'bg-emerald-100 text-emerald-800' : ($pengajuan->status === \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN ? 'bg-blue-100 text-blue-800' : ($pengajuan->status === \App\Enums\StatusPengajuan::MENUNGGU_FINANCE ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800')) }}">
          {{ $pengajuan->status }}
        </div>
        <p class="text-xs text-slate-500 mt-0.5">Pemohon: <span class="font-medium text-slate-700">{{ $pengajuan->pengguna->nama_lengkap ?? 'Staf' }}</span> ({{ $pengajuan->divisi->nama_divisi ?? '-' }})</p>
      </div>
      <div class="text-right">
        <span class="text-xs text-slate-400 block">Total Pengajuan:</span>
        <span class="text-xl font-bold font-mono text-indigo-700">Rp {{ number_format((float) $pengajuan->estimasi_total, 0, ',', '.') }}</span>
      </div>
    </div>

    <!-- Informasi Pokok Operasional Sekolah & Rentang Waktu Pelaksanaan -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
          <i class="fa-solid fa-school text-amber-600"></i>
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
      <div class="bg-amber-50/40 p-4 rounded-xl border border-amber-100/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-amber-600 text-white flex items-center justify-center text-sm shadow-xs shrink-0">
            <i class="fa-regular fa-calendar-check"></i>
          </div>
          <div>
            <span class="text-amber-950 font-bold block">Rentang Waktu Penggunaan / Kegiatan:</span>
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
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
      <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">
        Rincian Item Anggaran
      </h2>
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
            <td colspan="5" class="px-4 py-3 text-right text-xs uppercase text-slate-700">Total:</td>
            <td class="px-4 py-3 text-right font-mono text-sm text-indigo-700">Rp {{ number_format((float) $pengajuan->estimasi_total, 0, ',', '.') }}</td>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- Dokumen Pendukung -->
    @if($pengajuan->dokumenPendukung->isNotEmpty())
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mt-6">
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
                  <a href="{{ $downloadUrl }}" class="text-xs px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Unduh File</a>
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <!-- Form Keputusan Finance -->
    @if($pengajuan->status === \App\Enums\StatusPengajuan::MENUNGGU_FINANCE)
      <div class="mt-6 bg-blue-50/50 p-6 rounded-2xl border border-blue-100 shadow-sm">
        <h2 class="text-sm font-bold text-blue-900 uppercase tracking-wider mb-2">
          Verifikasi Tahap 1 (Finance)
        </h2>
        <p class="text-xs text-slate-500 mb-4">
          Bila disetujui, status akan menjadi <strong>Menunggu Persetujuan Pimpinan</strong>. Bila ditolak, akan dikembalikan ke Staff dengan status <strong>Revisi / Ditolak</strong>.
        </p>

        {{-- PRESENTASI: Menambahkan x-data untuk state control modal dan logika submit --}}
        <div x-data="{ 
            showModal: false, 
            actionValue: '', 
            actionTitle: '',
            actionText: '', 
            actionColor: '',
            submitForm() {
                // PRESENTASI: Fungsi validasi catatan khusus untuk aksi penolakan dan revisi
                if ((this.actionValue === 'Revisi' || this.actionValue === 'Ditolak') && !$refs.catatanInput.value.trim()) {
                    alert('Catatan evaluasi WAJIB diisi jika pengajuan direvisi atau ditolak.');
                    this.showModal = false;
                    $refs.catatanInput.focus();
                    return;
                }
                // PRESENTASI: Fungsi untuk memasukkan nilai status ke dalam form tersembunyi dan men-submit form secara programatik
                $refs.statusInput.value = this.actionValue;
                $refs.approvalForm.submit();
            }
        }">
          <form x-ref="approvalForm" action="{{ route('finance.approve', $pengajuan->id_pengajuan) }}" method="POST" class="space-y-4">
            @csrf
            {{-- PRESENTASI: Input hidden untuk menampung status persetujuan yang diatur dari modal --}}
            <input type="hidden" name="action" x-ref="statusInput">
            
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan / Evaluasi Finansial</label>
              <textarea name="catatan" x-ref="catatanInput" rows="3" placeholder="Masukkan catatan ketersediaan anggaran atau catatan penyesuaian..."
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:border-indigo-500"></textarea>
            </div>

            {{-- PRESENTASI: Memisahkan Logika Revisi dan Tolak Permanen --}}
            <div class="flex flex-wrap items-center justify-end gap-3 pt-2">
              {{-- Tombol Tolak Permanen --}}
              <button type="button" 
                      @click="showModal = true; actionValue = 'Ditolak'; actionTitle = 'Tolak Permanen?'; actionText = 'Apakah Anda yakin ingin MENOLAK PERMANEN pengajuan RAB ini? Pengajuan tidak bisa diedit kembali.'; actionColor = 'bg-rose-700 hover:bg-rose-800'"
                      class="px-5 py-2 bg-rose-700 hover:bg-rose-800 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors">
                Tolak Permanen
              </button>
              
              {{-- Tombol Kembalikan untuk Revisi --}}
              <button type="button" 
                      @click="showModal = true; actionValue = 'Revisi'; actionTitle = 'Kembalikan untuk Revisi?'; actionText = 'Apakah Anda yakin ingin KEMBALIKAN pengajuan RAB ini ke Staf untuk diperbaiki?'; actionColor = 'bg-amber-500 hover:bg-amber-600'"
                      class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors">
                Kembalikan untuk Revisi
              </button>

              {{-- Tombol Persetujuan --}}
              <button type="button" 
                      @click="showModal = true; actionValue = 'ACC'; actionTitle = 'Konfirmasi Persetujuan'; actionText = 'Apakah Anda yakin ingin menyetujui dan meneruskan ke Pimpinan?'; actionColor = 'bg-[#2e358b] hover:bg-blue-900'"
                      class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-colors">
                Setujui & Teruskan &rarr;
              </button>
            </div>
          </form>

          {{-- PRESENTASI: Komponen Custom Modal UI (Tailwind CSS) yang baru ditambahkan untuk menggantikan browser alert bawaan --}}
          <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
              <!-- Background Overlay -->
              <div x-show="showModal" 
                   x-transition:enter="transition ease-out duration-300"
                   x-transition:enter-start="opacity-0 backdrop-blur-none"
                   x-transition:enter-end="opacity-100 backdrop-blur-sm"
                   x-transition:leave="transition ease-in duration-200"
                   x-transition:leave-start="opacity-100 backdrop-blur-sm"
                   x-transition:leave-end="opacity-0 backdrop-blur-none"
                   class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" 
                   @click="showModal = false"></div>

              <!-- Modal Card Container -->
              <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                  <div x-show="showModal" 
                       x-transition:enter="transition ease-out duration-300"
                       x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                       x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                       x-transition:leave="transition ease-in duration-200"
                       x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                       x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                       class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md">
                      
                      <!-- Modal Body -->
                      <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                          <div class="sm:flex sm:items-start">
                              <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 sm:mx-0 sm:h-10 sm:w-10">
                                  <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                                  </svg>
                              </div>
                              <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                  <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title" x-text="actionTitle"></h3>
                                  <div class="mt-2">
                                      <p class="text-sm text-slate-500" x-text="actionText"></p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      
                      <!-- Modal Footer (Actions) -->
                      <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                          <button type="button" 
                                  @click="submitForm()" 
                                  :class="actionColor"
                                  class="inline-flex w-full justify-center rounded-xl px-4 py-2 text-sm font-semibold text-white shadow-sm sm:w-auto transition-colors">
                              Ya, Lanjutkan
                          </button>
                          <button type="button" 
                                  @click="showModal = false" 
                                  class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                              Batal
                          </button>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
    @elseif($pengajuan->status === \App\Enums\StatusPengajuan::PROSES_PENCAIRAN)
      <div class="mt-6 bg-indigo-50 p-6 rounded-2xl border border-indigo-100 shadow-sm">
        <h2 class="text-sm font-bold text-indigo-900 uppercase tracking-wider mb-2">
          Pencairan Dana & Upload Bukti Transfer
        </h2>
        <p class="text-xs text-slate-500 mb-4">
          Pengajuan ini telah disetujui Pimpinan. Silakan lakukan pencairan dana dan unggah bukti transfer.
        </p>
        <form action="{{ route('finance.upload_bukti', $pengajuan->id_pengajuan) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
          @csrf
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">File Bukti Pencairan (PDF/Image)</label>
            <input type="file" name="bukti_pencairan" required class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
          </div>
          <div class="flex justify-end">
            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm">
              Upload Bukti & Selesai
            </button>
          </div>
        </form>
      </div>
    @else
      <div class="mt-6 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs text-slate-500 text-center">
        Pengajuan ini berstatus <strong>{{ $pengajuan->status }}</strong>.
      </div>
    @endif
  </div>
@endsection
