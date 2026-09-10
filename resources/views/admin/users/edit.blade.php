@extends('layouts.app')

@section('title', 'Edit Data Akun Staff - SIRAB Kelompok-3')

@section('content')
  <!-- Breadcrumb -->
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-800">Panel Administrator</a>
    <span>/</span>
    <a href="{{ route('admin.users.index') }}" class="hover:text-slate-800">Manajemen Staff</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Edit Staff: {{ $user->nama_lengkap }}</span>
  </div>

  <div class="max-w-3xl mx-auto">
    <!-- Header Card -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-6">
      <div>
        <div class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-1">
          Formulir Pembaruan Akun
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Edit Data Akun Staff</h1>
        <p class="text-sm text-slate-500 mt-1">
          Perbarui identitas pengguna, divisi penugasan, atau reset kata sandi akun <strong class="text-slate-700">{{ $user->nama_lengkap }}</strong>.
        </p>
      </div>
      <a href="{{ route('admin.users.index') }}" 
         class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors shadow-sm">
        &larr; Kembali ke Daftar Staff
      </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm">
      <form action="{{ route('admin.users.update', $user->id_pengguna) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Nama Lengkap -->
        <div>
          <label for="nama_lengkap" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
            Nama Lengkap Staf <span class="text-rose-500">*</span>
          </label>
          <input type="text" 
                 name="nama_lengkap" 
                 id="nama_lengkap" 
                 value="{{ old('nama_lengkap', $user->nama_lengkap) }}" 
                 required
                 placeholder="Contoh: Muhammad Fikri, S.Kom."
                 class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('nama_lengkap') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} text-sm text-slate-800 bg-white transition-colors">
          @error('nama_lengkap')
            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
              <span>⚠</span> {{ $message }}
            </p>
          @enderror
        </div>

        <!-- Email & Jabatan Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <!-- Email -->
          <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
              Alamat Email (Login) <span class="text-rose-500">*</span>
            </label>
            <input type="email" 
                   name="email" 
                   id="email" 
                   value="{{ old('email', $user->email) }}" 
                   required
                   placeholder="staf@sirab.local"
                   class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} text-sm text-slate-800 bg-white transition-colors font-mono">
            @error('email')
              <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                <span>⚠</span> {{ $message }}
              </p>
            @enderror
          </div>

          <!-- Jabatan -->
          <div>
            <label for="jabatan" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
              Jabatan / Posisi Kerja
            </label>
            <input type="text" 
                   name="jabatan" 
                   id="jabatan" 
                   value="{{ old('jabatan', $user->jabatan) }}" 
                   placeholder="Contoh: Staf IT / Analis Anggaran"
                   class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('jabatan') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} text-sm text-slate-800 bg-white transition-colors">
            <span class="text-[10px] text-slate-400 mt-1 block">Opsional. Jika kosong, akan otomatis diset sesuai divisi.</span>
            @error('jabatan')
              <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                <span>⚠</span> {{ $message }}
              </p>
            @enderror
          </div>
        </div>

        <!-- Divisi Dropdown & Password Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <!-- Divisi -->
          <div>
            <label for="id_divisi" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
              Unit Kerja / Divisi <span class="text-rose-500">*</span>
            </label>
            <select name="id_divisi" 
                    id="id_divisi" 
                    required
                    class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('id_divisi') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} text-sm text-slate-800 bg-white cursor-pointer transition-colors">
              <option value="">-- Pilih Unit Kerja / Divisi --</option>
              @foreach($divisi as $d)
                <option value="{{ $d->id_divisi }}" {{ (string) old('id_divisi', $user->id_divisi) === (string) $d->id_divisi ? 'selected' : '' }}>
                  {{ $d->nama_divisi }}
                </option>
              @endforeach
            </select>
            @error('id_divisi')
              <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                <span>⚠</span> {{ $message }}
              </p>
            @enderror
          </div>

          <!-- Password (Opsional) -->
          <div>
            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
              Kata Sandi Baru <span class="text-slate-400 font-normal normal-case">(Opsional)</span>
            </label>
            <input type="password" 
                   name="password" 
                   id="password" 
                   minlength="6"
                   placeholder="Kosongkan jika tidak ingin mengubah kata sandi"
                   class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-rose-300 focus:border-rose-500 focus:ring-rose-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }} text-sm text-slate-800 bg-white transition-colors">
            <span class="text-[10px] text-slate-400 mt-1 block">Kosongkan jika tidak ingin mengubah kata sandi (minimal 6 karakter bila diisi).</span>
            @error('password')
              <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                <span>⚠</span> {{ $message }}
              </p>
            @enderror
          </div>
        </div>

        <!-- Role Badge Info -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
          <div class="flex items-center gap-2">
            <span class="text-indigo-600 font-bold">🔒 Hak Akses Akun:</span>
            <span class="text-slate-600">Terdaftar sebagai</span>
            <span class="font-bold text-blue-700 bg-blue-100 px-2.5 py-0.5 rounded-full border border-blue-200">Staff / Pemohon RAB (user)</span>
          </div>
          <span class="text-[10px] text-slate-400 font-mono">ID Pengguna: #{{ $user->id_pengguna }}</span>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
          <a href="{{ route('admin.users.index') }}" 
             class="px-5 py-2.5 rounded-xl border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition-colors">
            Batal
          </a>
          <button type="submit" 
                  class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-md shadow-indigo-100 transition-all cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection
