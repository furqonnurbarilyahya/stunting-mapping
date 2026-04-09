@extends('layouts.app')

@section('title', 'Import Data CSV - Stunting Mapping')

@section('styles')
<style>
    .import-container {
        max-width: 600px;
        margin: 5rem auto;
        padding: 2.5rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 1rem;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
    }
    .form-group {
        margin-bottom: 2rem;
    }
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-primary);
    }
    .file-input {
        width: 100%;
        padding: 0.75rem;
        border: 2px dashed var(--border);
        border-radius: 0.5rem;
        background: rgba(255, 255, 255, 0.02);
        color: var(--text-secondary);
        cursor: pointer;
    }
    .file-input:focus {
        outline: none;
        border-color: var(--primary);
    }
    .alert {
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .alert-success { background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; }
    .alert-error { background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; }
</style>
@endsection

@section('content')
<div class="import-container">
    <h2 style="margin-bottom: 1rem; font-size: 1.5rem;">Upload Data Wilayah CSV</h2>
    <p style="color: var(--text-secondary); margin-bottom: 2rem; line-height: 1.6;">
        Unggah file dataset berekstensi <b>.csv</b> Anda di sini. Database sistem otomatis mengekstrak seluruh properti (gizi, kemiskinan, dsb) secara *real-time* dengan mendeteksi parameter utama kab/kota.
    </p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form action="{{ url('/import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label class="form-label" for="csv_file">Pilih Dokumen CSV</label>
            <input type="file" name="csv_file" id="csv_file" class="file-input" required accept=".csv,.txt">
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Mulai Import Data</button>
    </form>
</div>
@endsection
