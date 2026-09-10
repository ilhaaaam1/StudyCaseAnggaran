@extends('layouts.app')

@section('title', 'Buat Pengajuan RAB - SIRAB Kelompok-3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Buat Pengajuan</span>
  </div>

  <!-- Page Header -->
  <div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Buat Pengajuan Anggaran</h1>
        <p class="text-sm text-slate-500 mt-1">
          Buat Pengajuan RAB Baru &bull; Isi formulir pengajuan RAB dengan lengkap, akurat, dan sertakan rincian belanja.
        </p>
      </div>
      <a href="{{ route('user.dashboard') }}" 
         class="text-xs text-slate-500 hover:text-slate-800 flex items-center gap-1">
        &larr; Kembali ke Dashboard
      </a>
    </div>
  </div>

  <!-- Form Pengajuan RAB (2-Column Figma Layout) -->
  <form action="{{ route('user.rab.store') }}" method="POST" enctype="multipart/form-data" id="formRab">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
      <!-- LEFT COLUMN: Form Sections (Col Span 2) -->
      <div class="lg:col-span-2 space-y-6">
        
        <!-- SECTION A: Informasi Umum -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
          <div class="bg-[#f8fafc] px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-700 tracking-wider uppercase flex items-center gap-2">
              <span class="w-5 h-5 rounded-full bg-[#1e293b] text-white flex items-center justify-center text-[10px]">A</span>
              Informasi Umum
            </h2>
            <span class="text-[11px] font-mono text-slate-400 font-semibold">{{ $autoNoRab ?? 'RAB-'.date('Y').'-NEW' }}</span>
          </div>

          <div class="p-5 space-y-5">
            <!-- Judul Pengajuan -->
            <div>
              <label for="judul_pengajuan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Judul Pengajuan <span class="text-rose-500">*</span>
              </label>
              <input type="text" 
                     name="judul_pengajuan" 
                     id="judul_pengajuan" 
                     required
                     value="{{ old('judul_pengajuan') }}"
                     oninput="syncSidebarPreview()"
                     placeholder="Contoh: Pengadaan Peralatan IT Divisi Teknologi Q4"
                     class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-800 placeholder-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all"/>
            </div>

            <!-- Divisi & Periode Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label for="id_divisi" class="block text-xs font-semibold text-slate-700 mb-1.5">
                  Divisi / Unit Kerja <span class="text-rose-500">*</span>
                </label>
                <select name="id_divisi" 
                        id="id_divisi" 
                        required
                        onchange="syncSidebarPreview()"
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-700 bg-white cursor-pointer focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                  <option value="">Pilih Divisi</option>
                  @foreach($divisiList as $divisi)
                    <option value="{{ $divisi->id_divisi }}" {{ old('id_divisi', $user->id_divisi ?? null) == $divisi->id_divisi ? 'selected' : '' }}>
                      {{ $divisi->nama_divisi }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label for="periode_penggunaan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                  Periode Penggunaan <span class="text-rose-500">*</span>
                </label>
                <select name="periode_penggunaan" 
                        id="periode_penggunaan" 
                        required
                        onchange="syncSidebarPreview()"
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm text-slate-700 bg-white cursor-pointer focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                  <option value="Q3 {{ date('Y') }}" {{ old('periode_penggunaan') == 'Q3 '.date('Y') ? 'selected' : '' }}>Q3 {{ date('Y') }}</option>
                  <option value="Q4 {{ date('Y') }}" {{ old('periode_penggunaan', 'Q4 '.date('Y')) == 'Q4 '.date('Y') ? 'selected' : '' }}>Q4 {{ date('Y') }}</option>
                  <option value="Q1 {{ date('Y')+1 }}" {{ old('periode_penggunaan') == 'Q1 '.(date('Y')+1) ? 'selected' : '' }}>Q1 {{ date('Y')+1 }}</option>
                  <option value="Q2 {{ date('Y')+1 }}" {{ old('periode_penggunaan') == 'Q2 '.(date('Y')+1) ? 'selected' : '' }}>Q2 {{ date('Y')+1 }}</option>
                </select>
              </div>
            </div>

            <!-- Prioritas & Estimasi Total -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <!-- Prioritas Selection Pill Group -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                  Prioritas <span class="text-rose-500">*</span>
                </label>
                <input type="hidden" name="prioritas" id="prioritasInput" value="{{ old('prioritas', 'Sedang') }}">
                <div class="flex rounded-lg border border-slate-300 overflow-hidden text-xs font-medium">
                  <button type="button" 
                          onclick="selectPriority('Rendah')"
                          id="prioRendah"
                          class="flex-1 py-2 text-center transition-colors border-r border-slate-300 text-slate-600 hover:bg-slate-50">
                    Rendah
                  </button>
                  <button type="button" 
                          onclick="selectPriority('Sedang')"
                          id="prioSedang"
                          class="flex-1 py-2 text-center transition-colors bg-[#1e293b] text-white">
                    Sedang
                  </button>
                  <button type="button" 
                          onclick="selectPriority('Tinggi')"
                          id="prioTinggi"
                          class="flex-1 py-2 text-center transition-colors text-slate-600 hover:bg-slate-50 border-l border-slate-300">
                    Tinggi
                  </button>
                </div>
              </div>

              <!-- Estimasi Total Display (Readonly & Auto-Calculated) -->
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                  Estimasi Total <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                  <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 text-sm font-semibold">Rp</span>
                  <input type="text" 
                         id="estimasiTotalHeader" 
                         readonly
                         value="0"
                         class="w-full border border-slate-300 rounded-lg pl-10 pr-3.5 py-2 text-sm text-slate-900 bg-slate-50/70 font-mono-num font-bold cursor-not-allowed"/>
                </div>
              </div>
            </div>

            <!-- Latar Belakang & Justifikasi -->
            <div>
              <label for="latar_belakang" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Latar Belakang &amp; Justifikasi <span class="text-rose-500">*</span>
              </label>
              <textarea name="latar_belakang" 
                        id="latar_belakang" 
                        rows="3" 
                        required
                        placeholder="Jelaskan kebutuhan operasional dan urgensi pengajuan anggaran ini..."
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2 text-sm placeholder-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">{{ old('latar_belakang') }}</textarea>
            </div>
          </div>
        </div>

        <!-- SECTION B: Rincian Anggaran Biaya (Dynamic Table + Real-Time JS) -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
          <div class="bg-[#f8fafc] px-5 py-3.5 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-xs font-bold text-slate-700 tracking-wider uppercase flex items-center gap-2">
              <span class="w-5 h-5 rounded-full bg-[#1e293b] text-white flex items-center justify-center text-[10px]">B</span>
              Rincian Anggaran Biaya
            </h2>
            <span class="text-[11px] text-slate-400">Total dihitung otomatis secara real-time</span>
          </div>

          <div class="overflow-x-auto table-container">
            <table class="w-full text-left text-sm whitespace-nowrap" id="itemsTable">
              <thead class="text-[10px] text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-semibold">
                <tr>
                  <th class="px-4 py-3 w-10 text-center">No.</th>
                  <th class="px-4 py-3">Uraian Kegiatan / Barang <span class="text-rose-500">*</span></th>
                  <th class="px-4 py-3 w-28">Satuan <span class="text-rose-500">*</span></th>
                  <th class="px-3 py-3 w-24 text-center">Vol. <span class="text-rose-500">*</span></th>
                  <th class="px-4 py-3 w-40">Harga Satuan (Rp) <span class="text-rose-500">*</span></th>
                  <th class="px-4 py-3 w-40 text-right">Total Subtotal</th>
                  <th class="px-4 py-3 w-12 text-center">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-xs" id="itemsBody">
                <!-- Row 1 Default -->
                <tr class="item-row hover:bg-slate-50/50">
                  <td class="px-4 py-3 text-center text-slate-400 font-mono row-num">1</td>
                  <td class="px-4 py-3">
                    <input type="text" 
                           name="items[0][uraian_barang]" 
                           required
                           placeholder="Contoh: Laptop Dell XPS 15 (Core i7)"
                           class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs text-slate-800 placeholder-slate-300 focus:border-indigo-500">
                  </td>
                  <td class="px-4 py-3">
                    <input type="text" 
                           name="items[0][satuan]" 
                           required
                           value="Unit"
                           placeholder="Unit / Pcs / Bln"
                           class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs text-slate-800 focus:border-indigo-500">
                  </td>
                  <td class="px-3 py-3 text-center">
                    <input type="number" 
                           name="items[0][volume]" 
                           min="1" 
                           value="1" 
                           required
                           oninput="calculateRow(this)"
                           class="w-full min-w-[56px] border border-slate-300 rounded px-2.5 py-1.5 text-xs text-slate-900 font-bold bg-white text-center input-volume focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                  </td>
                  <td class="px-4 py-3">
                    <input type="number" 
                           name="items[0][harga_satuan]" 
                           min="0" 
                           step="1000" 
                           value="0" 
                           required
                           oninput="calculateRow(this)"
                           class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs text-slate-800 font-mono text-right input-harga focus:border-indigo-500">
                  </td>
                  <td class="px-4 py-3 text-right font-mono-num font-semibold text-slate-800 subtotal-text">
                    Rp 0
                  </td>
                  <td class="px-4 py-3 text-center">
                    <button type="button" 
                            onclick="removeRow(this)" 
                            class="text-slate-300 hover:text-rose-500 transition-colors p-1"
                            title="Hapus baris">
                      ✕
                    </button>
                  </td>
                </tr>
              </tbody>
              <tfoot class="bg-[#f8fafc] border-t-2 border-slate-200">
                <tr>
                  <td colspan="5" class="px-5 py-3 text-right text-xs font-bold text-slate-700 tracking-wider">
                    TOTAL KESELURUHAN
                  </td>
                  <td class="px-4 py-3 font-bold text-[#1e293b] font-mono-num text-xs text-right" id="grandTotalFooter">
                    Rp 0
                  </td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>

          <div class="p-4 border-t border-slate-200 bg-white">
            <button type="button" 
                    onclick="addRow()"
                    class="text-xs font-semibold text-[#1e293b] hover:text-indigo-600 flex items-center gap-1.5 transition-colors cursor-pointer">
              <span class="text-sm leading-none">+</span> Tambah Baris Item
            </button>
          </div>
        </div>

        <!-- SECTION C: Dokumen Pendukung -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
          <div class="bg-[#f8fafc] px-5 py-3.5 border-b border-slate-200">
            <h2 class="text-xs font-bold text-slate-700 tracking-wider uppercase flex items-center gap-2">
              <span class="w-5 h-5 rounded-full bg-[#1e293b] text-white flex items-center justify-center text-[10px]">C</span>
              Dokumen Pendukung
            </h2>
          </div>

          <div class="p-5">
            <!-- Drag & Drop Upload Area -->
            <label for="dokumen" 
                   class="border-2 border-dashed border-slate-300 rounded-xl bg-slate-50/60 p-8 text-center flex flex-col items-center justify-center cursor-pointer hover:bg-slate-100 hover:border-indigo-400 transition-all block mb-4">
              <svg class="w-8 h-8 text-slate-400 mb-2 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
              </svg>
              <p class="text-xs font-medium text-slate-700 mb-1">
                Klik untuk memilih file dokumen pendukung (Proposal / Penawaran / Spesifikasi)
              </p>
              <p class="text-[10px] text-slate-400">
                PDF, JPG, PNG &bull; Maks. 5MB per file
              </p>
              <input type="file" 
                     name="dokumen" 
                     id="dokumen" 
                     class="hidden" 
                     accept=".pdf,.jpg,.jpeg,.png,.xlsx,.docx"
                     onchange="handleFileSelect(this)"/>
            </label>

            <!-- Selected File Preview -->
            <div id="filePreview" class="hidden p-3.5 border border-slate-200 bg-slate-50 rounded-lg text-xs flex items-center justify-between">
              <div class="flex items-center gap-2.5 truncate">
                <span class="text-indigo-600 text-sm">📄</span>
                <span id="fileNameDisplay" class="font-medium text-slate-800 truncate">dokumen.pdf</span>
                <span id="fileSizeDisplay" class="text-[10px] text-slate-400 font-mono">(0 KB)</span>
              </div>
              <button type="button" onclick="clearFile()" class="text-rose-500 hover:text-rose-700 font-bold px-2 py-1">
                ✕
              </button>
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN: Sticky Sidebar & Summary (Col Span 1) -->
      <div class="lg:col-span-1 space-y-6 lg:sticky lg:top-20">
        
        <!-- Ringkasan Pengajuan (Dark Box) -->
        <div class="bg-[#111827] rounded-xl shadow-sm p-5 text-white">
          <h2 class="text-xs font-bold text-slate-400 tracking-wider uppercase mb-4">
            Ringkasan Pengajuan
          </h2>
          <div class="space-y-3 text-xs mb-6">
            <div class="flex justify-between border-b border-slate-800 pb-2.5">
              <span class="text-slate-400">Judul</span>
              <span id="previewJudul" class="font-medium text-slate-200 max-w-[140px] truncate text-right">-</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2.5">
              <span class="text-slate-400">Divisi</span>
              <span id="previewDivisi" class="font-medium text-slate-200 max-w-[140px] truncate text-right">-</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2.5">
              <span class="text-slate-400">Periode</span>
              <span id="previewPeriode" class="font-medium text-slate-200 text-right">Q4 {{ date('Y') }}</span>
            </div>
            <div class="flex justify-between border-b border-slate-800 pb-2.5">
              <span class="text-slate-400">Prioritas</span>
              <span id="previewPrioritas" class="font-medium capitalize text-amber-400">Sedang</span>
            </div>
          </div>
          <div class="flex justify-between items-end pt-2 border-t border-slate-800">
            <span class="text-xs text-slate-400">Est. Total</span>
            <span id="previewTotal" class="text-lg font-bold font-mono-num text-white">Rp 0</span>
          </div>
        </div>

        <!-- Alur Persetujuan Timeline (Figma design) -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
          <h2 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4">
            Alur Persetujuan
          </h2>
          <div class="relative border-l border-slate-200 ml-3 space-y-5">
            <div class="relative pl-6">
              <span class="absolute -left-3 top-0 w-6 h-6 rounded-full bg-indigo-50 border border-indigo-200 flex items-center justify-center text-[10px] font-bold text-indigo-700">1</span>
              <div class="text-xs font-semibold text-slate-800">Supervisor Langsung</div>
              <div class="text-[10px] text-slate-400">Review Awal &bull; 1-2 hari kerja</div>
            </div>

            <div class="relative pl-6">
              <span class="absolute -left-3 top-0 w-6 h-6 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">2</span>
              <div class="text-xs font-semibold text-slate-700">Manajer Divisi</div>
              <div class="text-[10px] text-slate-400">Evaluasi Kebutuhan &bull; 2-3 hari kerja</div>
            </div>

            <div class="relative pl-6">
              <span class="absolute -left-3 top-0 w-6 h-6 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">3</span>
              <div class="text-xs font-semibold text-slate-700">Direktur Keuangan</div>
              <div class="text-[10px] text-slate-400">Validasi Anggaran &bull; Persetujuan Final</div>
            </div>
          </div>
        </div>

        <!-- Kebijakan Anggaran Notice (Figma design) -->
        <div class="bg-amber-50/80 border border-amber-200 rounded-xl shadow-sm p-5">
          <h2 class="text-xs font-bold text-amber-900 mb-2.5 flex items-center gap-1.5">
            <span>📋</span> Kebijakan Anggaran
          </h2>
          <ul class="text-[11px] text-amber-800 space-y-1.5 list-disc pl-4 leading-relaxed">
            <li>Pengajuan di atas Rp 50 juta wajib melampirkan minimal 2 referensi harga.</li>
            <li>Sertakan uraian spesifikasi teknis barang secara jelas.</li>
            <li>Batas pengajuan reguler setiap tanggal 25 tiap bulan.</li>
            <li>Gunakan perkiraan harga pasar terkini termasuk PPN jika ada.</li>
          </ul>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3 pt-2">
          <button type="submit" 
                  class="w-full bg-[#1e293b] hover:bg-slate-800 text-white font-semibold text-xs sm:text-sm py-3 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Ajukan ke Supervisor
          </button>
          
          <a href="{{ route('user.dashboard') }}" 
             class="w-full block text-center bg-white hover:bg-slate-50 text-slate-600 font-medium text-xs sm:text-sm py-2.5 rounded-xl border border-slate-300 transition-colors">
            Batal &amp; Kembali
          </a>
        </div>

      </div>
    </div>
  </form>
@endsection

@push('scripts')
<script>
  // Format currency in Indonesian Rupiah format: Rp 12.345.678
  function formatRupiah(number) {
    return 'Rp ' + Math.round(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  // Calculate row subtotal and trigger grand total update
  function calculateRow(inputElement) {
    const row = inputElement.closest('tr');
    if (!row) return;

    const volumeInput = row.querySelector('.input-volume');
    const hargaInput = row.querySelector('.input-harga');
    const subtotalText = row.querySelector('.subtotal-text');

    const volume = parseFloat(volumeInput.value) || 0;
    const harga = parseFloat(hargaInput.value) || 0;
    const subtotal = Math.max(0, volume * harga);

    subtotalText.innerText = formatRupiah(subtotal);
    calculateGrandTotal();
  }

  // Calculate sum of all rows and update total displays
  function calculateGrandTotal() {
    let grandTotal = 0;
    const rows = document.querySelectorAll('#itemsBody .item-row');

    rows.forEach(row => {
      const volume = parseFloat(row.querySelector('.input-volume').value) || 0;
      const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
      grandTotal += Math.max(0, volume * harga);
    });

    const formatted = formatRupiah(grandTotal);

    // Update table footer
    const footerElem = document.getElementById('grandTotalFooter');
    if (footerElem) footerElem.innerText = formatted;

    // Update section A Estimasi Total
    const headerElem = document.getElementById('estimasiTotalHeader');
    if (headerElem) headerElem.value = Math.round(grandTotal).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    // Update right sidebar
    const previewTotal = document.getElementById('previewTotal');
    if (previewTotal) previewTotal.innerText = formatted;
  }

  // Add new dynamic row to items table
  function addRow() {
    const tbody = document.getElementById('itemsBody');
    const newIndex = tbody.children.length;

    const tr = document.createElement('tr');
    tr.className = 'item-row hover:bg-slate-50/50';
    tr.innerHTML = `
      <td class="px-4 py-3 text-center text-slate-400 font-mono row-num">${newIndex + 1}</td>
      <td class="px-4 py-3">
        <input type="text" 
               name="items[${newIndex}][uraian_barang]" 
               required
               placeholder="Nama kegiatan / barang belanja"
               class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs text-slate-800 placeholder-slate-300 focus:border-indigo-500">
      </td>
      <td class="px-4 py-3">
        <input type="text" 
               name="items[${newIndex}][satuan]" 
               required
               value="Unit"
               placeholder="Unit / Pcs / Set"
               class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs text-slate-800 focus:border-indigo-500">
      </td>
      <td class="px-3 py-3 text-center">
        <input type="number" 
               name="items[${newIndex}][volume]" 
               min="1" 
               value="1" 
               required
               oninput="calculateRow(this)"
               class="w-full min-w-[56px] border border-slate-300 rounded px-2.5 py-1.5 text-xs text-slate-900 font-bold bg-white text-center input-volume focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
      </td>
      <td class="px-4 py-3">
        <input type="number" 
               name="items[${newIndex}][harga_satuan]" 
               min="0" 
               step="1000" 
               value="0" 
               required
               oninput="calculateRow(this)"
               class="w-full border border-slate-300 rounded px-2.5 py-1.5 text-xs text-slate-800 font-mono text-right input-harga focus:border-indigo-500">
      </td>
      <td class="px-4 py-3 text-right font-mono-num font-semibold text-slate-800 subtotal-text">
        Rp 0
      </td>
      <td class="px-4 py-3 text-center">
        <button type="button" 
                onclick="removeRow(this)" 
                class="text-slate-300 hover:text-rose-500 transition-colors p-1"
                title="Hapus baris">
          ✕
        </button>
      </td>
    `;

    tbody.appendChild(tr);
    renumberRows();
    calculateGrandTotal();
  }

  // Remove row
  function removeRow(btn) {
    const tbody = document.getElementById('itemsBody');
    if (tbody.children.length <= 1) {
      alert('Pengajuan RAB minimal harus memiliki minimal 1 baris item belanja.');
      return;
    }

    const row = btn.closest('tr');
    if (row) {
      row.remove();
      renumberRows();
      calculateGrandTotal();
    }
  }

  // Renumber rows and inputs index
  function renumberRows() {
    const rows = document.querySelectorAll('#itemsBody .item-row');
    rows.forEach((row, index) => {
      row.querySelector('.row-num').innerText = index + 1;
      
      const uraian = row.querySelector('input[name*="[uraian_barang]"]');
      const satuan = row.querySelector('input[name*="[satuan]"]');
      const volume = row.querySelector('input[name*="[volume]"]');
      const harga = row.querySelector('input[name*="[harga_satuan]"]');

      if (uraian) uraian.name = `items[${index}][uraian_barang]`;
      if (satuan) satuan.name = `items[${index}][satuan]`;
      if (volume) volume.name = `items[${index}][volume]`;
      if (harga) harga.name = `items[${index}][harga_satuan]`;
    });
  }

  // Select Priority
  function selectPriority(priority) {
    document.getElementById('prioritasInput').value = priority;
    
    const pRendah = document.getElementById('prioRendah');
    const pSedang = document.getElementById('prioSedang');
    const pTinggi = document.getElementById('prioTinggi');

    // Reset styles
    [pRendah, pSedang, pTinggi].forEach(el => {
      el.className = el.className.replace('bg-[#1e293b] text-white', 'text-slate-600 hover:bg-slate-50');
    });

    if (priority === 'Rendah') {
      pRendah.className = pRendah.className.replace('text-slate-600 hover:bg-slate-50', 'bg-[#1e293b] text-white');
    } else if (priority === 'Sedang') {
      pSedang.className = pSedang.className.replace('text-slate-600 hover:bg-slate-50', 'bg-[#1e293b] text-white');
    } else if (priority === 'Tinggi') {
      pTinggi.className = pTinggi.className.replace('text-slate-600 hover:bg-slate-50', 'bg-[#1e293b] text-white');
    }

    syncSidebarPreview();
  }

  // Live sync with right sidebar
  function syncSidebarPreview() {
    const judul = document.getElementById('judul_pengajuan').value.trim();
    const divisiSelect = document.getElementById('id_divisi');
    const divisiText = divisiSelect.options[divisiSelect.selectedIndex]?.text || '-';
    const periode = document.getElementById('periode_penggunaan').value;
    const prioritas = document.getElementById('prioritasInput').value;

    document.getElementById('previewJudul').innerText = judul || '-';
    document.getElementById('previewJudul').title = judul;
    document.getElementById('previewDivisi').innerText = (divisiSelect.value ? divisiText : '-');
    document.getElementById('previewPeriode').innerText = periode;
    
    const prioElem = document.getElementById('previewPrioritas');
    prioElem.innerText = prioritas;
    if (prioritas === 'Tinggi') {
      prioElem.className = 'font-medium capitalize text-rose-400';
    } else if (prioritas === 'Sedang') {
      prioElem.className = 'font-medium capitalize text-amber-400';
    } else {
      prioElem.className = 'font-medium capitalize text-indigo-300';
    }
  }

  // File upload preview
  function handleFileSelect(input) {
    if (input.files && input.files[0]) {
      const file = input.files[0];
      const sizeKB = Math.round(file.size / 1024);

      document.getElementById('fileNameDisplay').innerText = file.name;
      document.getElementById('fileSizeDisplay').innerText = `(${sizeKB} KB)`;
      document.getElementById('filePreview').classList.remove('hidden');
    }
  }

  function clearFile() {
    const fileInput = document.getElementById('dokumen');
    fileInput.value = '';
    document.getElementById('filePreview').classList.add('hidden');
  }

  // Initialize
  document.addEventListener('DOMContentLoaded', () => {
    syncSidebarPreview();
    calculateGrandTotal();
  });
</script>
@endpush
