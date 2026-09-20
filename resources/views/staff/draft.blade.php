@extends('layouts.app')

@section('title', 'Draft Pengajuan RAB - SIRAB Kelompok-3')

@section('content')
<div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <span class="text-slate-800 font-medium">Draft Pengajuan RAB</span>
</div>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
        <div class="text-xs font-semibold text-amber-600 uppercase tracking-wider mb-1">
            Penyimpanan Sementara
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Draft Pengajuan</h1>
        <p class="text-sm text-slate-500 mt-1">
            Daftar pengajuan RAB yang belum selesai Anda buat dan belum dikirimkan ke tim Finance.
        </p>
    </div>
    <a href="{{ route('staff.rab.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs sm:text-sm font-semibold flex items-center gap-2 shadow-sm transition-all shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Buat Pengajuan Baru
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <h2 class="text-lg font-bold text-slate-900">Daftar Draft Anda</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
                <tr>
                    <th class="px-5 py-3">No. Urut</th>
                    <th class="px-5 py-3">Judul Pengajuan</th>
                    <th class="px-5 py-3">Unit Kerja</th>
                    <th class="px-5 py-3 text-right">Estimasi Sementara</th>
                    <th class="px-5 py-3 text-center">Tanggal Dibuat</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($draftList as $index => $item)
                <tr class="hover:bg-slate-50">
                    <td class="px-5 py-3.5 font-mono font-semibold text-slate-500">{{ $draftList->firstItem() + $index }}</td>
                    <td class="px-5 py-3.5 text-slate-900 font-medium">{{ $item->judul_pengajuan ?: 'Draft Tanpa Judul' }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $item->divisi->nama_divisi ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-right font-mono font-semibold text-slate-800">Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}</td>
                    <td class="px-5 py-3.5 text-center text-slate-500 font-mono">{{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y H:i') : '-' }}</td>
                    <td class="px-5 py-3.5 text-center flex items-center justify-center gap-2">
                        <a href="{{ route('staff.rab.edit', $item->id_pengajuan) }}" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 rounded-lg text-xs font-semibold transition-colors">
                            Lanjutkan Edit
                        </a>
                        <form action="{{ route('staff.rab.destroy', $item->id_pengajuan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus draft ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-semibold transition-colors">
                                Hapus Draft
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="font-medium text-slate-500">Tidak ada draft tersimpan.</p>
                            <p class="text-xs mt-1">Pengajuan yang belum selesai akan muncul di sini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($draftList->hasPages())
    <div class="p-4 border-t border-slate-100">
        {{ $draftList->links() }}
    </div>
    @endif
</div>
@endsection