@extends('layouts.app')

@section('title', 'Persetujuan Final Pimpinan - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('pimpinan.antrean') }}" class="hover:text-slate-800">Antrean Tahap 2</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Keputusan: {{ $pengajuan->no_rab }}</span>
  </div>

  <div class="max-w-4xl mx-auto space-y-6 mb-10">
    <!-- Header Card -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
      <div>
        <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Persetujuan Tingkat Eksekutif (Tahap 2 Final)</div>
        <h1 class="text-2xl font-bold text-slate-900 font-mono mt-0.5">{{ $pengajuan->no_rab }}</h1>
        <div class="mt-2 inline-block px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pengajuan->status === \App\Enums\StatusPengajuan::SELESAI || $pengajuan->status === \App\Enums\StatusPengajuan::PROSES_PENCAIRAN ? 'bg-emerald-100 text-emerald-800' : ($pengajuan->status === \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN ? 'bg-blue-100 text-blue-800' : ($pengajuan->status === \App\Enums\StatusPengajuan::MENUNGGU_FINANCE ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800')) }}">
          {{ $pengajuan->status }}
        </div>
        <p class="text-sm font-semibold text-slate-700 mt-1">{{ $pengajuan->judul_pengajuan }}</p>
        <div class="mt-2 flex items-center gap-2">
            <span class="px-2.5 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg border border-slate-200">
                <i class="fa-solid fa-tag mr-1"></i> {{ $pengajuan->kategori_anggaran ?? 'Tanpa Kategori' }}
            </span>
        </div>
        <p class="text-xs text-slate-500 mt-0.5">Pemohon: <span class="font-medium text-slate-700">{{ $pengajuan->pengguna->nama_lengkap ?? 'Staf' }}</span> ({{ $pengajuan->divisi->nama_divisi ?? '-' }})</p>
      </div>
      <div class="text-right">
        <span class="text-xs text-slate-400 block">Total Anggaran:</span>
        <span class="text-xl font-bold font-mono text-indigo-700">Rp {{ number_format((float) $pengajuan->estimasi_total, 0, ',', '.') }}</span>
      </div>
    </div>

    <!-- Catatan Verifikasi Finance (Level 1) -->
    <div class="bg-blue-50/60 p-5 rounded-2xl border border-blue-200 shadow-sm">
      <div class="flex items-center gap-2 mb-2">
        <span class="text-xs font-bold uppercase tracking-wider text-blue-900">Catatan Reviewer Finance (Tahap 1)</span>
        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 border border-blue-200">ACC Finance</span>
      </div>
      @php
        $financeLog = $pengajuan->alurPersetujuan->where('level_persetujuan', 1)->last();
      @endphp
      @if($financeLog)
        <p class="text-xs text-slate-700 italic">&ldquo;{{ $financeLog->catatan ?? 'Telah diverifikasi sesuai pagu anggaran dan harga satuan wajar.' }}&rdquo;</p>
        <p class="text-[11px] text-slate-500 mt-2">Diverifikasi oleh: <span class="font-semibold">{{ $financeLog->reviewer->nama_lengkap ?? 'Tim Finance' }}</span> &bull; {{ $financeLog->tanggal_proses ? \Carbon\Carbon::parse($financeLog->tanggal_proses)->format('d/m/Y H:i') : '' }}</p>
      @else
        <p class="text-xs text-slate-500 italic">Belum ada catatan log tersimpan.</p>
      @endif
    </div>

    <!-- Informasi Pokok Operasional Sekolah & Rentang Waktu Pelaksanaan -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
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
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
      <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3 mb-4">
        Rincian Item Belanja
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

    <!-- Form Keputusan Final Pimpinan -->
    @if($pengajuan->status === \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN)
      <div class="bg-white p-6 rounded-2xl border border-indigo-200 shadow-sm bg-indigo-50/20">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-2">Formulir Keputusan Final Pimpinan</h2>
        <p class="text-xs text-slate-500 mb-4">
          Bila disetujui, status akan diteruskan ke Finance untuk <strong>Proses Pencairan</strong>. Bila ditolak, status menjadi <strong>Ditolak</strong>.
        </p>

        {{-- PRESENTASI: Menambahkan x-data untuk state control modal persetujuan/penolakan (Alpine.js) --}}
        <div x-data="{ 
            showModal: false, 
            actionValue: '', 
            actionTitle: '',
            actionText: '', 
            actionColor: '',
            submitForm() {
                // PRESENTASI: Fungsi validasi catatan khusus untuk aksi penolakan dan revisi
                if ((this.actionValue === 'Revisi' || this.actionValue === 'Ditolak') && !$refs.catatanInput.value.trim()) {
                    alert('Catatan/disposisi WAJIB diisi jika pengajuan direvisi atau ditolak.');
                    this.showModal = false;
                    $refs.catatanInput.focus();
                    return;
                }
                // PRESENTASI: Fungsi untuk memasukkan nilai status ke dalam form tersembunyi dan men-submit form pimpinan
                $refs.statusInput.value = this.actionValue;
                $refs.approvalForm.submit();
            }
        }">
          <form x-ref="approvalForm" action="{{ route('pimpinan.approve', $pengajuan->id_pengajuan) }}" method="POST" class="space-y-4">
            @csrf
            {{-- PRESENTASI: Input hidden untuk menampung nilai status (ACC/Ditolak) --}}
            <input type="hidden" name="action" x-ref="statusInput">
            
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Pimpinan / Disposisi</label>
              <textarea name="catatan" x-ref="catatanInput" rows="3" placeholder="Masukkan arahan realisasi, alasan penolakan, atau instruksi perbaikan..."
                        class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:border-indigo-500"></textarea>
            </div>

            {{-- PRESENTASI: Memisahkan Logika Revisi dan Tolak Permanen --}}
            <div class="flex flex-wrap items-center justify-end gap-3 pt-2">
              {{-- Tombol Tolak Permanen --}}
              <button type="button" 
                      @click="showModal = true; actionValue = 'Ditolak'; actionTitle = 'Tolak Permanen?'; actionText = 'Apakah Anda yakin ingin MENOLAK pengajuan RAB ini secara permanen?'; actionColor = 'bg-rose-700 hover:bg-rose-800'"
                      class="px-5 py-2 bg-rose-700 hover:bg-rose-800 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                Tolak Permanen
              </button>
              
              {{-- Tombol Kembalikan untuk Revisi --}}
              <button type="button" 
                      @click="showModal = true; actionValue = 'Revisi'; actionTitle = 'Kembalikan untuk Revisi?'; actionText = 'Apakah Anda yakin ingin mengembalikan RAB ini ke Staf untuk perbaikan?'; actionColor = 'bg-amber-500 hover:bg-amber-600'"
                      class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                Kembalikan untuk Revisi
              </button>
              
              {{-- Tombol Persetujuan --}}
              <button type="button" 
                      @click="showModal = true; actionValue = 'ACC'; actionTitle = 'Konfirmasi Persetujuan Final'; actionText = 'Apakah Anda yakin ingin memberikan persetujuan akhir dan meneruskan ke proses pencairan?'; actionColor = 'bg-[#2e358b] hover:bg-blue-900'"
                      class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                Setujui &amp; Lanjutkan ke Pencairan
              </button>
            </div>
          </form>

          {{-- PRESENTASI: Komponen Custom Modal UI (Tailwind CSS & Alpine.js) menggantikan modal Vanilla JS & browser alert --}}
          <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
              <!-- Background Overlay dengan backdrop blur -->
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                  </svg>
                              </div>
                              <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                  <h3 class="text-lg font-bold leading-6 text-slate-900" id="modal-title" x-text="actionTitle"></h3>
                                  <div class="mt-2">
                                      <p class="text-sm text-slate-500" x-text="actionText"></p>
                                  </div>
                              </div>
                          </div>
                      </div>
                      
                      <!-- Modal Footer -->
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
    @else
      <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs text-slate-500 text-center">
        Pengajuan ini berstatus <strong>{{ $pengajuan->status }}</strong>.
      </div>
    @endif
  </div>
@endsection
