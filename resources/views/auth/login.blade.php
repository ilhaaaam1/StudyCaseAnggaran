@extends('layouts.app')

@section('title', 'Login - SIRAB Kelompok-3')

@section('main_class', 'flex items-center justify-center min-h-[85vh] px-4 sm:px-6 lg:px-8')

@section('content')
<div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
  <!-- Header Card -->
  <div class="text-center">
    <div class="mx-auto h-14 w-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center font-bold text-2xl shadow-md shadow-indigo-100">
      K3
    </div>
    <h2 class="mt-4 text-2xl font-bold text-slate-800 tracking-tight">
      Sistem Informasi RAB
    </h2>
    <p class="mt-1 text-xs text-slate-500">
      Masuk ke akun Anda untuk mengelola pengajuan dan persetujuan anggaran.
    </p>
  </div>

  <!-- Form Login -->
  <form class="mt-6 space-y-5" action="{{ route('login.post') }}" method="POST">
    @csrf

    <div>
      <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
        Alamat Email
      </label>
      <div class="relative">
        <input id="email" name="email" type="email" autocomplete="email" required
               value="{{ old('email', 'arif@sirab.local') }}"
               class="appearance-none block w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
               placeholder="nama@sirab.local">
      </div>
    </div>

    <div>
      <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">
        Kata Sandi
      </label>
      <div class="relative">
        <input id="password" name="password" type="password" autocomplete="current-password" required
               value="password"
               class="appearance-none block w-full px-3.5 py-2.5 border border-slate-300 rounded-lg text-slate-800 placeholder-slate-400 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
               placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;">
      </div>
    </div>

    <div class="flex items-center justify-between text-xs">
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
        <span class="text-slate-600">Ingat saya di perangkat ini</span>
      </label>
    </div>

    <div>
      <button type="submit"
              class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all cursor-pointer">
        Masuk ke Sistem
      </button>
    </div>
  </form>

  <!-- Demo Accounts Helper Box -->
  <div class="pt-4 border-t border-slate-100">
    <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider text-center mb-2.5">
      Akun Demo Pengujian Cepat
    </div>
    <div class="grid grid-cols-2 gap-2 text-xs">
      <button type="button" onclick="fillDemo('arif@sirab.local', 'password')"
              class="flex flex-col items-start p-2.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-200 transition-all text-left group cursor-pointer">
        <span class="font-bold text-slate-800 group-hover:text-indigo-700">Administrator</span>
        <span class="text-[10px] text-slate-500">arif@sirab.local</span>
      </button>

      <button type="button" onclick="fillDemo('sari@sirab.local', 'password')"
              class="flex flex-col items-start p-2.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-200 transition-all text-left group cursor-pointer">
        <span class="font-bold text-slate-800 group-hover:text-indigo-700">Staf Pemohon (User)</span>
        <span class="text-[10px] text-slate-500">sari@sirab.local</span>
      </button>
    </div>
    <div class="mt-2 text-center text-[10px] text-slate-400">
      Password bawaan untuk semua akun demo: <code class="font-mono bg-slate-100 px-1 py-0.5 rounded text-slate-700">password</code>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function fillDemo(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
  }
</script>
@endpush
@endsection
