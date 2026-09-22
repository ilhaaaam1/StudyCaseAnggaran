@extends('layouts.app')

@section('title', 'Buat Pengajuan RAB Baru - SIRAB Kelompok-3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('staff.dashboard') }}" class="hover:text-slate-800">Dashboard Staf</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Buat Pengajuan RAB</span>
  </div>

  <div class="max-w-5xl mx-auto mb-10">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-6 flex items-center justify-between">
      <div>
        <span class="text-xs font-semibold text-indigo-600 uppercase tracking-wider">Formulir Pengajuan RAB</span>
        <h1 class="text-2xl font-bold text-slate-900 mt-0.5">Buat Rencana Anggaran Biaya</h1>
        <p class="text-xs text-slate-500 mt-1">Isi identitas pengajuan, rincian barang/jasa, dan lampirkan dokumen pendukung.</p>
      </div>
      <div class="text-right">
        <span class="text-[10px] text-slate-400 font-semibold block uppercase">Nomor RAB Otomatis</span>
        <span class="font-mono text-base font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-200 inline-block">
          {{ $autoNoRab ?? 'RAB-'.date('Y').'-AUTO' }}
        </span>
      </div>
    </div>

    <form action="{{ route('staff.rab.update', $pengajuan->id_pengajuan) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
      @csrf
      @method('PUT')

      <!-- Card 1: Data Pokok Pengajuan -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">
          1. Data Pokok Pengajuan
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Pengajuan <span class="text-rose-500">*</span></label>
            <input type="text" name="judul_pengajuan" value="{{ old('judul_pengajuan', $pengajuan->judul_pengajuan) }}" required
                   placeholder="Contoh: Pengadaan Laptop Divisi Operasional"
                   class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500">
            @error('judul_pengajuan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            {{-- PRESENTASI: Penyesuaian Label Field Divisi di Edit --}}
            <label class="block text-xs font-semibold text-slate-700 mb-1">Bidang / Bagian <span class="text-rose-500">*</span></label>
            <select name="id_divisi" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500">
              <option value="">-- Pilih Bidang / Bagian --</option>
              @foreach($divisiList ?? [] as $d)
                <option value="{{ $d->id_divisi }}" {{ old('id_divisi', $pengajuan->id_divisi) == $d->id_divisi ? 'selected' : '' }}>
                  {{ $d->nama_divisi }}
                </option>
              @endforeach
            </select>
            @error('id_divisi') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Periode Penggunaan <span class="text-rose-500">*</span></label>
            <input type="text" name="periode_penggunaan" value="{{ old('periode_penggunaan', $pengajuan->periode_penggunaan) }}" required
                   placeholder="Contoh: Triwulan I 2026"
                   class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500">
            @error('periode_penggunaan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <div>
            {{-- PRESENTASI: Mengganti Input Dropdown Prioritas di Edit --}}
            <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Anggaran <span class="text-rose-500">*</span></label>
            <select name="kategori_anggaran" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500">
              <option value="" disabled>-- Pilih Kategori --</option>
              <option value="Operasional Rutin" {{ old('kategori_anggaran', $pengajuan->kategori_anggaran) === 'Operasional Rutin' ? 'selected' : '' }}>Operasional Rutin</option>
              <option value="Pengadaan Barang/Aset" {{ old('kategori_anggaran', $pengajuan->kategori_anggaran) === 'Pengadaan Barang/Aset' ? 'selected' : '' }}>Pengadaan Barang/Aset</option>
              <option value="Pemeliharaan & Perbaikan" {{ old('kategori_anggaran', $pengajuan->kategori_anggaran) === 'Pemeliharaan & Perbaikan' ? 'selected' : '' }}>Pemeliharaan & Perbaikan</option>
              <option value="Kegiatan / Acara" {{ old('kategori_anggaran', $pengajuan->kategori_anggaran) === 'Kegiatan / Acara' ? 'selected' : '' }}>Kegiatan / Acara</option>
            </select>
            @error('kategori_anggaran') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Latar Belakang & Urgensi <span class="text-rose-500">*</span></label>
          <textarea name="latar_belakang" rows="3" required
                    placeholder="Uraikan justifikasi kebutuhan anggaran ini secara ringkas dan jelas..."
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500">{{ old('latar_belakang', $pengajuan->latar_belakang) }}</textarea>
          @error('latar_belakang') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <!-- Card 2: Rincian Item Anggaran -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">2. Rincian Item Belanja</h2>
          <button type="button" onclick="tambahBarisItem()"
                  class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-200">
            + Tambah Baris
          </button>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse" id="tabelItem">
            <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
              <tr>
                <th class="px-3 py-2 w-10 text-center">#</th>
                <th class="px-3 py-2">Uraian Barang / Jasa</th>
                <th class="px-3 py-2 w-28">Satuan</th>
                <th class="px-3 py-2 w-24">Volume</th>
                <th class="px-3 py-2 w-36">Harga Satuan (Rp)</th>
                <th class="px-3 py-2 w-36 text-right">Subtotal</th>
                <th class="px-3 py-2 w-12 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody id="bodyItem" class="divide-y divide-slate-100">
              @if($pengajuan->rincianItem && count($pengajuan->rincianItem) > 0)
                @foreach($pengajuan->rincianItem as $index => $item)
                  <tr class="item-row">
                    <td class="px-3 py-2.5 text-center text-slate-400 font-mono row-index">{{ $index + 1 }}</td>
                    <td class="px-3 py-2.5">
                      <input type="text" name="items[{{ $index }}][uraian_barang]" value="{{ $item->uraian_barang }}" required placeholder="Nama item / spek"
                             class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs">
                    </td>
                    <td class="px-3 py-2.5">
                      <input type="text" name="items[{{ $index }}][satuan]" value="{{ $item->satuan }}" required placeholder="Unit / Pcs / Bulan"
                             class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs">
                    </td>
                    <td class="px-3 py-2.5">
                      <input type="number" name="items[{{ $index }}][volume]" step="any" min="1" value="{{ (float)$item->volume }}" required oninput="hitungSubtotal(this)"
                             class="input-volume w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs font-mono">
                    </td>
                    <td class="px-3 py-2.5">
                      <input type="number" name="items[{{ $index }}][harga_satuan]" step="any" min="0" value="{{ (float)$item->harga_satuan }}" required oninput="hitungSubtotal(this)"
                             placeholder="0" class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs font-mono">
                    </td>
                    <td class="px-3 py-2.5 text-right font-mono font-semibold text-slate-800 subtotal-text">
                      Rp {{ number_format((float)($item->volume * $item->harga_satuan), 0, ',', '.') }}
                    </td>
                    <td class="px-3 py-2.5 text-center">
                      <button type="button" onclick="hapusBaris(this)" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                    </td>
                  </tr>
                @endforeach
              @else
                <tr class="item-row">
                  <td class="px-3 py-2.5 text-center text-slate-400 font-mono row-index">1</td>
                  <td class="px-3 py-2.5">
                    <input type="text" name="items[0][uraian_barang]" required placeholder="Nama item / spek"
                           class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs">
                  </td>
                  <td class="px-3 py-2.5">
                    <input type="text" name="items[0][satuan]" required placeholder="Unit / Pcs / Bulan"
                           class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs">
                  </td>
                  <td class="px-3 py-2.5">
                    <input type="number" name="items[0][volume]" step="any" min="1" value="1" required oninput="hitungSubtotal(this)"
                           class="input-volume w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs font-mono">
                  </td>
                  <td class="px-3 py-2.5">
                    <input type="number" name="items[0][harga_satuan]" step="any" min="0" required oninput="hitungSubtotal(this)"
                           placeholder="0" class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs font-mono">
                  </td>
                  <td class="px-3 py-2.5 text-right font-mono font-semibold text-slate-800 subtotal-text">
                    Rp 0
                  </td>
                  <td class="px-3 py-2.5 text-center">
                    <button type="button" onclick="hapusBaris(this)" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
                  </td>
                </tr>
              @endif
            </tbody>
            <tfoot class="bg-slate-50 font-bold border-t border-slate-200">
              <tr>
                <td colspan="5" class="px-3 py-3 text-right text-slate-700 uppercase tracking-wider text-xs">Total Estimasi Anggaran:</td>
                <td id="grandTotalText" class="px-3 py-3 text-right font-mono text-sm text-indigo-700">Rp 0</td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- Card 3: Dokumen Pendukung & Submit -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">
          3. Dokumen Pendukung & Finalisasi
        </h2>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-2">Dokumen Pendukung Saat Ini</label>
          {{-- PRESENTASI: Preview Dokumen Lama pada Form Edit --}}
          {{-- Menampilkan daftar dokumen yang sudah diunggah sebelumnya. Jika user mengupload dokumen baru, dokumen lama ini akan digantikan di sistem. --}}
          @if($pengajuan->dokumenPendukung && $pengajuan->dokumenPendukung->isNotEmpty())
            <ul class="mb-3 space-y-2">
              @foreach($pengajuan->dokumenPendukung as $doc)
                <li class="flex items-center gap-2 text-xs text-indigo-700 bg-indigo-50 px-3 py-2 rounded border border-indigo-100">
                  <i class="fa-solid fa-file-lines"></i>
                  <a href="{{ asset('storage/' . $doc->path_file) }}" target="_blank" class="hover:underline font-medium">{{ $doc->nama_file }}</a>
                </li>
              @endforeach
            </ul>
            <p class="text-[11px] text-amber-600 font-semibold mb-3">
              <i class="fa-solid fa-circle-info mr-1"></i> Jika Anda mengunggah file baru di bawah ini, file dokumen lama akan terhapus.
            </p>
          @else
            <p class="text-xs text-slate-500 mb-3 italic">Belum ada dokumen yang dilampirkan.</p>
          @endif

          <label class="block text-xs font-semibold text-slate-700 mb-1 mt-4">Upload Berkas Pengganti (PDF/JPG/PNG max 5MB)</label>
          <input type="file" name="dokumen_pendukung" accept=".pdf,.jpg,.jpeg,.png"
                 class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
          <p class="text-[11px] text-slate-400 mt-1">Lampirkan proposal kegiatan, perbandingan harga vendor, atau TOR acuan.</p>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
          <a href="{{ route('staff.dashboard') }}" class="px-4 py-2 border border-slate-300 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50">
            Batal
          </a>
          <button type="submit" name="action" value="draft" class="px-6 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-semibold shadow-sm">
            Simpan Draft
          </button>
          <button type="submit" name="action" value="send" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm">
            Kirim Pengajuan
          </button>
        </div>
      </div>
    </form>
  </div>

  <script>
    let barisIndex = {{ $pengajuan->rincianItem ? count($pengajuan->rincianItem) : 1 }};

    function tambahBarisItem() {
      const tbody = document.getElementById('bodyItem');
      const tr = document.createElement('tr');
      tr.className = 'item-row';
      tr.innerHTML = `
        <td class="px-3 py-2.5 text-center text-slate-400 font-mono row-index">${tbody.children.length + 1}</td>
        <td class="px-3 py-2.5">
          <input type="text" name="items[${barisIndex}][uraian_barang]" required placeholder="Nama item / spek"
                 class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs">
        </td>
        <td class="px-3 py-2.5">
          <input type="text" name="items[${barisIndex}][satuan]" required placeholder="Unit / Pcs"
                 class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs">
        </td>
        <td class="px-3 py-2.5">
          <input type="number" name="items[${barisIndex}][volume]" step="any" min="1" value="1" required oninput="hitungSubtotal(this)"
                 class="input-volume w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs font-mono">
        </td>
        <td class="px-3 py-2.5">
          <input type="number" name="items[${barisIndex}][harga_satuan]" step="any" min="0" required oninput="hitungSubtotal(this)"
                 placeholder="0" class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs font-mono">
        </td>
        <td class="px-3 py-2.5 text-right font-mono font-semibold text-slate-800 subtotal-text">
          Rp 0
        </td>
        <td class="px-3 py-2.5 text-center">
          <button type="button" onclick="hapusBaris(this)" class="text-rose-500 hover:text-rose-700 font-bold">&times;</button>
        </td>
      `;
      tbody.appendChild(tr);
      barisIndex++;
      updateRowNumbers();
    }

    function hapusBaris(btn) {
      const tbody = document.getElementById('bodyItem');
      if (tbody.children.length <= 1) {
        alert('Minimal harus ada 1 baris rincian item.');
        return;
      }
      btn.closest('tr').remove();
      updateRowNumbers();
      hitungTotalKeseluruhan();
    }

    function updateRowNumbers() {
      const rows = document.querySelectorAll('#bodyItem tr');
      rows.forEach((row, idx) => {
        const span = row.querySelector('.row-index');
        if (span) span.innerText = idx + 1;
      });
    }

    function hitungSubtotal(input) {
      const tr = input.closest('tr');
      const volumeInput = tr.querySelector('input[name*="[volume]"]');
      const hargaInput = tr.querySelector('input[name*="[harga_satuan]"]');
      const subtotalText = tr.querySelector('.subtotal-text');

      const vol = parseFloat(volumeInput.value) || 0;
      const hrg = parseFloat(hargaInput.value) || 0;
      const sub = vol * hrg;

      subtotalText.innerText = 'Rp ' + sub.toLocaleString('id-ID');
      hitungTotalKeseluruhan();
    }

    function hitungTotalKeseluruhan() {
      let total = 0;
      const rows = document.querySelectorAll('#bodyItem tr');
      rows.forEach(row => {
        const volumeInput = row.querySelector('input[name*="[volume]"]');
        const hargaInput = row.querySelector('input[name*="[harga_satuan]"]');
        if (volumeInput && hargaInput) {
          const vol = parseFloat(volumeInput.value) || 0;
          const hrg = parseFloat(hargaInput.value) || 0;
          total += (vol * hrg);
        }
      });
      document.getElementById('grandTotalText').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }
    
    // Inisialisasi total saat halaman pertama dimuat
    document.addEventListener('DOMContentLoaded', function() {
        hitungTotalKeseluruhan();
    });
  </script>
@endsection
