@extends('layouts.app')

@section('title', 'Beranda - Stunting Mapping')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .map-layout {
        display: flex;
        gap: 1.5rem;
        align-items: stretch;
    }
    .map-container {
        flex: 2;
        width: 100%;
        height: 500px;
        border-radius: 1rem;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
        border: 1px solid var(--border);
        z-index: 10;
        position: relative;
    }
    .detail-panel {
        flex: 1;
        background: var(--surface);
        border-radius: 1rem;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
        border: 1px solid var(--border);
        padding: 1.5rem;
        color: var(--text-primary);
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        max-height: 500px;
    }
    .detail-panel h3 {
        margin-bottom: 1rem;
        font-size: 1.25rem;
        border-bottom: 1px solid var(--border);
        padding-bottom: 0.5rem;
    }
    .detail-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.03);
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid rgba(255,255,255, 0.05);
    }
    .detail-label {
        color: var(--text-secondary);
        font-size: 0.9rem;
        text-transform: capitalize;
    }
    .detail-value {
        font-weight: 600;
        color: var(--primary);
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
    .info.legend {
        background: var(--surface);
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
        color: var(--text-primary);
        font-size: 0.9rem;
    }
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 6px;
    }
    .legend-item:last-child {
        margin-bottom: 0;
    }
    .legend-color {
        width: 14px;
        height: 14px;
        display: inline-block;
        border-radius: 50%;
        margin-right: 8px;
    }
    .map-controls {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    .filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--surface);
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        border: 1px solid var(--border);
    }
    .filter-label {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 500;
    }
    .select-control {
        padding: 0.3rem 0.5rem;
        border-radius: 9999px;
        border: none;
        background: transparent;
        color: var(--text-primary);
        font-family: inherit;
        outline: none;
        cursor: pointer;
    }
    .number-input {
        width: 60px;
        padding: 0.2rem 0.5rem;
        border-radius: 0.5rem;
        border: 1px solid var(--border);
        background: rgba(255,255,255,0.05);
        color: var(--text-primary);
        outline: none;
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
        <div class="filter-group">
            <span class="filter-label">Wilayah:</span>
            <select id="region-dropdown" class="select-control">
                <option value="">-- Pilih Wilayah --</option>
            </select>
        </div>
        
        <div class="filter-group">
            <span class="filter-label">Klaster:</span>
            <select id="cluster-filter" class="select-control">
                <option value="all">Semua Klaster</option>
                <option value="1">High-High (Hotspot)</option>
                <option value="2">Low-High</option>
                <option value="3">Low-Low</option>
                <option value="4">High-Low (ColdSpot)</option>
            </select>
        </div>

        <div class="filter-group">
            <span class="filter-label">Stunting:</span>
            <input type="number" id="stunting-min" class="number-input" placeholder="Min" step="0.1">
            <span style="color: var(--border)">-</span>
            <input type="number" id="stunting-max" class="number-input" placeholder="Max" step="0.1">
        </div>

        <button id="gps-button" class="btn btn-primary" style="border-radius: 9999px;">📍 Lokasi Saya</button>
    </div>
    <div class="map-layout">
        <div id="map" class="map-container"></div>
        <div id="detail-panel" class="detail-panel">
            <h3 id="detail-title">Pilih wilayah...</h3>
            <div id="detail-content" class="detail-list">
                <p style="color: var(--text-secondary); text-align: center; margin-top: 2rem;">Pilih marker pada peta atau dropdown untuk melihat data indikator kemiskinan dan nutrisi.</p>
            </div>
        </div>
    </div>
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

        // Menambahkan panel Legenda Peta (Legend Control)
        const legend = L.control({position: 'bottomright'});
        legend.onAdd = function (map) {
            const div = L.DomUtil.create('div', 'info legend');
            div.innerHTML = `
                <h4 style="margin: 0 0 10px 0; font-size: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 5px;">Legenda Klaster</h4>
                <div class="legend-item"><span class="legend-color" style="background: #ef4444"></span> High-High (Hotspot)</div>
                <div class="legend-item"><span class="legend-color" style="background: #f59e0b"></span> Low-High</div>
                <div class="legend-item"><span class="legend-color" style="background: #10b981"></span> Low-Low</div>
                <div class="legend-item"><span class="legend-color" style="background: #3b82f6"></span> High-Low (ColdSpot)</div>
            `;
            return div;
        };
        legend.addTo(map);

        // Marker Management
        const markerLayer = L.layerGroup().addTo(map);
        let allRegions = [];

        // Fungsi Detail Panel
        function showDetailPanel(region) {
            const title = document.getElementById('detail-title');
            const content = document.getElementById('detail-content');
            
            title.textContent = "Detail " + (region['kab/kota'] || 'Wilayah');
            content.innerHTML = '';
            
            // Eksklusi kolom yang tidak boleh tampil sesuai issue #14
            const excludeFields = ['lisa_cluster', 'id', 'created_at', 'updated_at', 'latitude', 'longitude', 'kab/kota'];
            
            Object.entries(region).forEach(([key, value]) => {
                if (!excludeFields.includes(key)) {
                    // Fallback untuk handling data kosong
                    const displayValue = (value === null || value === '') ? '-' : value;
                    const formatKey = key.replace(/_/g, ' ');
                    
                    const itemHtml = `
                        <div class="detail-item">
                            <span class="detail-label">${formatKey}</span>
                            <span class="detail-value">${displayValue}</span>
                        </div>
                    `;
                    content.insertAdjacentHTML('beforeend', itemHtml);
                }
            });
        }

        // Fungsi Filter
        function applyFilters() {
            const clusterVal = document.getElementById('cluster-filter').value;
            const minStunting = parseFloat(document.getElementById('stunting-min').value) || 0;
            const maxStunting = parseFloat(document.getElementById('stunting-max').value) || Infinity;

            markerLayer.clearLayers();

            allRegions.forEach(region => {
                const matchesCluster = clusterVal === 'all' || region.lisa_cluster.toString() === clusterVal;
                const matchesStunting = region.stunting >= minStunting && region.stunting <= maxStunting;

                if (matchesCluster && matchesStunting) {
                    addMarkerToMap(region);
                }
            });
        }

        function addMarkerToMap(region) {
            let hexColor = '#94a3b8';
            let clusterName = 'Tidak Terklasifikasi';
            
            if (region.lisa_cluster === 1) {
                hexColor = '#ef4444';
                clusterName = 'High-High (Hotspot)';
            } else if (region.lisa_cluster === 2) {
                hexColor = '#f59e0b';
                clusterName = 'Low-High';
            } else if (region.lisa_cluster === 3) {
                hexColor = '#10b981';
                clusterName = 'Low-Low';
            } else if (region.lisa_cluster === 4) {
                hexColor = '#3b82f6';
                clusterName = 'High-Low (ColdSpot)';
            }

            const marker = L.circleMarker([region.latitude, region.longitude], {
                radius: 12,
                color: hexColor,
                weight: 2,
                fillColor: hexColor,
                fillOpacity: 0.6
            })
            .addTo(markerLayer)
            .bindPopup(`<b>${region['kab/kota']}</b><br>Klaster: <b>${clusterName}</b><br>Stunting: <b>${region.stunting}%</b>`);
            
            marker.on('click', () => { 
                showDetailPanel(region); 
                map.flyTo([region.latitude, region.longitude], 14);
            });
        }

        // Fetch Regions API
        fetch('/api/regions')
            .then(res => res.json())
            .then(response => {
                if(response.success && response.data) {
                    allRegions = response.data;
                    const dropdown = document.getElementById('region-dropdown');
                    
                    allRegions.forEach(region => {
                        addMarkerToMap(region);
                        
                        // Populate dropdown
                        const option = document.createElement('option');
                        option.value = JSON.stringify({...region, lat: region.latitude, lng: region.longitude});
                        option.textContent = region['kab/kota'];
                        dropdown.appendChild(option);
                    });

                    // Add Event Listeners for Filters
                    document.getElementById('cluster-filter').addEventListener('change', applyFilters);
                    document.getElementById('stunting-min').addEventListener('input', applyFilters);
                    document.getElementById('stunting-max').addEventListener('input', applyFilters);

                    // Dropdown interaction
                    dropdown.addEventListener('change', function(e) {
                        if(this.value) {
                            const regionData = JSON.parse(this.value);
                            map.flyTo([regionData.lat, regionData.lng], 14);
                            showDetailPanel(regionData);
                        } else {
                            map.setView([-6.21, 106.82], 12); // Reset view
                            document.getElementById('detail-title').textContent = 'Pilih wilayah...';
                            document.getElementById('detail-content').innerHTML = '<p style="color: var(--text-secondary); text-align: center; margin-top: 2rem;">Pilih marker pada peta atau dropdown untuk melihat data indikator kemiskinan dan nutrisi.</p>';
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
