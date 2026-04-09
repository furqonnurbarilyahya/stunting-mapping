@extends('layouts.app')

@section('title', 'Beranda - Stunting Mapping')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endsection

@section('content')
<section class="hero">
    <h1 class="hero-title">Sistem Pemetaan<br>Klaster Stunting</h1>
    <p class="hero-subtitle">Membantu mengelola, menganalisis, dan memvisualisasikan data wilayah stunting untuk pengambilan keputusan yang lebih akurat.</p>
    <div>
        <a href="#map-section" class="btn btn-primary">Mulai Eksplorasi</a>
    </div>
</section>

<!-- Map Section -->
<section id="map-section" class="map-section">
    <h2 class="section-title">Peta Persebaran Stunting</h2>
    <div class="map-controls">
        <div class="filter-group">
            <span class="filter-label">Cari:</span>
            <input type="text" id="region-search" class="select-control" list="region-datalist" placeholder="Ketik Kab/Kota..." autocomplete="off">
            <datalist id="region-datalist"></datalist>
        </div>
        
        <div class="filter-group">
            <span class="filter-label">Klaster:</span>
            <select id="cluster-filter" class="select-control">
                <option value="all">Semua Klaster</option>
                <option value="1">Hotspot (High-High)</option>
                <option value="outlier">Spatial Outlier (Low-High / High-Low)</option>
                <option value="3">Coldspot (Low-Low)</option>
            </select>
        </div>

        <div class="filter-group">
            <span class="filter-label">Stunting:</span>
            <input type="number" id="stunting-min" class="number-input" placeholder="Min" step="0.1">
            <span class="filter-separator">-</span>
            <input type="number" id="stunting-max" class="number-input" placeholder="Max" step="0.1">
        </div>

        <button id="gps-button" class="btn btn-primary">📍 Lokasi Saya</button>
    </div>
    <div class="map-layout">
        <div id="map" class="map-container"></div>
        <div id="detail-panel" class="detail-panel">
            <h3 id="detail-title">Pilih wilayah...</h3>
            <div id="detail-content" class="detail-list">
                <p class="detail-placeholder">Pilih marker pada peta atau ketik di kotak pencarian untuk melihat data indikator kemiskinan dan nutrisi.</p>
            </div>
        </div>
    </div>
</section>

<!-- Ranking Section -->
<section class="ranking-section" id="ranking-section">
    <h1 class="section-title">Data Statistik</h1>
    <h2 class="section-subtitle">Prioritas Intervensi Stunting</h2>
    <div class="ranking-layout">
        <div class="ranking-card danger">
            <h3>🔴 Top 5 Kasus Tertinggi (Prioritas)</h3>
            <ul id="highest-ranking-list" class="ranking-list">
                <li class="ranking-loading">Memuat data...</li>
            </ul>
        </div>
        <div class="ranking-card success">
            <h3>🟢 Top 5 Pencapaian Terbaik (Terendah)</h3>
            <ul id="lowest-ranking-list" class="ranking-list">
                <li class="ranking-loading">Memuat data...</li>
            </ul>
        </div>
    </div>
</section>

