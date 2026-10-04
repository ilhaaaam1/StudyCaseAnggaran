@php
  $currentRole = Auth::user()->role instanceof \BackedEnum ? Auth::user()->role->value : (string) (Auth::user()->role ?? 'user');
  $currentModel = class_basename(get_class(Auth::user()));
  try {
    $allPengguna = \App\Models\Pengguna::with('divisi')->get();
  } catch (\Throwable $e) {
    $allPengguna = collect();
  }
@endphp

<!-- Topbar Component with Role & Model Switcher -->
<nav x-data="{ roleDropdown: false, roleModal: false }" class="bg-white text-slate-800 flex items-center justify-between px-4 sm:px-6 py-2.5 sm:py-3 border-b border-slate-200 sticky top-0 z-30 shadow-xs">
  <div class="flex items-center gap-3 sm:gap-4">
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

  <div class="flex items-center gap-2 sm:gap-4">


    <!-- Notification Dropdown Component -->
    {{-- PRESENTASI: Implementasi Alpine.js pada UI Dropdown Lonceng --}}
    <div x-data="{ open: false }" class="relative">
      <button @click="open = !open" type="button" class="relative text-slate-500 hover:text-[#2b337c] transition-colors cursor-pointer text-base p-1 focus:outline-none">
        <i class="fa-regular fa-bell"></i>
        {{-- Badge indikator merah hanya muncul jika ada notifikasi belum terbaca --}}
        @if(Auth::user()->unreadNotifications->count() > 0)
          <div class="absolute top-1 right-1 w-[7px] h-[7px] bg-red-500 rounded-full ring-2 ring-white"></div>
        @endif
      </button>

      <!-- Dropdown Panel -->
      <div x-show="open" 
           @click.outside="open = false" 
           x-transition:enter="transition ease-out duration-150"
           x-transition:enter-start="opacity-0 scale-95"
           x-transition:enter-end="opacity-100 scale-100"
           x-transition:leave="transition ease-in duration-100"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95"
           class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-200 py-1 z-50 text-left overflow-hidden"
           style="display: none;">
        
        <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
          <span class="text-xs font-bold text-slate-700">Notifikasi</span>
          @if(Auth::user()->unreadNotifications->count() > 0)
            <span class="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">
              {{ Auth::user()->unreadNotifications->count() }} Baru
            </span>
          @endif
        </div>

        <div class="max-h-72 overflow-y-auto">
          @forelse(Auth::user()->notifications()->take(5)->get() as $notification)
            <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-3 border-b border-slate-50 hover:bg-slate-50 transition-colors {{ $notification->unread() ? 'bg-indigo-50/30' : '' }}">
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                  <i class="fa-solid fa-file-invoice text-xs"></i>
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-semibold text-slate-800 {{ $notification->unread() ? '' : 'text-opacity-80' }}">
                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                  </p>
                  <p class="text-[11px] text-slate-500 mt-0.5 leading-snug line-clamp-2">
                    {{ $notification->data['message'] ?? '' }}
                  </p>
                  <p class="text-[9px] text-slate-400 mt-1">
                    {{ $notification->created_at->diffForHumans() }}
                  </p>
                </div>
                @if($notification->unread())
                  <div class="w-2 h-2 rounded-full bg-indigo-500 shrink-0 mt-1"></div>
                @endif
              </div>
            </a>
          @empty
            <div class="py-6 text-center text-slate-400">
              <i class="fa-regular fa-bell-slash text-2xl mb-2 opacity-50"></i>
              <p class="text-xs">Tidak ada notifikasi baru.</p>
            </div>
          @endforelse
        </div>
        <div class="border-t border-slate-100 pt-1 pb-1">
          <a href="#" class="block text-center text-[11px] text-indigo-600 hover:text-indigo-800 font-medium py-1.5 transition-colors">
            Lihat Semua Notifikasi
          </a>
        </div>
      </div>
    </div>

    <!-- User Profile & Info -->
    {{-- PRESENTASI: Wrapper Link Profil --}}
    {{-- Mengubah area profil menjadi tautan (clickable) yang mengarah ke halaman Pengaturan Akun dengan efek hover visual --}}
    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 sm:gap-2.5 cursor-pointer group hover:bg-slate-50 p-2 rounded-lg transition-colors">
      <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold shrink-0 overflow-hidden
        @if($currentRole === 'admin' || $currentRole === 'admin_it') bg-indigo-600
        @elseif($currentRole === 'finance') bg-emerald-600
        @elseif($currentRole === 'pimpinan') bg-purple-600
        @else bg-blue-600 @endif">
        {{-- PRESENTASI: Logika Render Avatar --}}
        {{-- Mengecek jika user memiliki foto profil yang diunggah, maka tampilkan tag <img>, jika tidak, render inisial nama secara statis --}}
        @if(Auth::user()->foto_profil)
          <img src="{{ asset('storage/' . Auth::user()->foto_profil) }}" alt="Foto Profil" class="w-full h-full object-cover">
        @else
          {{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->name ?? 'US', 0, 2)) }}
        @endif
      </div>
      <div class="hidden sm:block text-left leading-tight">
        <h5 class="text-[13px] font-semibold text-slate-800 truncate max-w-[120px] group-hover:text-indigo-600 transition-colors">
          {{ Auth::user()->nama_lengkap ?? Auth::user()->name ?? 'Pengguna' }}
        </h5>
        <p class="text-[11px] text-slate-500 truncate max-w-[120px]">
          {{ Auth::user()->jabatan ?? Auth::user()->position ?? 'Staf' }} &bull; <span class="capitalize">{{ $currentRole }}</span>
        </p>
      </div>
    </a>
    
    <!-- Logout Form -->
    <form action="{{ route('logout') }}" method="POST" class="inline ml-1 border-l border-slate-200 pl-3 sm:pl-4">
      @csrf
      <button type="submit" class="text-slate-500 hover:text-red-500 transition-colors text-base cursor-pointer p-1" title="Keluar / Logout">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
      </button>
    </form>
  </div>

</nav>
