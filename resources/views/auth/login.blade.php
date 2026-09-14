<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Sistem Informasi RAB</title>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Import Font dari Google -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <!-- Font Awesome untuk Icon -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Tailwind CSS CDN (No Preflight) -->
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
            --text-dark: #000000;
            --text-gray: #444444;
            --btn-gray: #6d757f;
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
            min-height: 120vh;
            position: relative;
            background: var(--bg-light);
        }

        .hero-left {
            flex: 0 0 68%;
            padding: 40px 80px;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .hero-right {
            flex: 0 0 32%;
            background-color: var(--primary-purple);
            padding: 40px;
            position: relative;
        }

        .header-left {
            margin-bottom: auto;
        }

        .logo-wrap {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* --- LOGO IMAGE --- */
        .logo-img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            object-fit: contain;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 600;
            color: #4b5563;
        }

        .header-right {
            text-align: right;
            margin-right: 20px;
        }

        .menu-icon {
            color: white;
            font-size: 24px;
            cursor: pointer;
        }

        /* --- BAGIAN KONTEN TEKS --- */
        .main-content {
            margin-top: -100px;
            margin-bottom: auto;
            position: relative;
            max-width: 650px;
        }

        .bg-text {
            position: absolute;
            top: -65px;
            left: -10px;
            font-size: clamp(170px, 23vw, 250px);
            font-weight: 800;
            color: #aeb1fa;
            z-index: 1;
            line-height: 1;
            letter-spacing: -2px;
        }

        .content-inner {
            position: relative;
            z-index: 2;
        }

        .content-inner h1 {
            font-size: 56px;
            font-weight: 800;
            color: var(--text-dark);
            line-height: 1.15;
            margin-bottom: 25px;
        }

        .content-inner p {
            font-size: 18px;
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 35px;
            max-width: 95%;
        }

        .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background-color: var(--btn-gray);
            color: white;
            padding: 14px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #555c65;
        }

        /* --- DROPDOWN CUSTOM STYLES --- */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: var(--bg-light);
            min-width: 250px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.1);
            z-index: 20;
            border-radius: 8px;
            margin-top: 10px;
            overflow: hidden;
            border: 1px solid #eee;
        }

        .dropdown-content a {
            color: var(--text-gray);
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 14px;
            font-weight: 500;
            border-bottom: 1px solid #f1f1f1;
            transition: all 0.2s;
            cursor: pointer;
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        .dropdown-content a:hover {
            background-color: var(--btn-gray);
            color: white;
        }

        .dropdown-content.show {
            display: block;
        }

        /* --- GRAFIS & KLASE --- */
        .hero-graphics {
            position: absolute;
            top: 45%;
            left: 78%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            pointer-events: none;
            z-index: 10;
        }

        .mask {
            position: absolute;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            background-color: #e2e8f0;
        }

        .mask-1 {
            width: 220px;
            height: 240px;
            top: 80px;
            left: 20px;
            border-radius: 40px 10px 100px 10px;
        }

        .mask-2 {
            width: 140px;
            height: 190px;
            top: 130px;
            left: 250px;
            border-radius: 80px 80px 15px 15px;
        }

        .mask-3 {
            width: 160px;
            height: 160px;
            top: 340px;
            left: 50px;
            border-radius: 50%;
        }

        .mask-4 {
            width: 210px;
            height: 260px;
            top: 290px;
            left: 215px;
            border-radius: 60px 60px 15px 60px;
        }

        /* Elemen Dekoratif Mengambang */
        .float {
            position: absolute;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .dash {
            width: 25px;
            height: 4px;
            border-radius: 2px;
        }

        .pill {
            width: 45px;
            height: 14px;
            border-radius: 7px;
        }

        .dot-1 {
            background-color: #00bcd4;
            top: 290px;
            left: -10px;
        }

        .dot-2 {
            background-color: #ff9800;
            top: 340px;
            left: -30px;
        }

        .dot-3 {
            background-color: #ffc107;
            bottom: 120px;
            left: 480px;
        }

        .dash-1 {
            background-color: #00bcd4;
            top: 70px;
            left: 400px;
        }

        .dash-2 {
            background-color: #8bc34a;
            top: 160px;
            left: 420px;
        }

        .pill-1 {
            background-color: #ffc107;
            top: 90px;
            left: 270px;
            width: 35px;
            height: 10px;
        }

        .pill-2 {
            background-color: #ffc107;
            bottom: 130px;
            left: -40px;
        }

        .pill-3 {
            background-color: #8bc34a;
            bottom: 40px;
            left: 130px;
            width: 35px;
            height: 10px;
        }

        /* --- FOOTER SECTION --- */
        .footer-top {
            background-color: var(--primary-blue);
            padding: 100px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }

        .footer-top-left h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #f1f5f9;
        }

        .footer-top-left h2 {
            font-size: 56px;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -1px;
        }

        .footer-top-right {
            text-align: right;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .footer-logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .footer-logo .seal-inner {
            width: 100px;
            height: 100px;
            background-color: #1e3a8a;
            border-radius: 50%;
            color: #fff;
            font-size: 22px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-top-right p {
            font-size: 15px;
            line-height: 1.6;
            font-weight: 500;
        }

        .footer-bottom {
            background-color: #fff;
            padding: 25px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .copyright {
            font-size: 11px;
            color: #000;
            font-weight: 700;
        }

        .footer-links {
            display: flex;
            align-items: center;
            gap: 40px;
        }

        .links {
            display: flex;
            gap: 20px;
        }

        .links a {
            text-decoration: none;
            color: #000;
            font-size: 12px;
            font-weight: 700;
        }

        .socials {
            display: flex;
            gap: 15px;
        }

        .socials a {
            color: #000;
            font-size: 16px;
        }

        @media (max-width: 1024px) {
            .hero {
                flex-direction: column;
            }

            .hero-left {
                flex: 1;
                width: 100%;
                padding: 40px 30px;
            }

            .hero-right,
            .hero-graphics {
                display: none;
            }

            .footer-top {
                flex-direction: column;
                align-items: flex-start;
                padding: 50px 30px;
                gap: 40px;
            }

            .footer-top-right {
                align-items: flex-start;
                text-align: left;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 20px;
                padding: 25px 30px;
                text-align: center;
            }
        }
    </style>
</head>

<body x-data="{ overlayOpen: false }">
    <!-- Hidden Form untuk Login Otomatis -->
    <form id="hiddenLoginForm" action="{{ route('login.post') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="email" id="hiddenEmail">
        <input type="hidden" name="password" id="hiddenPassword">
    </form>

    <!-- HEADER UTAMA ABSOLUTE -->
    <header class="absolute top-0 left-0 w-full flex items-center justify-between px-10 py-8 md:px-16 md:py-10 z-[60] bg-transparent pointer-events-none">
        <div class="flex items-center gap-4 pointer-events-auto">
            <img src="{{ asset('images/logo-sdn3.png') }}" alt="Logo" class="h-14 w-14 object-contain">
            <h1 class="text-2xl md:text-3xl font-semibold text-gray-800" style="margin:0; font-family: 'Poppins', sans-serif;">SD Negeri Sidokare 3</h1>
        </div>

        <div class="pointer-events-auto">
            <button @click="overlayOpen = true" class="text-gray-800 lg:text-white text-3xl hover:opacity-80 transition-opacity" style="background:transparent; border:none; cursor:pointer;">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- OVERLAY MENU -->
    <div x-show="overlayOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-y-full"
        x-transition:enter-end="translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-y-0"
        x-transition:leave-end="-translate-y-full"
        class="fixed inset-0 z-[70] bg-[#8b8df7] flex items-center justify-center"
        style="display: none; font-family: 'Poppins', sans-serif;">

        <!-- HEADER OVERLAY (IDENTIK) -->
        <header class="absolute top-0 left-0 w-full flex items-center justify-between px-10 py-8 md:px-16 md:py-10 z-[80] bg-transparent">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo-sdn3.png') }}" alt="Logo" class="h-14 w-14 object-contain">
                <h1 class="text-2xl md:text-3xl font-semibold text-white" style="margin:0;">SD Negeri Sidokare 3</h1>
            </div>

            <div>
                <button @click="overlayOpen = false" class="text-white text-4xl hover:opacity-80 transition-opacity font-light leading-none" style="background:transparent; border:none; cursor:pointer;">
                    &times;
                </button>
            </div>
        </header>

        <!-- CONTENT OVERLAY -->
        <div class="w-full max-w-7xl mx-auto px-10 md:px-16 grid grid-cols-1 md:grid-cols-2 gap-16 mt-20 text-left">
            <!-- Kiri -->
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-4" style="margin-top:0;">Apa itu Gate SDN Sidokare 3 ?</h2>
                <p class="text-white/90 text-sm md:text-base leading-relaxed" style="max-width: 500px;">
                    Gate SDN Sidokare 3 adalah sebuah portal berbasis Single Sign On (SSO) yang berfungsi sebagai pintu masuk ke dalam sistem informasi layanan terpadu yang telah kami kembangkan untuk memberikan kemudahan akses akan informasi dan layanan bagi seluruh pengguna layanan Sistem Informasi SD Negeri Sidokare 3.
                </p>
            </div>

            <!-- Kanan -->
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-4" style="margin-top:0;">Akses</h2>
                <div class="flex flex-wrap gap-4 mb-10">
                    <button @click="overlayOpen = false" class="bg-[#4ade80] hover:bg-[#22c55e] text-white font-semibold py-2 px-6 rounded shadow-sm transition-colors" style="border:none; cursor:pointer;">Login</button>
                    <button class="bg-[#4ade80] hover:bg-[#22c55e] text-white font-semibold py-2 px-6 rounded shadow-sm transition-colors" style="border:none; cursor:pointer;">Lupa Kata Sandi</button>
                    <button class="bg-[#4ade80] hover:bg-[#22c55e] text-white font-semibold py-2 px-6 rounded shadow-sm transition-colors" style="border:none; cursor:pointer;">Bantuan</button>
                </div>

                <p class="text-white/70 text-xs italic">
                    © TIK SD Negeri Sidokare 3 2026. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    <section class="hero">
        <div class="hero-left">
            <header class="header-left" style="opacity: 0; pointer-events: none;">
                <div class="logo-wrap">
                    <img src="{{ asset('images/logo-sdn3.png') }}" alt="Logo SDN Sidokare 3" class="logo-img">
                    <span class="logo-text">SD Negeri Sidokare 3</span>
                </div>
            </header>

            <div class="main-content">
                <div class="bg-text">GATE</div>
                <div class="content-inner">
                    <h1>Pengajuan Anggaran<br />Dana Kegiatan</h1>
                    <p>
                        Portal berbasis Single Sign On (SSO) yang berfungsi
                        sebagai pintu masuk ke dalam sistem informasi
                        layanan terpadu.
                    </p>

                    <!-- Dropdown Button -->
                    <div class="dropdown">
                        <button onclick="toggleDropdown(event)" class="btn-login">
                            Login / Masuk <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <div id="loginDropdown" class="dropdown-content">
                            <a onclick="loginAs('arif@sirab.local')">Login sebagai Administrator</a>
                            <a onclick="loginAs('sari@sirab.local')">Login sebagai Staff Pemohon</a>
                            <a onclick="loginAs('finance@sirab.local')">Login sebagai Finance</a>
                            <a onclick="loginAs('pimpinan@sirab.local')">Login sebagai Pimpinan</a>
                        </div>
                    </div>

                    <!-- Menampilkan Error jika ada -->
                    @if ($errors->any())
                    <div style="color: #e3342f; margin-top: 15px; font-size: 13px; font-weight: 500;">
                        @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                        @endforeach
                    </div>
                    @endif

                </div>
            </div>
        </div>

        <div class="hero-right">
            <header class="header-right" style="opacity: 0; pointer-events: none;">
                <i class="fa-solid fa-bars menu-icon"></i>
            </header>
        </div>

        <div class="hero-graphics">
            <!-- 4 GAMBAR SUDAH DIGANTI DARI FOLDER UI FIGMA -->
            <img
                src="{{ asset('images/FotoKegiatan1.jpeg') }}"
                alt="Foto Kegiatan 1"
                class="mask mask-1" />
            <img
                src="{{ asset('images/FotoKegiatan2.jpeg') }}"
                alt="Foto Kegiatan 2"
                class="mask mask-2" />
            <img
                src="{{ asset('images/FotoKegiatan3.jpeg') }}"
                alt="Foto Kegiatan 3"
                class="mask mask-3" />
            <img
                src="{{ asset('images/FotoKegiatan4.jpeg') }}"
                alt="Foto Kegiatan 4"
                class="mask mask-4" />

            <!-- Elemen Geometris -->
            <div class="float dot dot-1"></div>
            <div class="float dot dot-2"></div>
            <div class="float dot dot-3"></div>
            <div class="float dash dash-1"></div>
            <div class="float dash dash-2"></div>
            <div class="float pill pill-1"></div>
            <div class="float pill pill-2"></div>
            <div class="float pill pill-3"></div>
        </div>
    </section>

    <footer>
        <div class="footer-top">
            <div class="footer-top-left">
                <h4>SDN Sidokare 3</h4>
                <h2>Pengajuan anggaran kegiatan<br />SD Negeri Sidokare 3</h2>
            </div>
            <div class="footer-top-right">
                <div class="footer-logo">
                    <img src="{{ asset('images/logo-sdn3.png') }}" alt="Logo SDN Sidokare 3" style="width:100%; height:100%; object-fit:contain;">
                </div>
                <p>
                    Cangkring, Sidokare, Kec. Sidoarjo, Kabupaten Sidoarjo<br />Telepon: (031) 8965532
                    Jawa Timur 61214, Indonesia
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="copyright">
                © TIK SDN Sidokare 4 2026. All rights reserved.
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

    <!-- Script untuk Logika Dropdown dan Login -->
    <script>
        function toggleDropdown(event) {
            event.stopPropagation();
            document.getElementById("loginDropdown").classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.btn-login') && !event.target.closest('.btn-login')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }

        function loginAs(email) {
            document.getElementById('hiddenEmail').value = email;
            document.getElementById('hiddenPassword').value = 'password';
            document.getElementById('hiddenLoginForm').submit();
        }
    </script>
</body>

</html>