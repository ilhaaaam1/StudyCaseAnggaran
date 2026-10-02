@extends('layouts.app')

@section('title', 'Dashboard Staf Pemohon - SIRAB Kelompok-3')

@section('content')
<!-- Breadcrumb -->
<div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Dashboard Staf Pemohon</span>
</div>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
        <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
            Portal Pemohon RAB &bull; Role: Staff
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Dashboard Staf Pemohon</h1>
        <p class="text-sm text-slate-500 mt-1">
            Selamat datang, <span class="font-semibold text-slate-700">{{ Auth::user()->nama_lengkap }}</span> ({{ Auth::user()->jabatan ?? 'Staf' }} &bull; {{ Auth::user()->divisi->nama_divisi ?? 'Unit Kerja' }}).
        </p>
    </div>
    {{-- PRESENTASI: Penyembunyian Tombol dan Banner Peringatan (Frontend) --}}
    {{-- Mengecek jika mode pemeliharaan tidak aktif, tampilkan tombol. Jika aktif, tampilkan banner peringatan kuning. --}}
    @if(\App\Models\Setting::getSetting('maintenance_mode', '0') != '1')
    <a href="{{ route('staff.rab.create') }}"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm transition-all shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Buat Pengajuan RAB
    </a>
    @else
    <div class="bg-amber-100 text-amber-800 border border-amber-300 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm shrink-0" title="Sistem sedang dalam masa pemeliharaan, pembuatan RAB baru ditutup sementara.">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Pembuatan RAB ditutup (Maintenance)
    </div>
    @endif
</div>

<!-- Metric Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 sm:gap-5 mb-8">
    <!-- Card 1: Total Diajukan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
        <div>
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Diajukan</span>
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-calculator text-[15px]"></i>
                </div>
            </div>
            <div class="text-xl sm:text-2xl xl:text-[20px] 2xl:text-2xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1" title="Rp {{ number_format($totalAnggaranDiajukan ?? 0, 0, ',', '.') }}">
                Rp {{ number_format($totalAnggaranDiajukan ?? 0, 0, ',', '.') }}
            </div>
        </div>
        <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
            <i class="fa-regular fa-file-lines text-slate-400 text-[11px] shrink-0"></i>
            <span>{{ number_format($totalPengajuan ?? 0) }} berkas diajukan</span>
        </div>
    </div>

    <!-- Card 2: Menunggu Review -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
        <div>
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Menunggu Review</span>
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-hourglass-half text-[15px]"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
                {{ number_format(($totalPending ?? 0) + ($totalAccFinance ?? 0)) }}
            </div>
        </div>
        <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
            <span>Finance &amp; Pimpinan</span>
        </div>
    </div>

    <!-- Card 3: Disetujui (ACC Final) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
        <div>
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Disetujui (ACC Final)</span>
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-circle-check text-[15px]"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
                {{ number_format($totalAccFinal ?? 0) }}
            </div>
        </div>
        <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
            <span>Anggaran disetujui penuh</span>
        </div>
    </div>

    <!-- Card 4: Perlu Revisi -->
    <div class="bg-white rounded-2xl border {{ ($totalRevisi ?? 0) > 0 ? 'border-amber-300 ring-1 ring-amber-200/60' : 'border-slate-200/80' }} shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
        <div>
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Perlu Revisi</span>
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-[15px]"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight {{ ($totalRevisi ?? 0) > 0 ? 'text-amber-700' : 'text-slate-900' }} tabular-nums mt-1">
                {{ number_format($totalRevisi ?? 0) }}
            </div>
        </div>
        <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0 {{ ($totalRevisi ?? 0) > 0 ? 'animate-pulse' : '' }}"></span>
            <span>Perlu perbaikan berkas</span>
        </div>
    </div>

    <!-- Card 5: Ditolak Permanen -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
        <div>
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Ditolak Permanen</span>
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-circle-xmark text-[15px]"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
                {{ number_format($totalDitolak ?? 0) }}
            </div>
        </div>
        <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
            <span>Tidak dapat direvisi</span>
        </div>
    </div>
