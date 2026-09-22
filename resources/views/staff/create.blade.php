@extends('layouts.app')

@section('title', 'Buat Pengajuan RAB Sekolah - SIRAB SDN Sidokare 3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('staff.dashboard') }}" class="hover:text-slate-800">Dashboard Staf</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Buat Pengajuan Anggaran Sekolah</span>
  </div>

  <div class="max-w-5xl mx-auto mb-10">
    <!-- Header Banner -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 mb-2">
          <i class="fa-solid fa-school text-[11px]"></i> Manajemen Anggaran Sekolah
        </div>
        <h1 class="text-2xl font-bold text-slate-900 mt-0.5">Formulir Rencana Anggaran Biaya (RAB)</h1>
        <p class="text-xs text-slate-500 mt-1">Lengkapi data pokok kegiatan sekolah, rentang jadwal pelaksanaan, dan rincian belanja anggaran.</p>
      </div>
      <div class="sm:text-right shrink-0">
        <span class="text-[10px] text-slate-400 font-semibold block uppercase">Nomor Registrasi RAB</span>
        <span class="font-mono text-base font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-200 inline-block mt-0.5">
          {{ $autoNoRab ?? 'RAB-'.date('Y').'-AUTO' }}
        </span>
      </div>
    </div>

    @if ($errors->any())
      <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
        <div class="font-bold flex items-center gap-2 mb-1 text-sm">
          <i class="fa-solid fa-triangle-exclamation"></i> Terdapat beberapa kesalahan input:
        </div>
        <ul class="list-disc list-inside space-y-0.5 pl-1">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('staff.rab.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="formRab">
      @csrf

      <!-- CARD 1: INFORMASI KEGIATAN & UNIT KERJA -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs">1</span>
            Data Pokok & Unit Kerja Pengaju
          </h2>
          <span class="text-[11px] text-slate-400">Tahap Awal Pengajuan</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <!-- Judul Pengajuan Kegiatan -->
          <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
              Judul Pengajuan Kegiatan <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="judul_pengajuan" value="{{ old('judul_pengajuan') }}" required
                   placeholder="Contoh: Pengadaan Modul Literasi ANBK dan Alat Peraga Kelas 5"
                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
            <p class="text-[11px] text-slate-400 mt-1">Buat nama kegiatan yang spesifik dan jelas sesuai sasaran kegiatan sekolah.</p>
            @error('judul_pengajuan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Unit Kerja Sekolah (Divisi) -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
              Unit Kerja Sekolah (Bidang) <span class="text-rose-500">*</span>
            </label>
            <select name="id_divisi" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 text-slate-800 bg-white">
              <option value="">-- Pilih Unit Kerja Sekolah --</option>
              @foreach($divisiList ?? [] as $d)
                <option value="{{ $d->id_divisi }}" {{ old('id_divisi', Auth::user()->id_divisi) == $d->id_divisi ? 'selected' : '' }}>
                  {{ $d->nama_divisi }}
                </option>
              @endforeach
            </select>
            <p class="text-[11px] text-slate-400 mt-1">Pilih bidang penanggung jawab operasional kegiatan.</p>
            @error('id_divisi') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Kategori Pos Anggaran BOS / RKAS -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
              Kategori Pos Anggaran (Acuan BOS & RKAS) <span class="text-rose-500">*</span>
            </label>
            <select name="kategori_anggaran" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 text-slate-800 bg-white">
              <option value="" disabled selected>-- Pilih Kategori Pos Anggaran --</option>
              @php
                $posAnggaran = [
                  'Belanja Barang Operasional & ATK' => 'Kertas HVS, spidol, tinta printer, map rapor, perlengkapan kelas & kantor',
                  'Kegiatan Kesiswaan & Lomba' => 'Pramuka, tari, drum band, PHBN/PHBI, lomba O2SN/FLS2N, konsumsi & transport',
                  'Pemeliharaan Sarana & Prasarana' => 'Perbaikan ruang kelas, sanitasi/toilet, meja-kursi, pengecatan, listrik & air',
                  'Pengembangan Perpustakaan & Literasi' => 'Pengadaan buku ajar/bacaan, inventarisasi literasi, sarana perpustakaan',
                  'Peningkatan Kompetensi Guru (SDM)' => 'Pelatihan guru, workshop kurikulum merdeka, KKG, seminar kompetensi',
                  'Langganan Daya & Jasa' => 'Tagihan listrik PLN, internet/WiFi sekolah, air bersih PDAM, jasa kebersihan',
                  'Belanja Modal / Alat Elektronik' => 'Proyektor LCD, laptop ANBK, sound system, komputer dan peralatan TIK',
                ];
              @endphp
              @foreach($posAnggaran as $kat => $deskripsi)
                <option value="{{ $kat }}" {{ old('kategori_anggaran') === $kat ? 'selected' : '' }}>
                  {{ $kat }}
                </option>
              @endforeach
            </select>
            <p class="text-[11px] text-slate-400 mt-1">Klasifikasi belanja mengacu pada pos alokasi BOS & RAPBS.</p>
            @error('kategori_anggaran') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>
      </div>

      <!-- CARD 2: PERIODE PENGGUNAAN & RENTANG WAKTU PELAKSANAAN -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5" x-data="{
        tglMulai: '{{ old('tanggal_mulai', date('Y-m-d')) }}',
        tglSelesai: '{{ old('tanggal_selesai', date('Y-m-d', strtotime('+3 days'))) }}',
        get durasiHari() {
          if (!this.tglMulai || !this.tglSelesai) return 0;
          let start = new Date(this.tglMulai);
          let end = new Date(this.tglSelesai);
          let diffTime = end - start;
          let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
          return diffDays > 0 ? diffDays : 0;
        },
        get isInvalidRange() {
          if (!this.tglMulai || !this.tglSelesai) return false;
          return new Date(this.tglSelesai) < new Date(this.tglMulai);
        }
      }">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center text-xs">2</span>
            Periode Penggunaan & Rentang Waktu Pelaksanaan
          </h2>
          <span class="text-[11px] text-emerald-600 font-semibold bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
            Jadwal SPJ & Pencairan
          </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <!-- Tahun Ajaran & Semester -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
              Tahun Ajaran & Semester <span class="text-rose-500">*</span>
            </label>
            <select name="tahun_ajaran_semester" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 text-slate-800 bg-white">
              <option value="" disabled selected>-- Pilih Tahun Ajaran & Semester --</option>
              <option value="2026/2027 - Semester Ganjil" {{ old('tahun_ajaran_semester', '2026/2027 - Semester Ganjil') === '2026/2027 - Semester Ganjil' ? 'selected' : '' }}>
                2026/2027 - Semester Ganjil
              </option>
              <option value="2026/2027 - Semester Genap" {{ old('tahun_ajaran_semester') === '2026/2027 - Semester Genap' ? 'selected' : '' }}>
                2026/2027 - Semester Genap
              </option>
              <option value="2025/2026 - Semester Genap" {{ old('tahun_ajaran_semester') === '2025/2026 - Semester Genap' ? 'selected' : '' }}>
                2025/2026 - Semester Genap
              </option>
            </select>
            @error('tahun_ajaran_semester') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
          </div>

          <!-- Tahap Penyaluran BOS -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
              Tahap Penyaluran Dana BOS <span class="text-rose-500">*</span>
            </label>
            <select name="tahap_bos" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 text-slate-800 bg-white">
              <option value="" disabled selected>-- Pilih Tahap Penyaluran BOS --</option>
              <option value="BOS Reguler Tahap 1 (Januari – Juni)" {{ old('tahap_bos', 'BOS Reguler Tahap 1 (Januari – Juni)') === 'BOS Reguler Tahap 1 (Januari – Juni)' ? 'selected' : '' }}>
                BOS Reguler Tahap 1 (Januari – Juni)
              </option>
              <option value="BOS Reguler Tahap 2 (Juli – Desember)" {{ old('tahap_bos') === 'BOS Reguler Tahap 2 (Juli – Desember)' ? 'selected' : '' }}>
                BOS Reguler Tahap 2 (Juli – Desember)
              </option>
            </select>
            @error('tahap_bos') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
          </div>
        </div>

        <!-- RENTANG WAKTU PENGGUNAAN (DATEPICKER) -->
        <div class="p-4 bg-slate-50/80 rounded-xl border border-slate-200/80 space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
              <i class="fa-regular fa-calendar-days text-indigo-600"></i> Rentang Waktu Pelaksanaan / Penggunaan Anggaran
            </span>
            <!-- Live Duration Indicator Badge -->
            <span x-show="!isInvalidRange && durasiHari > 0" 
                  class="text-[11px] font-semibold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-200">
              <i class="fa-solid fa-clock text-[10px] mr-1"></i> <span x-text="durasiHari"></span> Hari Kegiatan
            </span>
            <span x-show="isInvalidRange" 
                  class="text-[11px] font-semibold text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded-full border border-rose-200">
              <i class="fa-solid fa-circle-exclamation text-[10px] mr-1"></i> Tanggal tidak valid
            </span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">
                Tanggal Mulai Kegiatan <span class="text-rose-500">*</span>
              </label>
              <input type="date" 
                     name="tanggal_mulai" 
                     x-model="tglMulai"
                     required
                     class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 bg-white">
              @error('tanggal_mulai') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1">
                Tanggal Selesai Kegiatan <span class="text-rose-500">*</span>
              </label>
              <input type="date" 
                     name="tanggal_selesai" 
                     x-model="tglSelesai"
                     :min="tglMulai"
                     required
                     class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500 bg-white">
              @error('tanggal_selesai') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
          </div>

          <p class="text-[11px] text-slate-500 italic">
            * Rentang tanggal digunakan sebagai acuan pencairan dana oleh Bendahara dan batas waktu SPJ pelaksanaan.
          </p>
        </div>

        <!-- Latar Belakang & Urgensi -->
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">
            Latar Belakang & Urgensi Kegiatan <span class="text-rose-500">*</span>
          </label>
          <textarea name="latar_belakang" rows="3" required
                    placeholder="Uraikan justifikasi kebutuhan kegiatan, target peserta siswa/guru, serta urgensi alokasi anggaran ini..."
                    class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-sm focus:border-indigo-500">{{ old('latar_belakang') }}</textarea>
          @error('latar_belakang') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <!-- CARD 3: RINCIAN ITEM BELANJA ANGGARAN (TABEL DINAMIS) -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <div>
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
              <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">3</span>
              Rincian Item Belanja & Anggaran
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Masukkan daftar komoditas/jasa belanja beserta volume dan estimasi harga satuan wajar.</p>
          </div>
          <button type="button" onclick="tambahBarisItem()"
                  class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3.5 py-2 rounded-xl border border-indigo-200 transition-colors cursor-pointer flex items-center gap-1.5 shadow-2xs">
            <i class="fa-solid fa-plus text-[10px]"></i> Tambah Baris Item
          </button>
        </div>

        <!-- Datalist Satuan Standar Sekolah -->
        <datalist id="satuanList">
          <option value="Rim"></option>
          <option value="Pak"></option>
          <option value="Dus"></option>
          <option value="Kotak"></option>
          <option value="Buah"></option>
          <option value="Unit"></option>
          <option value="Set"></option>
          <option value="Lembar"></option>
          <option value="Meter"></option>
          <option value="Liter"></option>
          <option value="Paket"></option>
          <option value="Kegiatan"></option>
          <option value="Orang/Hari"></option>
          <option value="Bulan"></option>
        </datalist>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse" id="tabelItem">
            <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-200">
              <tr>
                <th class="px-3 py-2.5 w-10 text-center">#</th>
                <th class="px-3 py-2.5">Nama Barang / Deskripsi Belanja</th>
                <th class="px-3 py-2.5 w-32">Satuan</th>
                <th class="px-3 py-2.5 w-24">Volume</th>
                <th class="px-3 py-2.5 w-40">Harga Satuan (Rp)</th>
                <th class="px-3 py-2.5 w-44 text-right">Subtotal</th>
                <th class="px-3 py-2.5 w-12 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody id="bodyItem" class="divide-y divide-slate-100">
              <tr class="item-row">
                <td class="px-3 py-2.5 text-center text-slate-400 font-mono row-index">1</td>
                <td class="px-3 py-2.5">
                  <input type="text" name="items[0][uraian_barang]" required placeholder="Contoh: Kertas HVS F4 70gr Sinar Dunia"
                         class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs focus:border-indigo-500">
                </td>
                <td class="px-3 py-2.5">
                  <input type="text" name="items[0][satuan]" list="satuanList" required placeholder="Rim / Unit"
                         class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs focus:border-indigo-500">
                </td>
                <td class="px-3 py-2.5">
                  <input type="number" name="items[0][volume]" step="any" min="0.01" value="1" required oninput="hitungSubtotal(this)"
                         class="input-volume w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs font-mono text-center focus:border-indigo-500">
                </td>
                <td class="px-3 py-2.5">
                  <input type="number" name="items[0][harga_satuan]" step="any" min="0" required oninput="hitungSubtotal(this)"
                         placeholder="0" class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs font-mono text-right focus:border-indigo-500">
                </td>
                <td class="px-3 py-2.5 text-right font-mono font-semibold text-slate-800 subtotal-text">
                  Rp 0
                </td>
                <td class="px-3 py-2.5 text-center">
                  <button type="button" onclick="hapusBaris(this)" class="text-rose-400 hover:text-rose-600 font-bold p-1 transition-colors cursor-pointer" title="Hapus Baris">&times;</button>
                </td>
              </tr>
            </tbody>
            <tfoot class="bg-slate-50 font-bold border-t border-slate-200">
              <tr>
                <td colspan="5" class="px-4 py-3.5 text-right text-slate-700 uppercase tracking-wider text-xs">
                  Total Estimasi Anggaran Pengajuan:
                </td>
                <td id="grandTotalText" class="px-3 py-3.5 text-right font-mono text-base text-indigo-700">
                  Rp 0
                </td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>

      <!-- CARD 4: DOKUMEN PENDUKUNG & FINALISASI -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
          <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-slate-600 text-white flex items-center justify-center text-xs">4</span>
            Lampiran Dokumen & Finalisasi
          </h2>
          <span class="text-[11px] text-slate-400">Opsional (Maks. 5 MB)</span>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">
            Lampirkan Berkas Pendukung (TOR, Rincian Vendor, Brosur atau Proposal)
          </label>
          <input type="file" name="dokumen_pendukung" accept=".pdf,.jpg,.jpeg,.png"
                 class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
          <p class="text-[11px] text-slate-400 mt-1">Format file yang diperbolehkan: PDF, JPG, JPEG, atau PNG (Maksimal 5 MB).</p>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
          <a href="{{ route('staff.dashboard') }}" class="px-4 py-2.5 border border-slate-300 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
            Batal
          </a>
          <button type="submit" name="action" value="draft" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-colors cursor-pointer">
            <i class="fa-regular fa-bookmark mr-1"></i> Simpan Draft
          </button>
          <button type="submit" name="action" value="send" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold transition-colors shadow-sm cursor-pointer">
            <i class="fa-solid fa-paper-plane mr-1.5"></i> Kirim Pengajuan RAB
          </button>
        </div>
      </div>
    </form>
  </div>

  <script>
    let barisIndex = 1;

    function tambahBarisItem() {
      const tbody = document.getElementById('bodyItem');
      const tr = document.createElement('tr');
      tr.className = 'item-row';
      tr.innerHTML = `
        <td class="px-3 py-2.5 text-center text-slate-400 font-mono row-index">${tbody.children.length + 1}</td>
        <td class="px-3 py-2.5">
          <input type="text" name="items[${barisIndex}][uraian_barang]" required placeholder="Nama item / spek"
                 class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs focus:border-indigo-500">
        </td>
        <td class="px-3 py-2.5">
          <input type="text" name="items[${barisIndex}][satuan]" list="satuanList" required placeholder="Rim / Unit"
                 class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs focus:border-indigo-500">
        </td>
        <td class="px-3 py-2.5">
          <input type="number" name="items[${barisIndex}][volume]" step="any" min="0.01" value="1" required oninput="hitungSubtotal(this)"
                 class="input-volume w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs font-mono text-center focus:border-indigo-500">
        </td>
        <td class="px-3 py-2.5">
          <input type="number" name="items[${barisIndex}][harga_satuan]" step="any" min="0" required oninput="hitungSubtotal(this)"
                 placeholder="0" class="w-full px-2.5 py-1.5 border border-slate-300 rounded-lg text-xs font-mono text-right focus:border-indigo-500">
        </td>
        <td class="px-3 py-2.5 text-right font-mono font-semibold text-slate-800 subtotal-text">
          Rp 0
        </td>
        <td class="px-3 py-2.5 text-center">
          <button type="button" onclick="hapusBaris(this)" class="text-rose-400 hover:text-rose-600 font-bold p-1 transition-colors cursor-pointer">&times;</button>
        </td>
      `;
      tbody.appendChild(tr);
      barisIndex++;
      updateRowNumbers();
    }

    function hapusBaris(btn) {
      const tbody = document.getElementById('bodyItem');
      if (tbody.children.length <= 1) {
        alert('Minimal harus ada 1 baris rincian item belanja.');
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
  </script>
@endsection
