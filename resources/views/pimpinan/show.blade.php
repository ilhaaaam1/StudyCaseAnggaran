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

    <!-- Form Keputusan Final Pimpinan -->
    @if($pengajuan->status === 'ACC Finance')
      <div class="bg-white p-6 rounded-2xl border border-indigo-200 shadow-sm bg-indigo-50/20">
        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-2">Formulir Keputusan Final Pimpinan</h2>
        <p class="text-xs text-slate-500 mb-4">
          Bila disetujui, status akan menjadi <strong>ACC Final</strong> dan dana anggaran dapat direalisasikan. Bila ditolak, status menjadi <strong>Ditolak Pimpinan</strong>.
        </p>

        <form action="{{ route('pimpinan.approve', $pengajuan->id_pengajuan) }}" method="POST" class="space-y-4">
          @csrf
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Pimpinan / Disposisi</label>
            <textarea name="catatan" rows="3" placeholder="Masukkan arahan realisasi atau alasan penolakan..."
                      class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-xs focus:border-indigo-500"></textarea>
          </div>

          <div class="flex items-center justify-end gap-3 pt-2">
            <button type="submit" name="status" value="Ditolak"
                    onclick="return confirm('Apakah Anda yakin ingin MENOLAK pengajuan RAB ini pada tahap final?');"
                    class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold shadow-sm">
              Tolak Pengajuan
            </button>
            <button type="submit" name="status" value="ACC"
                    onclick="return confirm('Apakah Anda yakin ingin memberikan persetujuan akhir (ACC Final)?');"
                    class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-sm">
              Setujui (ACC Final)
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
@endsection
