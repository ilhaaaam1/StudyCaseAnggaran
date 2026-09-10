<!-- Navbar Component -->
<nav class="bg-[#1e293b] text-white flex items-center justify-between px-4 sm:px-6 py-3 text-sm sticky top-0 z-30 shadow-md border-b border-slate-800">
  <div class="flex items-center gap-4 sm:gap-6">
    <!-- Mobile Hamburger Toggle -->
    <button type="button" 
            onclick="toggleSidebar()"
            class="md:hidden text-slate-300 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 focus:outline-none cursor-pointer"
            aria-label="Buka Menu Navigasi">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>

    <!-- Brand / Title on Mobile -->
    <a href="{{ Auth::user()?->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}" class="flex md:hidden items-center gap-2">
      <div class="bg-indigo-600 text-white font-bold px-2 py-0.5 rounded text-xs">
        K3
      </div>
      <span class="font-bold tracking-wide text-white text-sm">SIRAB</span>
    </a>

    <!-- Top Links (Desktop Quick Navigation) -->
    <div class="hidden lg:flex items-center gap-1 text-slate-300 text-xs font-medium">
      @if(Auth::user()?->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" 
           class="{{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white px-3 py-1.5 rounded-md font-semibold' : 'hover:text-white hover:bg-slate-800/50 px-3 py-1.5 rounded-md' }} transition-colors">
          Dashboard
        </a>
        <a href="{{ route('admin.rab.index') }}" 
           class="{{ request()->routeIs('admin.rab.*') || request()->routeIs('admin.approval.*') ? 'bg-slate-800 text-white px-3 py-1.5 rounded-md font-semibold' : 'hover:text-white hover:bg-slate-800/50 px-3 py-1.5 rounded-md' }} transition-colors">
          Antrean &amp; Persetujuan
        </a>
        <a href="{{ route('admin.laporan') }}" 
           class="{{ request()->routeIs('admin.laporan') ? 'bg-slate-800 text-white px-3 py-1.5 rounded-md font-semibold' : 'hover:text-white hover:bg-slate-800/50 px-3 py-1.5 rounded-md' }} transition-colors">
          Laporan
        </a>
        <a href="{{ route('admin.users.index') }}" 
           class="{{ request()->routeIs('admin.users.*') ? 'bg-slate-800 text-white px-3 py-1.5 rounded-md font-semibold' : 'hover:text-white hover:bg-slate-800/50 px-3 py-1.5 rounded-md' }} transition-colors">
          Staff
        </a>
      @else
        <a href="{{ route('user.dashboard') }}" 
           class="{{ request()->routeIs('user.dashboard') ? 'bg-slate-800 text-white px-3 py-1.5 rounded-md font-semibold' : 'hover:text-white hover:bg-slate-800/50 px-3 py-1.5 rounded-md' }} transition-colors">
          Dashboard
        </a>
        <a href="{{ route('user.rab.create') }}" 
           class="{{ request()->routeIs('user.rab.create') ? 'bg-slate-800 text-white px-3 py-1.5 rounded-md font-semibold' : 'hover:text-white hover:bg-slate-800/50 px-3 py-1.5 rounded-md' }} transition-colors">
          Buat Pengajuan
        </a>
        <a href="{{ route('user.laporan') }}" 
           class="{{ request()->routeIs('user.laporan') || request()->routeIs('user.rab.laporan') ? 'bg-slate-800 text-white px-3 py-1.5 rounded-md font-semibold' : 'hover:text-white hover:bg-slate-800/50 px-3 py-1.5 rounded-md' }} transition-colors">
          Riwayat Laporan
        </a>
      @endif
    </div>
  </div>

  <div class="flex items-center gap-3 sm:gap-4">
    <!-- Notification Indicator Icon -->
    <div class="relative text-slate-300 hover:text-yellow-400 transition-colors cursor-pointer p-1">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/>
      </svg>
      <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-amber-400 ring-2 ring-[#1e293b]"></span>
    </div>

    <!-- User Profile & Logout -->
    <div class="flex items-center gap-3 border-l border-slate-700 pl-3 sm:pl-4">
      <div class="bg-indigo-700 text-white font-bold rounded-full h-8 w-8 flex items-center justify-center text-xs shadow-inner shrink-0">
        {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'US', 0, 2)) }}
      </div>
      <div class="hidden sm:block text-left leading-tight">
        <div class="text-xs font-semibold text-slate-100 max-w-[140px] truncate">
          {{ Auth::user()->nama_lengkap ?? 'Pengguna' }}
        </div>
        <div class="text-[10px] text-slate-400 max-w-[140px] truncate">
          {{ Auth::user()->jabatan ?? 'Staf' }} &bull; <span class="capitalize text-indigo-400 font-medium">{{ Auth::user()->role ?? 'user' }}</span>
        </div>
      </div>

      <!-- Logout Button -->
      <form action="{{ route('logout') }}" method="POST" class="inline">
        @csrf
        <button type="submit" class="text-slate-400 hover:text-rose-400 transition-colors p-1.5 rounded-lg hover:bg-slate-800 cursor-pointer" title="Keluar / Logout">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
          </svg>
        </button>
      </form>
    </div>
  </div>
</nav>
