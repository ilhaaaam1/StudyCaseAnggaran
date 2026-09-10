<!-- Sidebar Component (Desktop & Mobile Drawer) -->
<div id="sidebarBackdrop" 
     class="fixed inset-0 bg-slate-900/60 z-40 hidden md:hidden transition-opacity duration-300"
     onclick="toggleSidebar()"></div>

<aside id="mainSidebar" 
       class="fixed md:sticky top-0 left-0 z-50 md:z-30 h-screen w-64 bg-[#1e293b] text-slate-300 flex flex-col justify-between shrink-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out border-r border-slate-800 shadow-xl md:shadow-none">
  
  <!-- Sidebar Header / Brand -->
  <div class="px-5 py-4.5 border-b border-slate-800 flex items-center justify-between">
    <a href="{{ Auth::user()?->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="flex items-center gap-3 group">
      <div class="bg-indigo-600 group-hover:bg-indigo-500 text-white font-bold px-2.5 py-1.5 rounded-lg shadow-sm text-sm tracking-wider transition-colors">
        K3
      </div>
      <div>
        <div class="font-bold tracking-wide text-white text-base leading-tight">SIRAB</div>
        <div class="text-[9px] text-slate-400 font-medium tracking-wider uppercase">Sistem Informasi RAB</div>
      </div>
    </a>
    <button type="button" 
            onclick="toggleSidebar()" 
            class="md:hidden text-slate-400 hover:text-white p-1 rounded-lg focus:outline-none cursor-pointer"
            aria-label="Tutup Sidebar">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>

  <!-- Navigation Links -->
  <div class="flex-1 overflow-y-auto px-3.5 py-4 space-y-6">
    @php
      $userRole = Auth::user()?->role;
    @endphp

    @if($userRole === 'admin_it' || $userRole === 'admin')
      <!-- Admin IT Menu -->
      <div>
        <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
          Menu Administrator IT
        </div>
        <nav class="space-y-1">
          <a href="{{ route('admin-it.dashboard') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('admin-it.dashboard') ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard IT</span>
          </a>

          <a href="{{ route('admin-it.users.index') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('admin-it.users.*') ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Manajemen Pengguna</span>
          </a>

          <a href="{{ route('admin-it.divisi.index') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('admin-it.divisi.*') ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Master Divisi</span>
          </a>
        </nav>
      </div>
    @elseif($userRole === 'finance')
      <!-- Finance Menu -->
      <div>
        <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
          Menu Reviewer Finance
        </div>
        <nav class="space-y-1">
          <a href="{{ route('finance.dashboard') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('finance.dashboard') ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard Finance</span>
          </a>

          <a href="{{ route('finance.antrean') }}" 
             class="flex items-center justify-between px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('finance.antrean*') || request()->routeIs('finance.show') ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <div class="flex items-center gap-3">
              <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <span>Antrean Tahap 1</span>
            </div>
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-500/20 text-amber-300 border border-amber-500/30">Pending</span>
          </a>

          <a href="{{ route('finance.riwayat') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('finance.riwayat') ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Riwayat Review</span>
          </a>
        </nav>
      </div>
    @elseif($userRole === 'pimpinan')
      <!-- Pimpinan Menu -->
      <div>
        <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
          Menu Reviewer Final
        </div>
        <nav class="space-y-1">
          <a href="{{ route('pimpinan.dashboard') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('pimpinan.dashboard') ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard Pimpinan</span>
          </a>

          <a href="{{ route('pimpinan.antrean') }}" 
             class="flex items-center justify-between px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('pimpinan.antrean*') || request()->routeIs('pimpinan.show') ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <div class="flex items-center gap-3">
              <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span>Antrean Tahap 2</span>
            </div>
            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30">ACC Finance</span>
          </a>

          <a href="{{ route('pimpinan.riwayat') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('pimpinan.riwayat') ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Riwayat Final</span>
          </a>
        </nav>
      </div>
    @else
      <!-- Staff Menu -->
      <div>
        <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">
          Menu Pemohon / Staf
        </div>
        <nav class="space-y-1">
          <a href="{{ route('staff.dashboard') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('staff.dashboard') || request()->routeIs('user.dashboard') ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard Staf</span>
          </a>

          <a href="{{ route('staff.rab.create') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('staff.rab.create') || request()->routeIs('user.rab.create') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Buat Pengajuan RAB</span>
          </a>

          <a href="{{ route('staff.riwayat') }}" 
             class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-colors {{ request()->routeIs('staff.riwayat') || request()->routeIs('user.laporan') ? 'bg-slate-700 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            <span>Riwayat Pengajuan</span>
          </a>
        </nav>
      </div>
    @endif

  </div>

  <!-- Sidebar Footer: Profile Info & Logout -->
  <div class="p-4 border-t border-slate-800 bg-[#162032]">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2.5 min-w-0">
        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-inner">
          {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'US', 0, 2)) }}
        </div>
        <div class="min-w-0">
          <div class="text-xs font-semibold text-white truncate">
            {{ Auth::user()->nama_lengkap ?? 'Pengguna' }}
          </div>
          <div class="text-[10px] text-slate-400 truncate">
            {{ Auth::user()->jabatan ?? 'Staf' }} &bull; <span class="text-indigo-400 font-medium uppercase">{{ Auth::user()->role ?? 'user' }}</span>
          </div>
        </div>
      </div>
      <form action="{{ route('logout') }}" method="POST" class="shrink-0 ml-1">
        @csrf
        <button type="submit" 
                class="text-slate-400 hover:text-rose-400 p-1.5 rounded-lg hover:bg-slate-800 transition-colors cursor-pointer" 
                title="Keluar / Logout">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
        </button>
      </form>
    </div>
  </div>
</aside>