</div>

{{-- PRESENTASI: Mengubah card redundan menjadi Alokasi Kategori Anggaran --}}
<div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 sm:p-6 mb-8">
    <h2 class="font-semibold text-slate-800 text-sm mb-4 flex items-center justify-between">
        <span>Alokasi Kategori Anggaran</span>
        <span class="text-xs font-normal text-slate-400">Total: {{ $totalPengajuan ?? 0 }} Dokumen</span>
    </h2>
    
    {{-- PRESENTASI: Struktur loop Kategori Anggaran menggunakan flexbox dan dinamis dari controller --}}
    <div class="flex flex-col text-sm">
        @forelse($alokasiKategori as $item)
            @php
                // Warna ikon bergantian secara otomatis berdasarkan indeks
                $colors = [
                    'bg-blue-100 text-blue-700', 
                    'bg-emerald-100 text-emerald-700', 
                    'bg-amber-100 text-amber-700', 
                    'bg-purple-100 text-purple-700',
                    'bg-rose-100 text-rose-700'
                ];
                $colorClass = $colors[$loop->index % count($colors)];
            @endphp
            <div class="flex justify-between items-center py-3 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg {{ $colorClass }}">
                        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776" />
                        </svg>
                    </div>
                    <span class="font-medium text-gray-700">{{ $item->kategori_anggaran ?: 'Tanpa Kategori' }}</span>
                </div>
                <div class="text-right">
                    <div class="font-bold text-gray-900">Rp {{ number_format($item->total_rupiah, 0, ',', '.') }}</div>
                    <div class="text-[11px] text-gray-500 font-medium">{{ $item->jumlah_dokumen }} Pengajuan</div>
                </div>
            </div>
        @empty
            <div class="py-4 text-center text-slate-500 text-sm">Belum ada pengajuan untuk ditampilkan.</div>
        @endforelse
    </div>
</div>

