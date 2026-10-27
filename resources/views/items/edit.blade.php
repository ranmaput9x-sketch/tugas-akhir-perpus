@extends('adminlte::page')

@section('title', 'Edit Eksemplar')

@section('content_header')
    {{-- Tampilkan judul buku dan kode barcode agar jelas --}}
    <h1 class="m-0 text-dark">Edit Eksemplar: {{ $item->kode_barcode }}</h1>
    <small>Buku: {{ $item->buku->judul_buku }}</small>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Data Eksemplar</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <x-adminlte-alert theme="danger" title="Error!">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-adminlte-alert>
                    @endif

                    <form action="{{ route('items.update', $item) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Wajib untuk proses update --}}

                        {{-- Menampilkan info yang tidak bisa diubah --}}
                        <x-adminlte-input name="kode_barcode_display" label="Kode Barcode" value="{{ $item->kode_barcode }}"
                            readonly />
                        <x-adminlte-input name="nomor_eksemplar_display" label="Nomor Eksemplar"
                            value="{{ $item->nomor_eksemplar }}" readonly />

                        {{-- Field yang bisa diubah --}}
                        <x-adminlte-select name="rak_id" label="Lokasi Rak" required>
                            <option value="" disabled>-- Pilih Rak --</option>
                            @foreach ($raks as $rak)
                                {{-- Pilih rak saat ini secara otomatis --}}
                                <option value="{{ $rak->id }}" {{ $item->rak_id == $rak->id ? 'selected' : '' }}>
                                    {{ $rak->kode_rak }} - {{ $rak->lokasi }}
                                </option>
                            @endforeach
                        </x-adminlte-select>

                        <x-adminlte-select name="status" label="Status" required>
                            @php
                                $statuses = ['Tersedia', 'Hilang', 'Rusak', 'Perbaikan'];
                            @endphp
                            @foreach ($statuses as $status)
                                {{-- Pilih status saat ini secara otomatis --}}
                                <option value="{{ $status }}" {{ $item->status == $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </x-adminlte-select>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('buku.show', $item->buku_id) }}" class="btn btn-secondary mr-2">Batal</a>
                            <x-adminlte-button type="submit" label="Update Data" theme="primary" icon="fas fa-save" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
