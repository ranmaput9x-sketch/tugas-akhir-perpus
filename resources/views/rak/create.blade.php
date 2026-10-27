@extends('adminlte::page')

@section('title', 'Tambah Rak Buku')

@section('content')
    <x-adminlte-card title="Form Tambah Rak" theme="info" icon="fas fa-plus">
        @if ($errors->any())
            <x-adminlte-alert theme="danger" title="Error!">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-adminlte-alert>
        @endif

        <form action="{{ route('rak.store') }}" method="POST">
            @csrf
            <x-adminlte-input name="kode_rak" label="Kode Rak" placeholder="Contoh: A1" fgroup-class="mb-3" required />
            <x-adminlte-input name="lokasi" label="Deskripsi Rak" placeholder="Contoh: Rak depan" fgroup-class="mb-3"
                required />
            <x-adminlte-button type="submit" theme="success" label="Simpan" icon="fas fa-save" />
            <a href="{{ route('rak.index') }}" class="btn btn-secondary ml-2">Batal</a>
        </form>
    </x-adminlte-card>
@endsection
