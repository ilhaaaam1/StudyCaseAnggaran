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
              $fileUrl = Storage::url($dokumen->path_file);
            @endphp
            <div class="border border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center bg-slate-50 relative group">
              @if($isImage)
                <img src="{{ $fileUrl }}" alt="{{ $dokumen->nama_file }}" class="max-h-48 object-contain rounded-lg mb-3 shadow-sm border border-slate-200" />
                <p class="text-xs text-slate-600 font-medium truncate w-full text-center" title="{{ $dokumen->nama_file }}">{{ $dokumen->nama_file }}</p>
                <div class="mt-3 flex gap-2">
                  <a href="{{ $fileUrl }}" target="_blank" class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Lihat Penuh</a>
                  <a href="{{ $fileUrl }}" download="{{ $dokumen->nama_file }}" class="text-xs px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Unduh</a>
                </div>
              @elseif($isPdf)
                <div class="w-full h-48 mb-3 border border-slate-200 rounded-lg overflow-hidden bg-white">
                  <iframe src="{{ $fileUrl }}" class="w-full h-full" title="{{ $dokumen->nama_file }}"></iframe>
                </div>
                <p class="text-xs text-slate-600 font-medium truncate w-full text-center" title="{{ $dokumen->nama_file }}">{{ $dokumen->nama_file }}</p>
                <div class="mt-3 flex gap-2">
                  <a href="{{ $fileUrl }}" target="_blank" class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Buka Tab Baru</a>
                  <a href="{{ $fileUrl }}" download="{{ $dokumen->nama_file }}" class="text-xs px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Unduh PDF</a>
                </div>
              @else
                <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center mb-3">
                  <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-xs text-slate-600 font-medium truncate w-full text-center" title="{{ $dokumen->nama_file }}">{{ $dokumen->nama_file }}</p>
                <div class="mt-3">
                  <a href="{{ $fileUrl }}" download="{{ $dokumen->nama_file }}" class="text-xs px-3 py-1.5 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition">Unduh File</a>
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

        <form id="approvalForm" action="{{ route('pimpinan.approve', $pengajuan->id_pengajuan) }}" method="POST" class="space-y-4">
          @csrf
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Pimpinan / Disposisi</label>
            <textarea name="catatan" rows="3" placeholder="Masukkan arahan realisasi atau alasan penolakan..."
                      class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:border-indigo-500"></textarea>
          </div>

          <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" onclick="showTolakModal()"
                    class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
              Tolak Pengajuan
            </button>
            <button type="submit" name="status" value="ACC"
                    onclick="return confirm('Apakah Anda yakin ingin memberikan persetujuan akhir dan meneruskan ke proses pencairan?');"
                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
              Setujui &amp; Lanjutkan ke Pencairan
            </button>
          </div>
        </form>
      </div>
    @else
      <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs text-slate-500 text-center">
        Pengajuan ini berstatus <strong>{{ $pengajuan->status }}</strong>.
      </div>
    @endif
  </div>

  <!-- Modal Konfirmasi Penolakan -->
  <div id="tolakModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div id="tolakModalContent" class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform scale-95 transition-transform duration-300">
      <div class="px-6 py-5 border-b border-slate-100 flex items-center gap-3 text-rose-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <h3 class="text-lg font-bold text-slate-900">Konfirmasi Penolakan</h3>
      </div>
      <div class="p-6 bg-slate-50">
        <p class="text-sm text-slate-600 leading-relaxed">
          Apakah Anda yakin ingin <strong>MENOLAK</strong> pengajuan RAB ini secara permanen? Alasan penolakan (jika ada) akan dikirimkan kepada pemohon.
        </p>
      </div>
      <div class="px-6 py-4 border-t border-slate-100 bg-white flex justify-end gap-3">
        <button type="button" onclick="closeTolakModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-colors">
          Batal
        </button>
        <button type="button" onclick="submitTolak()" class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-colors">
          Ya, Tolak
        </button>
      </div>
    </div>
  </div>

  <script>
    function showTolakModal() {
      const modal = document.getElementById('tolakModal');
      const content = document.getElementById('tolakModalContent');
      
      modal.classList.remove('hidden');
      // trigger reflow
      void modal.offsetWidth;
      modal.classList.remove('opacity-0');
      content.classList.remove('scale-95');
    }

    function closeTolakModal() {
      const modal = document.getElementById('tolakModal');
      const content = document.getElementById('tolakModalContent');
      
      modal.classList.add('opacity-0');
      content.classList.add('scale-95');
      
      setTimeout(() => {
        modal.classList.add('hidden');
      }, 300);
    }

    function submitTolak() {
      const form = document.getElementById('approvalForm');
      // Add hidden input to simulate 'Tolak' button click
      const hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'status';
      hiddenInput.value = 'Ditolak';
      form.appendChild(hiddenInput);
      form.submit();
    }

    // Tutup modal jika klik di luar modal (area backdrop)
    document.getElementById('tolakModal').addEventListener('click', function(e) {
      if (e.target === this) {
        closeTolakModal();
      }
    });
  </script>
@endsection
