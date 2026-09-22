<!doctype html>
<html lang="id" class="h-full bg-[#f8fafc]">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@\
        ('title', 'SIRAB Kelompok-3 - Sistem Pengajuan & Persetujuan RAB')</title>

    <!-- Performance: DNS Prefetch & Preconnect for external assets -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>

    <!-- Fonts: Poppins & JetBrains Mono -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Tailwind CSS: Local Compiled Vite Asset (Instant Load) with CDN Fallback -->
    @if(file_exists(public_path('build/manifest.json')))
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
      <script src="https://cdn.tailwindcss.com"></script>
    @endif
    
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
    <!-- Top Progress Bar for Instant Feedback on Navigation & Submit -->
    <div id="sirabProgressBar" class="fixed top-0 left-0 right-0 h-1 bg-indigo-600 z-[9999] transition-all duration-300 pointer-events-none opacity-0" style="width: 0%;"></div>
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

    <!-- Performance: Instant Hover Prefetch & Submit Loading Handler -->
    <script>
      /*! instant.page v5.2.0 - MIT License */
      (function(){
        function prefetchLink(el) {
          if (!el || !el.href) return;
          if (el.origin !== location.origin) return;
          if (el.hasAttribute('download') || el.target && el.target !== '_self') return;
          if (el.href === location.href || el.href.includes('#') || el.href.startsWith('javascript:')) return;
          if (el.href.includes('logout') || el.getAttribute('data-no-instant') !== null) return;
          if (document.querySelector(`link[rel="prefetch"][href="${el.href}"]`)) return;
          
          const prefetcher = document.createElement('link');
          prefetcher.rel = 'prefetch';
          prefetcher.href = el.href;
          document.head.appendChild(prefetcher);
        }

        // Prefetch on mouse hover (>65ms) or mobile touchstart
        let hoverTimer = null;
        document.addEventListener('mouseover', function(e) {
          const a = e.target.closest('a');
          if (a) {
            hoverTimer = setTimeout(() => prefetchLink(a), 65);
          }
        }, { passive: true });

        document.addEventListener('mouseout', function(e) {
          if (hoverTimer) clearTimeout(hoverTimer);
        }, { passive: true });

        document.addEventListener('touchstart', function(e) {
          const a = e.target.closest('a');
          if (a) prefetchLink(a);
        }, { passive: true });
      })();

      // Top progress bar on link clicks
      document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href && link.origin === location.origin && !link.target && !link.hasAttribute('download') && !link.href.startsWith('javascript:') && !link.href.includes('#') && link.href !== location.href) {
          const bar = document.getElementById('sirabProgressBar');
          if (bar) {
            bar.style.opacity = '1';
            bar.style.width = '75%';
          }
        }
      });

      // Prevent double submit and show instant loading spinner
      document.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form || form.getAttribute('data-no-submit-lock') !== null) return;
        if (typeof form.checkValidity === 'function' && !form.checkValidity()) return;

        const bar = document.getElementById('sirabProgressBar');
        if (bar) {
          bar.style.opacity = '1';
          bar.style.width = '90%';
        }

        const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
        if (submitBtn && !submitBtn.disabled) {
          setTimeout(() => {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            if (submitBtn.tagName.toLowerCase() === 'button') {
              const origHtml = submitBtn.innerHTML;
              submitBtn.setAttribute('data-original-html', origHtml);
              submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg> Memproses...
              `;
            }
          }, 40);
        }
      });

      window.addEventListener('pageshow', function() {
        const bar = document.getElementById('sirabProgressBar');
        if (bar) {
          bar.style.width = '100%';
          setTimeout(() => {
            bar.style.opacity = '0';
            bar.style.width = '0%';
          }, 150);
        }
        document.querySelectorAll('button[type="submit"]').forEach(btn => {
          btn.disabled = false;
          btn.classList.remove('opacity-75', 'cursor-not-allowed');
          const orig = btn.getAttribute('data-original-html');
          if (orig) btn.innerHTML = orig;
        });
      });
    </script>

    @stack('scripts')
  </body>
</html>
