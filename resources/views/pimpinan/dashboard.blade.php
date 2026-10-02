@extends('layouts.app')

@section('title', 'Dashboard Pimpinan - SIRAB Kelompok-3')

@section('content')
<div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Dashboard Pimpinan (Reviewer Final)</span>
</div>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
        <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
            Reviewer Final / Tahap 2 &bull; Role: Pimpinan
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Dashboard Pimpinan</h1>
        <p class="text-sm text-slate-500 mt-1">
            Persetujuan akhir pengajuan RAB yang telah diverifikasi kelayakannya oleh tim Finance (ACC Finance).
        </p>
    </div>
    <a href="{{ route('pimpinan.antrean') }}"
        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm transition-all shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Antrean Menunggu Keputusan ({{ $totalAntreanAccFinance ?? 0 }})
    </a>
</div>

<!-- Metric Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
    <!-- Card 1: Menunggu Keputusan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
        <div>
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Menunggu Keputusan</span>
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-stamp text-[15px]"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
                {{ number_format($totalAntreanAccFinance ?? 0) }}
            </div>
        </div>
        <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
            <span>Menunggu persetujuan Final</span>
        </div>
    </div>

    <!-- Card 2: Disetujui -->
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
            <span>Diteruskan ke pencairan</span>
        </div>
    </div>

    <!-- Card 3: Ditolak -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
        <div>
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Ditolak Pimpinan</span>
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-circle-xmark text-[15px]"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1">
                {{ number_format($totalDitolakPimpinan ?? 0) }}
            </div>
        </div>
        <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
            <span>Ditolak secara permanen</span>
        </div>
    </div>

    <!-- Card 4: Total Anggaran Disetujui -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 transition-all hover:shadow-sm hover:border-slate-300 h-full flex flex-col justify-between">
        <div>
            <div class="flex items-start justify-between gap-2 mb-2">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Anggaran Disetujui</span>
                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm shrink-0">
                    <i class="fa-solid fa-coins text-[15px]"></i>
                </div>
            </div>
            <div class="text-xl sm:text-2xl xl:text-[22px] 2xl:text-2xl font-bold font-mono tracking-tight text-slate-900 tabular-nums mt-1" title="Rp {{ number_format($totalAnggaranDisetujui ?? 0, 0, ',', '.') }}">
                Rp {{ number_format($totalAnggaranDisetujui ?? 0, 0, ',', '.') }}
            </div>
        </div>
        <div class="text-xs text-slate-400 mt-3 pt-3 border-t border-slate-100 flex items-center gap-1.5">
            <i class="fa-solid fa-check-double text-slate-400 text-[11px] shrink-0"></i>
            <span>Akumulasi anggaran disetujui</span>
        </div>
    </div>
</div>

<!-- Antrean Terbaru -->
<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-8">
    <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Antrean Persetujuan Final Terkini</h2>
            <p class="text-xs text-slate-500 mt-0.5">Berkas yang sudah lolos uji Finance dan memerlukan keputusan persetujuan Anda</p>
        </div>
        <a href="{{ route('pimpinan.antrean') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors shrink-0">
            <span>Buka Semua Antrean</span>
            <span aria-hidden="true">&rarr;</span>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] text-left text-xs border-collapse">
            <thead class="bg-slate-50/80 text-slate-500 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-200/80">
                <tr>
                    <th class="px-5 py-3.5 whitespace-nowrap">No. RAB</th>
                    <th class="px-5 py-3.5 whitespace-nowrap">Pemohon</th>
                    <th class="px-5 py-3.5 whitespace-nowrap">Unit Kerja</th>
                    <th class="px-5 py-3.5 whitespace-nowrap">Judul Pengajuan</th>
                    <th class="px-5 py-3.5 text-right whitespace-nowrap">Estimasi Anggaran</th>
                    <th class="px-5 py-3.5 text-center whitespace-nowrap">Keputusan Final</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($antreanTerbaru ?? [] as $item)
                <tr class="hover:bg-slate-50/70 transition-colors">
                    <td class="px-5 py-3.5 font-mono font-bold text-indigo-700 whitespace-nowrap">{{ $item->no_rab }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-900 whitespace-nowrap">{{ $item->pengguna->nama_lengkap ?? 'Staf' }}</td>
                    <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap">{{ $item->divisi->nama_divisi ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-slate-800 max-w-xs md:max-w-sm truncate" title="{{ $item->judul_pengajuan }}">{{ $item->judul_pengajuan }}</td>
                    <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-900 whitespace-nowrap tabular-nums">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5 text-center whitespace-nowrap">
                        <a href="{{ route('pimpinan.show', $item->id_pengajuan) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 shadow-2xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 active:scale-95 transition-all duration-150 shrink-0"
                           title="Tinjau dan berikan keputusan">
                            <i class="fa-solid fa-stamp text-[12px] shrink-0"></i>
                            <span>Review</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-8 text-center text-slate-400">
                        Tidak ada antrean yang menunggu review saat ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection