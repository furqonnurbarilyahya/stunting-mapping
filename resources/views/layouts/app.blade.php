<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Stunting Mapping'))</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        html { scroll-behavior: smooth; }
        .app-footer {
            background-color: #0f172a;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding: 4rem 5% 1rem;
            color: #94a3b8;
            margin-top: 4rem;
        }
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 3rem;
        }
        .footer-brand h3 {
            color: #f8fafc;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-family: 'Outfit', sans-serif;
        }
        .footer-brand p {
            line-height: 1.6;
            font-size: 0.95rem;
        }
        .footer-links h4, .footer-contact h4 {
            color: #f8fafc;
            margin-bottom: 1.2rem;
            font-size: 1.1rem;
        }
        .footer-links ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-links li {
            margin-bottom: 0.8rem;
        }
        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .footer-links a:hover {
            color: #60a5fa;
        }
        .footer-contact p {
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            text-align: center;
            font-size: 0.85rem;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="navbar-brand">
                <span>Stunting Mapping</span>
            </a>
            
            <button class="navbar-toggle" id="navbar-toggle" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <div class="navbar-menu" id="navbar-menu">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
                <a href="#why-section" class="nav-link">Tentang Kami</a>
                <a href="#ranking-section" class="nav-link">Statistik</a>
                <a href="{{ url('/import') }}" class="nav-link">Import Data</a>
            </div>
        </div>
    </nav>

    <!-- Mobile overlay (outside navbar-container so it can cover full screen) -->
    <div class="navbar-overlay" id="navbar-overlay"></div>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="app-footer">
        <div class="footer-content">
            <div class="footer-brand">
                <h3>Stunting Mapping</h3>
                <p>Sistem Informasi Geografis untuk memetakan, menganalisis, dan mendukung keputusan intervensi stunting di wilayah Provinsi Jawa Timur secara tepat sasaran.</p>
            </div>
            <div class="footer-links">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="#why-section">Tentang Kami</a></li>
                    <li><a href="#ranking-section">Data Statistik</a></li>
                    <li><a href="#map-section">Peta Persebaran</a></li>
                </ul>
            </div>
            <div class="footer-contact">
                <h4>Instansi</h4>
                <p>Pemerintah Provinsi Jawa Timur</p>
                <p>Dinas Kesehatan & BAPPEDA</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>
    @yield('scripts')

    <!-- Mobile Menu Toggle -->
    <script>
    (function() {
        const toggle = document.getElementById('navbar-toggle');
        const menu = document.getElementById('navbar-menu');
        const overlay = document.getElementById('navbar-overlay');

        function closeMenu() {
            menu.classList.remove('active');
            overlay.classList.remove('active');
        }

        toggle.addEventListener('click', function() {
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', closeMenu);

        // Close menu when a nav link is clicked
        menu.querySelectorAll('.nav-link').forEach(function(link) {
            link.addEventListener('click', closeMenu);
        });
    })();
    </script>
</body>
</html>
