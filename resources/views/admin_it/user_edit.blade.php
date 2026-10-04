@extends('layouts.app')

@section('title', 'Edit Akun Pengguna - Administrator IT')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('admin-it.users.index') }}" class="hover:text-slate-800">Manajemen Pengguna</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Edit: {{ $user->nama_lengkap }}</span>
  </div>

  <div class="max-w-2xl mx-auto bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
    <div class="mb-6 border-b border-slate-100 pb-4">
      <h1 class="text-xl font-bold text-slate-900">Edit Akun Pengguna</h1>
      <p class="text-xs text-slate-500 mt-1">Perbarui profil, divisi, role hak akses, atau reset password akun <strong class="text-slate-700">{{ $user->nama_lengkap }}</strong>.</p>
    </div>

    @php 
      $tu = collect($divisi ?? [])->first(function($d) {
          return str_contains($d->nama_divisi, 'Tata Usaha');
      }); 
      $tuId = $tu ? $tu->id_divisi : ''; 
    @endphp

    <form x-data="{ 
            role: '{{ old('role', $user->role) }}', 
            divisi: '{{ old('id_divisi', $user->id_divisi) }}',
            tuId: '{{ $tuId }}' 
          }" 
          x-init="$watch('role', value => { 
            if(['admin_it', 'admin', 'finance', 'pimpinan'].includes(value) && tuId) { 
                divisi = tuId; 
            } 
          })"
          action="{{ route('admin-it.users.update', $user->id_pengguna) }}" method="POST" class="space-y-4">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-sm focus:border-indigo-500">
        @error('nama_lengkap') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Email (Login) <span class="text-rose-500">*</span></label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-sm font-mono focus:border-indigo-500">
          @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Password Baru (Opsional)</label>
          <input type="password" name="password" minlength="6" placeholder="Kosongkan jika tidak diubah" class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-sm focus:border-indigo-500">
          @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Role Hak Akses <span class="text-rose-500">*</span></label>
          <select name="role" x-model="role" required class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-sm focus:border-indigo-500">
            @foreach($roles ?? [] as $roleKey => $roleLabel)
              <option value="{{ $roleKey }}">{{ $roleLabel }}</option>
            @endforeach
          </select>
          @error('role') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1">Unit Kerja / Divisi <span class="text-rose-500">*</span></label>
          <select name="id_divisi" x-model="divisi" required class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-sm focus:border-indigo-500"
                  :class="['admin_it', 'admin', 'finance', 'pimpinan'].includes(role) ? 'bg-slate-50 cursor-not-allowed' : ''"
                  x-bind:disabled="['admin_it', 'admin', 'finance', 'pimpinan'].includes(role)">
            <option value="">-- Pilih Divisi --</option>
            @foreach($divisi ?? [] as $d)
              <option value="{{ $d->id_divisi }}">{{ $d->nama_divisi }}</option>
            @endforeach
          </select>
          <input type="hidden" name="id_divisi" x-bind:value="divisi" :disabled="!['admin_it', 'admin', 'finance', 'pimpinan'].includes(role)">
          @error('id_divisi') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1">Jabatan</label>
        <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" class="w-full px-3.5 py-2 border border-slate-300 rounded-xl text-sm focus:border-indigo-500">
      </div>

      <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
        <a href="{{ route('admin-it.users.index') }}" class="px-4 py-2 border border-slate-300 rounded-xl text-xs font-semibold text-slate-600">Batal</a>
        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold">Simpan Perubahan</button>
      </div>
    </form>
  </div>
@endsection