<!-- Chart Section -->
<section class="chart-section" id="chart-section">
    <h2 class="section-title">Visualisasi Statistik Stunting per Wilayah</h2>
    <div class="chart-container">
        <canvas id="stuntingChart"></canvas>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init map centered on East Java (Shifted East to see more regions)
        const map = L.map('map', {
            zoomControl: true,
            scrollWheelZoom: true
        }).setView([-7.6000, 112.9000], 8);

        // Add initial class to hide tooltips at zoom 8
        document.getElementById('map').classList.add('hide-tooltips');

        // Zoom event to toggle tooltip visibility
        map.on('zoomend', function() {
            const zoom = map.getZoom();
            if (zoom > 8) {
                document.getElementById('map').classList.remove('hide-tooltips');
            } else {
                document.getElementById('map').classList.add('hide-tooltips');
            }
        });

        // Dark Theme Tile Layer
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        // Menambahkan panel Legenda Peta (Legend Control)
        const legend = L.control({position: 'bottomleft'});
        legend.onAdd = function (map) {
            const div = L.DomUtil.create('div', 'info legend');
            div.innerHTML = `
                <h4 class="legend-title">Legenda Klaster</h4>
                <div class="legend-item"><span class="legend-color" style="background: #ef4444"></span> Hotspot (High-High)</div>
                <div class="legend-item"><span class="legend-color" style="background: #c2c2c2"></span> Spatial Outlier</div>
                <div class="legend-item"><span class="legend-color" style="background: #10b981"></span> Coldspot (Low-Low)</div>
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

        // Fungsi Filter Dinamis (Map & Chart)
        function applyFilters() {
            const clusterVal = document.getElementById('cluster-filter').value;
            const minStunting = parseFloat(document.getElementById('stunting-min').value) || 0;
            const maxStunting = parseFloat(document.getElementById('stunting-max').value) || Infinity;

            markerLayer.clearLayers();
            const filteredRegions = [];

            allRegions.forEach(region => {
                let matchesCluster = false;
                if (clusterVal === 'all') {
                    matchesCluster = true;
                } else if (clusterVal === 'outlier') {
                    matchesCluster = (region.lisa_cluster === 2 || region.lisa_cluster === 4);
                } else {
                    matchesCluster = region.lisa_cluster.toString() === clusterVal;
                }
                const matchesStunting = region.stunting >= minStunting && region.stunting <= maxStunting;

                if (matchesCluster && matchesStunting) {
                    addMarkerToMap(region);
                    filteredRegions.push(region);
                }
            });
            
            updateChart(filteredRegions);
        }

        let stuntingChart = null;

        function updateChart(data) {
            const ctx = document.getElementById('stuntingChart');
            if(!ctx) return;
            
            if (stuntingChart) {
                stuntingChart.destroy();
            }

            // Urutkan nilai persentase agar rapi (tinggi ke rendah)
            const sortedData = [...data].sort((a, b) => b.stunting - a.stunting);
            
            const labels = sortedData.map(r => r['kab/kota']);
            const values = sortedData.map(r => r.stunting);
            
            // Merah bila gawat kritis (> 20%), Biru jika rendah
            const bgColors = sortedData.map(r => r.stunting >= 20 ? 'rgba(239, 68, 68, 0.7)' : 'rgba(59, 130, 246, 0.7)');

            stuntingChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Persentase Stunting (%)',
                        data: values,
                        backgroundColor: bgColors,
                        borderWidth: 1,
                        borderColor: 'rgba(255,255,255,0.1)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255,255,255,0.1)' },
                            ticks: { color: 'rgba(255,255,255,0.6)' }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: 'rgba(255,255,255,0.6)' }
                        }
                    }
                }
            });
        }

        function addMarkerToMap(region) {
            let hexColor = '#94a3b8';
            let clusterName = 'Tidak Terklasifikasi';
            
            if (region.lisa_cluster === 1) {
                hexColor = '#ef4444';
                clusterName = 'Hotspot (High-High)';
            } else if (region.lisa_cluster === 2 || region.lisa_cluster === 4) {
                hexColor = '#c2c2c2';
                clusterName = 'Spatial Outlier';
            } else if (region.lisa_cluster === 3) {
                hexColor = '#10b981';
                clusterName = 'Coldspot (Low-Low)';
            }

            const marker = L.circleMarker([region.latitude, region.longitude], {
                radius: 12,
                color: hexColor,
                weight: 2,
                fillColor: hexColor,
                fillOpacity: 0.6
            })
            .addTo(markerLayer)
            .bindPopup(`<b>${region['kab/kota']}</b><br>Klaster: <b>${clusterName}</b><br>Stunting: <b>${region.stunting}%</b>`)
            .bindTooltip(region['kab/kota'], { 
                permanent: true, 
                direction: 'top', 
                className: 'region-tooltip',
                offset: [0, -10]
            });
            
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
                    const datalist = document.getElementById('region-datalist');
                    
                    allRegions.forEach(region => {
                        // Populate datalist search
                        const option = document.createElement('option');
                        option.value = region['kab/kota'];
                        datalist.appendChild(option);
                    });

                    // Add Event Listeners for Filters
                    document.getElementById('cluster-filter').addEventListener('change', applyFilters);
                    document.getElementById('stunting-min').addEventListener('input', applyFilters);
                    document.getElementById('stunting-max').addEventListener('input', applyFilters);

                    // Initialize first render (peta & chart)
                    applyFilters();

                    // Fungsi membangun Ranking UI
                    function renderRanking() {
                        const listHigh = document.getElementById('highest-ranking-list');
                        const listLow = document.getElementById('lowest-ranking-list');
                        
                        listHigh.innerHTML = '';
                        listLow.innerHTML = '';
                        
                        // Menghindari mutasi array asli
                        const sortedByStunting = [...allRegions].sort((a, b) => b.stunting - a.stunting);
                        
                        // Top 5 Tertinggi
                        const topHighest = sortedByStunting.slice(0, 5);
                        // Top 5 Terendah
                        const topLowest = [...sortedByStunting].reverse().slice(0, 5);
                        
                        const buildItemHTML = (region) => {
                            const li = document.createElement('li');
                            li.className = 'ranking-item';
                            li.innerHTML = `<span class="ranking-name">${region['kab/kota']}</span><span class="ranking-value">${region.stunting}%</span>`;
                            li.addEventListener('click', () => {
                                // Scroll lembut ke layer peta
                                document.getElementById('map-section').scrollIntoView({ behavior: 'smooth' });
                                // Panggil efek interaktif terbang ke Peta dan tampilkan panel
                                setTimeout(() => {
                                    map.flyTo([region.latitude, region.longitude], 14);
                                    showDetailPanel(region);
                                }, 300);
                            });
                            return li;
                        };
                        
                        topHighest.forEach(r => listHigh.appendChild(buildItemHTML(r)));
                        topLowest.forEach(r => listLow.appendChild(buildItemHTML(r)));
                    }
                    
                    renderRanking(); // Panggil saat pemuatan data usai

                    // Search Box interaction
                    const searchInput = document.getElementById('region-search');
                    searchInput.addEventListener('change', function(e) {
                        const searchedName = this.value;
                        const match = allRegions.find(r => r['kab/kota'] === searchedName);
                        
                        if(match) {
                            map.flyTo([match.latitude, match.longitude], 14);
                            showDetailPanel(match);
                        } else if(searchedName.trim() === '') {
                            map.setView([-7.6000, 112.9000], 8); // Reset view to East Java
                            document.getElementById('detail-title').textContent = 'Pilih wilayah...';
                            document.getElementById('detail-content').innerHTML = '<p class="detail-placeholder">Pilih marker pada peta atau ketik di kotak pencarian untuk melihat data indikator kemiskinan dan nutrisi.</p>';
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
