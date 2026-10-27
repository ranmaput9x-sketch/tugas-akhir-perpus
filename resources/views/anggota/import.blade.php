@extends('adminlte::page')

@section('title', 'Import Anggota')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Import Data Anggota</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('anggota.index') }}">Data Anggota</a></li>
                <li class="breadcrumb-item active" aria-current="page">Import</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Upload File Excel</h3>
        </div>
        <div class="card-body">
            <div class="row align-items-end">
                <div class="col-md-9">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle"></i>
                        <strong>Catatan:</strong> Jika ingin mengimpor anggota,
                        <span class="text-danger font-weight-bold">WAJIB</span> menggunakan file
                        <u>template resmi</u> berikut agar sistem bisa membaca data dengan benar.
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="{{ asset('template/template_import_anggota_new.xlsx') }}" target="_blank"
                            class="btn btn-light">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                    <form method="POST" action="{{ route('anggota.import.preview') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="file">Pilih File Excel (.xlsx)</label>
                            <input type="file" name="file" class="form-control" required accept=".xlsx">
                        </div>

                        <div class="form-group">
                            <label for="tingkat">Pilih Tingkat</label>
                            <select name="tingkat" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="siswa">Siswa</option>
                                <option value="guru">Guru</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-search"></i> Preview
                        </button>
                        <a href="{{ route('anggota.index') }}" class="btn btn-danger ml-2">
                            <i class="fas fa-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
