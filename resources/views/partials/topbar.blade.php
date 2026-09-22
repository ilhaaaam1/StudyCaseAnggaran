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
    <!-- ROLE & MODEL SWITCHER COMPONENT -->
    <div class="relative">
      <button @click="roleDropdown = !roleDropdown" 
              type="button" 
              class="inline-flex items-center gap-1.5 sm:gap-2 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-full text-xs font-semibold border transition-all shadow-xs focus:outline-none cursor-pointer
              @if($currentRole === 'admin' || $currentRole === 'admin_it')
                bg-indigo-50 text-indigo-700 border-indigo-200 hover:bg-indigo-100
              @elseif($currentRole === 'finance')
                bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100
              @elseif($currentRole === 'pimpinan')
                bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100
              @else
                bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100
              @endif"
              title="Ganti Peran / Role Model Switch">
        <span class="flex h-2 w-2 relative">
          <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75
          @if($currentRole === 'admin' || $currentRole === 'admin_it') bg-indigo-400
          @elseif($currentRole === 'finance') bg-emerald-400
          @elseif($currentRole === 'pimpinan') bg-purple-400
          @else bg-blue-400 @endif"></span>
          <span class="relative inline-flex rounded-full h-2 w-2
          @if($currentRole === 'admin' || $currentRole === 'admin_it') bg-indigo-600
          @elseif($currentRole === 'finance') bg-emerald-600
          @elseif($currentRole === 'pimpinan') bg-purple-600
          @else bg-blue-600 @endif"></span>
        </span>
        <i class="fa-solid fa-repeat text-[11px] text-slate-500"></i>
        <span class="font-semibold">
          <span class="hidden sm:inline text-slate-500 font-normal">Role:</span>
          @if($currentRole === 'admin' || $currentRole === 'admin_it')
            Admin IT
          @elseif($currentRole === 'finance')
            Finance
          @elseif($currentRole === 'pimpinan')
            Pimpinan
          @else
            Staff
          @endif
        </span>
        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': roleDropdown }"></i>
      </button>

      <!-- Dropdown Quick Switcher Menu -->
      <div x-show="roleDropdown" 
           @click.outside="roleDropdown = false" 
           x-transition:enter="transition ease-out duration-150"
           x-transition:enter-start="opacity-0 scale-95"
           x-transition:enter-end="opacity-100 scale-100"
           x-transition:leave="transition ease-in duration-100"
           x-transition:leave-start="opacity-100 scale-100"
           x-transition:leave-end="opacity-0 scale-95"
           class="absolute right-0 mt-2 w-72 sm:w-80 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50 text-left"
           style="display: none;">
        
        <div class="px-3.5 py-2 border-b border-slate-100">
          <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Role Model Switch</span>
            <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-mono font-medium" title="Active Model">
              Model: {{ $currentModel }}
            </span>
          </div>
          <p class="text-[11px] text-slate-400 mt-0.5">Pilih role untuk berganti akses seketika</p>
        </div>

        <div class="p-1.5 space-y-1">
          <!-- Administrator IT -->
          <form action="{{ route('role.switch') }}" method="POST">
            @csrf
            <input type="hidden" name="role" value="admin">
            <input type="hidden" name="email" value="arif@sirab.local">
            <button type="submit" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs transition-colors cursor-pointer text-left
              {{ ($currentRole === 'admin' || $currentRole === 'admin_it') ? 'bg-indigo-50 text-indigo-900 font-semibold ring-1 ring-indigo-200' : 'text-slate-700 hover:bg-slate-50' }}">
              <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                  <i class="fa-solid fa-shield-halved text-xs"></i>
                </div>
                <div>
                  <div class="font-medium text-slate-800">Administrator IT</div>
                  <div class="text-[10px] text-slate-400">arif@sirab.local &bull; Direktur Keuangan</div>
                </div>
              </div>
              @if($currentRole === 'admin' || $currentRole === 'admin_it')
                <span class="text-[10px] font-bold text-indigo-700 bg-indigo-100 px-1.5 py-0.5 rounded">Aktif</span>
              @endif
            </button>
          </form>

          <!-- Staff Pemohon -->
          <form action="{{ route('role.switch') }}" method="POST">
            @csrf
            <input type="hidden" name="role" value="staff">
            <input type="hidden" name="email" value="sari@sirab.local">
            <button type="submit" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs transition-colors cursor-pointer text-left
              {{ ($currentRole === 'staff' || $currentRole === 'user') ? 'bg-blue-50 text-blue-900 font-semibold ring-1 ring-blue-200' : 'text-slate-700 hover:bg-slate-50' }}">
              <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                  <i class="fa-solid fa-file-pen text-xs"></i>
                </div>
                <div>
                  <div class="font-medium text-slate-800">Staff Pemohon RAB</div>
                  <div class="text-[10px] text-slate-400">sari@sirab.local &bull; Staf IT</div>
                </div>
              </div>
              @if($currentRole === 'staff' || $currentRole === 'user')
                <span class="text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">Aktif</span>
              @endif
            </button>
          </form>

          <!-- Finance Reviewer -->
          <form action="{{ route('role.switch') }}" method="POST">
            @csrf
            <input type="hidden" name="role" value="finance">
            <input type="hidden" name="email" value="finance@sirab.local">
            <button type="submit" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs transition-colors cursor-pointer text-left
              {{ $currentRole === 'finance' ? 'bg-emerald-50 text-emerald-900 font-semibold ring-1 ring-emerald-200' : 'text-slate-700 hover:bg-slate-50' }}">
              <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                  <i class="fa-solid fa-wallet text-xs"></i>
                </div>
                <div>
                  <div class="font-medium text-slate-800">Finance (Reviewer 1)</div>
                  <div class="text-[10px] text-slate-400">finance@sirab.local &bull; Bendahara</div>
                </div>
              </div>
              @if($currentRole === 'finance')
                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-100 px-1.5 py-0.5 rounded">Aktif</span>
              @endif
            </button>
          </form>

          <!-- Pimpinan Reviewer -->
          <form action="{{ route('role.switch') }}" method="POST">
            @csrf
            <input type="hidden" name="role" value="pimpinan">
            <input type="hidden" name="email" value="pimpinan@sirab.local">
            <button type="submit" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs transition-colors cursor-pointer text-left
              {{ $currentRole === 'pimpinan' ? 'bg-purple-50 text-purple-900 font-semibold ring-1 ring-purple-200' : 'text-slate-700 hover:bg-slate-50' }}">
              <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center shrink-0">
                  <i class="fa-solid fa-user-tie text-xs"></i>
                </div>
                <div>
                  <div class="font-medium text-slate-800">Pimpinan (Approval Final)</div>
                  <div class="text-[10px] text-slate-400">pimpinan@sirab.local &bull; Kepala Sekolah</div>
                </div>
              </div>
              @if($currentRole === 'pimpinan')
                <span class="text-[10px] font-bold text-purple-700 bg-purple-100 px-1.5 py-0.5 rounded">Aktif</span>
              @endif
            </button>
          </form>
        </div>

        <div class="border-t border-slate-100 pt-2 px-3 pb-1 text-center">
          <button type="button" 
                  @click="roleDropdown = false; roleModal = true" 
                  class="text-[11px] text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center gap-1.5 cursor-pointer py-1 px-2 rounded hover:bg-indigo-50 transition-colors w-full justify-center">
            <i class="fa-solid fa-users-gear text-xs"></i> 
            <span>Buka Modal Switch Role (Semua Akun)</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Notification Indicator Icon -->
    <div class="relative text-slate-500 hover:text-[#2b337c] transition-colors cursor-pointer text-base p-1">
      <i class="fa-regular fa-bell"></i>
      <div class="absolute 1.5 top-1 right-1 w-[7px] h-[7px] bg-red-500 rounded-full"></div>
    </div>

    <!-- User Profile & Info -->
    <div class="flex items-center gap-2 sm:gap-2.5 cursor-pointer group">
      <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold shrink-0
        @if($currentRole === 'admin' || $currentRole === 'admin_it') bg-indigo-600
        @elseif($currentRole === 'finance') bg-emerald-600
        @elseif($currentRole === 'pimpinan') bg-purple-600
        @else bg-blue-600 @endif">
        {{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->name ?? 'US', 0, 2)) }}
      </div>
      <div class="hidden sm:block text-left leading-tight">
        <h5 class="text-[13px] font-semibold text-slate-800 truncate max-w-[120px]">
          {{ Auth::user()->nama_lengkap ?? Auth::user()->name ?? 'Pengguna' }}
        </h5>
        <p class="text-[11px] text-slate-500 truncate max-w-[120px]">
          {{ Auth::user()->jabatan ?? Auth::user()->position ?? 'Staf' }} &bull; <span class="capitalize">{{ $currentRole }}</span>
        </p>
      </div>
    </div>
    
    <!-- Logout Form -->
    <form action="{{ route('logout') }}" method="POST" class="inline ml-1 border-l border-slate-200 pl-3 sm:pl-4">
      @csrf
      <button type="submit" class="text-slate-500 hover:text-red-500 transition-colors text-base cursor-pointer p-1" title="Keluar / Logout">
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
      </button>
    </form>
  </div>

  <!-- ROLE MODEL SWITCH MODAL DIALOG -->
  <div x-show="roleModal" 
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4"
       style="display: none;">
    
    <div @click.outside="roleModal = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border border-slate-200 overflow-hidden">
      
      <!-- Modal Header -->
      <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-xs">
            <i class="fa-solid fa-repeat text-sm"></i>
          </div>
          <div>
            <h3 class="text-base font-bold text-slate-800">Role & Model Switcher</h3>
            <p class="text-xs text-slate-500">Pilih akun pengguna di bawah ini untuk beralih hak akses dan peran</p>
          </div>
        </div>
        <button type="button" @click="roleModal = false" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer p-1">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="p-6">
        <div class="mb-4 flex items-center justify-between bg-indigo-50/70 border border-indigo-100 rounded-xl p-3 text-xs text-indigo-900">
          <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-indigo-600"></i>
            <span>Akun Aktif: <strong>{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</strong> ({{ strtoupper($currentRole) }})</span>
          </div>
          <span class="font-mono bg-white px-2 py-0.5 rounded border border-indigo-200 text-[11px]">
            Model: App\Models\{{ $currentModel }}
          </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[380px] overflow-y-auto pr-1">
          @forelse($allPengguna as $p)
            @php
              $isCurrent = (Auth::id() == $p->id_pengguna || Auth::user()->email === $p->email);
            @endphp
            <div class="border rounded-xl p-3.5 flex flex-col justify-between transition-all
              {{ $isCurrent ? 'border-indigo-500 bg-indigo-50/40 shadow-xs ring-1 ring-indigo-500' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50/50' }}">
              <div>
                <div class="flex items-center justify-between mb-1.5">
                  <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full
                    @if($p->role === 'admin' || $p->role === 'admin_it') bg-indigo-100 text-indigo-700
                    @elseif($p->role === 'finance') bg-emerald-100 text-emerald-700
                    @elseif($p->role === 'pimpinan') bg-purple-100 text-purple-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ $p->role === 'user' ? 'Staff' : ($p->role === 'admin' ? 'Admin IT' : $p->role) }}
                  </span>
                  @if($isCurrent)
                    <span class="text-[10px] font-semibold text-emerald-600 flex items-center gap-1">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sedang Aktif
                    </span>
                  @endif
                </div>

                <h4 class="text-[13px] font-bold text-slate-800 leading-snug">{{ $p->nama_lengkap }}</h4>
                <div class="text-[11px] text-slate-500 mt-0.5">{{ $p->jabatan }} &bull; {{ $p->divisi->nama_divisi ?? '-' }}</div>
                <div class="text-[11px] text-slate-400 font-mono mt-1">{{ $p->email }}</div>
              </div>

              <div class="mt-3 pt-2.5 border-t border-slate-100">
                @if($isCurrent)
                  <button type="button" disabled class="w-full py-1.5 px-3 bg-slate-100 text-slate-400 text-xs font-semibold rounded-lg cursor-not-allowed">
                    Akun Saat Ini
                  </button>
                @else
                  <form action="{{ route('role.switch') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $p->id_pengguna }}">
                    <input type="hidden" name="email" value="{{ $p->email }}">
                    <button type="submit" class="w-full py-1.5 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors cursor-pointer shadow-xs flex items-center justify-center gap-1.5">
                      <i class="fa-solid fa-arrow-right-to-bracket text-[10px]"></i> Beralih ke Akun Ini
                    </button>
                  </form>
                @endif
              </div>
            </div>
          @empty
            <div class="col-span-2 text-center py-6 text-slate-400 text-xs">
              Tidak ada data akun pengguna di database.
            </div>
          @endforelse
        </div>
      </div>

      <!-- Modal Footer -->
      <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500">
        <span>Gunakan fitur ini untuk simulasi pengujian alur persetujuan RAB multi-peran.</span>
        <button type="button" @click="roleModal = false" class="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-lg transition-colors cursor-pointer">
          Tutup
        </button>
      </div>
    </div>
  </div>
</nav>
