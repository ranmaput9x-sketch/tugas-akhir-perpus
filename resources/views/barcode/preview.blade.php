@extends('adminlte::page')

@section('title', 'Preview Barcode Buku')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark">Preview Barcode Buku</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Preview Barcode</li>
        </ol>
    </div>
@stop

@section('content')
    <form action="{{ route('Barcode.cetak') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Pratinjau Label Barcode</h3>
            </div>
            <div class="card-body text-center">
                <input type="hidden" name="jumlah" value="{{ $jumlah }}">
                <input type="hidden" name="ukuran" value="{{ $ukuran }}">

                @foreach ($bukus as $index => $buku)
                    <input type="hidden" name="buku_data[{{ $index }}][id]" value="{{ $buku->id }}">
                    <input type="hidden" name="buku_data[{{ $index }}][judul_buku]" value="{{ $buku->judul_buku }}">
                    <input type="hidden" name="buku_data[{{ $index }}][barcode]" value="{{ $buku->barcode }}">

                    <h5 class="mt-4">{{ $buku->judul_buku }} ({{ $buku->barcode }})</h5>
                    <div class="d-flex flex-wrap justify-content-center">
                        @for ($i = 0; $i < $jumlah; $i++)
                            <div class="m-2">
                                <svg id="barcode-{{ $buku->id }}-{{ $i }}"></svg>
                            </div>
                        @endfor
                    </div>
                @endforeach

                <button type="submit" class="btn btn-success mt-4">
                    <i class="fas fa-print"></i> Cetak PDF Barcode
                </button>
            </div>
        </div>
    </form>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bukus = @json($bukus);
            const jumlah = {{ $jumlah }};
            bukus.forEach((buku) => {
                for (let i = 0; i < jumlah; i++) {
                    JsBarcode(`#barcode-${buku.id}-${i}`, buku.barcode, {
                        format: 'CODE128',
                        lineColor: '#000',
                        width: 2,
                        height: 60,
                        displayValue: true
                    });
                }
            });
        });
    </script>
@stop
