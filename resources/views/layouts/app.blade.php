<!doctype html>
<html lang="id" class="h-full bg-[#f8fafc]">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SIRAB Kelompok-3 - Sistem Pengajuan & Persetujuan RAB')</title>
    
    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
      body {
        font-family: "Poppins", sans-serif;
        background-color: #f8fafc; /* slate-50 */
      }
      .font-mono-num {
        font-family: "JetBrains Mono", monospace;
      }
      /* Custom scrollbar for table */
      .table-container::-webkit-scrollbar {
        height: 8px;
        width: 8px;
      }
      .table-container::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 4px;
      }
      .table-container::-webkit-scrollbar-track {
        background-color: #f1f5f9;
      }
      input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 1px #6366f1;
      }
      /* Hilangkan spinner bawaan browser pada input volume agar angka tidak tertutup/terpotong */
      .input-volume::-webkit-outer-spin-button,
      .input-volume::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
      .input-volume {
        -moz-appearance: textfield;
        appearance: textfield;
        color: #0f172a !important; /* slate-900 */
      }
    </style>
    @stack('styles')
  </head>
  <body class="text-slate-800 antialiased min-h-full flex bg-[#f8fafc]">
    @if(Auth::check())
      <!-- Sidebar Nav (Off-canvas on mobile, sticky left column on desktop) -->
      @include('partials.sidebar')
    @endif

    <!-- Main Content Area -->
    <div class="flex-1 min-w-0 flex flex-col justify-between min-h-screen">
      <div>
        @if(Auth::check())
          @include('partials.navbar')
        @endif

        <!-- Flash Messages -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
          @if(session('success'))
            <div class="mb-4 flex items-center justify-between gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm">
              <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs sm:text-sm font-medium">{{ session('success') }}</span>
              </div>
              <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-sm">✕</button>
            </div>
          @endif

          @if(session('error'))
            <div class="mb-4 flex items-center justify-between gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl shadow-sm">
              <div class="flex items-center gap-2.5">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs sm:text-sm font-medium">{{ session('error') }}</span>
              </div>
              <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 text-sm">✕</button>
            </div>
          @endif

          @if($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl shadow-sm">
              <div class="font-semibold text-xs sm:text-sm mb-1 flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Mohon periksa kembali input formulir Anda:
              </div>
              <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-700 pl-6">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
        </div>

        <!-- Dynamic Content -->
        <main class="@yield('main_class', 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6')">
          @yield('content')
        </main>
      </div>
    </div>

    <!-- Modals Stack -->
    @stack('modals')

    <!-- Responsive Sidebar Toggle Script -->
    <script>
      function toggleSidebar() {
        const sidebar = document.getElementById('mainSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        if (!sidebar || !backdrop) return;

        if (sidebar.classList.contains('-translate-x-full')) {
          // Open sidebar
          sidebar.classList.remove('-translate-x-full');
          backdrop.classList.remove('hidden');
        } else {
          // Close sidebar
          sidebar.classList.add('-translate-x-full');
          backdrop.classList.add('hidden');
        }
      }
    </script>

    @stack('scripts')
  </body>
</html>
