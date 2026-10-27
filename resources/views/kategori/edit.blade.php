@extends('adminlte::page')

@section('title', 'Edit Kategori')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Edit Kategori Buku </h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Daftar Kategori</a></li>
            <li class="breadcrumb-item active">Edit Kategori Buku</li>
        </ol>
    </div>
@endsection

@section('content')
    <x-adminlte-card title="Form Edit Kategori" theme="warning" icon="fas fa-pen">

        @if ($errors->any())
            <x-adminlte-alert theme="danger" title="Validasi Gagal!">
                <ul>
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </x-adminlte-alert>
        @endif

        <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
            @csrf @method('PUT')
            <x-adminlte-input name="nama_kategori" label="Nama Kategori" value="{{ $kategori->nama_kategori }}"
                fgroup-class="mb-3" />
            <x-adminlte-button type="submit" theme="primary" label="Update" icon="fas fa-save" />
        </form>

    </x-adminlte-card>
@endsection
