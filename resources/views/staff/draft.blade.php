@extends('layouts.app')

@section('title', 'Draft Pengajuan RAB Saya - SIRAB SDN Sidokare 3')

@section('content')
  <!-- Breadcrumb Nav -->
  <div class="text-xs text-slate-400 mb-5 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('staff.dashboard') }}" class="hover:text-slate-700 transition-colors">Dashboard Staf</a>
    <span>/</span>
    <span class="text-slate-700 font-medium">Draft Pengajuan</span>
  </div>

  <!-- Page Header (Ringkas & Bersih) -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
    <div>
      <div class="text-[11px] font-semibold text-amber-600 uppercase tracking-wider mb-0.5">
        SDN Sidokare 3 &bull; Penyimpanan Sementara
      </div>
      <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Draft Pengajuan RAB</h1>
      <p class="text-xs text-slate-400 mt-0.5">
        Daftar berkas RAB yang belum selesai dibuat dan belum dikirimkan untuk proses verifikasi.
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

  <!-- Container Tabel Draft -->
  <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden mb-8">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
      <div>
        <h2 class="text-sm font-bold text-slate-900">Daftar Draft Tersimpan</h2>
        <p class="text-[11px] text-slate-400 mt-0.5">Lanjutkan pengisian rincian barang/jasa atau hapus berkas yang tidak digunakan.</p>
      </div>
      <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200/80 rounded-lg text-xs font-semibold inline-flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
        <span>{{ $draftList->total() }} Berkas</span>
      </span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50/70 text-slate-400 uppercase font-semibold text-[11px] tracking-wider border-b border-slate-200/80">
          <tr>
            <th class="px-5 py-3.5">No. Urut</th>
            <th class="px-5 py-3.5">Judul Pengajuan</th>
            <th class="px-5 py-3.5">Unit Kerja</th>
            <th class="px-5 py-3.5 text-right">Estimasi Sementara</th>
            <th class="px-5 py-3.5 text-center">Tanggal Dibuat</th>
            <th class="px-4 py-3.5 text-center whitespace-nowrap">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($draftList as $index => $item)
            <tr class="hover:bg-slate-50/60 transition-colors">
              <td class="px-5 py-3.5 align-middle font-mono font-semibold text-slate-500">
                {{ $draftList->firstItem() + $index }}
              </td>
              <td class="px-5 py-3.5 align-middle">
                <div class="font-semibold text-slate-900 text-xs truncate max-w-xs md:max-w-sm" title="{{ $item->judul_pengajuan ?: 'Draft Tanpa Judul' }}">
                  {{ $item->judul_pengajuan ?: 'Draft Tanpa Judul' }}
                </div>
                @if($item->no_rab)
                  <div class="font-mono text-[11px] text-slate-400 mt-0.5">
                    {{ $item->no_rab }}
                  </div>
                @endif
              </td>
              <td class="px-5 py-3.5 align-middle">
                <span class="inline-flex items-center px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium text-[11px]">
                  {{ $item->divisi->nama_divisi ?? '-' }}
                </span>
              </td>
              <td class="px-5 py-3.5 align-middle text-right font-mono font-semibold text-slate-800 whitespace-nowrap">
                Rp {{ number_format((float) $item->estimasi_total, 0, ',', '.') }}
              </td>
              <td class="px-5 py-3.5 align-middle text-center text-slate-500 font-mono text-[11px] whitespace-nowrap">
                {{ $item->tanggal_pengajuan ? $item->tanggal_pengajuan->format('d/m/Y H:i') : '-' }}
              </td>
              <td class="px-4 py-3.5 align-middle text-center whitespace-nowrap">
                <div class="flex items-center justify-center gap-1.5">
                  <!-- Tombol Detail (Tema Indigo Riwayat) -->
                  <a href="{{ route('staff.rab.show', $item->id_pengajuan) }}"
                     class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium text-indigo-700 bg-indigo-50/80 hover:bg-indigo-100 border border-indigo-200/70 transition-colors"
                     title="Lihat Detail Draft Pengajuan">
                    <i class="fa-regular fa-eye text-[11px]"></i>
                    <span>Detail</span>
                  </a>

                  <!-- Tombol Edit (Tema Amber Riwayat) -->
                  <a href="{{ route('staff.rab.edit', $item->id_pengajuan) }}"
                     class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition-colors"
                     title="Lanjutkan / Edit Pengajuan">
                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                    <span>Edit</span>
                  </a>

                  <!-- Tombol Hapus (Tema Rose Riwayat) -->
                  <form action="{{ route('staff.rab.destroy', $item->id_pengajuan) }}"
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
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-12 text-center">
                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center mx-auto text-slate-400 text-lg mb-2.5">
                  <i class="fa-regular fa-folder-open"></i>
                </div>
                <p class="text-xs font-semibold text-slate-700">Tidak ada draft tersimpan.</p>
                <p class="text-[11px] text-slate-400 mt-0.5">
                  Pengajuan yang belum selesai akan muncul di sini.
                </p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if(method_exists($draftList, 'hasPages') && $draftList->hasPages())
      <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/40 flex items-center justify-between">
        <div class="text-[11px] text-slate-400">
          Menampilkan <span class="font-semibold text-slate-700">{{ $draftList->firstItem() ?? 0 }}</span>–<span class="font-semibold text-slate-700">{{ $draftList->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-slate-700">{{ $draftList->total() }}</span> draft
        </div>
        <div>
          {{ $draftList->links() }}
        </div>
      </div>
    @endif
  </div>
@endsection