<!-- Pengajuan Terbaru Table -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-8">
    <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Pengajuan Terbaru Anda</h2>
            <p class="text-xs text-slate-500 mt-0.5">Daftar pengajuan terkini yang diajukan oleh akun Anda</p>
        </div>
        <a href="{{ route('staff.riwayat') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors shrink-0">
            <span>Lihat Semua</span>
            <span aria-hidden="true">&rarr;</span>
        </a>
    </div>

    <!-- Container Responsif dengan Scroll Horizontal -->
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] text-left border-collapse text-xs">
            <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-200/80">
                <tr>
                    <th scope="col" class="px-4 py-3.5 sm:px-5 sm:py-3.5 whitespace-nowrap">No. RAB</th>
                    <th scope="col" class="px-4 py-3.5 sm:px-5 sm:py-3.5">Judul Pengajuan</th>
                    <th scope="col" class="px-4 py-3.5 sm:px-5 sm:py-3.5 text-right whitespace-nowrap">Estimasi Total</th>
                    <th scope="col" class="px-4 py-3.5 sm:px-5 sm:py-3.5 text-center whitespace-nowrap">Status Alur</th>
                    <th scope="col" class="px-4 py-3.5 sm:px-5 sm:py-3.5 text-center whitespace-nowrap">Tanggal</th>
                    <th scope="col" class="px-4 py-3.5 sm:px-5 sm:py-3.5 text-center whitespace-nowrap">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pengajuanTerbaru ?? [] as $item)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <!-- No. RAB -->
                    <td class="px-4 py-3.5 sm:px-5 sm:py-4 align-middle whitespace-nowrap">
                        <span class="font-mono font-bold text-indigo-700 text-xs">{{ $item->no_rab }}</span>
                        @if($item->divisi)
                            <div class="text-[11px] text-slate-400 mt-0.5 truncate max-w-[140px]">
                                {{ $item->divisi->nama_divisi }}
                            </div>
                        @endif
                    </td>

                    <!-- Judul Pengajuan -->
                    <td class="px-4 py-3.5 sm:px-5 sm:py-4 align-middle max-w-xs md:max-w-md">
                        <div class="font-semibold text-slate-900 text-xs truncate" title="{{ $item->judul_pengajuan }}">
                            {{ $item->judul_pengajuan }}
                        </div>
                        @if($item->kategori_anggaran)
                            <div class="text-[11px] text-slate-400 mt-0.5 truncate" title="{{ $item->kategori_anggaran }}">
                                {{ $item->kategori_anggaran }}
                            </div>
                        @endif
                    </td>

                    <!-- Estimasi Total (Rata Kanan & Tabular Nums) -->
                    <td class="px-4 py-3.5 sm:px-5 sm:py-4 align-middle text-right whitespace-nowrap font-mono font-semibold text-slate-800 tabular-nums">
                        Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
                    </td>

                    <!-- Status Alur (Badge Kapsul) -->
                    <td class="px-4 py-3.5 sm:px-5 sm:py-4 align-middle text-center whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold shrink-0 whitespace-nowrap {{ $item->status->badge() }}">
                            {{ $item->status->label() }}
                        </span>
                    </td>

                    <!-- Tanggal Pengajuan -->
                    <td class="px-4 py-3.5 sm:px-5 sm:py-4 align-middle text-center whitespace-nowrap text-slate-500 font-mono text-xs">
                        {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y') : '-' }}
                    </td>

                    <!-- Tombol Aksi -->
                    <td class="px-4 py-3.5 sm:px-5 sm:py-4 align-middle text-center whitespace-nowrap">
                        <div class="inline-flex items-center justify-center gap-1.5 shrink-0">
                            <!-- Detail & Track -->
                            <a href="{{ route('staff.rab.show', $item->id_pengajuan) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 border border-slate-200/80 hover:border-indigo-200 shadow-2xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 active:scale-95 transition-all duration-150 shrink-0"
                               title="Lihat Detail & Lacak Progres">
                                <i class="fa-regular fa-eye text-[12px] shrink-0"></i>
                                <span>Detail</span>
                            </a>

                            <!-- Edit (Jika Status Revisi atau Draft) -->
                            @if(in_array($item->status, [\App\Enums\StatusPengajuan::REVISI, \App\Enums\StatusPengajuan::DRAFT]))
                                <a href="{{ route('staff.rab.edit', $item->id_pengajuan) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 shadow-2xs focus:outline-none focus:ring-2 focus:ring-amber-500/20 active:scale-95 transition-all duration-150 shrink-0"
                                   title="Edit / Perbaiki Pengajuan">
                                    <i class="fa-solid fa-pen-to-square text-[11px] shrink-0"></i>
                                    <span>Edit</span>
                                </a>
                            @endif

                            <!-- Hapus (Khusus Status Draft) -->
                            @if($item->status === \App\Enums\StatusPengajuan::DRAFT)
                                <form action="{{ route('staff.rab.destroy', $item->id_pengajuan) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus draft pengajuan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-7 h-7 rounded-lg inline-flex items-center justify-center text-rose-600 hover:text-rose-700 hover:bg-rose-50 border border-rose-200 transition-colors shrink-0"
                                            title="Hapus Draft">
                                        <i class="fa-regular fa-trash-can text-[11px]"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-2xl flex items-center justify-center mx-auto text-xl mb-3 shadow-xs">
                            <i class="fa-solid fa-file-circle-plus"></i>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Belum Ada Pengajuan RAB</p>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                            Anda belum membuat berkas pengajuan anggaran. Klik tombol di bawah untuk membuat RAB baru.
                        </p>
                        @if(\App\Models\Setting::getSetting('maintenance_mode', '0') != '1')
                            <div class="mt-4">
                                <a href="{{ route('staff.rab.create') }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 shadow-xs transition-colors">
                                    <i class="fa-solid fa-plus text-[11px]"></i>
                                    Buat Pengajuan Baru
                                </a>
                            </div>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection