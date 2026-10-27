@extends('adminlte::page')

@section('title', 'Laporan Peminjaman')

@section('content_header')
    <h1>Laporan Peminjaman</h1>
@stop

@section('content')
    <form action="{{ route('laporan.pinjam.export') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Dari Tanggal</label>
                    <input type="date" name="from" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="to" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4 align-self-end">
                <button type="submit" class="btn btn-primary">Export Excel</button>
            </div>
        </div>
    </form>
@stop
