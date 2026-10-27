@extends('adminlte::page')

@section('title', 'Edit Rak Buku')

@section('content')
    <x-adminlte-card title="Form Edit Rak" theme="warning" icon="fas fa-edit">
        @if ($errors->any())
            <x-adminlte-alert theme="danger" title="Error!">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-adminlte-alert>
        @endif

        <form action="{{ route('rak.update', $rak->id) }}" method="POST">
            @csrf
            @method('PUT')
            <x-adminlte-input name="kode_rak" label="Kode Rak" value="{{ old('kode_rak', $rak->kode_rak) }}"
                fgroup-class="mb-3" required />
            <x-adminlte-input name="lokasi" label="Deskripsi Rak" value="{{ old('lokasi', $rak->lokasi) }}"
                fgroup-class="mb-3" required />

            <x-adminlte-button label="Update Rak" theme="primary" icon="fas fa-save" />
            <a href="{{ route('rak.index') }}" class="btn btn-secondary ml-2">Kembali</a>
        </form>
    </x-adminlte-card>
@endsection
