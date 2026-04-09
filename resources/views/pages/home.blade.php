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
    .map-controls {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .select-control {
        padding: 0.6rem 1rem;
        border-radius: 9999px;
        border: 1px solid var(--border);
        background: var(--surface);
        color: var(--text-primary);
        font-family: inherit;
        outline: none;
        cursor: pointer;
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
    <div class="map-controls">
        <select id="region-dropdown" class="select-control">
            <option value="">-- Pilih Wilayah --</option>
        </select>
        <button id="gps-button" class="btn btn-primary">📍 Lokasi Saya</button>
    </div>
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
                    const dropdown = document.getElementById('region-dropdown');
                    
                    response.data.forEach(region => {
                        let hexColor = '#94a3b8'; // Gray default
                        let clusterName = 'Tidak Terklasifikasi';
                        
                        if (region.lisa_cluster === 1) {
                            hexColor = '#ef4444'; // Merah
                            clusterName = 'Hot Spot';
                        } else if (region.lisa_cluster === 2) {
                            hexColor = '#f59e0b'; // Kuning (Amber)
                            clusterName = 'Waspada (Outlier)';
                        } else if (region.lisa_cluster === 3) {
                            hexColor = '#10b981'; // Hijau
                            clusterName = 'Cold Spot';
                        } else if (region.lisa_cluster === 4) {
                            hexColor = '#ef4444'; // Merah
                            clusterName = 'Sangat Rawan (Outlier)';
                        }

                        // Create circle marker
                        const marker = L.circleMarker([region.latitude, region.longitude], {
                            radius: 12,
                            color: hexColor,
                            weight: 2,
                            fillColor: hexColor,
                            fillOpacity: 0.6
                        })
                        .addTo(map)
                        .bindPopup(`<b>${region['kab/kota']}</b><br>Klaster: <b>${clusterName}</b>`);
                        
                        // Populate dropdown
                        const option = document.createElement('option');
                        option.value = JSON.stringify({lat: region.latitude, lng: region.longitude});
                        option.textContent = region['kab/kota'];
                        dropdown.appendChild(option);
                    });

                    // Dropdown interaction
                    dropdown.addEventListener('change', function(e) {
                        if(this.value) {
                            const coords = JSON.parse(this.value);
                            map.flyTo([coords.lat, coords.lng], 14);
                        } else {
                            map.setView([-6.21, 106.82], 12); // Reset view
                        }
                    });
                }
            })
            .catch(err => console.error('Error fetching regions:', err));

        // Map click interaction
        let clickMarker = null;
        map.on('click', function(e) {
            if(clickMarker) map.removeLayer(clickMarker);
            clickMarker = L.marker(e.latlng).addTo(map)
                .bindPopup("Koordinat dipilih:<br>" + e.latlng.lat.toFixed(5) + ", " + e.latlng.lng.toFixed(5))
                .openPopup();
        });

        // GPS Location functionality
        document.getElementById('gps-button').addEventListener('click', function() {
            const btn = this;
            if ("geolocation" in navigator) {
                const originalText = btn.innerHTML;
                btn.innerHTML = "⏳ Melacak...";
                btn.disabled = true;
                
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.flyTo([lat, lng], 14);
                    
                    if(clickMarker) map.removeLayer(clickMarker);
                    clickMarker = L.marker([lat, lng]).addTo(map)
                        .bindPopup("Lokasi Anda Saat Ini")
                        .openPopup();
                        
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, function(error) {
                    alert('Gagal mendapatkan lokasi. Pastikan izin GPS diberikan.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            } else {
                alert("Browser Anda tidak mendukung Geolocation.");
            }
        });
    });
</script>
@endsection
