@extends('adminlte::page')

@section('title', 'Laporan Pengembalian')

@section('content_header')
    <h1 class="m-0 text-dark">Laporan Pengembalian</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('laporan.kembali.export') }}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="from">Dari Tanggal</label>
                        <input type="date" id="from" name="from" class="form-control" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="to">Sampai Tanggal</label>
                        <input type="date" id="to" name="to" class="form-control" required>
                    </div>
                    <div class="form-group col-md-4 align-self-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
