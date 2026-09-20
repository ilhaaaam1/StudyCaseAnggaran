@extends('layouts.app')

@section('title', 'Pengaturan Sistem - Admin IT')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

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

  <!-- Header -->
  <div>
    <h1 class="text-2xl font-bold text-slate-900">Pengaturan Sistem Global</h1>
    <p class="text-sm text-slate-500 mt-1">Konfigurasi variabel utama aplikasi, logo instansi, dan mode sistem.</p>
  </div>

  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <form action="{{ route('admin-it.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      
      <div class="p-6 md:p-8 space-y-8">
        
        <!-- Identitas Sekolah -->
        <div>
          <h2 class="text-base font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Identitas Instansi</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <label for="app_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Nama Instansi / Sekolah <span class="text-rose-500">*</span></label>
              <input type="text" id="app_name" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="Contoh: SD Negeri Sidokare 3">
            </div>

            <div>
              <label for="academic_year" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Tahun Ajaran / Periode <span class="text-rose-500">*</span></label>
              <select id="academic_year" name="academic_year" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @php
                    $currentYear = date('Y');
                    $options = [
                        ($currentYear - 1) . '/' . $currentYear,
                        $currentYear . '/' . ($currentYear + 1),
                        ($currentYear + 1) . '/' . ($currentYear + 2),
                    ];
                    $selectedYear = old('academic_year', $settings['academic_year']);
                    if (!in_array($selectedYear, $options)) {
                        $options[] = $selectedYear;
                        sort($options);
                    }
                @endphp
                @foreach($options as $opt)
                    <option value="{{ $opt }}" {{ $selectedYear === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
              </select>
            </div>

            <div>
              <label for="admin_email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Email Administrator <span class="text-rose-500">*</span></label>
              <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email', $settings['admin_email']) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-800 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" placeholder="admin@sekolah.sch.id">
            </div>
          </div>
        </div>

        <!-- Logo Instansi -->
        <div>
          <h2 class="text-base font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Logo Instansi</h2>
          <div class="flex items-start gap-6">
            <div class="shrink-0 w-24 h-24 rounded-2xl border-2 border-dashed border-slate-300 flex items-center justify-center bg-slate-50 overflow-hidden">
              @if($settings['app_logo'])
                <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Logo Instansi" class="w-full h-full object-contain p-2">
              @else
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
              @endif
            </div>
            <div class="flex-1">
              <label for="app_logo" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">Unggah Logo Baru</label>
              <input type="file" id="app_logo" name="app_logo" accept=".jpg,.jpeg,.png,.svg" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
              <p class="text-xs text-slate-500 mt-2">Format yang didukung: JPG, PNG, SVG (Maks. 2MB). Kosongkan jika tidak ingin mengubah logo saat ini.</p>
            </div>
          </div>
        </div>

        <!-- System Preferences -->
        <div>
          <h2 class="text-base font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">Preferensi Sistem</h2>
          
          <div class="flex items-center justify-between p-4 rounded-xl border border-amber-200 bg-amber-50">
            <div>
              <h3 class="text-sm font-bold text-amber-900">Mode Pemeliharaan (Maintenance Mode)</h3>
              <p class="text-xs text-amber-700 mt-0.5">Jika diaktifkan, pembuatan pengajuan RAB baru oleh staf akan dinonaktifkan sementara waktu untuk keperluan maintenance.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer shrink-0">
              <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" {{ old('maintenance_mode', $settings['maintenance_mode']) === '1' ? 'checked' : '' }}>
              <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
            </label>
          </div>
        </div>

      </div>

      <div class="px-6 md:px-8 py-5 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 rounded-b-2xl">
        <button type="reset" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-colors">Batal</button>
        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-md shadow-indigo-100 transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
          Simpan Perubahan
        </button>
      </div>

    </form>
  </div>
</div>
@endsection
