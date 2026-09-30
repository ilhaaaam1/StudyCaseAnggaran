<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Sistem Informasi RAB</title>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            corePlugins: {
                preflight: false,
            }
        }
    </script>

    <style>
        :root {
            --primary-purple: #8b8df7;
            --primary-blue: #2e358b;
            --text-dark: #1f2937;
            --text-gray: #4b5563;
            --btn-gray: #4b5563;
            --bg-light: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            overflow-x: hidden;
            background-color: var(--bg-light);
        }

        /* --- HERO SECTION --- */
        .hero {
            display: flex;
            min-height: 100vh;
            position: relative;
            background: var(--bg-light);
        }

        .hero-left {
            flex: 0 0 65%;
            padding: 140px 80px 60px 80px; /* Padding atas besar agar tidak mentok header */
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .hero-right {
            flex: 0 0 35%;
            background-color: var(--primary-purple);
            position: relative;
        }

        /* --- MAIN CONTENT LAYOUT FIX --- */
        .main-content {
            position: relative;
            max-width: 620px;
            z-index: 10;
        }

        .bg-text {
            position: absolute;
            top: -90px;
            left: -20px;
            font-size: clamp(120px, 16vw, 190px);
            font-weight: 900;
            color: #8b8df7;
            opacity: 0.18; /* Transparansi agar tidak mengganggu bacaan */
            z-index: 1;
            line-height: 1;
            letter-spacing: -2px;
            user-select: none;
            pointer-events: none;
        }

        .content-inner {
            position: relative;
            z-index: 2;
        }

        .content-inner h1 {
            font-size: 48px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .content-inner p {
            font-size: 16px;
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 30px;
            max-width: 90%;
        }

        .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background-color: var(--btn-gray);
            color: white;
            padding: 14px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-login:hover {
            background-color: #374151;
            transform: translateY(-2px);
        }

        /* --- HERO GRAPHICS --- */
        .hero-graphics {
            position: absolute;
            top: 50%;
            left: 75%;
            transform: translate(-50%, -50%);
            width: 550px;
            height: 550px;
            pointer-events: none;
            z-index: 20;
        }

        .mask {
            position: absolute;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            background-color: #e2e8f0;
        }

        .mask-1 {
            width: 200px;
            height: 220px;
            top: 60px;
            left: 20px;
            border-radius: 40px 10px 90px 10px;
        }

        .mask-2 {
            width: 130px;
            height: 170px;
            top: 110px;
            left: 235px;
            border-radius: 70px 70px 15px 15px;
        }

        .mask-3 {
            width: 150px;
            height: 150px;
            top: 300px;
            left: 50px;
            border-radius: 50%;
        }

        .mask-4 {
            width: 190px;
            height: 240px;
            top: 260px;
            left: 210px;
            border-radius: 50px 50px 15px 50px;
        }

        /* --- FOOTER --- */
        .footer-top {
            background-color: var(--primary-blue);
            padding: 80px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .footer-top-left h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #cbd5e1;
        }

        .footer-top-left h2 {
            font-size: 42px;
            font-weight: 800;
            line-height: 1.2;
        }

        .footer-top-right {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            font-size: 14px;
            color: #e2e8f0;
        }

        .footer-logo {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 15px;
            background: white;
            padding: 5px;
        }

        .footer-bottom {
            background-color: #fff;
            padding: 20px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f5f9;
        }

        .copyright {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
        }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .links {
            display: flex;
            gap: 20px;
        }

        .links a {
            text-decoration: none;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
        }

        .socials {
            display: flex;
            gap: 15px;
        }

        .socials a {
            color: #475569;
            font-size: 15px;
        }

        @media (max-width: 1024px) {
            .hero { flex-direction: column; }
            .hero-left { width: 100%; padding: 120px 30px 60px 30px; }
            .hero-right, .hero-graphics { display: none; }
            .footer-top { flex-direction: column; align-items: flex-start; padding: 40px 30px; gap: 30px; }
            .footer-top-right { align-items: flex-start; text-align: left; }
            .footer-bottom { flex-direction: column; gap: 15px; padding: 20px 30px; text-align: center; }
        }
    </style>
</head>

<body x-data="{ 
    overlayOpen: false, 
    loginModalOpen: {{ $errors->any() ? 'true' : 'false' }},
    selectedRole: '',
    email: '',
    password: '',
    setRolePreset(role) {
        this.selectedRole = role;
        if (role === 'pimpinan') {
            this.email = 'pimpinan@test.com';
            this.password = 'password';
        } else if (role === 'finance') {
            this.email = 'finance@test.com';
            this.password = 'password';
        } else if (role === 'admin') {
            this.email = 'admin@test.com';
            this.password = 'password';
        } else if (role === 'user') {
            this.email = 'staff@test.com';
            this.password = 'password';
        } else {
            this.email = '';
            this.password = '';
        }
    }
}">

    @php
        $logoPath = \App\Models\Setting::getSetting('app_logo');
        $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('images/logo-sdn3.png');
        $appName = \App\Models\Setting::getSetting('app_name', 'SD Negeri Sidokare 3');
    @endphp

    <!-- HEADER UTAMA -->
    <header class="absolute top-0 left-0 w-full flex items-center justify-between px-8 py-6 md:px-16 md:py-8 z-[50] bg-transparent">
        <div class="flex items-center gap-4">
            <img src="{{ $logoUrl }}" alt="Logo" class="h-12 w-12 object-contain">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800" style="margin:0;">{{ $appName }}</h1>
        </div>

        <div>
            <button @click="overlayOpen = true" class="text-gray-800 lg:text-white text-2xl hover:opacity-80 transition-opacity bg-transparent border-none cursor-pointer">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- OVERLAY MENU SIDEBAR -->
    <div x-show="overlayOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="-translate-y-full"
        class="fixed inset-0 z-[70] bg-[#8b8df7] flex items-center justify-center"
        style="display: none;">

        <header class="absolute top-0 left-0 w-full flex items-center justify-between px-8 py-6 md:px-16 md:py-8 z-[80]">
            <div class="flex items-center gap-4">
                <img src="{{ $logoUrl }}" alt="Logo" class="h-12 w-12 object-contain">
                <h1 class="text-xl md:text-2xl font-bold text-white" style="margin:0;">{{ $appName }}</h1>
            </div>

            <div>
                <button @click="overlayOpen = false" class="text-white text-3xl hover:opacity-80 transition-opacity font-light leading-none bg-transparent border-none cursor-pointer">
                    &times;
                </button>
            </div>
        </header>

        <div class="w-full max-w-7xl mx-auto px-8 md:px-16 grid grid-cols-1 md:grid-cols-2 gap-12 mt-16 text-left">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-4" style="margin-top:0;">Apa itu Gate SDN Sidokare 3 ?</h2>
                <p class="text-white/90 text-sm md:text-base leading-relaxed" style="max-width: 500px;">
                    Gate SDN Sidokare 3 adalah sebuah portal berbasis Single Sign On (SSO) yang berfungsi sebagai pintu masuk ke dalam sistem informasi layanan terpadu.
                </p>
            </div>

            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-4" style="margin-top:0;">Akses</h2>
                <div class="flex flex-wrap gap-3 mb-8">
                    <button @click="overlayOpen = false; loginModalOpen = true" class="bg-[#4ade80] hover:bg-[#22c55e] text-white font-semibold py-2 px-5 rounded shadow-sm border-none cursor-pointer">Login</button>
                    <button class="bg-[#4ade80] hover:bg-[#22c55e] text-white font-semibold py-2 px-5 rounded shadow-sm border-none cursor-pointer">Lupa Kata Sandi</button>
                    <button class="bg-[#4ade80] hover:bg-[#22c55e] text-white font-semibold py-2 px-5 rounded shadow-sm border-none cursor-pointer">Bantuan</button>
                </div>

                <p class="text-white/70 text-xs italic">
                    © TIK SD Negeri Sidokare 3 2026. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-left">
            <div class="main-content">
                <div class="bg-text">GATE</div>
                <div class="content-inner">
                    <h1>Pengajuan Anggaran<br />Dana Kegiatan</h1>
                    <p>
                        Portal berbasis Single Sign On (SSO) yang berfungsi
                        sebagai pintu masuk ke dalam sistem informasi
                        layanan terpadu.
                    </p>

                    <!-- Tombol Login Dropdown -->
                    <div x-data="{ dropdownOpen: false }" class="relative inline-block text-left mt-2">
                        <button @click="dropdownOpen = !dropdownOpen" @click.outside="dropdownOpen = false" class="btn-login">
                            Login / Masuk <i class="fa-solid fa-chevron-down ml-1 text-sm"></i>
                        </button>

                        <div x-show="dropdownOpen"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute left-0 mt-3 w-64 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-[60]"
                             style="display: none;">
                            <div class="py-1">
                                <form method="POST" action="{{ route('login.post') }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="password" value="password">
                                    <input type="hidden" name="email" value="admin@test.com">
                                    <button type="submit" class="w-full text-left block px-5 py-3.5 text-sm font-medium text-gray-700 hover:bg-[#6b7280] hover:text-white border-b border-gray-100 transition-colors border-none cursor-pointer bg-white">
                                        Login sebagai Administrator
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('login.post') }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="password" value="password">
                                    <input type="hidden" name="email" value="staff@test.com">
                                    <button type="submit" class="w-full text-left block px-5 py-3.5 text-sm font-medium text-gray-700 hover:bg-[#6b7280] hover:text-white border-b border-gray-100 transition-colors border-none cursor-pointer bg-white">
                                        Login sebagai Staff Pemohon
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('login.post') }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="password" value="password">
                                    <input type="hidden" name="email" value="finance@test.com">
                                    <button type="submit" class="w-full text-left block px-5 py-3.5 text-sm font-medium text-gray-700 hover:bg-[#6b7280] hover:text-white border-b border-gray-100 transition-colors border-none cursor-pointer bg-white">
                                        Login sebagai Finance
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('login.post') }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="password" value="password">
                                    <input type="hidden" name="email" value="pimpinan@test.com">
                                    <button type="submit" class="w-full text-left block px-5 py-3.5 text-sm font-medium text-gray-700 hover:bg-[#6b7280] hover:text-white transition-colors border-none cursor-pointer bg-white rounded-b-md">
                                        Login sebagai Pimpinan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hero-right"></div>

        <div class="hero-graphics">
            <img src="{{ asset('images/FotoKegiatan1.jpeg') }}" alt="Foto 1" class="mask mask-1" />
            <img src="{{ asset('images/FotoKegiatan2.jpeg') }}" alt="Foto 2" class="mask mask-2" />
            <img src="{{ asset('images/FotoKegiatan3.jpeg') }}" alt="Foto 3" class="mask mask-3" />
            <img src="{{ asset('images/FotoKegiatan4.jpeg') }}" alt="Foto 4" class="mask mask-4" />
        </div>
    </section>

    <!-- MODAL POPUP FORM LOGIN -->
    <div x-show="loginModalOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4"
         style="display: none;">

        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 md:p-8 relative">
            <!-- Tombol Close Modal -->
            <button @click="loginModalOpen = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-2xl font-bold bg-transparent border-none cursor-pointer">
                &times;
            </button>

            <!-- Title -->
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Login Sistem RAB</h3>
                <p class="text-xs text-gray-500 mt-1">Pilih Jabatan / Role atau Masukkan Akun Anda</p>
            </div>

            <!-- Pesan Error -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded text-xs">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Dropdown Pilih Role -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Pilih Jabatan / Role (Opsional)</label>
                    <select x-model="selectedRole" @change="setRolePreset($event.target.value)" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8b8df7]">
                        <option value="">-- Masukkan Manual --</option>
                        <option value="pimpinan">Pimpinan</option>
                        <option value="finance">Finance</option>
                        <option value="admin">Admin IT</option>
                        <option value="user">User</option>
                    </select>
                </div>

                <!-- Input Email -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Email</label>
                    <input type="email" name="email" x-model="email" required placeholder="nama@domain.com" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8b8df7]">
                </div>

                <!-- Input Password -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Password</label>
                    <input type="password" name="password" x-model="password" required placeholder="••••••••" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#8b8df7]">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-[#2e358b] hover:bg-[#1e2360] text-white font-semibold rounded-lg text-sm shadow transition-colors border-none cursor-pointer mt-2">
                    Masuk ke Sistem
                </button>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer-top">
            <div class="footer-top-left">
                <h4>SDN Sidokare 3</h4>
                <h2>Pengajuan anggaran kegiatan<br />SD Negeri Sidokare 3</h2>
            </div>
            <div class="footer-top-right">
                <div class="footer-logo">
                    <img src="{{ $logoUrl }}" alt="Logo Instansi" style="width:100%; height:100%; object-fit:contain;">
                </div>
                <p>
                    Cangkring, Sidokare, Kec. Sidoarjo, Kabupaten Sidoarjo<br />Telepon: (031) 8965532<br />
                    Jawa Timur 61214, Indonesia
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="copyright">
                © TIK SDN Sidokare 3 2026. All rights reserved.
            </div>
            <div class="footer-links">
                <div class="links">
                    <a href="#">Official Site</a>
                    <a href="#">Support & Dokumentasi</a>
                </div>
                <div class="socials">
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>