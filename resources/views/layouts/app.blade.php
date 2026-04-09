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
    <footer>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
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
