@extends('adminlte::page')

@section('title', 'Tambah Jenis Koleksi')

@section('content_header')
    <h1>Tambah Jenis Koleksi</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('koleksi.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nama_koleksi">Nama Koleksi</label>
                    <input type="text" name="nama_koleksi" id="nama_koleksi" class="form-control"
                        value="{{ old('nama_koleksi') }}" required>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('koleksi.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </form>
        </div>
    </div>
@stop
