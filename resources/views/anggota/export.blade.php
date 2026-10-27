@extends('adminlte::page')

@section('title', 'Export Laporan Anggota')

@section('content_header')
    <h1>Export Laporan Anggota</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-success text-white">
            <h3 class="card-title">Pilih Jenis Laporan & Filter Data</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('anggota.export.execute') }}" method="GET" target="_blank">

                {{-- Input untuk Custom Nama File --}}
                <div class="form-group">
                    <label for="nama_file">Nama File Laporan (Opsional)</label>
                    <input type="text" name="nama_file" class="form-control"
                        placeholder="Contoh: laporan_anggota_juli_2025">
                    <small class="form-text text-muted">
                        Kosongkan untuk menggunakan nama default (misal: laporan_anggota_2025-07-24).
                    </small>
                </div>

                {{-- Dropdown untuk Jenis Export --}}
                <div class="form-group">
                    <label for="exportType">Jenis Laporan</label>
                    <select name="type" id="exportType" class="form-control" required>
                        <option value="">-- Pilih Jenis Laporan --</option>
                        <option value="excel-img">Excel (Lengkap dengan Gambar)</option>
                        <option value="pdf">PDF</option>
                        <option value="print">Print Preview (Cetak Langsung)</option>
                    </select>
                </div>

                <hr>
                <p><strong>Filter Data (Opsional):</strong></p>

                {{-- Filter Jenis Kelamin --}}
                <div class="form-group">
                    <label for="gender">Filter Berdasarkan Jenis Kelamin</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value="">Semua Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                {{-- Filter Status Keanggotaan --}}
                <div class="form-group">
                    <label for="status">Filter Berdasarkan Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="non-aktif">Non-Aktif</option>
                        <option value="kadaluwarsa">Kadaluwarsa</option>
                    </select>
                </div>

                {{-- Filter Tingkat --}}
                <div class="form-group">
                    <label for="tingkat">Filter Berdasarkan Tingkat</label>
                    <select name="tingkat" id="tingkat" class="form-control">
                        <option value="">Semua Tingkat</option>
                        <option value="Siswa">Siswa</option>
                        <option value="Guru">Guru</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-download"></i> Buat Laporan Sekarang
                </button>
            </form>
        </div>
    </div>
@stop
