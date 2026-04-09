@extends('layouts.app')

@section('title', 'Import Data CSV - Stunting Mapping')

@section('content')
<div class="import-container">
    <h2>Upload Data Wilayah CSV</h2>
    <p>
        Unggah file dataset berekstensi <b>.csv</b> Anda di sini. Database sistem otomatis mengekstrak seluruh properti (gizi, kemiskinan, dsb) secara <em>real-time</em> dengan mendeteksi parameter utama kab/kota.
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
