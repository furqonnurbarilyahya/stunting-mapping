@extends('layouts.app')

@section('title', 'Beranda - Stunting Mapping')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .insight-section {
        padding: 2rem 5%;
        margin-bottom: 2rem;
    }
    .insight-layout {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    .insight-card {
        border-radius: 12px;
        padding: 1.5rem;
        transition: transform 0.3s ease;
    }
    .insight-card:hover {
        transform: translateY(-5px);
    }
    .insight-card.danger {
        background-color: rgba(239, 68, 68, 0.05);
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-left: 4px solid #ef4444;
    }
    .insight-card.success {
        background-color: rgba(16, 185, 129, 0.05);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-left: 4px solid #10b981;
    }
    .insight-card h3 {
        margin-top: 0;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.25rem;
    }
    .insight-card.danger h3 { color: #ef4444; }
    .insight-card.success h3 { color: #10b981; }
    .insight-card p {
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
        margin-bottom: 0;
        font-size: 0.95rem;
    }

    /* Hero Overview Section Styles */
    .hero-overview {
        padding: 4rem 5%;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 3rem;
        margin-bottom: 2rem;
    }
    .hero-content {
        flex: 1 1 500px;
    }
    .hero-visual {
        flex: 1 1 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .hero-logo {
        max-width: 180px;
        filter: drop-shadow(0 10px 15px rgba(0,0,0,0.3));
        margin-bottom: 2rem;
    }
    .hero-overview-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        background: linear-gradient(to right, #60a5fa, #3b82f6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1.2;
    }
    .hero-overview-desc {
        font-size: 1.1rem;
        color: #cbd5e1;
        line-height: 1.7;
        margin-bottom: 1.5rem;
    }
    .hero-fun-fact {
        background: rgba(59, 130, 246, 0.1);
        border-left: 4px solid #3b82f6;
        padding: 1.2rem 1.5rem;
        border-radius: 0 8px 8px 0;
        margin-bottom: 2rem;
    }
    .hero-fun-fact-title {
        color: #60a5fa;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .hero-fun-fact-text {
        color: #94a3b8;
        font-size: 0.95rem;
        margin: 0;
        line-height: 1.5;
    }
    .hero-stats {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        width: 100%;
        justify-content: center;
    }
    .stat-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem 1rem;
        text-align: center;
        flex: 1;
        min-width: 100px;
        max-width: 150px;
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.08);
    }
    .stat-value {
        display: block;
        font-size: 2.2rem;
        font-weight: 700;
        color: #f8fafc;
        margin-bottom: 0.25rem;
    }
    .stat-label {
        font-size: 0.8rem;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    @media (max-width: 768px) {
        .hero-overview {
            flex-direction: column-reverse;
            text-align: center;
            padding: 3rem 5%;
        }
        .hero-fun-fact {
            text-align: left;
        }
        .hero-overview-title {
            font-size: 2rem;
        }
        .hero-logo {
            max-width: 150px;
            margin-bottom: 1.5rem;
        }
    }

    /* Animation Utility Classes */
    html {
        scroll-behavior: smooth;
    }
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease-out;
    }
    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Mengapa WebGIS Dibuat Section */
    .why-section {
        padding: 4rem 5%;
        text-align: center;
        margin-bottom: 2rem;
    }
    .why-title {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #f8fafc;
    }
    .why-subtitle {
        font-size: 1.1rem;
        color: #94a3b8;
        max-width: 700px;
        margin: 0 auto 3rem auto;
        line-height: 1.6;
    }
    .why-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    .why-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 2.5rem 1.5rem;
        text-align: left;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease, border-color 0.4s ease, background 0.4s ease;
    }
    .why-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15);
        border-color: rgba(59, 130, 246, 0.4);
        background: rgba(30, 41, 59, 0.8);
    }
    .why-icon {
        width: 50px;
        height: 50px;
        margin-bottom: 1.5rem;
        color: #60a5fa;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(59, 130, 246, 0.1);
        border-radius: 12px;
        padding: 10px;
    }
    .why-card h3 {
        font-size: 1.4rem;
        color: #f8fafc;
        margin-bottom: 1rem;
        font-family: 'Outfit', sans-serif;
    }
    .why-card p {
        color: #cbd5e1;
        line-height: 1.7;
        font-size: 0.95rem;
    }
</style>
@endsection

