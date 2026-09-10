@extends('layouts.app')

@section('title', 'Buat Pengajuan RAB - SIRAB')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6">
    SIRAB / Pengajuan / <span class="text-slate-800 font-medium">Buat Baru</span>
  </div>

  <!-- Page Header -->
  <div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">
      Buat Pengajuan Anggaran (RAB)
    </h1>
    <p class="text-sm text-slate-500 mt-1">
      Isi formulir pengajuan Rencana Anggaran Biaya dengan lengkap dan akurat
    </p>
  </div>

  @if($errors->any())
    <div class="mb-6 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-sm">
      <div class="font-semibold mb-1">Terdapat kesalahan pada formulir:</div>
      <ul class="list-disc list-inside text-xs space-y-1">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('pengajuan.store') }}" enctype="multipart/form-data" id="formPengajuanRab">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
      <!-- LEFT COLUMN: Form Inputs -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Section A: Informasi Umum -->
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
          <div class="bg-[#f8fafc] px-5 py-3 border-b border-slate-200 flex justify-between items-center">
            <h2 class="text-xs font-bold text-slate-700 tracking-wide uppercase">
              A. Informasi Umum
            </h2>
            <div class="flex items-center gap-2">
              <span class="text-xs text-slate-400 font-mono">No. RAB:</span>
              <span class="text-xs font-mono font-bold text-slate-800 bg-slate-100 px-2.5 py-0.5 rounded border border-slate-200">
                {{ $noRabOtomatis }}
              </span>
              <input type="hidden" name="no_rab" value="{{ old('no_rab', $noRabOtomatis) }}">
            </div>
          </div>
          <div class="p-5 space-y-5">
            <!-- Judul Pengajuan -->
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                Judul Pengajuan <span class="text-rose-500">*</span>
              </label>
              <input
                type="text"
                name="judul_pengajuan"
                id="judul_pengajuan"
                required
                value="{{ old('judul_pengajuan') }}"
                placeholder="Contoh: Pengadaan Perangkat Server & Jaringan Kantor Pusat"
                class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm placeholder-slate-400 text-slate-800 focus:outline-none"
              />
            </div>

            <!-- Pemohon & Divisi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                  Pengguna Pemohon <span class="text-rose-500">*</span>
                </label>
                <select
                  name="id_pengguna"
                  id="id_pengguna"
                  required
                  class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-700 bg-white focus:outline-none"
                >
                  <option value="">Pilih Pengguna Pemohon</option>
                  @foreach($penggunaList as $user)
                    <option value="{{ $user->id_pengguna }}" {{ old('id_pengguna') == $user->id_pengguna ? 'selected' : '' }}>
                      {{ $user->nama_lengkap }} ({{ $user->jabatan }})
                    </option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                  Divisi / Unit Kerja <span class="text-rose-500">*</span>
                </label>
                <select
                  name="id_divisi"
                  id="id_divisi"
                  required
                  class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-700 bg-white focus:outline-none"
                >
                  <option value="">Pilih Divisi</option>
                  @foreach($divisiList as $div)
                    <option value="{{ $div->id_divisi }}" {{ old('id_divisi') == $div->id_divisi ? 'selected' : '' }}>
                      {{ $div->nama_divisi }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Periode & Prioritas & Estimasi Total -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                  Periode Penggunaan <span class="text-rose-500">*</span>
                </label>
                <select
                  name="periode_penggunaan"
                  id="periode_penggunaan"
                  required
                  class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm text-slate-700 bg-white focus:outline-none"
                >
                  <option value="">Pilih Periode</option>
                  @foreach($periodeList as $per)
                    <option value="{{ $per }}" {{ old('periode_penggunaan') === $per ? 'selected' : '' }}>
                      {{ $per }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                  Prioritas <span class="text-rose-500">*</span>
                </label>
                <input type="hidden" name="prioritas" id="prioritas" value="{{ old('prioritas', 'sedang') }}">
                <div class="flex rounded-md border border-slate-300 overflow-hidden" id="priorityGroup">
                  <button
                    type="button"
                    onclick="selectPriority('rendah')"
                    data-priority="rendah"
                    class="priority-btn flex-1 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 border-r border-slate-300 transition-colors"
                  >
                    Rendah
                  </button>
                  <button
                    type="button"
                    onclick="selectPriority('sedang')"
                    data-priority="sedang"
                    class="priority-btn flex-1 py-2 text-xs font-medium bg-[#1e293b] text-white transition-colors"
                  >
                    Sedang
                  </button>
                  <button
                    type="button"
                    onclick="selectPriority('tinggi')"
                    data-priority="tinggi"
                    class="priority-btn flex-1 py-2 text-xs font-medium text-slate-600 hover:bg-slate-50 border-l border-slate-300 transition-colors"
                  >
                    Tinggi
                  </button>
                </div>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                  Estimasi Total (Otomatis) <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                  <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-sm">Rp</span>
                  <input
                    type="text"
                    id="estimasi_total_display"
                    value="0"
                    class="w-full border border-slate-300 rounded-md pl-10 pr-3 py-2 text-sm font-mono-num font-semibold text-slate-800 bg-slate-50 cursor-not-allowed"
                    readonly
                  />
                </div>
              </div>
            </div>

            <!-- Latar Belakang & Justifikasi -->
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                Latar Belakang & Justifikasi Kebutuhan <span class="text-rose-500">*</span>
              </label>
              <textarea
                name="latar_belakang"
                id="latar_belakang"
                rows="3"
                required
                placeholder="Jelaskan secara detail urgensi, tujuan, dan justifikasi pengajuan anggaran ini..."
                class="w-full border border-slate-300 rounded-md px-3 py-2 text-sm placeholder-slate-400 text-slate-800 focus:outline-none"
              >{{ old('latar_belakang') }}</textarea>
            </div>
          </div>
        </div>

        <!-- Section B: Rincian Item (Child Table: rincian_item) -->
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
          <div class="bg-[#f8fafc] px-5 py-3 border-b border-slate-200 flex justify-between items-center">
            <h2 class="text-xs font-bold text-slate-700 tracking-wide uppercase">
              B. Rincian Item Anggaran Biaya
            </h2>
            <span class="text-[11px] text-slate-400">Tersimpan ke tabel <code>rincian_item</code></span>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
              <thead class="text-[10px] text-slate-500 uppercase border-b border-slate-200 bg-slate-50">
                <tr>
                  <th class="px-4 py-3 font-semibold w-12 text-center">No.</th>
                  <th class="px-4 py-3 font-semibold">Uraian Kegiatan / Barang</th>
                  <th class="px-4 py-3 font-semibold w-24">Satuan</th>
                  <th class="px-4 py-3 font-semibold w-20">Volume</th>
                  <th class="px-4 py-3 font-semibold w-36">Harga Satuan (Rp)</th>
                  <th class="px-4 py-3 font-semibold w-36">Total Harga (Rp)</th>
                  <th class="px-4 py-3 font-semibold w-12 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-xs" id="rincianBody">
                <tr class="rincian-row">
                  <td class="px-4 py-3 text-slate-400 text-center row-num">1</td>
                  <td class="px-4 py-2">
                    <input
                      type="text"
                      name="rincian[0][uraian_barang]"
                      required
                      placeholder="Contoh: Laptop Dell XPS 15 (Core i7)"
                      class="w-full border border-slate-200 rounded px-2.5 py-1.5 text-xs text-slate-800 focus:outline-none"
                    />
                  </td>
                  <td class="px-4 py-2">
                    <input
                      type="text"
                      name="rincian[0][satuan]"
                      required
                      placeholder="Unit/Set/m2"
                      value="Unit"
                      class="w-full border border-slate-200 rounded px-2.5 py-1.5 text-xs text-slate-800 focus:outline-none"
                    />
                  </td>
                  <td class="px-4 py-2">
                    <input
                      type="number"
                      name="rincian[0][volume]"
                      required
                      min="1"
                      value="1"
                      oninput="recalculateRincian()"
                      class="vol-input w-full border border-slate-200 rounded px-2.5 py-1.5 text-xs text-center text-slate-800 focus:outline-none"
                    />
                  </td>
                  <td class="px-4 py-2">
                    <input
                      type="number"
                      name="rincian[0][harga_satuan]"
                      required
                      min="0"
                      step="1000"
                      value="0"
                      oninput="recalculateRincian()"
                      class="price-input w-full border border-slate-200 rounded px-2.5 py-1.5 text-xs text-right font-mono-num text-slate-800 focus:outline-none"
                    />
                  </td>
                  <td class="px-4 py-2 font-semibold text-slate-800 font-mono-num text-right subtotal-text">
                    Rp 0
                  </td>
                  <td class="px-4 py-2 text-center">
                    <button type="button" onclick="deleteRow(this)" class="text-slate-300 hover:text-rose-500 font-bold text-sm px-1.5 transition-colors">✕</button>
                  </td>
                </tr>
              </tbody>
              <tfoot class="bg-[#f8fafc] border-t-2 border-slate-200">
                <tr>
                  <td colspan="5" class="px-5 py-3 text-right text-xs font-bold text-slate-700">
                    TOTAL KESELURUHAN
                  </td>
                  <td class="px-4 py-3 font-bold text-[#1e293b] font-mono-num text-xs text-right" id="footerTotal">
                    Rp 0
                  </td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
          <div class="p-4 border-t border-slate-200 bg-white">
            <button
              type="button"
              onclick="addRincianRow()"
              class="text-xs font-semibold text-[#1e293b] hover:text-indigo-600 flex items-center gap-1 transition-colors"
            >
              + Tambah Baris Item
            </button>
          </div>
        </div>

        <!-- Section C: Dokumen Pendukung (Child Table: dokumen_pendukung) -->
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
          <div class="bg-[#f8fafc] px-5 py-3 border-b border-slate-200 flex justify-between items-center">
            <h2 class="text-xs font-bold text-slate-700 tracking-wide uppercase">
              C. Dokumen Pendukung
            </h2>
            <span class="text-[11px] text-slate-400">Tersimpan ke tabel <code>dokumen_pendukung</code></span>
          </div>
          <div class="p-5">
            <input
              type="file"
              name="dokumen[]"
              id="filePicker"
              multiple
              class="hidden"
              onchange="renderSelectedFiles(this)"
            />
            <div
              onclick="document.getElementById('filePicker').click()"
              class="border-2 border-dashed border-slate-300 rounded-lg bg-slate-50 p-6 text-center flex flex-col items-center justify-center cursor-pointer hover:bg-slate-100 transition-colors mb-4"
            >
              <svg class="w-6 h-6 text-slate-400 mb-2 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
              </svg>
              <p class="text-xs font-medium text-slate-600 mb-0.5">
                Klik untuk upload dokumen pendukung (Surat, Spek Teknis, Penawaran)
              </p>
              <p class="text-[10px] text-slate-400">
                PDF, Excel, Word, Gambar — Maksimal 10MB per file
              </p>
            </div>
            <div id="fileListWrapper" class="space-y-2">
              <div id="fileEmptyNotice" class="text-center text-xs text-slate-400 py-2">
                Belum ada file dokumen yang dipilih.
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN: Summary & Action Buttons -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Ringkasan Pengajuan Card -->
        <div class="bg-[#111827] rounded-lg shadow-sm p-5 text-white">
          <h2 class="text-xs font-bold text-slate-400 tracking-wide uppercase mb-4">
            Ringkasan Pengajuan
          </h2>
          <div class="space-y-3 text-xs mb-6">
            <div class="flex justify-between border-b border-slate-700 pb-2">
              <span class="text-slate-400">No. RAB</span>
              <span class="font-mono font-medium text-indigo-400">{{ $noRabOtomatis }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-700 pb-2">
              <span class="text-slate-400">Judul</span>
              <span class="font-medium truncate max-w-[150px]" id="previewJudul">-</span>
            </div>
            <div class="flex justify-between border-b border-slate-700 pb-2">
              <span class="text-slate-400">Divisi</span>
              <span class="font-medium" id="previewDivisi">-</span>
            </div>
            <div class="flex justify-between border-b border-slate-700 pb-2">
              <span class="text-slate-400">Periode</span>
              <span class="font-medium" id="previewPeriode">-</span>
            </div>
            <div class="flex justify-between border-b border-slate-700 pb-2">
              <span class="text-slate-400">Prioritas</span>
              <span class="font-medium capitalize" id="previewPrioritas">Sedang</span>
            </div>
          </div>
          <div class="flex justify-between items-end pt-2 border-t border-slate-800">
            <span class="text-xs text-slate-400">Estimasi Total</span>
            <span class="text-lg font-bold font-mono-num text-emerald-400" id="previewTotal">
              Rp 0
            </span>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3 pt-2">
          <button
            type="submit"
            name="status"
            value="diajukan"
            class="w-full bg-[#1e293b] hover:bg-slate-800 text-white font-medium text-sm py-2.5 rounded-md transition-colors shadow-sm flex items-center justify-center gap-2"
          >
            Ajukan ke Supervisor / Admin
          </button>
          <button
            type="submit"
            name="status"
            value="draft"
            class="w-full bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-medium text-sm py-2.5 rounded-md transition-colors"
          >
            Simpan sebagai Draft
          </button>
        </div>
      </div>
    </div>
  </form>
@endsection

@push('scripts')
<script>
  let rincianIndex = 1;

  function selectPriority(level) {
    document.getElementById('prioritas').value = level;
    document.getElementById('previewPrioritas').textContent = level.charAt(0).toUpperCase() + level.slice(1);

    document.querySelectorAll('.priority-btn').forEach(btn => {
      if (btn.dataset.priority === level) {
        btn.classList.remove('bg-white', 'text-slate-600');
        btn.classList.add('bg-[#1e293b]', 'text-white');
      } else {
        btn.classList.remove('bg-[#1e293b]', 'text-white');
        btn.classList.add('bg-white', 'text-slate-600');
      }
    });
  }

  function addRincianRow() {
    const tbody = document.getElementById('rincianBody');
    const tr = document.createElement('tr');
    tr.className = 'rincian-row';
    tr.innerHTML = `
      <td class="px-4 py-3 text-slate-400 text-center row-num"></td>
      <td class="px-4 py-2">
        <input
          type="text"
          name="rincian[${rincianIndex}][uraian_barang]"
          required
          placeholder="Nama kegiatan atau barang..."
          class="w-full border border-slate-200 rounded px-2.5 py-1.5 text-xs text-slate-800 focus:outline-none"
        />
      </td>
      <td class="px-4 py-2">
        <input
          type="text"
          name="rincian[${rincianIndex}][satuan]"
          required
          placeholder="Unit/Set/m2"
          value="Unit"
          class="w-full border border-slate-200 rounded px-2.5 py-1.5 text-xs text-slate-800 focus:outline-none"
        />
      </td>
      <td class="px-4 py-2">
        <input
          type="number"
          name="rincian[${rincianIndex}][volume]"
          required
          min="1"
          value="1"
          oninput="recalculateRincian()"
          class="vol-input w-full border border-slate-200 rounded px-2.5 py-1.5 text-xs text-center text-slate-800 focus:outline-none"
        />
      </td>
      <td class="px-4 py-2">
        <input
          type="number"
          name="rincian[${rincianIndex}][harga_satuan]"
          required
          min="0"
          step="1000"
          value="0"
          oninput="recalculateRincian()"
          class="price-input w-full border border-slate-200 rounded px-2.5 py-1.5 text-xs text-right font-mono-num text-slate-800 focus:outline-none"
        />
      </td>
      <td class="px-4 py-2 font-semibold text-slate-800 font-mono-num text-right subtotal-text">
        Rp 0
      </td>
      <td class="px-4 py-2 text-center">
        <button type="button" onclick="deleteRow(this)" class="text-slate-300 hover:text-rose-500 font-bold text-sm px-1.5 transition-colors">✕</button>
      </td>
    `;
    tbody.appendChild(tr);
    rincianIndex++;
    updateRowIndices();
    recalculateRincian();
  }

  function deleteRow(btn) {
    const rows = document.querySelectorAll('.rincian-row');
    if (rows.length <= 1) {
      alert('Pengajuan wajib memiliki minimal satu baris rincian item.');
      return;
    }
    btn.closest('tr').remove();
    updateRowIndices();
    recalculateRincian();
  }

  function updateRowIndices() {
    document.querySelectorAll('.row-num').forEach((cell, idx) => {
      cell.textContent = idx + 1;
    });
  }

  function recalculateRincian() {
    let grandTotal = 0;
    document.querySelectorAll('.rincian-row').forEach(row => {
      const vol = parseFloat(row.querySelector('.vol-input').value) || 0;
      const price = parseFloat(row.querySelector('.price-input').value) || 0;
      const subtotal = vol * price;
      grandTotal += subtotal;
      row.querySelector('.subtotal-text').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    });

    const formatted = 'Rp ' + grandTotal.toLocaleString('id-ID');
    document.getElementById('estimasi_total_display').value = grandTotal.toLocaleString('id-ID');
    document.getElementById('footerTotal').textContent = formatted;
    document.getElementById('previewTotal').textContent = formatted;
  }

  function renderSelectedFiles(input) {
    const container = document.getElementById('fileListWrapper');
    const notice = document.getElementById('fileEmptyNotice');
    if (notice) notice.remove();
    container.innerHTML = '';

    if (input.files.length === 0) {
      container.innerHTML = '<div id="fileEmptyNotice" class="text-center text-xs text-slate-400 py-2">Belum ada file dokumen yang dipilih.</div>';
      return;
    }

    Array.from(input.files).forEach(file => {
      const sizeKB = Math.round(file.size / 1024) + ' KB';
      const div = document.createElement('div');
      div.className = 'flex items-center justify-between p-2.5 border border-slate-100 bg-slate-50 rounded-md text-xs';
      div.innerHTML = `
        <div class="flex items-center gap-2 truncate">
          <span>📎</span>
          <span class="text-slate-700 font-medium truncate">${file.name}</span>
        </div>
        <span class="text-slate-400 text-[10px] shrink-0 font-mono">${sizeKB}</span>
      `;
      container.appendChild(div);
    });
  }

  // Reactive listeners untuk ringkasan samping
  document.getElementById('judul_pengajuan').addEventListener('input', function() {
    document.getElementById('previewJudul').textContent = this.value || '-';
  });
  document.getElementById('id_divisi').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    document.getElementById('previewDivisi').textContent = selected.text || '-';
  });
  document.getElementById('periode_penggunaan').addEventListener('change', function() {
    document.getElementById('previewPeriode').textContent = this.value || '-';
  });

  document.addEventListener('DOMContentLoaded', () => {
    updateRowIndices();
    recalculateRincian();
  });
</script>
@endpush
