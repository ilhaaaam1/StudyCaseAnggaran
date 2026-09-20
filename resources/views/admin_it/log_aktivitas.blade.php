@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem - Admin IT')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

  <!-- Top Header -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
      <h1 class="text-2xl font-bold text-slate-900">Log Aktivitas Sistem (Audit Trail)</h1>
      <p class="text-sm text-slate-500 mt-1">Pantau seluruh riwayat aktivitas yang dilakukan pengguna dalam sistem SIRAB.</p>
    </div>

    <!-- Search Form -->
    <form action="{{ route('admin-it.log.index') }}" method="GET" class="w-full md:w-80">
      <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari aktivitas atau nama pengguna..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors shadow-sm">
      </div>
    </form>
  </div>

  <!-- Table Card -->
  <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse text-sm">
        <thead>
          <tr class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
            <th class="py-3 px-6 w-48">Waktu</th>
            <th class="py-3 px-6 w-64">Pengguna</th>
            <th class="py-3 px-6">Aktivitas</th>
            <th class="py-3 px-6 w-40 text-center">IP Address</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-700">
          @forelse($logs as $log)
            <tr class="hover:bg-slate-50/60 transition-colors">
              <td class="py-3.5 px-6">
                <div class="font-medium text-slate-800">{{ $log->created_at->format('d M Y') }}</div>
                <div class="text-xs text-slate-500">{{ $log->created_at->format('H:i:s') }} WIB</div>
              </td>
              <td class="py-3.5 px-6">
                @if($log->user)
                  <div class="font-bold text-slate-800">{{ $log->user->nama_lengkap }}</div>
                  <div class="text-[11px] text-slate-500 uppercase tracking-wide">
                    @if($log->user->role === 'admin_it' || $log->user->role === 'admin')
                      <span class="text-indigo-600 font-semibold">Admin IT</span>
                    @elseif($log->user->role === 'finance')
                      <span class="text-amber-600 font-semibold">Finance</span>
                    @elseif($log->user->role === 'pimpinan')
                      <span class="text-blue-600 font-semibold">Pimpinan</span>
                    @else
                      <span class="text-emerald-600 font-semibold">Staff/User</span>
                    @endif
                  </div>
                @else
                  <span class="text-slate-400 italic">Sistem / Pengguna Terhapus</span>
                @endif
              </td>
              <td class="py-3.5 px-6 text-slate-700 font-medium">
                {{ $log->activity }}
              </td>
              <td class="py-3.5 px-6 text-center">
                <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 rounded-md font-mono text-xs border border-slate-200">
                  {{ $log->ip_address ?? '-' }}
                </span>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                  <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                  <p class="text-slate-500 text-sm font-medium">Tidak ada data aktivitas sistem saat ini.</p>
                  @if(request('q'))
                    <a href="{{ route('admin-it.log.index') }}" class="text-indigo-600 hover:text-indigo-700 text-xs font-semibold mt-1">Reset Pencarian</a>
                  @endif
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    @if($logs->hasPages())
      <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
        {{ $logs->links() }}
      </div>
    @endif
  </div>

</div>
@endsection
