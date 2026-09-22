@extends('layouts.app')

@section('title', 'Riwayat Pengajuan RAB Saya - SIRAB SDN Sidokare 3')

@section('content')
  <!-- Breadcrumb Nav -->
  <div class="text-xs text-slate-400 mb-5 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('staff.dashboard') }}" class="hover:text-slate-700 transition-colors">Dashboard Staf</a>
    <span>/</span>
    <span class="text-slate-700 font-medium">Riwayat Pengajuan</span>
  </div>

  <!-- Page Header (Ringkas & Bersih) -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
    <div>
      <div class="text-[11px] font-semibold text-indigo-600 uppercase tracking-wider mb-0.5">
        SDN Sidokare 3 &bull; Manajemen Anggaran
      </div>
      <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Riwayat Pengajuan RAB</h1>
      <p class="text-xs text-slate-400 mt-0.5">
        Pantau status verifikasi Bendahara BOS, persetujuan Kepala Sekolah, dan pencairan dana.
      </p>
    </div>
    <a href="{{ route('staff.rab.create') }}"
       class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 shadow-xs transition-colors shrink-0">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
      </svg>
      Buat Pengajuan Baru
    </a>
  </div>

  <!-- 1. TOP METRIC CARDS (Pangkas Keterangan, Ikon Kecil & Angka Jelas) -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 mb-6">
    <!-- Card 1: Total Pengajuan -->
    <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-xs flex items-center justify-between">
      <div>
        <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">TOTAL PENGAJUAN</span>
        <div class="text-2xl font-bold text-slate-900 font-mono mt-1">
          {{ number_format($metrics['total_pengajuan'] ?? 0) }}
        </div>
      </div>
      <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-sm shrink-0">
        <i class="fa-regular fa-folder-open"></i>
      </div>
    </div>

    <!-- Card 2: Sedang Diproses -->
    <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-xs flex items-center justify-between">
      <div>
        <span class="text-[11px] font-semibold text-blue-600 uppercase tracking-wider block">SEDANG DIPROSES</span>
        <div class="text-2xl font-bold text-slate-900 font-mono mt-1">
          {{ number_format($metrics['total_diproses'] ?? 0) }}
        </div>
      </div>
      <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
        <i class="fa-solid fa-hourglass-half"></i>
      </div>
    </div>

    <!-- Card 3: Perlu Revisi -->
    <div class="bg-white border {{ ($metrics['total_revisi'] ?? 0) > 0 ? 'border-amber-300 ring-1 ring-amber-200' : 'border-slate-200/80' }} rounded-xl p-4 shadow-xs flex items-center justify-between">
      <div>
        <span class="text-[11px] font-semibold text-amber-700 uppercase tracking-wider block">PERLU REVISI</span>
        <div class="text-2xl font-bold {{ ($metrics['total_revisi'] ?? 0) > 0 ? 'text-amber-700' : 'text-slate-900' }} font-mono mt-1">
          {{ number_format($metrics['total_revisi'] ?? 0) }}
        </div>
      </div>
      <div class="w-9 h-9 rounded-lg {{ ($metrics['total_revisi'] ?? 0) > 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }} flex items-center justify-center text-sm shrink-0">
        <i class="fa-solid fa-triangle-exclamation"></i>
      </div>
    </div>

    <!-- Card 4: Disetujui / Cair -->
    <div class="bg-white border border-slate-200/80 rounded-xl p-4 shadow-xs flex items-center justify-between">
      <div>
        <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider block">DANA DISETUJUI / CAIR</span>
        <div class="text-lg sm:text-xl font-bold text-slate-900 font-mono mt-1 truncate max-w-[150px]" title="Rp {{ number_format($metrics['total_disetujui'] ?? 0, 0, ',', '.') }}">
          Rp {{ number_format($metrics['total_disetujui'] ?? 0, 0, ',', '.') }}
        </div>
      </div>
      <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shrink-0">
        <i class="fa-solid fa-check-double"></i>
      </div>
    </div>
  </div>

  <!-- 2. CONTAINER UTAMA: TABS, FILTER, DAN TABEL -->
  <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-8">
    
    <!-- TAB FILTER STATUS (Ringkas & Bersih) -->
    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50/50">
      <div class="flex flex-wrap items-center gap-1.5">
        {{-- Tab: Semua --}}
        @php
          $isSemua = empty($statusFilter) || $statusFilter === 'semua';
        @endphp
        <a href="{{ route('staff.riwayat', array_merge(request()->except(['status', 'page']), ['status' => 'semua'])) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $isSemua ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
          Semua
        </a>

        {{-- Tab: Menunggu Verifikasi Finance (Bendahara BOS) --}}
        @php
          $isFinance = $statusFilter === \App\Enums\StatusPengajuan::MENUNGGU_FINANCE->value;
        @endphp
        <a href="{{ route('staff.riwayat', array_merge(request()->except(['status', 'page']), ['status' => \App\Enums\StatusPengajuan::MENUNGGU_FINANCE->value])) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors flex items-center gap-1.5 {{ $isFinance ? 'bg-blue-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-blue-50/60 hover:text-blue-700' }}">
          <span class="w-1.5 h-1.5 rounded-full {{ $isFinance ? 'bg-white' : 'bg-blue-500' }}"></span>
          <span>Verifikasi Bendahara</span>
        </a>

        {{-- Tab: Menunggu Persetujuan Pimpinan (Kepala Sekolah) --}}
        @php
          $isPimpinan = $statusFilter === \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN->value;
        @endphp
        <a href="{{ route('staff.riwayat', array_merge(request()->except(['status', 'page']), ['status' => \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN->value])) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors flex items-center gap-1.5 {{ $isPimpinan ? 'bg-purple-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-purple-50/60 hover:text-purple-700' }}">
          <span class="w-1.5 h-1.5 rounded-full {{ $isPimpinan ? 'bg-white' : 'bg-purple-500' }}"></span>
          <span>Persetujuan Kepsek</span>
        </a>

        {{-- Tab: Perlu Perbaikan (Revisi) --}}
        @php
          $isRevisi = $statusFilter === \App\Enums\StatusPengajuan::REVISI->value;
          $countRevisi = $metrics['total_revisi'] ?? 0;
        @endphp
        <a href="{{ route('staff.riwayat', array_merge(request()->except(['status', 'page']), ['status' => \App\Enums\StatusPengajuan::REVISI->value])) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors flex items-center gap-1.5 {{ $isRevisi ? 'bg-amber-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-amber-50/60 hover:text-amber-800' }}">
          <span class="w-1.5 h-1.5 rounded-full {{ $isRevisi ? 'bg-white' : 'bg-amber-500' }}"></span>
          <span>Perlu Revisi</span>
          @if($countRevisi > 0)
            <span class="ml-0.5 px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $isRevisi ? 'bg-white text-amber-700' : 'bg-rose-500 text-white' }}">
              {{ $countRevisi }}
            </span>
          @endif
        </a>

        {{-- Tab: Pencairan & Selesai --}}
        @php
          $isCairSelesai = in_array($statusFilter, ['pencairan_selesai', 'cair', \App\Enums\StatusPengajuan::PROSES_PENCAIRAN->value, \App\Enums\StatusPengajuan::SELESAI->value]);
        @endphp
        <a href="{{ route('staff.riwayat', array_merge(request()->except(['status', 'page']), ['status' => 'pencairan_selesai'])) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors flex items-center gap-1.5 {{ $isCairSelesai ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-emerald-50/60 hover:text-emerald-700' }}">
          <span class="w-1.5 h-1.5 rounded-full {{ $isCairSelesai ? 'bg-white' : 'bg-emerald-500' }}"></span>
          <span>Pencairan &amp; Selesai</span>
        </a>

        {{-- Tab: Ditolak --}}
        @php
          $isDitolak = $statusFilter === \App\Enums\StatusPengajuan::DITOLAK->value;
        @endphp
        <a href="{{ route('staff.riwayat', array_merge(request()->except(['status', 'page']), ['status' => \App\Enums\StatusPengajuan::DITOLAK->value])) }}"
           class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors flex items-center gap-1.5 {{ $isDitolak ? 'bg-rose-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-600 hover:bg-rose-50/60 hover:text-rose-700' }}">
          <span class="w-1.5 h-1.5 rounded-full {{ $isDitolak ? 'bg-white' : 'bg-rose-500' }}"></span>
          <span>Ditolak</span>
        </a>
      </div>
    </div>

    <!-- FILTER BAR KONTEKSTUAL SEKOLAH (Rapi & Minimalis) -->
    <div class="px-4 py-3.5 border-b border-slate-100 bg-white">
      <form method="GET" action="{{ route('staff.riwayat') }}" class="space-y-3">
        @if(!empty($statusFilter))
          <input type="hidden" name="status" value="{{ $statusFilter }}">
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
          <!-- Input Search -->
          <div>
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Cari No. RAB / Judul</label>
            <div class="relative">
              <input type="text"
                     name="q"
                     value="{{ $search ?? '' }}"
                     placeholder="Contoh: RAB-2026 atau Modul..."
                     class="w-full pl-8 pr-3 py-1.5 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
              <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
            </div>
          </div>

          <!-- Dropdown Tahun Ajaran & Semester -->
          <div>
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Tahun Ajaran &amp; Semester</label>
            <select name="tahun_ajaran_semester"
                    class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs text-slate-800 bg-white focus:border-indigo-500">
              <option value="">Semua Semester</option>
              <option value="2026/2027 - Semester Ganjil" {{ ($tahunAjaranSemester ?? '') === '2026/2027 - Semester Ganjil' ? 'selected' : '' }}>
                2026/2027 - Semester Ganjil
              </option>
              <option value="2026/2027 - Semester Genap" {{ ($tahunAjaranSemester ?? '') === '2026/2027 - Semester Genap' ? 'selected' : '' }}>
                2026/2027 - Semester Genap
              </option>
              <option value="2025/2026 - Semester Genap" {{ ($tahunAjaranSemester ?? '') === '2025/2026 - Semester Genap' ? 'selected' : '' }}>
                2025/2026 - Semester Genap
              </option>
              <option value="2025/2026 - Semester Ganjil" {{ ($tahunAjaranSemester ?? '') === '2025/2026 - Semester Ganjil' ? 'selected' : '' }}>
                2025/2026 - Semester Ganjil
              </option>
            </select>
          </div>

          <!-- Dropdown Tahap Penyaluran BOS -->
          <div>
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Tahap Penyaluran BOS</label>
            <select name="tahap_bos"
                    class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs text-slate-800 bg-white focus:border-indigo-500">
              <option value="">Semua Tahap BOS</option>
              <option value="BOS Reguler Tahap 1 (Januari – Juni)" {{ ($tahapBos ?? '') === 'BOS Reguler Tahap 1 (Januari – Juni)' ? 'selected' : '' }}>
                BOS Reguler Tahap 1 (Jan – Jun)
              </option>
              <option value="BOS Reguler Tahap 2 (Juli – Desember)" {{ ($tahapBos ?? '') === 'BOS Reguler Tahap 2 (Juli – Desember)' ? 'selected' : '' }}>
                BOS Reguler Tahap 2 (Jul – Des)
              </option>
            </select>
          </div>

          <!-- Dropdown Pos Kategori Anggaran -->
          <div>
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Pos Kategori Anggaran</label>
            <select name="kategori_anggaran"
                    class="w-full px-2.5 py-1.5 border border-slate-200 rounded-lg text-xs text-slate-800 bg-white focus:border-indigo-500 truncate">
              <option value="">Semua Pos Kategori</option>
              @php
                $listKategori = [
                  'Belanja Barang Operasional & ATK',
                  'Kegiatan Kesiswaan & Lomba',
                  'Pemeliharaan Sarana & Prasarana',
                  'Pengembangan Perpustakaan & Literasi',
                  'Peningkatan Kompetensi Guru (SDM)',
                  'Langganan Daya & Jasa',
                  'Belanja Modal / Alat Elektronik',
                ];
              @endphp
              @foreach($listKategori as $kat)
                <option value="{{ $kat }}" {{ ($kategoriAnggaran ?? '') === $kat ? 'selected' : '' }}>
                  {{ $kat }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Tombol Filter & Reset -->
        <div class="flex items-center justify-between pt-0.5">
          <div class="text-[11px] text-slate-400">
            @if(!empty($search) || !empty($tahunAjaranSemester) || !empty($tahapBos) || !empty($kategoriAnggaran))
              <span class="inline-flex items-center gap-1 text-indigo-600 font-medium bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100/70">
                <i class="fa-solid fa-filter text-[10px]"></i> Filter aktif
              </span>
            @endif
          </div>
          <div class="flex items-center gap-2">
            @if(!empty($search) || !empty($tahunAjaranSemester) || !empty($tahapBos) || !empty($kategoriAnggaran) || !empty($statusFilter))
              <a href="{{ route('staff.riwayat') }}"
                 class="px-2.5 py-1.5 text-xs text-slate-500 hover:text-slate-800 font-medium hover:underline inline-flex items-center gap-1">
                <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset
              </a>
            @endif
            <button type="submit"
                    class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold inline-flex items-center gap-1.5 shadow-xs transition-colors">
              <i class="fa-solid fa-magnifying-glass text-[10px]"></i> Terapkan
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- 3. TABEL DATA RINGKAS, LAPANG & MUDAH DIPINDAI -->
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50/70 text-slate-400 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-200/80">
          <tr>
            <th class="px-5 py-3.5">No. RAB</th>
            <th class="px-5 py-3.5">Kegiatan &amp; Unit</th>
            <th class="px-5 py-3.5">Kategori Anggaran</th>
            <th class="px-5 py-3.5">Jadwal &amp; Tahap</th>
            <th class="px-5 py-3.5 text-right">Estimasi Biaya</th>
            <th class="px-5 py-3.5 text-center">Status</th>
            <th class="px-4 py-3.5 text-center whitespace-nowrap">Lihat Detail</th>
            <th class="px-4 py-3.5 text-center whitespace-nowrap">Aksi CRUD</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($pengajuanList ?? [] as $rab)
            <tr class="hover:bg-slate-50/60 transition-colors {{ $rab->status === \App\Enums\StatusPengajuan::REVISI ? 'bg-amber-50/20' : '' }}">
              
              <!-- 1. No. RAB & Tanggal Pengajuan -->
              <td class="px-5 py-3.5 align-middle whitespace-nowrap">
                <div class="font-mono font-bold text-slate-800 text-xs tracking-tight">
                  {{ $rab->no_rab }}
                </div>
                <div class="text-[11px] text-slate-400 mt-0.5">
                  {{ $rab->tanggal_pengajuan ? $rab->tanggal_pengajuan->format('d M Y') : '-' }}
                </div>
              </td>

              <!-- 2. Judul Kegiatan & Unit Kerja (line-clamp-1 / truncate + tooltip) -->
              <td class="px-5 py-3.5 align-middle max-w-xs md:max-w-sm">
                <div class="font-semibold text-slate-800 text-xs truncate" title="{{ $rab->judul_pengajuan }}">
                  {{ $rab->judul_pengajuan }}
                </div>
                <div class="flex items-center gap-1.5 mt-0.5 text-[11px] text-slate-400">
                  <span class="inline-flex items-center px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 font-medium">
                    {{ $rab->divisi->nama_divisi ?? 'Unit Sekolah' }}
                  </span>
                </div>
              </td>

              <!-- 3. Kategori Anggaran (Pos BOS / RKAS) -->
              <td class="px-5 py-3.5 align-middle">
                @if($rab->kategori_anggaran)
                  <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-indigo-50/80 text-indigo-700 border border-indigo-100/80 max-w-[190px] truncate" title="{{ $rab->kategori_anggaran }}">
                    {{ $rab->kategori_anggaran }}
                  </span>
                @else
                  <span class="text-slate-400 text-xs">-</span>
                @endif
              </td>

              <!-- 4. Jadwal & Tahap (Format ringkas: contoh 12–15 Okt 2026 + mini badge Tahap 1) -->
              <td class="px-5 py-3.5 align-middle whitespace-nowrap">
                <div class="text-xs font-medium text-slate-700">
                  {{ $rab->rentang_tanggal_ringkas }}
                </div>
                <div class="mt-0.5 flex items-center gap-1.5 text-[11px] text-slate-400">
                  <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-medium bg-slate-100 text-slate-600">
                    {{ $rab->tahap_bos_ringkas }}
                  </span>
                  @if($rab->durasi_hari)
                    <span>({{ $rab->durasi_hari }}h)</span>
                  @endif
                </div>
              </td>

              <!-- 5. Estimasi Biaya (Nominal utama + 📦 jumlah item) -->
              <td class="px-5 py-3.5 align-middle text-right whitespace-nowrap">
                <div class="font-mono font-semibold text-slate-900 text-xs">
                  Rp {{ number_format((float) $rab->estimasi_total, 0, ',', '.') }}
                </div>
                <div class="text-[11px] text-slate-400 mt-0.5 flex items-center justify-end gap-1">
                  <i class="fa-solid fa-box text-[9px] text-slate-300"></i>
                  <span>{{ $rab->rincianItem->count() }} item</span>
                </div>
              </td>

              <!-- 6. Status Badge (Kapsul Ringkas dengan Dot Warna) -->
              <td class="px-5 py-3.5 align-middle text-center whitespace-nowrap">
                @if($rab->status === \App\Enums\StatusPengajuan::MENUNGGU_FINANCE)
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-200/70">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    Verifikasi Bendahara
                  </span>
                @elseif($rab->status === \App\Enums\StatusPengajuan::MENUNGGU_PIMPINAN)
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-purple-50 text-purple-700 border border-purple-200/70">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                    Persetujuan Kepsek
                  </span>
                @elseif($rab->status === \App\Enums\StatusPengajuan::REVISI)
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    Perlu Revisi
                  </span>
                @elseif($rab->status === \App\Enums\StatusPengajuan::PROSES_PENCAIRAN)
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Pencairan
                  </span>
                @elseif($rab->status === \App\Enums\StatusPengajuan::SELESAI)
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                    Selesai
                  </span>
                @elseif($rab->status === \App\Enums\StatusPengajuan::DITOLAK)
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Ditolak
                  </span>
                @elseif($rab->status === \App\Enums\StatusPengajuan::DRAFT)
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    Draft
                  </span>
                @else
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    {{ $rab->status }}
                  </span>
                @endif
              </td>

              <!-- 7. Lihat Detail Pengajuan -->
              <td class="px-4 py-3.5 align-middle text-center whitespace-nowrap">
                <a href="{{ route('staff.rab.show', $rab->id_pengajuan) }}"
                   class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium text-indigo-700 bg-indigo-50/80 hover:bg-indigo-100 border border-indigo-200/70 transition-colors"
                   title="Lihat Detail & Lacak Alur Pengajuan">
                  <i class="fa-regular fa-eye text-[11px]"></i>
                  <span>Detail</span>
                </a>
              </td>

              <!-- 8. Aksi CRUD (Edit / Hapus jika diizinkan) -->
              <td class="px-4 py-3.5 align-middle text-center whitespace-nowrap">
                @if(in_array($rab->status, [\App\Enums\StatusPengajuan::REVISI, \App\Enums\StatusPengajuan::DRAFT]))
                  <div class="flex items-center justify-center gap-1.5">
                    <!-- Tombol Edit (Update) -->
                    <a href="{{ route('staff.rab.edit', $rab->id_pengajuan) }}"
                       class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition-colors"
                       title="Perbaiki / Edit Pengajuan">
                      <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                      <span>Edit</span>
                    </a>

                    <!-- Tombol Hapus (Delete) - Khusus status Draft -->
                    @if($rab->status === \App\Enums\StatusPengajuan::DRAFT)
                      <form action="{{ route('staff.rab.destroy', $rab->id_pengajuan) }}"
                            method="POST"
                            class="inline"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus draft pengajuan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-7 h-7 rounded-lg inline-flex items-center justify-center text-rose-600 hover:text-rose-700 hover:bg-rose-50 border border-rose-200 transition-colors"
                                title="Hapus Draft Pengajuan">
                          <i class="fa-regular fa-trash-can text-[11px]"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                @else
                  <span class="text-slate-300 text-xs select-none" title="Terkunci dalam proses alur persetujuan">&ndash;</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-5 py-12 text-center">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto text-slate-400 text-lg mb-2.5">
                  <i class="fa-regular fa-folder-open"></i>
                </div>
                <p class="text-xs font-semibold text-slate-700">Tidak ada pengajuan yang cocok.</p>
                <p class="text-[11px] text-slate-400 mt-0.5">
                  Coba sesuaikan kata kunci atau reset filter untuk melihat seluruh berkas.
                </p>
                @if(!empty($search) || !empty($tahunAjaranSemester) || !empty($tahapBos) || !empty($kategoriAnggaran) || !empty($statusFilter))
                  <div class="mt-3">
                    <a href="{{ route('staff.riwayat') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-medium inline-flex items-center gap-1 transition-colors">
                      <i class="fa-solid fa-rotate-left text-[10px]"></i> Reset Filter
                    </a>
                  </div>
                @endif
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination (Ringkas) -->
    @if(method_exists($pengajuanList, 'hasPages') && $pengajuanList->hasPages())
      <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/40 flex items-center justify-between">
        <div class="text-[11px] text-slate-400">
          Menampilkan <span class="font-semibold text-slate-700">{{ $pengajuanList->firstItem() ?? 0 }}</span>–<span class="font-semibold text-slate-700">{{ $pengajuanList->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-slate-700">{{ $pengajuanList->total() }}</span> berkas
        </div>
        <div>
          {{ $pengajuanList->links() }}
        </div>
      </div>
    @endif

  </div>
@endsection
