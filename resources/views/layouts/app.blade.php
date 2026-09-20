<!doctype html>
<html lang="id" class="h-full bg-[#f8fafc]">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@\
        ('title', 'SIRAB Kelompok-3 - Sistem Pengajuan & Persetujuan RAB')</title>

    <!-- Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
        '''''''''border-radius: 4px;
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
          @include('partials.topbar')
        @endif

        <!-- Alert / Flash Messages Component -->
        @include('partials.alert')

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
