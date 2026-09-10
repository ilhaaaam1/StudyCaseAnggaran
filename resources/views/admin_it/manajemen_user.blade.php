@extends('layouts.app')

@section('title', 'Manajemen Pengguna (4 Role) - SIRAB Kelompok-3')

@section('content')
  <div class="text-xs text-slate-500 mb-6 flex items-center gap-1.5">
    <span>SIRAB</span>
    <span>/</span>
    <a href="{{ route('admin-it.dashboard') }}" class="hover:text-slate-800">Administrator IT</a>
    <span>/</span>
    <span class="text-slate-800 font-medium">Manajemen Pengguna</span>
  </div>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Manajemen Pengguna &amp; Hak Akses</h1>
      <p class="text-xs text-slate-500 mt-1">Kelola data akun untuk 4 role: Staff, Finance, Pimpinan, dan Admin IT.</p>
    </div>
    <a href="{{ route('admin-it.users.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm">
      + Tambah Akun Pengguna
    </a>
  </div>

  <!-- Filter & Table -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
      <div class="flex items-center gap-2">
        <a href="{{ route('admin-it.users.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ empty($roleFilter) ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600' }}">Semua</a>
        <a href="{{ route('admin-it.users.index', ['role' => 'staff']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $roleFilter === 'staff' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-800' }}">Staff</a>
        <a href="{{ route('admin-it.users.index', ['role' => 'finance']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $roleFilter === 'finance' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-800' }}">Finance</a>
        <a href="{{ route('admin-it.users.index', ['role' => 'pimpinan']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $roleFilter === 'pimpinan' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-800' }}">Pimpinan</a>
        <a href="{{ route('admin-it.users.index', ['role' => 'admin_it']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $roleFilter === 'admin_it' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-700' }}">Admin IT</a>
      </div>
      <form method="GET" action="{{ route('admin-it.users.index') }}" class="flex items-center gap-2">
        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama / email..." class="px-3.5 py-1.5 border border-slate-300 rounded-xl text-xs">
        <button type="submit" class="px-3.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">Cari</button>
      </form>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs border-collapse">
        <thead class="bg-slate-50 text-slate-600 uppercase font-semibold border-b border-slate-200">
          <tr>
            <th class="px-5 py-3.5 w-10 text-center">#</th>
            <th class="px-5 py-3.5">Nama Lengkap</th>
            <th class="px-5 py-3.5">Email</th>
            <th class="px-5 py-3.5">Divisi</th>
            <th class="px-5 py-3.5">Jabatan</th>
            <th class="px-5 py-3.5 text-center">Role Akses</th>
            <th class="px-5 py-3.5 text-center w-36">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse($users ?? [] as $idx => $user)
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-3.5 text-center text-slate-400 font-mono">{{ $idx + 1 }}</td>
              <td class="px-5 py-3.5 font-semibold text-slate-900">{{ $user->nama_lengkap }}</td>
              <td class="px-5 py-3.5 font-mono text-slate-600">{{ $user->email }}</td>
              <td class="px-5 py-3.5 text-slate-700">{{ $user->divisi->nama_divisi ?? '-' }}</td>
              <td class="px-5 py-3.5 text-slate-600">{{ $user->jabatan ?? '-' }}</td>
              <td class="px-5 py-3.5 text-center">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                  {{ $user->role === 'admin_it' || $user->role === 'admin' ? 'bg-slate-800 text-white' : ($user->role === 'pimpinan' ? 'bg-indigo-100 text-indigo-800' : ($user->role === 'finance' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800')) }}">
                  {{ $user->role }}
                </span>
              </td>
              <td class="px-5 py-3.5 text-center">
                <div class="flex items-center justify-center gap-1.5">
                  <a href="{{ route('admin-it.users.edit', $user->id_pengguna) }}" class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg text-xs font-semibold border border-indigo-200">
                    Edit
                  </a>
                  <form action="{{ route('admin-it.users.destroy', $user->id_pengguna) }}" method="POST" class="inline"
                        onsubmit="return confirm('Hapus akun {{ $user->nama_lengkap }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold border border-rose-200">
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-8 text-center text-slate-400">Tidak ada data pengguna.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if(method_exists($users, 'links'))
      <div class="p-4 border-t border-slate-100">
        {{ $users->links() }}
      </div>
    @endif
  </div>
@endsection