@section('content')
<!-- Hero Overview Section -->
<section class="hero-overview">
    <div class="hero-content">
        <h1 class="hero-overview-title">Sistem Pemetaan Stunting<br>Provinsi Jawa Timur</h1>
        <p class="hero-overview-desc">
            Sistem pemetaan (WebGIS) ini dirancang untuk mengelola, menganalisis, dan memvisualisasikan data sebaran kerawanan stunting di seluruh wilayah Jawa Timur guna mendukung pengambilan keputusan strategis.
        </p>
        
        <div class="hero-fun-fact">
            <div class="hero-fun-fact-title">💡 Tahukah Anda?</div>
            <p class="hero-fun-fact-text">Provinsi Jawa Timur merupakan provinsi terluas di Pulau Jawa. Wilayah ini tidak hanya mencakup daratan utama, tetapi juga memiliki wilayah kepulauan seperti Pulau Madura dan Kepulauan Kangean, menjadikannya wilayah dengan karakteristik sosiodemografis yang sangat beragam.</p>
        </div>

        <div>
            <a href="#map-section" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-weight: 600; font-size: 1.1rem; border-radius: 8px;">Mulai Eksplorasi Peta</a>
        </div>
    </div>
    
    <div class="hero-visual">
        <img src="{{ asset('images/Coat_of_arms_of_East_Java.png') }}" alt="Lambang Provinsi Jawa Timur" class="hero-logo">
        <div class="hero-stats">
            <div class="stat-card">
                <span class="stat-value">29</span>
                <span class="stat-label">Kabupaten</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">9</span>
                <span class="stat-label">Kota</span>
            </div>
            <div class="stat-card">
                <span class="stat-value">38</span>
                <span class="stat-label">Total Wilayah</span>
            </div>
        </div>
    </div>
</section>

<!-- Why WebGIS Section -->
<section class="why-section reveal" id="why-section">
    <h2 class="why-title">Mengapa WebGIS Ini Dibuat?</h2>
    <p class="why-subtitle">Pemetaan stunting bukan sekadar visualisasi angka, melainkan alat bantu strategis untuk memastikan intervensi kebijakan lebih tepat sasaran.</p>
    
    <div class="why-grid">
        <div class="why-card reveal">
            <div class="why-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3>Identifikasi Wilayah Prioritas</h3>
            <p>Membantu pemerintah dan stakeholder menemukan titik kritis (hotspot) kerawanan stunting secara spasial, sehingga alokasi bantuan dan program intervensi dapat difokuskan pada daerah yang paling membutuhkan.</p>
        </div>
        <div class="why-card reveal" style="transition-delay: 150ms;">
            <div class="why-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h3>Pendukung Keputusan (DSS)</h3>
            <p>Visualisasi dan analisis klaster LISA memberikan insight objektif berbasis data. Kebijakan percepatan penurunan stunting tidak lagi dibuat berdasarkan asumsi, melainkan fakta persebaran di lapangan.</p>
        </div>
        <div class="why-card reveal" style="transition-delay: 300ms;">
            <div class="why-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </div>
            <h3>Aksesibilitas Informasi Terpadu</h3>
            <p>Memberikan kemudahan bagi berbagai instansi untuk mengakses, memantau, dan memahami kondisi stunting di Provinsi Jawa Timur secara transparan melalui satu antarmuka yang modern dan responsif.</p>
        </div>
    </div>

    <div class="why-reference reveal" style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.05); text-align: left; max-width: 900px; margin-left: auto; margin-right: auto;">
        <h3 style="font-size: 1.1rem; color: #94a3b8; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            Referensi Ilmiah
        </h3>
        <p style="color: #cbd5e1; font-size: 0.95rem; line-height: 1.6;">
            Pengembangan website pemetaan dan analisis spasial stunting ini didasarkan pada penelitian dan metodologi ilmiah yang telah digunakan dalam kajian analisis spasial kesehatan masyarakat. Pendekatan spasial menggunakan metode LISA (Local Indicator of Spatial Association) digunakan untuk mengidentifikasi pola persebaran stunting, hotspot, coldspot, serta wilayah prioritas penanganan secara lebih terukur dan berbasis data.
        </p>
        <a href="https://ejurnal.stmik-budidarma.ac.id/index.php/JSON/article/view/8877/4195" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #60a5fa; text-decoration: none; font-size: 0.9rem; margin-top: 0.5rem; transition: color 0.3s ease;">
            <span>Lihat jurnal dan studi terkait analisis spasial stunting kab/kota Provinsi Jawa Timur</span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
        </a>
    </div>
</section>

<!-- Map Section -->
<section id="map-section" class="map-section reveal">
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

