@extends('adminlte::page')

@section('title', 'Tambah Penulis')

@section('content_header')
    <h1 class="m-0 text-dark">Tambah Penulis</h1>
@stop

@section('content')
    @if ($errors->any())
        <x-adminlte-alert theme="danger" title="Gagal Validasi" dismissable>
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </x-adminlte-alert>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Form Penulis Baru</h3>
        </div>
        <div class="card-body">
            <form id="formPenulis" method="POST" action="{{ route('penulis.store') }}">
                @csrf

                <div class="form-group">
                    <label for="nama_penulis">Nama Penulis</label>
                    <input type="text" name="nama_penulis" id="nama_penulis" class="form-control"
                        placeholder="Masukkan nama penulis" value="{{ old('nama_penulis') }}" required>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('penulis.index') }}" class="btn btn-secondary mr-2">
                        Batal
                    </a>
                    <x-adminlte-button id="submitBtn" type="submit" label="Simpan" theme="primary" icon="fas fa-save" />
                </div>
            </form>
        </div>
    </div>
@stop
