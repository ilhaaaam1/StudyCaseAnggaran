@extends('layouts.app')

@section('title', 'Delegasi Wewenang - Pimpinan')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

  @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2 shadow-sm">
      <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
      </svg>
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium shadow-sm">
      <div class="flex items-center gap-2 mb-2">
        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        Terdapat kesalahan dalam pengisian form:
      </div>
      <ul class="list-disc pl-7 text-xs">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Top Header -->
  <div class="flex items-center justify-between">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Delegasi Wewenang</h1>
      <p class="text-sm text-slate-500 mt-1">Atur dan kelola pendelegasian tugas (misal kepada Wakasek) selama Anda berhalangan.</p>
    </div>
  </div>

  <!-- Form Tambah Delegasi -->
  <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div class="flex items-center gap-2 pb-4 border-b border-slate-100 mb-5">
      <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
      </div>
      <h2 class="text-base font-bold text-slate-800">Tambah Delegasi Baru</h2>
    </div>

    <form action="{{ route('pimpinan.delegasi.store') }}" method="POST">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        
        <!-- Pegawai Pengganti -->
        <div class="md:col-span-1 space-y-4">
          <div>
            <label for="delegate_to_user_id" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Pegawai Pengganti <span class="text-rose-500">*</span></label>
            <select id="delegate_to_user_id" name="delegate_to_user_id" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
              <option value="" disabled selected>-- Pilih User/Pegawai --</option>
              @foreach($users as $user)
                <option value="{{ $user->id_pengguna }}" {{ old('delegate_to_user_id') == $user->id_pengguna ? 'selected' : '' }}>
                  {{ $user->nama_lengkap }} ({{ $user->jabatan ?? $user->role }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label for="start_date" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Mulai <span class="text-rose-500">*</span></label>
              <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            </div>
            <div>
              <label for="end_date" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Berakhir <span class="text-rose-500">*</span></label>
              <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
            </div>
          </div>
        </div>

        <!-- Alasan -->
        <div class="md:col-span-2 flex flex-col">
          <label for="reason" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">Alasan Delegasi</label>
          <textarea id="reason" name="reason" rows="3" class="w-full flex-1 px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none" placeholder="Masukkan alasan pendelegasian (misal: Cuti Tahunan, Dinas Luar Kota, dll)...">{{ old('reason') }}</textarea>
        </div>
      </div>

      <div class="mt-5 flex justify-end">
        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-md shadow-indigo-100 transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
          Simpan Delegasi
        </button>
      </div>
    </form>
  </div>

  <!-- Riwayat Delegasi -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
      <h2 class="text-base font-bold text-slate-800">Riwayat Delegasi Wewenang</h2>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-sm">
        <thead>
          <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
            <th class="py-3 px-6 w-12 text-center">No</th>
            <th class="py-3 px-4">Nama Penerima Delegasi</th>
            <th class="py-3 px-4">Periode</th>
            <th class="py-3 px-4">Alasan</th>
            <th class="py-3 px-4 w-32 text-center">Status</th>
            <th class="py-3 px-6 w-32 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-700">
          @forelse($delegations as $idx => $delegasi)
            <tr class="hover:bg-slate-50/60 transition-colors">
              <td class="py-3 px-6 text-center text-slate-400 font-mono text-xs">{{ $idx + 1 }}</td>
              <td class="py-3 px-4">
                <div class="font-bold text-slate-800">{{ $delegasi->delegateTo->nama_lengkap ?? 'Unknown' }}</div>
                <div class="text-[11px] text-slate-500">{{ $delegasi->delegateTo->jabatan ?? '-' }}</div>
              </td>
              <td class="py-3 px-4">
                <div class="font-medium text-slate-700">
                  {{ $delegasi->start_date->format('d M Y') }} - {{ $delegasi->end_date->format('d M Y') }}
                </div>
                @if($delegasi->status === 'Aktif' && now()->isBetween($delegasi->start_date, $delegasi->end_date->endOfDay()))
                  <div class="text-[10px] text-emerald-600 font-bold mt-0.5">Sedang Berlangsung</div>
                @endif
              </td>
              <td class="py-3 px-4 text-xs">
                {{ $delegasi->reason ?? '-' }}
              </td>
              <td class="py-3 px-4 text-center">
                @if($delegasi->status === 'Aktif')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">Aktif</span>
                @elseif($delegasi->status === 'Dibatalkan')
                  <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">Dibatalkan</span>
                @else
                  <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200">Selesai</span>
                @endif
              </td>
              <td class="py-3 px-6 text-center">
                <div class="flex items-center justify-center gap-2">
                  @if($delegasi->status === 'Aktif')
                    <form action="{{ route('pimpinan.delegasi.cancel', $delegasi->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan delegasi ini?');">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Batalkan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                      </button>
                    </form>
                  @endif
                  
                  <form action="{{ route('pimpinan.delegasi.destroy', $delegasi->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus riwayat ini secara permanen?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Riwayat">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="py-8 text-center text-slate-400 text-sm">
                Belum ada riwayat delegasi wewenang yang ditambahkan.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
