@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Pengaturan Akun</h1>
    <p class="text-sm text-slate-500 mt-1">Kelola informasi profil dan keamanan akun Anda.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Card 1: Informasi Profil -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-semibold text-slate-800">Informasi Profil</h2>
            <p class="text-sm text-slate-500 mt-1">Perbarui foto profil, nama lengkap, dan nomor HP Anda.</p>
        </div>
        
        <div class="p-6">
            {{-- Form tersembunyi untuk hapus foto --}}
            <form id="delete-photo-form" action="{{ route('profile.photo.destroy') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>

            {{-- PRESENTASI: Form Update Profil dengan dukungan Upload File --}}
            {{-- enctype="multipart/form-data" ditambahkan agar form bisa mengirimkan file foto_profil ke server --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Foto Profil -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Foto Profil</label>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-slate-200 flex items-center justify-center overflow-hidden shrink-0 border border-slate-300">
                            @if($user->foto_profil)
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto Profil" class="w-full h-full object-cover">
                            @else
                                <span class="text-slate-500 font-bold text-xl">{{ strtoupper(substr($user->nama_lengkap ?? 'US', 0, 2)) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" name="foto_profil" id="foto_profil" accept="image/*"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors">
                            <p class="text-xs text-slate-400 mt-1 mb-2">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                            @error('foto_profil')
                                <p class="text-red-500 text-xs mt-1 mb-2">{{ $message }}</p>
                            @enderror
                            {{-- PRESENTASI: Tombol Hapus Foto --}}
                            {{-- Menampilkan tombol reset foto yang memicu form DELETE terpisah jika user sudah memiliki foto profil --}}
                            @if($user->foto_profil)
                                <button type="submit" form="delete-photo-form" onclick="return confirm('Apakah Anda yakin ingin menghapus foto profil?')" class="inline-flex items-center text-xs font-medium text-red-600 hover:text-red-700 border border-red-200 hover:bg-red-50 px-3 py-1.5 rounded-md transition-colors">
                                    <i class="fa-solid fa-trash-can mr-1.5"></i> Hapus Foto
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Email (Readonly) -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" id="email" value="{{ $user->email }}" disabled
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 bg-slate-50 text-slate-500 cursor-not-allowed">
                    <p class="text-xs text-slate-400 mt-1">Email digunakan untuk login dan tidak dapat diubah.</p>
                </div>

                {{-- PRESENTASI: Input Jabatan/Role (Readonly) --}}
                {{-- Mempertegas identitas institusi pengguna yang mengambil data jabatan dan role dari model/database --}}
                <div class="mb-4">
                    <label for="jabatan" class="block text-sm font-medium text-slate-700 mb-1">Jabatan / Role</label>
                    <input type="text" id="jabatan" value="{{ $user->jabatan }} ({{ strtoupper($user->role) }})" disabled
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 bg-slate-50 text-slate-500 cursor-not-allowed font-medium">
                    <p class="text-xs text-slate-400 mt-1">Identitas jabatan dan peran (role) Anda di sistem.</p>
                </div>

                <!-- Nama Lengkap -->
                <div class="mb-4">
                    <label for="nama_lengkap" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @error('nama_lengkap')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PRESENTASI: Input NIP / NUPTK --}}
                {{-- Menambahkan field identitas resmi pegawai agar sistem lebih otentik --}}
                <div class="mb-4">
                    <label for="nip" class="block text-sm font-medium text-slate-700 mb-1">NIP / NUPTK</label>
                    <input type="text" name="nip" id="nip" value="{{ old('nip', $user->nip) }}" placeholder="Masukkan NIP atau Nomor Induk..."
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @error('nip')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor HP -->
                <div class="mb-6">
                    <label for="no_hp" class="block text-sm font-medium text-slate-700 mb-1">Nomor Handphone</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp) }}" placeholder="Contoh: 08123456789"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @error('no_hp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
                        Simpan Profil
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Card 2: Ubah Password -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden h-fit">
        <div class="p-6 border-b border-slate-100">
            <h2 class="text-lg font-semibold text-slate-800">Ubah Password</h2>
            <p class="text-sm text-slate-500 mt-1">Pastikan akun Anda menggunakan password yang panjang dan acak.</p>
        </div>
        
        <div class="p-6">
            {{-- PRESENTASI: Form Ubah Password --}}
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Password Saat Ini -->
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Password Saat Ini <span class="text-red-500">*</span></label>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Baru -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password Baru <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg font-medium text-sm transition-colors shadow-sm">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
