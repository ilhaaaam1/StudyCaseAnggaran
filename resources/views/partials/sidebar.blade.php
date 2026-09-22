<!-- Sidebar Component (Desktop & Mobile Drawer) -->
<div id="sidebarBackdrop" 
     class="fixed inset-0 bg-slate-900/60 z-40 hidden md:hidden transition-opacity duration-300"
     onclick="toggleSidebar()"></div>

<aside id="mainSidebar" 
       class="fixed md:sticky top-0 left-0 z-50 md:z-30 h-screen w-[260px] bg-[#1e255e] text-white flex flex-col justify-between shrink-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
  
  <div class="flex-1 overflow-y-auto">
    <!-- Sidebar Header / Brand -->
    <div class="flex items-center gap-3 px-3 py-4 mx-4 mt-2 border-b border-white/10 mb-5">
      <div class="w-10 h-10 flex items-center justify-center shrink-0">
        <img src="{{ asset('images/logo-sdn3.png') }}" alt="Logo SDN Sidokare 3" class="w-full h-full object-contain drop-shadow-sm">
      </div>
      <div>
        <h1 class="text-base font-bold tracking-wide leading-tight">SIRAB</h1>
        <p class="text-[10px] text-slate-400 tracking-wide uppercase leading-tight mt-0.5">Sistem Informasi RAB</p>
      </div>
      <button type="button" 
              onclick="toggleSidebar()" 
              class="md:hidden ml-auto text-white/50 hover:text-white p-1 rounded-lg focus:outline-none cursor-pointer"
              aria-label="Tutup Sidebar">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <!-- Navigation Links -->
    <div class="px-3.5 pb-6 space-y-1">
      @php
        $userRole = Auth::user()?->role;
      @endphp

      @if($userRole === 'admin_it' || $userRole === 'admin')
        <!-- Admin IT Menu -->
        <div class="text-[10px] uppercase text-slate-400 px-2.5 pb-2 tracking-wide font-semibold mt-4 mb-1">
          Menu Administrator IT
        </div>
        <a href="{{ route('admin-it.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('admin-it.dashboard') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-house w-[18px] text-center text-[15px]"></i>
            <span>Dashboard IT</span>
          </div>
        </a>
        <a href="{{ route('admin-it.users.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('admin-it.users.*') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-users w-[18px] text-center text-[15px]"></i>
            <span>Manajemen Pengguna</span>
          </div>
        </a>
        {{-- PRESENTASI: Perubahan Nama Menu Sidebar --}}
        {{-- Menu Admin IT 'Master Divisi' disesuaikan namanya menjadi 'Master Bidang/Bagian' --}}
        <a href="{{ route('admin-it.divisi.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('admin-it.divisi.*') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-sitemap w-[18px] text-center text-[15px]"></i>
            <span>Master Bidang/Bagian</span>
          </div>
        </a>
        <a href="{{ route('admin-it.log.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('admin-it.log.*') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-clock-rotate-left w-[18px] text-center text-[15px]"></i>
            <span>Log Aktivitas</span>
          </div>
        </a>
        <a href="{{ route('admin-it.pengaturan.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('admin-it.pengaturan.*') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-gear w-[18px] text-center text-[15px]"></i>
            <span>Pengaturan Sistem</span>
          </div>
        </a>
      @endif

      @if($userRole === 'finance')
        <!-- Finance Menu -->
        <div class="text-[10px] uppercase text-slate-400 px-2.5 pb-2 tracking-wide font-semibold mt-4 mb-1">
          Menu Reviewer Finance
        </div>
        <a href="{{ route('finance.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('finance.dashboard') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-house w-[18px] text-center text-[15px]"></i>
            <span>Dashboard Finance</span>
          </div>
        </a>
        <a href="{{ route('finance.antrean') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('finance.antrean*') || request()->routeIs('finance.show') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-folder-open w-[18px] text-center text-[15px]"></i>
            <span>Antrean Verifikasi</span>
          </div>
          <span class="bg-amber-500 text-white text-[10px] px-1.5 py-0.5 rounded font-semibold tracking-wide">Tahap 1</span>
        </a>
        <a href="{{ route('finance.pencairan') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('finance.pencairan*') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-wallet w-[18px] text-center text-[15px]"></i>
            <span>Pencairan Dana</span>
          </div>
        </a>
        <a href="{{ route('finance.riwayat') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('finance.riwayat') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-clock-rotate-left w-[18px] text-center text-[15px]"></i>
            <span>Riwayat Review</span>
          </div>
        </a>

        <div class="text-[10px] uppercase text-slate-400 px-2.5 pb-2 tracking-wide font-semibold mt-6 mb-1">
          Manajemen Data
        </div>
        <a href="{{ route('finance.kategori.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('finance.kategori.*') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-layer-group w-[18px] text-center text-[15px]"></i>
            <span>Master Kategori & Pagu</span>
          </div>
        </a>
        <a href="{{ route('finance.rekapitulasi.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('finance.rekapitulasi.*') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-chart-pie w-[18px] text-center text-[15px]"></i>
            <span>Rekapitulasi Laporan</span>
          </div>
        </a>
      @endif

      {{-- PRESENTASI: Logika IF Sidebar Memunculkan Menu Dinamis --}}
      {{-- Menampilkan menu "Antrean Persetujuan Tahap 2" JIKA role user adalah 'Pimpinan' --}}
      {{-- ATAU user yang sedang login menerima delegasi wewenang yang aktif --}}
      @if($userRole === 'pimpinan' || (auth()->check() && auth()->user()->hasActiveDelegation()))
        <!-- Pimpinan Menu -->
        <div class="text-[10px] uppercase text-slate-400 px-2.5 pb-2 tracking-wide font-semibold mt-4 mb-1 flex items-center justify-between">
          <span>Menu Reviewer Final</span>
          {{-- PRESENTASI: Penanda visual (Badge) "Delegated" --}}
          {{-- Badge ini dimunculkan agar user (misal Finance) sadar bahwa menu ini adalah menu tambahan dari pimpinan --}}
          @if($userRole !== 'pimpinan')
            <span class="bg-indigo-600 text-white text-[8px] px-1.5 py-0.5 rounded font-bold uppercase tracking-widest">Delegated</span>
          @endif
        </div>
        
        @if($userRole === 'pimpinan')
        <a href="{{ route('pimpinan.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('pimpinan.dashboard') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-house w-[18px] text-center text-[15px]"></i>
            <span>Dashboard Pimpinan</span>
          </div>
        </a>
        @endif
        
        <a href="{{ route('pimpinan.antrean') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('pimpinan.antrean*') || request()->routeIs('pimpinan.show') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-folder-open w-[18px] text-center text-[15px]"></i>
            <span>Antrean Persetujuan</span>
          </div>
          @if($userRole !== 'pimpinan')
            <span class="bg-indigo-500 text-white text-[10px] px-1.5 py-0.5 rounded font-semibold tracking-wide">Delegated</span>
          @else
            <span class="bg-blue-500 text-white text-[10px] px-1.5 py-0.5 rounded font-semibold tracking-wide">Tahap 2</span>
          @endif
        </a>
        
        @if($userRole === 'pimpinan')
        <a href="{{ route('pimpinan.riwayat') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('pimpinan.riwayat') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-clock-rotate-left w-[18px] text-center text-[15px]"></i>
            <span>Riwayat Final</span>
          </div>
        </a>
        <a href="{{ route('pimpinan.statistik.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('pimpinan.statistik.*') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-chart-line w-[18px] text-center text-[15px]"></i>
            <span>Statistik Anggaran</span>
          </div>
        </a>
        <a href="{{ route('pimpinan.delegasi.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('pimpinan.delegasi.*') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-user-shield w-[18px] text-center text-[15px]"></i>
            <span>Delegasi Wewenang</span>
          </div>
        </a>
        @endif
      @endif

      @if(in_array($userRole, ['staff', 'user']))
        <!-- Staff Menu -->
        <div class="text-[10px] uppercase text-slate-400 px-2.5 pb-2 tracking-wide font-semibold mt-4 mb-1">
          Menu Pemohon / Staf
        </div>
        <a href="{{ route('staff.dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('staff.dashboard') || request()->routeIs('user.dashboard') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-house w-[18px] text-center text-[15px]"></i>
            <span>Dashboard Staf</span>
          </div>
        </a>
        <a href="{{ route('staff.rab.create') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('staff.rab.create') || request()->routeIs('user.rab.create') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-file-circle-plus w-[18px] text-center text-[15px]"></i>
            <span>Buat Pengajuan RAB</span>
          </div>
        </a>
        <a href="{{ route('staff.riwayat') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('staff.riwayat') || request()->routeIs('user.laporan') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-folder-open w-[18px] text-center text-[15px]"></i>
            <span>Riwayat Pengajuan</span>
          </div>
        </a>
        <a href="{{ route('staff.draft') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('staff.draft') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-file-lines w-[18px] text-center text-[15px]"></i>
            <span>Draft Pengajuan</span>
          </div>
        </a>
        <a href="{{ route('staff.panduan') }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg mb-1 text-[13px] font-medium transition-all {{ request()->routeIs('staff.panduan') ? 'bg-[#2b337c] text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
          <div class="flex items-center gap-3">
            <i class="fa-solid fa-book-open w-[18px] text-center text-[15px]"></i>
            <span>Panduan / SOP</span>
          </div>
        </a>
      @endif

    </div>
  </div>

  <!-- Sidebar Footer: Profile Info & Logout -->
  <div class="p-4 m-3 bg-black/20 rounded-xl flex items-center justify-between">
    <div class="flex items-center gap-3 min-w-0">
      <div class="w-9 h-9 rounded-full bg-blue-500 text-white font-semibold text-[13px] flex items-center justify-center shrink-0">
        {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'US', 0, 2)) }}
      </div>
      <div class="min-w-0">
        <h4 class="text-[13px] font-semibold text-white truncate">
          {{ Auth::user()->nama_lengkap ?? 'Pengguna' }}
        </h4>
        <p class="text-[11px] text-slate-400 truncate">
          {{ Auth::user()->jabatan ?? 'Staf' }} &bull; <span class="capitalize">{{ Auth::user()->role ?? 'user' }}</span>
        </p>
      </div>
    </div>
    <form action="{{ route('logout') }}" method="POST" class="shrink-0 ml-1 flex items-center">
      @csrf
      <button type="submit" 
              class="text-slate-400 hover:text-white text-base transition-colors cursor-pointer" 
              title="Keluar / Logout">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
      </button>
    </form>
  </div>
</aside>