<!-- Insight Section -->
<section class="insight-section reveal" id="insight-section">
    <h1 class="section-title">Insight Spasial</h1>
    <h2 class="section-subtitle">Interpretasi Pola Persebaran Stunting</h2>
    <div class="insight-layout">
        <div class="insight-card danger">
            <h3><span>🔴</span> Pola Hotspot (Wilayah Prioritas)</h3>
            <p id="hotspot-insight-text">Memuat insight spasial...</p>
        </div>
        <div class="insight-card success">
            <h3><span>🟢</span> Pola Coldspot (Wilayah Terkendali)</h3>
            <p id="coldspot-insight-text">Memuat insight spasial...</p>
        </div>
    </div>
</section>

<!-- Ranking Section -->
<section class="ranking-section reveal" id="ranking-section">
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
<section class="chart-section reveal" id="chart-section">
    <h2 class="section-title">Visualisasi Statistik Stunting per Wilayah Kab/Kota Provinsi Jawa Timur</h2>
    <div class="chart-container">
        <canvas id="stuntingChart"></canvas>
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

        // GeoJSON & Data Management
        let allRegions = [];
        let geoJsonData = null;
        let geoJsonLayer = null;

        // Fungsi Detail Panel
        function showDetailPanel(region) {
            const title = document.getElementById('detail-title');
            const content = document.getElementById('detail-content');
            
            title.textContent = "Detail " + (region['kab/kota'] || 'Wilayah');
            content.innerHTML = '';
            
            // Deskripsi Variabel
            const varDescriptions = {
                "stunting": "Persentase balita yang mengalami kondisi stunting pada setiap kabupaten/kota di Provinsi Jawa Timur. Variabel ini digunakan untuk menggambarkan tingkat permasalahan gizi kronis akibat kekurangan asupan nutrisi dalam jangka panjang yang berdampak pada pertumbuhan dan perkembangan anak.",
                "hamil": "Persentase cakupan pelayanan kesehatan bagi ibu hamil pada setiap wilayah. Variabel ini mencerminkan tingkat akses dan pemanfaatan layanan kesehatan maternal selama masa kehamilan.",
                "tambah_darah": "Persentase ibu hamil yang memperoleh dan mengonsumsi tablet tambah darah sebagai upaya pencegahan anemia selama kehamilan. Variabel ini berkaitan dengan kualitas intervensi kesehatan ibu dan risiko gangguan pertumbuhan janin.",
                "persalinan_nakes": "Persentase proses persalinan yang ditangani atau dibantu oleh tenaga kesehatan profesional. Variabel ini menunjukkan tingkat akses masyarakat terhadap layanan persalinan yang aman dan terstandarisasi.",
                "vit_a": "Persentase cakupan pemberian vitamin A pada anak usia 6–59 bulan. Variabel ini digunakan untuk menggambarkan upaya pemenuhan kebutuhan mikronutrien penting bagi pertumbuhan dan daya tahan tubuh anak.",
                "bblr": "Persentase bayi yang lahir dengan berat badan rendah (kurang dari 2500 gram). Variabel ini menjadi indikator risiko kesehatan bayi baru lahir yang dapat berpengaruh terhadap potensi stunting di masa mendatang.",
                "imd": "Persentase pelaksanaan Inisiasi Menyusu Dini (IMD), yaitu proses pemberian ASI pertama dalam satu jam setelah kelahiran bayi. Variabel ini mencerminkan praktik awal pemberian nutrisi dan ikatan ibu-anak setelah persalinan.",
                "asi_baduta": "Persentase pemberian Air Susu Ibu (ASI) pada anak usia bawah dua tahun (baduta). Variabel ini menunjukkan keberlanjutan praktik pemberian ASI sebagai sumber nutrisi utama pada masa awal pertumbuhan anak.",
                "lama_asi": "Rata-rata atau persentase lama pemberian ASI eksklusif kepada bayi sesuai rekomendasi kesehatan. Variabel ini digunakan untuk melihat kualitas pola pemberian nutrisi pada bayi usia dini.",
                "asi_pendamping": "Persentase pemberian makanan pendamping ASI (MP-ASI) pada bayi dan balita. Variabel ini menggambarkan pemenuhan kebutuhan nutrisi tambahan setelah masa ASI eksklusif.",
                "imunisasi": "Persentase cakupan imunisasi dasar lengkap pada bayi atau balita. Variabel ini menunjukkan tingkat perlindungan anak terhadap berbagai penyakit infeksi yang dapat memengaruhi kondisi kesehatan dan pertumbuhan.",
                "vit_a_6_11": "Persentase pemberian vitamin A pada anak usia 6–11 bulan. Variabel ini digunakan untuk melihat cakupan intervensi gizi pada kelompok usia bayi awal.",
                "vit_a_12_59": "Persentase pemberian vitamin A pada anak usia 12–59 bulan. Variabel ini menggambarkan cakupan suplementasi vitamin A pada kelompok usia balita.",
                "vit_a_6_59": "Persentase total cakupan pemberian vitamin A pada anak usia 6–59 bulan sebagai indikator intervensi gizi mikronutrien secara umum.",
                "cakupan": "Persentase cakupan pelayanan kesehatan umum pada setiap wilayah. Variabel ini menunjukkan tingkat akses masyarakat terhadap fasilitas dan layanan kesehatan dasar.",
                "jiwa_rt": "Rata-rata jumlah jiwa dalam satu rumah tangga (RT) pada setiap wilayah. Variabel ini digunakan untuk menggambarkan kepadatan hunian dan kondisi sosial demografi masyarakat.",
                "sanitasi": "Persentase rumah tangga yang memiliki akses terhadap sanitasi layak. Variabel ini berkaitan dengan kondisi lingkungan sehat dan pencegahan penyakit berbasis lingkungan.",
                "air_minum": "Persentase rumah tangga dengan akses terhadap sumber air minum layak dan aman. Variabel ini mencerminkan kualitas fasilitas dasar kesehatan lingkungan masyarakat.",
                "ipm": "Indeks Pembangunan Manusia (IPM) pada setiap kabupaten/kota yang mencerminkan kualitas pembangunan manusia berdasarkan aspek kesehatan, pendidikan, dan standar hidup layak.",
                "miskin": "Persentase penduduk miskin pada setiap wilayah. Variabel ini digunakan untuk menggambarkan kondisi sosial ekonomi masyarakat yang berpotensi memengaruhi tingkat stunting."
            };

            // Eksklusi kolom yang tidak boleh tampil sesuai issue #14
            const excludeFields = ['lisa_cluster', 'id', 'created_at', 'updated_at', 'latitude', 'longitude', 'kab/kota'];
            
            Object.entries(region).forEach(([key, value]) => {
                if (!excludeFields.includes(key)) {
                    // Fallback untuk handling data kosong
                    const displayValue = (value === null || value === '') ? '-' : value;
                    const formatKey = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                    const desc = varDescriptions[key];
                    const randomId = Math.random().toString(36).substr(2, 9);
                    const toggleId = 'desc-' + key + '-' + randomId;
                    
                    const descHtml = desc ? `
                        <div id="${toggleId}" style="max-height: 0; overflow: hidden; opacity: 0; transition: max-height 0.3s ease, opacity 0.3s ease;">
                            <div style="font-size: 0.85rem; color: #94a3b8; margin-top: 0.5rem; padding-left: 0.75rem; border-left: 2px solid rgba(59, 130, 246, 0.5); font-weight: normal; line-height: 1.5;">
                                ${desc}
                            </div>
                        </div>
                    ` : '';
                    
                    const iconHtml = desc ? `
                        <button onclick="
                            const content = document.getElementById('${toggleId}');
                            const isCollapsed = content.style.maxHeight === '0px' || content.style.maxHeight === '';
                            content.style.maxHeight = isCollapsed ? content.scrollHeight + 'px' : '0px';
                            content.style.opacity = isCollapsed ? '1' : '0';
                            this.style.transform = isCollapsed ? 'rotate(180deg)' : 'rotate(0deg)';
                        " style="background: none; border: none; color: #60a5fa; cursor: pointer; padding: 0 0.25rem; margin-left: 0.25rem; transition: transform 0.3s ease; display: inline-flex; align-items: center;" aria-label="Toggle Description">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                    ` : '';

                    const itemHtml = `
                        <div class="detail-item-container" style="border-bottom: 1px solid rgba(255,255,255,0.05); padding: 0.75rem 0; display: flex; flex-direction: column;">
                            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                                <div style="font-weight: 600; color: #cbd5e1; flex: 1; padding-right: 1rem;">${formatKey}</div>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="font-weight: 700; color: #f8fafc; text-align: right;">${displayValue}</div>
                                    <div>${iconHtml}</div>
                                </div>
                            </div>
                            ${descHtml}
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
                    filteredRegions.push(region);
                }
            });
            
            updateChart(filteredRegions);
            renderGeoJson(filteredRegions);
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

        function normalizeRegionName(name) {
            if (!name) return '';
            // Hapus semua karakter non-alfanumerik (spasi, titik, koma, dsb)
            let n = name.toLowerCase().replace(/[^a-z0-9]/g, '');
            // Hapus prefix kab atau kabupaten di awal string
            if (n.startsWith('kabupaten')) {
                n = n.substring(9);
            } else if (n.startsWith('kab')) {
                n = n.substring(3);
            }
            return n;
        }

        function renderGeoJson(filteredRegions) {
            if (geoJsonLayer) {
                map.removeLayer(geoJsonLayer);
                // Hapus tooltips manual jika ada
                if (geoJsonLayer.tooltips) {
                    geoJsonLayer.tooltips.forEach(t => map.removeLayer(t));
                }
            }

            if (!geoJsonData) return;

            // Inisialisasi array untuk menyimpan manual tooltips
            const tooltips = [];

            geoJsonLayer = L.geoJSON(geoJsonData, {
                style: function(feature) {
                    const regionName = feature.properties.WADMKK;
                    
                    if (!regionName) {
                        return { fillColor: 'transparent', weight: 0, opacity: 0, fillOpacity: 0 };
                    }
                    
                    const normalizedGeoName = normalizeRegionName(regionName);
                    const region = filteredRegions.find(r => normalizeRegionName(r['kab/kota']) === normalizedGeoName);

                    let hexColor = 'transparent';
                    let fillOpacity = 0;
                    let weight = 1;
                    let color = '#444'; 

                    if (region) {
                        fillOpacity = 0.7;
                        weight = 2;
                        color = '#ffffff'; 
                        
                        if (region.lisa_cluster === 1) {
                            hexColor = '#ef4444'; 
                        } else if (region.lisa_cluster === 2 || region.lisa_cluster === 4) {
                            hexColor = '#c2c2c2'; 
                        } else if (region.lisa_cluster === 3) {
                            hexColor = '#10b981'; 
                        } else {
                            hexColor = '#94a3b8'; 
                        }
                    }

                    return {
                        fillColor: hexColor,
                        weight: weight,
                        opacity: 1,
                        color: color,
                        fillOpacity: fillOpacity
                    };
                },
                onEachFeature: function(feature, layer) {
                    const regionName = feature.properties.WADMKK;
                    if (!regionName) return;

                    const normalizedGeoName = normalizeRegionName(regionName);
                    const region = allRegions.find(r => normalizeRegionName(r['kab/kota']) === normalizedGeoName);

                    const displayName = region ? region['kab/kota'] : regionName;

                    // Buat tooltip manual untuk mencegah Leaflet crash (Error: latlngs not passed)
                    // saat menghitung centroid dari MultiPolygon yang kompleks.
                    try {
                        const center = layer.getBounds().getCenter();
                        const tooltip = L.tooltip({ 
                            permanent: true, 
                            direction: 'center', 
                            className: 'region-tooltip'
                        })
                        .setLatLng(center)
                        .setContent(displayName);
                        
                        tooltips.push(tooltip);
                    } catch (e) {
                        console.warn("Gagal membuat tooltip untuk:", displayName, e);
                    }

                    if (region) {
                        let clusterName = 'Tidak Terklasifikasi';
                        if (region.lisa_cluster === 1) clusterName = 'Hotspot (High-High)';
                        else if (region.lisa_cluster === 2 || region.lisa_cluster === 4) clusterName = 'Spatial Outlier';
                        else if (region.lisa_cluster === 3) clusterName = 'Coldspot (Low-Low)';

                        layer.bindPopup(`<b>${region['kab/kota']}</b><br>Klaster: <b>${clusterName}</b><br>Stunting: <b>${region.stunting}%</b>`);

                        layer.on({
                            click: function(e) {
                                showDetailPanel(region);
                                map.fitBounds(e.target.getBounds());
                            },
                            mouseover: function(e) {
                                const l = e.target;
                                l.setStyle({
                                    weight: 3,
                                    color: '#f8fafc',
                                    fillOpacity: 0.9
                                });
                                if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                                    l.bringToFront();
                                }
                            },
                            mouseout: function(e) {
                                if (geoJsonLayer) {
                                    geoJsonLayer.resetStyle(e.target);
                                }
                            }
                        });
                    }
                }
            }).addTo(map);

            // Simpan tooltips ke layer dan tampilkan ke map
            geoJsonLayer.tooltips = tooltips;
            tooltips.forEach(t => t.addTo(map));
        }

        // Fetch Regions API & GeoJSON Data dengan Cache Buster
        Promise.all([
            fetch('/api/regions?v=' + new Date().getTime()).then(res => res.json()),
            fetch('{{ asset("geojson/kab_kota_jatim.geojson") }}?v=' + new Date().getTime()).then(res => res.json())
        ])
        .then(([apiResponse, geojson]) => {
            if(apiResponse.success && apiResponse.data) {
                allRegions = apiResponse.data;
                geoJsonData = geojson;
                
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
                                // Pindah ke koordinat titik centroid (atau fitBounds jika dicari)
                                map.flyTo([region.latitude, region.longitude], 12);
                                showDetailPanel(region);
                            }, 300);
                        });
                        return li;
                    };
                    
                    topHighest.forEach(r => listHigh.appendChild(buildItemHTML(r)));
                    topLowest.forEach(r => listLow.appendChild(buildItemHTML(r)));
                }
                
                renderRanking(); // Panggil saat pemuatan data usai

                // Fungsi membangun Insight Spasial
                function renderInsight() {
                    const hotspots = allRegions.filter(r => r.lisa_cluster === 1).map(r => r['kab/kota']);
                    const coldspots = allRegions.filter(r => r.lisa_cluster === 3).map(r => r['kab/kota']);

                    const hotspotText = document.getElementById('hotspot-insight-text');
                    const coldspotText = document.getElementById('coldspot-insight-text');

                    if (hotspotText) {
                        if (hotspots.length > 0) {
                            hotspotText.innerHTML = `Berdasarkan analisis klaster spasial, terdapat <b>${hotspots.length}</b> kabupaten/kota yang teridentifikasi sebagai daerah Hotspot (kerawanan tinggi stunting yang saling berdekatan). Wilayah ini meliputi: <b>${hotspots.join(', ')}</b>.<br><br>Secara spasial, klaster hotspot ini dominan terkonsentrasi di kawasan <b>Madura dan Tapal Kuda</b>. Hal ini mengindikasikan perlunya intervensi kebijakan lintas wilayah yang terfokus pada kawasan tersebut untuk mencegah efek limpahan (spillover effect).`;
                        } else {
                            hotspotText.innerHTML = 'Saat ini tidak teridentifikasi adanya klaster Hotspot (High-High) yang signifikan pada peta persebaran stunting.';
                        }
                    }

                    if (coldspotText) {
                        if (coldspots.length > 0) {
                            coldspotText.innerHTML = `Analisis menunjukkan <b>${coldspots.length}</b> wilayah sebagai klaster Coldspot (angka stunting rendah yang dikelilingi wilayah dengan angka rendah pula). Wilayah tersebut adalah: <b>${coldspots.join(', ')}</b>.<br><br>Pola ini menunjukkan bahwa wilayah coldspot cenderung berada pada <b>kawasan perkotaan</b> dengan akses layanan kesehatan, infrastruktur, serta kondisi sosial ekonomi yang lebih baik.`;
                        } else {
                            coldspotText.innerHTML = 'Saat ini tidak teridentifikasi adanya klaster Coldspot (Low-Low) yang signifikan pada peta persebaran stunting.';
                        }
                    }
                }
                
                renderInsight(); // Panggil untuk generate narasi insight

                // Search Box interaction
                const searchInput = document.getElementById('region-search');
                searchInput.addEventListener('change', function(e) {
                    const searchedName = this.value;
                    const match = allRegions.find(r => r['kab/kota'] === searchedName);
                    
                    if(match) {
                        map.flyTo([match.latitude, match.longitude], 12);
                        showDetailPanel(match);
                    } else if(searchedName.trim() === '') {
                        map.setView([-7.6000, 112.9000], 8); // Reset view to East Java
                        document.getElementById('detail-title').textContent = 'Pilih wilayah...';
                        document.getElementById('detail-content').innerHTML = '<p class="detail-placeholder">Pilih marker pada peta atau ketik di kotak pencarian untuk melihat data indikator kemiskinan dan nutrisi.</p>';
                    }
                });
            }
        })
        .catch(err => console.error('Error fetching data:', err));

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

        // Scroll Reveal Animation
        const reveals = document.querySelectorAll(".reveal");
        const revealOnScroll = new IntersectionObserver(function(entries, observer) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("active");
                }
            });
        }, { threshold: 0.1 });

        reveals.forEach(reveal => {
            revealOnScroll.observe(reveal);
        });
    });
</script>
@endsection
