@extends('layouts.app')

@section('title', 'Beranda - Stunting Mapping')

@section('content')
<section class="hero">
    <h1 class="hero-title">Sistem Pemetaan<br>Klaster Stunting</h1>
    <p class="hero-subtitle">Membantu mengelola, menganalisis, dan memvisualisasikan data wilayah stunting untuk pengambilan keputusan yang lebih akurat.</p>
    <div>
        <a href="#" class="btn btn-primary">Mulai Eksplorasi</a>
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
