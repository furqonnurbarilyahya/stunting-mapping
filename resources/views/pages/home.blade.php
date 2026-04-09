@extends('layouts.app')

@section('title', 'Beranda - Stunting Mapping')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .map-container {
        width: 100%;
        height: 500px;
        border-radius: 1rem;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
        border: 1px solid var(--border);
        margin: 3rem 0;
        z-index: 10;
        position: relative;
    }
    .leaflet-popup-content-wrapper {
        background: var(--surface);
        color: var(--text-primary);
        border: 1px solid var(--border);
        border-radius: 0.5rem;
    }
    .leaflet-popup-tip {
        background: var(--surface);
    }
</style>
@endsection

@section('content')
<section class="hero">
    <h1 class="hero-title">Sistem Pemetaan<br>Klaster Stunting</h1>
    <p class="hero-subtitle">Membantu mengelola, menganalisis, dan memvisualisasikan data wilayah stunting untuk pengambilan keputusan yang lebih akurat.</p>
    <div>
        <a href="#map" class="btn btn-primary">Mulai Eksplorasi</a>
    </div>
</section>

<!-- Map Section -->
<section id="map-section" style="margin-top: 2rem;">
    <h2 style="text-align: center; margin-bottom: 1rem;">Peta Persebaran Stunting</h2>
    <div id="map" class="map-container"></div>
</section>

<section class="features-grid">
    <div class="feature-card">
        <div class="feature-icon">📊</div>
        <h3 class="feature-title">Manajemen Data Wilayah</h3>
        <p class="feature-desc">Kelola data administrasi dan demografi dengan sistem yang terintegrasi dan akurat.</p>
    </div>
    
    <div class="feature-card">
        <div class="feature-icon">📈</div>
        <h3 class="feature-title">Analisis Klaster Stunting</h3>
        <p class="feature-desc">Identifikasi wilayah dengan tingkat stunting tinggi dan prioritas intervensi secara otomatis.</p>
    </div>
    
    <div class="feature-card">
        <div class="feature-icon">🔒</div>
        <h3 class="feature-title">Akses Aman & Terpusat</h3>
        <p class="feature-desc">Sistem yang aman dengan pengelolaan otorisasi pengguna secara menyeluruh.</p>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init map roughly centered on the dummy data
        const map = L.map('map').setView([-6.21, 106.82], 12);

        // Dark Theme Tile Layer
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        // Fetch Regions API
        fetch('/api/regions')
            .then(res => res.json())
            .then(response => {
                if(response.success && response.data) {
                    response.data.forEach(region => {
                        let hexColor = '#10b981'; // Green for Rendah
                        
                        if(region.cluster.toLowerCase() === 'tinggi') {
                            hexColor = '#ef4444'; // Red
                        } else if(region.cluster.toLowerCase() === 'sedang') {
                            hexColor = '#f59e0b'; // Orange
                        }

                        // Create circle marker
                        L.circleMarker([region.latitude, region.longitude], {
                            radius: 12,
                            color: hexColor,
                            weight: 2,
                            fillColor: hexColor,
                            fillOpacity: 0.6
                        })
                        .addTo(map)
                        .bindPopup(`<b>${region.name}</b><br>Klaster: <b>${region.cluster}</b>`);
                    });
                }
            })
            .catch(err => console.error('Error fetching regions:', err));
    });
</script>
@endsection
