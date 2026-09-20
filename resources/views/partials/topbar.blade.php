<!-- Topbar Component -->
<nav class="bg-white text-slate-800 flex items-center justify-between px-6 py-3 border-b border-slate-200 sticky top-0 z-30">
  <div class="flex items-center gap-4">
    <!-- Mobile Hamburger Toggle -->
    <button type="button" 
            onclick="toggleSidebar()"
            class="md:hidden text-slate-500 hover:text-slate-800 p-1.5 rounded-lg hover:bg-slate-50 focus:outline-none cursor-pointer"
            aria-label="Buka Menu Navigasi">
      <i class="fa-solid fa-bars text-lg"></i>
    </button>

    <!-- Breadcrumb (Desktop) -->
    <div class="hidden md:flex items-center gap-2 text-[13px] text-slate-500">
      SIRAB 
      <i class="fa-solid fa-chevron-right text-[10px]"></i> 
      <span class="text-slate-800 font-medium">@yield('breadcrumb', 'Dashboard')</span>
    </div>

    <!-- Brand / Title on Mobile -->
    <a href="#" class="flex md:hidden items-center gap-2">
      <div class="w-7 h-7 flex items-center justify-center">
        <img src="{{ asset('images/logo-sdn3.png') }}" alt="Logo SDN Sidokare 3" class="w-full h-full object-contain">
      </div>
      <span class="font-bold tracking-wide text-slate-800 text-sm">SIRAB</span>
    </a>
  </div>

  <div class="flex items-center gap-5">
    <!-- Notification Indicator Icon -->
    <div class="relative text-slate-500 hover:text-[#2b337c] transition-colors cursor-pointer text-base">
      <i class="fa-regular fa-bell"></i>
      <div class="absolute -top-0.5 -right-0.5 w-[7px] h-[7px] bg-red-500 rounded-full"></div>
    </div>

    <!-- User Profile & Logout -->
    <div class="flex items-center gap-2.5 cursor-pointer group">
      <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-xs font-semibold shrink-0">
        {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'US', 0, 2)) }}
      </div>
      <div class="hidden sm:block text-left leading-tight">
        <h5 class="text-[13px] font-semibold text-slate-800 truncate max-w-[120px]">
          {{ Auth::user()->nama_lengkap ?? 'Pengguna' }}
        </h5>
        <p class="text-[11px] text-slate-500 truncate max-w-[120px]">
          {{ Auth::user()->jabatan ?? 'Staf' }} &bull; <span class="capitalize">{{ Auth::user()->role ?? 'user' }}</span>
        </p>
      </div>
      <i class="fa-solid fa-chevron-down text-[11px] text-slate-500 ml-1 hidden sm:block"></i>
    </div>
    
    <form action="{{ route('logout') }}" method="POST" class="inline ml-1 border-l border-slate-200 pl-4">
      @csrf
      <button type="submit" class="text-slate-500 hover:text-red-500 transition-colors text-base cursor-pointer" title="Keluar / Logout">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
      </button>
    </form>
  </div>
</nav>
