@extends('adminlte::page')

@section('title', 'Tambah Penerbit')

@section('content_header')
    <h1 class="m-0 text-dark">Tambah Penerbit</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{ route('penerbit.index') }}" class="btn btn-default btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('penerbit.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="nama_penerbit">Nama Penerbit</label>
                    <input type="text" name="nama_penerbit" id="nama_penerbit"
                        class="form-control @error('nama_penerbit') is-invalid @enderror" value="{{ old('nama_penerbit') }}"
                        placeholder="Masukkan nama penerbit" required>
                    @error('nama_penerbit')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <a href="{{ route('penerbit.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
